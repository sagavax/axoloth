<?php 

include '../include/dbconnect.php';


$note_id = $_POST['note_id'];
$cat_id = $_POST['cat_id'];

$sql = "INSERT INTO tblcustomer_notes_tags (note_id, cat_id) VALUES ($note_id,$cat_id)";
//echo $sql;
$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
//echo "<script>alert('a new category/tag has been added')</script>";
//zapis to logu
$curr_date = date('Y-m-d H:i:s');
$curr_action = "add_tag";
$curr_app = "notepad";
$text_logu = "new tag id $new_cat has been added to the note with ID $note_id";

$sql = "INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));