<?php

include "../include/dbconnect.php";
include "../include/functions.php";

$bug_id = $_POST['bug_id'];
$bug_status = $_POST['bug_status'];


$update_status = "UPDATE bugs SET status='$bug_status' WHERE bug_id=$bug_id";
$result = mysqli_query($con, $update_status) or die(mysqli_error($con));


// Add diary entry

$diary_text="Minecraft IS: Bug s id $bug_id status sa zmenil na $bug_status";
$create_record="INSERT INTO app_log (diary_text, date_added) VALUES ('$diary_text', now())";
$result = mysqli_query($con, $create_record) or die("MySQLi ERROR: ".mysqli_error($con));
