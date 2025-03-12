<?php
    session_start();
    include("../include/dbconnect.php");
    include("../include/functions.php");



global $con;

$note_id = mysqli_real_escape_string($con, $_POST['note_id']);

//check if the platform already exists  
$delete_platform ="DELETE from tbltrading_platform_notes WHERE note_id = $note_id";
$result = mysqli_query($con, $delete_platform)  or die("MySQL ERROR: ".mysqli_error($con));

//zapis do wallu
$curr_date=date('Y-m-d H:i:s');
$link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
$diary_text="axoloth: bola  platofmra s id $platform_id bola vymazana";

$sql="INSERT INTO diary (diary_text, date_added,location,isMobile,is_read) VALUES ('$diary_text','$curr_date','',0,0)";
$result = mysqli_query($link1, $sql) or die("MySQLi ERROR: ".mysqli_error($link1));
mysqli_close($link1);

//zapis to logu
$curr_action="remove_note";
$curr_app="trading";
$text_logu="the platformnote with id $note_id has been deleted";

$sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
$result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($con));