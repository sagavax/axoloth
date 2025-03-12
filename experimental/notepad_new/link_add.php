<?php session_start();

?>

<?php include("../include/dbconnect.php"); ?>
<?php include("../include/functions.php"); ?>

<?php
  
   if(isset($_POST['add_cat'])){ //pridat novy kategoriu
    
    AddNewCategory($_POST['cat_name']);
    
     //header('location:note_add.php');

   }

   if (isset($_POST['add_short_note']))
{
    global $con;
    
    $note_title=mysqli_real_escape_string($con, $_POST['note_title']);
    $note_text="";
    $note_source=mysqli_real_escape_string($con, $_POST['note_text']);
    $cust_id = 0; // if cust_id = moje vlastne poznamky
    //$tags_ids=$_POST['categories']; 
    $note_type="link";
    $date_created = date('Y-m-d H:m:s');
    
    //$sql="INSERT INTO tblcustomer_notes (cust_id, cat_id,title, note,note_source,date_created, note_type) VALUES ($cust_id,$cat_id,'$note_title','$note_text','$note_source','$date_created','$note_type')"; 
    $sql="INSERT INTO tblcustomer_notes (cust_id, title, note,note_source,date_created, note_type) VALUES ($cust_id,'$note_title','$note_text','$note_source','$date_created','$note_type')"; 
    $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error());
   
    $new_note_id=GetLatestNote();
      
   /*foreach($tags_ids as $tag_id){
        $sql="INSERT INTO tblcustomer_notes_tags (note_id,cat_id) VALUES($new_note_id,$tag_id)";
        //echo $sql;
        $result = mysqli_query($con, $sql)  or die("MySQLi ERROR: ".mysqli_error());
}*/
    
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
       
     //$link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
     $link1 = mysqli_connect("localhost", "root", "", "brick_wall", 3306 );
     $diary_text="EIS: Bola pridany novy link s id $new_note_id a nazvom <strong>$note_title</strong>";
     
     $sql="INSERT INTO diary (diary_text, date_added,location,isMobile,is_read) VALUES ('$diary_text','$curr_date','',0,0)";
     $result = mysqli_query($link1, $sql) or die("MySQLi ERROR: ".mysqli_error($link1));
		 mysqli_close($link1);

     //zapis to logu
    $curr_action="create_link";
    $curr_app="notepad";
    $text_logu="new link id $new_note_id has been created";
    
    $sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
    $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($con));
   
    echo "<script>alert('new link has been added');
    window.location.href='index.php';   
    </script>";
}
   ?>
   
<!DOCTYPE html>
<head>
   <meta charset="utf-8" />
   <title>E.I.S - Add new link</title>
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
       <div class="edit_note_wrap">
               <form action="" method="post" id="new_note_form">
                  <div class="edit_note_title"><input type="text" name="note_title" autocomplete="off" placeholder="title..."></div>
                  <div class="edit_note_url"><input name="note_text" placeholder="url...."></textarea></div>
                  <div class="edit_note_action"><button name="add_short_note" type="submit" class="flat-btn">Add note</button></div>
                 <a href='index.php' class="action_link">&lt;&lt; Back to home</a></td>
           </form>
       </div>
   </div> 
</body>
</html>