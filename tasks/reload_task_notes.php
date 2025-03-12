<?php
session_start();
include("../include/dbconnect.php");
include("../include/functions.php");


$task_id = $_GET["task_id"];

$sql = "SELECT * from tblcustomer_tasks_comments where task_id=$task_id";
global $con;
$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
if (mysqli_num_rows($result) == 0) {
    echo "<p>No notes for this task</p>";
} else {
    echo "<ul>";
    while ($row = mysqli_fetch_array($result)) {
        $task_note_id = $row['id'];
        $task_id = $row['task_id'];
        $comment = $row['comment'];
        $date_created = $row['date_created'];

        echo "<li class='task_note' note-id=$task_note_id><div class='task_note_text'>" . nl2br($comment) . "</div><div class='task_note_action'><button class='btn-small remove_task_note'><i class='fa fa-times'></i></button></div></li>";
        //echo nl2br($comment);
    }
    echo "</ul>";
}
