<?php include("../include/dbconnect.php");

$event_id = $_GET['event_id'];
$is_meeting = $_GET['is_meeting']; //color in rgb

//$color = rgb_to_hex($color);


$sql="UPDATE tblcustomer_events SET is_meeting=$is_meeting where event_id=$event_id";
$result = mysqli_query($con, $sql)  or die("MySQli ERROR: ".mysqli_error($con));
