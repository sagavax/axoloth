<?php
    include "../include/dbconnect.php";
    include "../include/functions.php";

    //var_dump($_POST);
    $new_cat = mysqli_real_escape_string($con, $_POST['category_name']);

    $sql = "INSERT IGNORE into tblcustomer_notes_category (category_name) VALUES ('$new_cat')";
    //echo $sql;
    $result = mysqli_query($con, $sql) or die("MySQLi ERROR: " . mysqli_error($con));

    //$sql = "INSERT IGNORE into tblcustomer_categories (cat_name) VALUES ('$new_cat')";
    //$result = mysqli_query($con, $sql) or die("MySQLi ERROR: " . mysqli_error($con));

    $curr_date = date("Y-m-d");
    $curr_action = "create_category";
    $curr_app = "notepad";
    $text_logu = "new category has been created";

    $sql = "INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
    $result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
    //echo "<script>alert('New category has been created sucessfully!');
    //href.location='index.php';