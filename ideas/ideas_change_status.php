<?php include "../include/dbconnect.php";
      include "../include/functions.php";

$idea_id = $_POST['idea_id'];
$idea_status = $_POST['idea_status'];


$update_status = "UPDATE ideas SET status='$idea_status' WHERE idea_id=$idea_id";
$result = mysqli_query($con, $update_status) or die(mysqli_error($con));


// Add diary entry

$diary_text="Minecraft IS: Status idea s id $idea_id sa zmenil na $idea_status";
$create_record="INSERT INTO app_log (diary_text, date_added) VALUES ('$diary_text', now())";
$result = mysqli_query($con, $create_record) or die("MySQLi ERROR: ".mysqli_error($con));
