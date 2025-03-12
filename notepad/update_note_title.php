<?php

include "../include/dbconnect.php";
include "../include/functions.php";

$title = $_POST['note_title'];
$note_id = $_POST['note_id'];

$add_title = "UPDATE tblcustomer_notes set title='$title' WHERE id=$note_id";
//echo $add_title;
$result = mysqli_query($con, $add_title) or die("MySQL ERROR: " . mysqli_error($con));