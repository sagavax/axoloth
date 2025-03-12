<?php
    session_start();
    include("../include/dbconnect.php");
    include("../include/functions.php");



global $con;

$platform_id = mysqli_real_escape_string($con, $_POST['platform_id']);

//check if the platform already exists  
$delete_platform ="DELETE from tbltrading_platforms WHERE id = $platform_id";
$result = mysqli_query($con, $delete_platform)  or die("MySQL ERROR: ".mysqli_error($con));

//delete all notes
$delete_platf_notes = "DELETE from tbltrading_platform_notes WHERE id=$platform_id";
$result = mysqli_query($con, $delete_platf_notes)  or die("MySQL ERROR: ".mysqli_error($con));

//remove all asset traded by this platform
$delete_platf_asset = "DELETE from tbltrading_platform_assets WHERE id=$platform_id";
$result = mysqli_query($con, $delete_platf_asset)  or die("MySQL ERROR: ".mysqli_error($con));


//zapis do wallu
$curr_date=date('Y-m-d H:i:s');
$link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
$diary_text="axoloth: bola  platofmra s id $platform_id bola vymazana";

$sql="INSERT INTO diary (diary_text, date_added,location,isMobile,is_read) VALUES ('$diary_text','$curr_date','',0,0)";
$result = mysqli_query($link1, $sql) or die("MySQLi ERROR: ".mysqli_error($link1));
mysqli_close($link1);

//zapis to logu
$curr_action="remove_platform";
$curr_app="trading";
$text_logu="the platform with id $id has been deleted";

$sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
$result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($con));

