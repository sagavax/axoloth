<?php include("../include/dbconnect.php"); ?>
<?php
   // $action=$_GET['action'];
    $pass_id=$_POST['id'];
    //echo $id;
    $sql="update tblpasswords set is_favorite=0 where PassID=$pass_id";
    $result = mysqli_query($con,$sql);
    
    $text_logu="Password with id $pass_id has been removed from favorites";
    $alert="The password has been removed from favorites";
   
    $curr_action="update_password";
    $curr_app="passmgr";


    //vlozenie to timeliny
    $sql="INSERT INTO tblpasswords_timeline (pass_id,pass_action, action_time) VALUES ($pass_id,'removed from favorites',now())";
    $result = mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error());   
  

    //vlozenie to note history
    $sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu',now(),0)";
    $result = mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error());
   
 