<?php

include "../include/dbconnect.php";
include "../include/functions.php";

global $con;

$note_id = $_POST['note_id'];

$remove_note = "DELETE from tblpassword_notes WHERE id=$note_id ";
$result = mysqli_query($con, $remove_note) or die("MySQLi ERROR: " . mysqli_error($con));

//zapis do wallu
$curr_date = date('Y-m-d H:i:s');
//$latest_note_id = GetLatestPasswordNote();
$link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
//$link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
$diary_text = "axoloth: poznamka s id $note_id bola vymazana</strong>";

$sql = "INSERT INTO diary (diary_text, date_added,location,isMobile,is_read) VALUES ('$diary_text','$curr_date','',0,0)";
$result = mysqli_query($link1, $sql) or die("MySQLi ERROR: " . mysqli_error($link1));
mysqli_close($link1);

//zapis to logu
$curr_action = "remove_pass_note";
$curr_app = "passmgr";
$text_logu = "password note with id $new_platform_id has been removed";

$sql = "INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));