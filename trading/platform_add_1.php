<?php
    session_start();
    include("../include/dbconnect.php");
    include("../include/functions.php");



global $con;

$platform_name = mysqli_real_escape_string($con, $_POST['platform']);

//check if the platform already exists  
$get_platform ="SELECT * from tbltrading_platforms WHERE platform_name = '$platform_name'";
$result = mysqli_query($con, $get_platform)  or die("MySQL ERROR: ".mysqli_error($con));
if(mysqli_num_rows($result)==1){
    echo "exists";
} else {

$curr_date=date('Y-m-d H:i:s');
$add_platform="INSERT INTO tbltrading_platforms (platform_name, added_date)VALUES ('$platform_name','$curr_date')"; 
echo $add_platform;
$result = mysqli_query($con, $add_platform)  or die("MySQL ERROR: ".mysqli_error($con));

//zapis do wallu
$curr_date=date('Y-m-d H:i:s');
$new_platform_id = GetLatestTradingPlatform();       
//$link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
$link1 = mysqli_connect("localhost","root","","brick_wall");
$diary_text="axoloth: bola vytvorena nova platofmra id  $new_platform_id  a nazvom <strong>$platform_name</strong>";

$sql="INSERT INTO diary (diary_text, date_added,location,isMobile,is_read) VALUES ('$diary_text','$curr_date','',0,0)";
$result = mysqli_query($link1, $sql) or die("MySQLi ERROR: ".mysqli_error($link1));
    mysqli_close($link1);

//zapis to logu
$curr_action="add_platform";
$curr_app="trading";
$text_logu="new platform with id $new_platform_id has been created";

$sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
$result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($con));
}
