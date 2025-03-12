<?php

include "../include/dbconnect.php";
include "../include/functions.php";

$note_id = $_POST['note_id'];

$add_title = "SELECT title from tblcustomer_notes WHERE id=$note_id";
//echo $add_title;
$result = mysqli_query($con, $add_title) or die("MySQL ERROR: " . mysqli_error($con));
$row = mysqli_fetch_array($result);
echo $row['title'];