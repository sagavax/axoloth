<?php include("../include/dbconnect.php");


$event_id = $_GEt['event_id'];
$color = $_GET['new_color'];

$sql="UPDATE tblcustomer_events SET even_color= $color where event_id=$event_id";
$result = mysqli_query($con, $sql)  or die("MySQli ERROR: ".mysqli_error($con));
