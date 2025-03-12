<?php 

include("../include/dbconnect.php");
include("../include/functions.php");

$platform_id = $_GET['platforn_id'];
$get_latest_note = "SELECT MAX(note_id) as latest_note_id from tbltrading_platform_notes WHERE platform_id =$platform_id";
$result = mysqli_query($con, $get_latest_note) or die("MySQL ERROR: " . mysqli_error($con));
while ($row = mysqli_fetch_array($result)) {
    $note_id = $row['latest_note_id'];
}    

echo $note_id;