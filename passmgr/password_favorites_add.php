<?php include("../include/dbconnect.php"); ?>
<?php
   // $action=$_GET['action'];
    $pass_id=$_POST['id'];
    //echo $id;
    $sql="update tblpasswords set is_favorite=1 where PassID=$pass_id";
    $result = mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error());
    
    $text_logu="Password with id $pass_id has been added into favorites";
    $alert="The password has been added into favorites";
   
    $curr_action="update_password";
    $curr_app="passmgr";
    
    //vlozenie to timeliny
    $sql="INSERT INTO tblpasswords_timeline (pass_id,pass_action, action_time) VALUES ($pass_id,'added into favorites',now())";
    $result = mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error());   
 