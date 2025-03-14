<?php
    include("../include/dbconnect.php");

    $task_id = $_POST['task_id'];

    $get_progress = "SELECT percent_done FROM tblcustomer_tasks WHERE TaskID = $task_id";
    $result = mysqli_query($con, $get_progress) or die(mysqli_error($con));
    $row = mysqli_fetch_array($result);
    $percent_done = $row['percent_done'];

    echo $percent_done; //echo or return the progress percentage here
?>