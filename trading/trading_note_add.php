<?php
    session_start();
    include("../include/dbconnect.php");
    include("../include/functions.php");


global $con;
var_dump($_POST);

$note_text = mysqli_real_escape_string($con, $_POST['note_text']);
$note_title = mysqli_real_escape_string($con, $_POST['note_title']);
$platform_id = 0;
//check if the platform already exists  
$curr_date=date('Y-m-d H:i:s');
$sql="INSERT INTO tbltrading_platform_notes (platform_id, note_title, note_text,added_date)VALUES ($platform_id,'$note_title','$note_text','$curr_date')";    
echo $sql;
$result = mysqli_query($con, $sql)  or die("MySQLi ERROR: ".mysqli_error($con));

//zapis do wallu
$curr_date=date('Y-m-d H:i:s');
$platform_note_id = GetLatestTradingPlatformNote();       
$link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
$diary_text="axoloth: bola vytvorena nova poznamka/komennt s id $platform_note_id a nazvom <strong>$note_title</strong>";

$sql="INSERT INTO diary (diary_text, date_added,location,isMobile,is_read) VALUES ('$diary_text','$curr_date','',0,0)";
$result = mysqli_query($link1, $sql) or die("MySQLi ERROR: ".mysqli_error($link1));
    mysqli_close($link1);

//zapis to logu
$curr_action="add_note";
$curr_app="trading";
$text_logu="new note wit id $platform_note_id has been created";

$sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
$result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($con));

