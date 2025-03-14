<?php
    include("../include/dbconnect.php");

    $task_id = $_POST['task_id'];
    $slide_value = $_POST['progress'];


    $update_progress = "UPDATE tblcustomer_tasks SET percent_done = $slide_value WHERE TaskID = $task_id";
    $result = mysqli_query($con, $update_progress) or die(mysqli_error($con));

    ?>