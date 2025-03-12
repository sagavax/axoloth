<?php session_start();

?>

<?php include("../include/dbconnect.php"); ?>
<?php include("../include/functions.php"); ?>

<?php
  
    global $con;
    //var_dump($_POST);
    $note_title=mysqli_real_escape_string($con, $_POST['image_title']);
    $note_text="";
    $note_source=mysqli_real_escape_string($con, $_POST['image_source']);
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
    }