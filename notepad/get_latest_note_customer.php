<?php

include "../include/dbconnect.php";
include "../include/functions.php";


$new_note_id=GetLatestNote();

$get_customer = "SELECT b.customer_name from tblcustomer_notes a, tblcustomers b where a.cust_id = b.cust_id and id = $new_note_id";
$result = mysqli_query($con, $get_customer)  or die("MySQL ERROR: ".mysqli_error($con));
$row = mysqli_fetch_array($result);
$customer_name = $row['customer_name'];

echo $customer_name;
