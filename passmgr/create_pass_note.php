<?php

include "../include/dbconnect.php";
include "../include/functions.php";

global $con;

$note_text = mysqli_real_escape_string($con, $_POST['note_text']);
$pass_id = $_POST['pass_id'];

$create_note = "INSERT INTO tblpassword_notes (pass_id,note_text,updated_date) VALUES ($pass_id, '$note_text',NOW())";
$result = mysqli_query($con, $create_note) or die("MySQLi ERROR: " . mysqli_error($con));

$newest_id = GetLatestPasswordNote();

echo $newest_id;