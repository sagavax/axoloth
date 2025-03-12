<?php
 //var_dump($_FILES);

 
 session_start();
 include("../include/dbconnect.php");
 include("../include/functions.php"); 

global $con;

$uploads_dir = "images/";
$file_name=basename($_FILES["picture"]["name"]);
$tmp_name=$_FILES["picture"]["tmp_name"];
$note_type="image";
$note_title=mysqli_real_escape_string($con, $_POST['note_title']);
$cust_id=0;
$note_text="";
$date_created = date('Y-m-d H:m:s');

$uploadOk = 1;
//$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["add_image"])) {
    move_uploaded_file($tmp_name,"$uploads_dir/$file_name");
    $note_text="";
    $note_source="";
    $cust_id = 0; // if cust_id = moje vlastne poznamky
    //$tags_ids=$_POST['categories']; 
    $note_type="image";
    $date_created = date('Y-m-d H:m:s');
    $sql="INSERT INTO tblcustomer_notes (cust_id, title, note,note_source,date_created, note_type) VALUES ($cust_id,'$note_title','$note_text','$file_name','$date_created','$note_type')"; 
    $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($con));


     //zapis do wallu
     $curr_date=date('Y-m-d H:i:s');
     $new_note_id=GetLatestNote();
     
     $link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
     $link1 = mysqli_connect("localhost", "root", "", "minecraft_db", 3306);
     $diary_text="EIS: Bola vytvorena nova poznamka s id $new_note_id a nazvom <strong>$note_title</strong>";
     
     $sql="INSERT INTO diary (diary_text, date_added,location,isMobile,is_read) VALUES ('$diary_text','$curr_date','',0,0)";
     $result = mysqli_query($link1, $sql) or die("MySQLi ERROR: ".mysqli_error($link1));
		 mysqli_close($link1);

     //zapis to logu
    $curr_action="add_picture";
    $curr_app="notepad";
    $text_logu="new note id $new_note_id has been created";
    
    $sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
    $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($link1));



    echo "<script>alert('new picture has been added');
    window.location.href='index.php';   
    </script>";
}
?>