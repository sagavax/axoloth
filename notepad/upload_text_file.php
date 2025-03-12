<?php
session_start();
 include("../include/dbconnect.php");
 include("../include/functions.php"); 

global $con;


//var_dump($_FILES);

//if(isset($_POST['upload_note'])){


$file_name=basename($_FILES["note-from-file"]["name"]);
$tmp_name=$_FILES["note-from-file"]["tmp_name"];
$uploads_dir="uploads/";
$note_type="note";
$path_parts = pathinfo($file_name);
$note_title=$path_parts['filename'];

move_uploaded_file($tmp_name,"$uploads_dir/$file_name");

//echo __DIR__."\uploads\\".$file_name;
//$note_text-file_get_contents(__DIR__."\uploads\\".$file_name);
$note_text=mysqli_real_escape_string($con,file_get_contents(__DIR__."/uploads/".$file_name));

$cust_id=0;
$date_created = date('Y-m-d H:m:s');
$note_source="";

$sql="INSERT INTO tblcustomer_notes (cust_id, title, note,note_source,date_created, note_type) VALUES ($cust_id,'$note_title','$note_text','$file_name','$date_created','$note_type')"; 
    $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($con));

//zmazat uploadovany subor
unlink(__DIR__."/uploads/".$file_name);


// zapis do wallu
$new_note_id=GetLatestNote();
$link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
//$link1 = mysqli_connect("localhost", "root", "", "brick_wall", 3306);
$diary_text="EIS: Bola vytvorena nova poznamka s id $new_note_id a nazvom <strong>$note_title</strong>";

$sql="INSERT INTO diary (diary_text, date_added,location,isMobile,is_read) VALUES ('$diary_text','$date_created','',0,0)";
$result = mysqli_query($link1, $sql) or die("MySQLi ERROR: ".mysqli_error($con));
    mysqli_close($link1);

//zapis to logu
$curr_action="add_note";
$curr_app="notepad";
$text_logu="new note id $new_note_id has been created";

$sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$date_created',0)";
$result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($con));



echo "<script>alert('new note has been added');
window.location.href='index.php';   
</script>";

