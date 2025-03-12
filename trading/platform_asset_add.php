<?php
    session_start();
    include("../include/dbconnect.php");
    include("../include/functions.php");

global $con;
//var_dump($_POST); 
$platform_id = $_POST["platform_id"];
$asset_symbol=mysqli_real_escape_string($con, $_POST["asset_symbol"]);
$asset_amount=mysqli_real_escape_string($con, $_POST["asset_amount"]);
$asset_note=mysqli_real_escape_string($con, $_POST["asset_note"]);
$curr_date=date('Y-m-d H:i:s');
    
$add_platf_asset = "INSERT INTO tbltrading_platform_assets (platform_id, asset_symbol, amount, asset_note,added_date) VALUES ($platform_id, '$asset_symbol','$asset_amount','$asset_note','$curr_date')";
echo $add_platf_asset;
//echo $add_asset;
$result = mysqli_query($con, $add_platf_asset)  or die("MySQLi ERROR: ".mysqli_error($con));

//zapis do wallu

$link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
//$link1 = mysqli_connect("localhost", "root", "","brick_wall");
$diary_text="axoloth: bol pridany novy asset do platformy ";

$sql="INSERT INTO diary (diary_text, date_added,location,isMobile,is_read) VALUES ('$diary_text','$curr_date','',0,0)";
$result = mysqli_query($link1, $sql) or die("MySQLi ERROR: ".mysqli_error($link1));
mysqli_close($link1);

//zapis to logu
$curr_action="assign_asset";
$curr_app="trading";
$text_logu="the asset has been assigned to the platform";

$sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
$result = mysqli_query($con, $sql)  or die("MySQLi ERROR: ".mysqli_error($con));