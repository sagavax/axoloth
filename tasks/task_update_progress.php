<?php
    include("../include/dbconnect.php");

    $task_id = $_POST['task_id'];
    $slide_value = $_POST['progress'];

    // update status to completed if progress is 100%  else update progress
    if($slide_value==100){
        $update_status = "UPDATE tblcustomer_tasks SET task_status = 'Completed' WHERE TaskID = $task_id";
        $result = mysqli_query($con, $update_status) or die(mysqli_error($con));
    } else {

    $update_progress = "UPDATE tblcustomer_tasks SET percent_done = $slide_value WHERE TaskID = $task_id";
    $result = mysqli_query($con, $update_progress) or die(mysqli_error($con));
    }
    ?>