<?php session_start() ?>

<?php include("../include/dbconnect.php"); ?>

<?php

  $cust_id=$_GET['cust_id'];
  $sql     = "UPDATE tblcustomers SET customer_status='active' where cust_id=$cust_id";
    $result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());
    
    $curr_date=date('Y-m-d H:i:s');
    $curr_action="make_customer_active";
    $curr_app="customers";
    $text_logu="the customer with id $cust_id has been made active";
  
    $sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
  
    $result = mysqli_query($con, $sql); // or die("MySQL ERROR: ".mysqli_error());
    echo "<script>alert('the customer with with id $cust_id has been made active');
    location.href='index.php'</script>";  
?>
