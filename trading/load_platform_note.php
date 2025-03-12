<?php 
include("../include/dbconnect.php");
include("../include/functions.php");

global $con;

$note_id = $_GET['note_id'];

$get_note="SELECT note_text from tbltrading_platform_notes WHERE note_id=$note_id";
//echo $get_note;
$result = mysqli_query($con, $get_note)  or die("MySQL ERROR: ".mysqli_error($con));
$row = mysqli_fetch_array($result);
$note_text = $row['note_text'];

echo $note_text;
