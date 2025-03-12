<?php
    session_start();
    include("../include/dbconnect.php");
    include("../include/functions.php");

global $con;


if(isset($_GET['platform_id'])){
    $platform_id = $_GET['platform_id'];
}


    $get_notes = "SELECT * from tbltrading_platform_notes where platform_id=$platform_id";

    $result = mysqli_query($con, $get_notes)  or die("MySQL ERROR: ".mysqli_error($con));
    while ($row = mysqli_fetch_array($result)) {
        $note_id = $row['note_id'];
        $note_title = $row['note_title'];
        $note_text = $row['note_text'];

        echo "<div class='note' note-id=$note_id platform-id=$platform_id>";
            //echo "<div class='note_title'>$note_title</div>";
            echo "<div class='note_text'>$note_text</div><div class='note_action'><button type='button' class='btn-small note_delete'><i class='fa fa-times'></i></button></div></div>";
        echo "</div>";
    }
