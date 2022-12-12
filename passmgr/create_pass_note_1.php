<?php

include "../include/dbconnect.php";
include "../include/functions.php";

global $con;

$note_text = mysqli_real_escape_string($con, $_POST['note_text']);
$pass_id = $_POST['pass_id'];

$create_note = "INSERT INTO tblpassword_notes (pass_id,note_text,updated_date) VALUES ($pass_id, '$note_text',NOW())";
$result = mysqli_query($con, $create_note) or die("MySQLi ERROR: " . mysqli_error($con));

//zapis do wallu
$curr_date = date('Y-m-d H:i:s');
$latest_note_id = GetLatestPasswordNote();
$link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
$diary_text = "axoloth: bola vytvorena nova poznamka/komennt s id $latest_note_id ku zaznamu s id <strong>$pass_id</strong>";

$sql = "INSERT INTO diary (diary_text, date_added,location,isMobile,is_read) VALUES ('$diary_text','$curr_date','',0,0)";
$result = mysqli_query($link1, $sql) or die("MySQLi ERROR: " . mysqli_error($link1));
mysqli_close($link1);

//zapis to logu
$curr_action = "add_pass_note";
$curr_app = "passmgr";
$text_logu = "new password note with id $new_platform_id has been created";

$sql = "INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));