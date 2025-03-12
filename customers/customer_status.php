<?php include("../include/dbconnect.php"); ?>

<?php

  $cust_id=$_GET['cust_id'];
  $cust_status = $_GET['cust_status'];

  $sql = "UPDATE tblcustomers SET customer_status='$cust_status' where cust_id=$cust_id";
    $result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());
    
    //zapis do logu
    $curr_date=date('Y-m-d H:i:s');
    $curr_action="make_customer_inactive";
    $curr_app="customers";
    $text_logu="the status of the customer  with id $cust_id has been changed to '$cust_status'";
  
    $sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
  
    $result = mysqli_query($con, $sql); // or die("MySQL ERROR: ".mysqli_error());
    echo "<script>alert('the status of the customer  with id $cust_id has been changed to '$cust_status');
    location.href='index.php'</script>";    

?>