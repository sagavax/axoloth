<?php session_start();

?>

<?php include("../include/dbconnect.php"); ?>
<?php include("../include/functions.php"); ?>

<?php
  
   if(isset($_POST['add_cat'])){ //pridat novy kategoriu
    
    AddNewCategory($_POST['cat_name']);
    
     //header('location:note_add.php');

   }

   if (isset($_POST['add_ext_pic']))
{
    global $con;
    
    $note_title=mysqli_real_escape_string($con, $_POST['note_title']);
    $note_text="";
    $note_source=mysqli_real_escape_string($con, $_POST['note_text']);
    $cust_id = 0; // if cust_id = moje vlastne poznamky
    //$tags_ids=$_POST['categories']; 
    $note_type="ext_image";
    $date_created = date('Y-m-d H:m:s');
    

     
    //zisti ci som uz nahodou tento obrazok nepridal

    $sql="SELECT * from tblcustomer_notes WHERE note_source='$note_source'";
    //echo $sql;
    $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($con));
  
    $num_row = mysqli_num_rows($result);
    if($num_row==1){ //ano pridal som
         echo "<script>alert('Obrazok je uz v databaze');
         window.location.href='picture_external_add.php'
         </script>
         ";

    } else {
    

    //$sql="INSERT INTO tblcustomer_notes (cust_id, cat_id,title, note,note_source,date_created, note_type) VALUES ($cust_id,$cat_id,'$note_title','$note_text','$note_source','$date_created','$note_type')"; 
    $sql="INSERT INTO tblcustomer_notes (cust_id, title, note,note_source,date_created, note_type) VALUES ($cust_id,'$note_title','$note_text','$note_source','$date_created','$note_type')"; 
    $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($con));
   
    $new_note_id=GetLatestNote();
    
    //crete a new gallery
    //$sql="INSERT into tblcustomer_notes_gallery (gallery_name, image_id, date_created) VALUES (uuid(), $new_note_id, now())";
    //$result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($con));
    

    /*   foreach($tags_ids as $tag_id){
        $sql="INSERT INTO tblcustomer_notes_tags (note_id,cat_id) VALUES($new_note_id,$tag_id)";
        //echo $sql;
        $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($con));
}*/


    //zapis do wallu
     $curr_date=date('Y-m-d H:i:s');
       
     $link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
     //$link1 = $link1 = mysqli_connect("localhost", "root", "", "brick_wall", 3306);
     $diary_text="EIS: Bol pidany obrazok s id $new_note_id a nazvom <strong>$note_title</strong>";
     
     $sql="INSERT INTO diary (diary_text, date_added,location,isMobile,is_read) VALUES ('$diary_text','$curr_date','',0,0)";
     $result = mysqli_query($link1, $sql) or die("MySQLi ERROR: ".mysqli_error($link1));
		 mysqli_close($link1);

     //zapis to logu
    $curr_action="create_note";
    $curr_app="notepad";
    $text_logu="new image with id $new_note_id has been created";
    
    $sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
    $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($con));
   
    echo "<script>alert('new image has been added');
    window.location.href='index.php';   
    </script>";
   }
}
   ?>
   
<!DOCTYPE html>
<head>
   <meta charset="utf-8" />
   <title>E.I.S - Add a new image (from internet)</title>
   <link rel="stylesheet" href="../css/style_new.css?<?php echo time()?>" type="text/css">
   <link href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
   <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
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
      <form action="" method="post" id="new_note_form">
         <div class="edit_note_wrap">
            <div class="edit_note_title">
               <input type="text" name="note_title" autocomplete="off" placeholder="title"> 
            </div>
            <div class="edit_note_text">
               <textarea name="note_text" id="new_note_url" placeholder="Url" wrap="soft"></textarea>   
            </div>
            <div class="edit_note_action">
               <button name="add_ext_pic" type="submit" class="flat-btn">Add picture</button>  
            </div>   
         </div>
      </form>
   </div>      
      
</body>
</html>