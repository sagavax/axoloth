<?php session_start();

   include("../include/dbconnect.php");
   include("../include/functions.php");

?>

<?php
  
   if(isset($_POST['add_cat'])){ //pridat novy kategoriu
    
    AddNewCategory($_POST['cat_name']);
    
     //header('location:note_add.php');

   }

   if (isset($_POST['add_short_note']))
{
    global $con;
    //var_dump($_POST);
    $note_title=mysqli_real_escape_string($con, $_POST['note_title']);
    $note_text=mysqli_real_escape_string($con, $_POST['note_text']);
    $cust_id = 0; // if cust_id = moje vlastne poznamky
    $note_type="note";
    $date_created = date('Y-m-d H:m:s');
    $note_source="";
    
    //$sql="INSERT INTO tblcustomer_notes (cust_id, cat_id,title, note,note_source,date_created, note_type) VALUES ($cust_id,$cat_id,'$note_title','$note_text','$note_source','$date_created','$note_type')"; 
    $sql="INSERT INTO tblcustomer_notes (cust_id, title, note,note_source,date_created, note_type) VALUES ($cust_id,'$note_title','$note_text','$note_source','$date_created','$note_type')"; 
    $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($con));
   
    $new_note_id=GetLatestNote();
     
    
    
   if(isset($_POST['categories'])){
    
      $tags_ids=$_POST['categories']; 
      foreach($tags_ids as $tag_id){
         $sql="INSERT INTO tblcustomer_notes_tags (note_id,cat_id) VALUES($new_note_id,$tag_id)";
         //echo $sql;
         $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($con));
         }
}
    
/*if(in_array(5,$tags_ids)){
  //mincraft tak presuniem do minecraft IS
  
    $connection =mysqli_connect(null, "minecraft_db", "eTIZAAMcSL", "minecraft_db", null, "/tmp/mariadb101.sock");
    //$connection = mysqli_connect("mariadb101.websupport.sk", "minecraft_db", "eTIZAAMcSL", "minecraft_db", 3312);
    $note_text=$note_text." "."$note_source";
    $sql="INSERT INTO notes (eis_note_id, note_header, note_text, added_date) VALUES ($new_note_id,'$note_title','$note_text','$date_created')";
    //echo $sql;
    $result = mysqli_query($connection, $sql) or die("MySQLi ERROR: ".mysqli_error($connection));
    mysqli_close($connection);
    }
  */

    //zapis do wallu
     $curr_date=date('Y-m-d H:i:s');
       
     $link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
     //$link1=mysqli_connect('localhost','root',null,'brick_wall',null,null);
     $diary_text="EIS: Bola vytvorena nova poznamka s id $new_note_id a nazvom <strong>$note_title</strong>";
     
     $sql="INSERT INTO diary (diary_text, date_added,location,isMobile,is_read) VALUES ('$diary_text','$curr_date','',0,0)";
     $result = mysqli_query($link1, $sql) or die("MySQLi ERROR: ".mysqli_error($link1));
		 mysqli_close($link1);

     //zapis to logu
    $curr_action="create_note";
    $curr_app="notepad";
    $text_logu="new note id $new_note_id has been created";
    
    $sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
    $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($con));
   
    echo "<script>alert('new note has been created');
    window.location.href='index.php';   
    </script>";
  
}
   ?>
   
<!DOCTYPE html>
<head>
   <meta charset="utf-8" />
   <title>E.I.S - Add new note</title>
   <link rel="stylesheet" href="../css/style_new.css?<?php echo time()?>" type="text/css">
   <link href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
   <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
   <link href="../css/toastr.min.css" rel="stylesheet" type='text/css'>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
   <script src="../js/toastr.min.js"></script>
   <link rel='shortcut icon' href='../eis.png'>
   
</head>
<body>
<div id="header"> <!--header -->
<div class="header-logo">E.I.S.</div> <!--logo -->
 <div class="header-menu">
     <!--menu-->
    <?php include('../include/menu.php') ?>
  </div>
</div> <!-- end of header -->
   <!--end of header -->
   <div id="layout">
      <div id="main_wrap">
         <div class="add_new_note_wrap">
             <form action="" method="post" id="new_note_form">
                  <table id="new_note">
                     <tr>
                       
                        <td><input type="text" name="note_title" autocomplete="off" placeholder="Note title...."></td>
                     </tr>
                     <tr>
                       
                        <td>
                           <textarea name="note_text" id="editor" placeholder="Text..."></textarea>
                        </td>
                     </tr>
                     <tr>
                     <tr>
                     <td  style="text-align:right"><button name="add_short_note" type="submit" class="flat-btn">Add note</button></td>
                     </tr>
                     <tr>
                     <td><a href='index.php' class="action_link">&lt;&lt; Back to home</a></td>
                     </tr>    
                  </table>
               </form>
           </div>
      </div>
      <!-- main wrap-->
  
  </div>
 
</body>
</html>