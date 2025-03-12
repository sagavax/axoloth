<?php
   include("../include/dbconnect.php");
   include("../include/functions.php");


   global $con;

    $cust_name = mysqli_real_escape_string($con,$_POST['cust_name']);
    $cust_description = mysqli_real_escape_string($con,$_POST['cust_description']);
    $cust_url = mysqli_real_escape_string($con,$_POST['cust_url']);

    $sql="SELECT count(*) as cust_count from tblcustomers WHERE customer_name='$cust_name'";
    $result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());
    $row     = mysqli_fetch_array($result);
    if ($row['cust_count']==1) { // zakaznik existuje
            echo "zakaznik_existuje";
        } else {
        
        $sql           = "SELECT MAX(cust_id) as cust_id from tblcustomers";
        $result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());
        $row     = mysqli_fetch_array($result);
        $cust_id = $row['cust_id'];

        $cust_id = $cust_id + 1;

        $sql = "INSERT INTO tblcustomers (cust_id, customer_name, customer_status) VALUES ($cust_id,'$cust_name','active')";
        echo $sql;
        $result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());


        //zapis do inboxu
        $curr_date=date('Y-m-d H:i:s');
        $curr_action="create_new_customer";
        $curr_app="customers";
        $new_cust_id=GetLatestCustomer();
        $text_logu="new customer with id $new_cust_id has been created";
        $sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
        
        $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error());

        
        //zapis do wallu
        $link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
        $diary_text="EIS: Bola vytvoreny novy zakaznik s id $new_cust_id a nazvom <strong>$csutomer_title</strong>";
        
        $sql="INSERT INTO diary (diary_text, date_added,location,isMobile,is_read) VALUES ('$diary_text','$curr_date','',0,0)";
        $result = mysqli_query($link1, $sql) or die("MySQLi ERROR: ".mysqli_error($link1));
        mysqli_close($link1);

    }

    ?>