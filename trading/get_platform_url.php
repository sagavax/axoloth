<?php
    session_start();
    include("../include/dbconnect.php");
    include("../include/functions.php");


    global $con;

    $platform_id = $_GET['platform'];

    $get_url = "SELECT platform_url from tbltrading_platforms WHERE id = $platform_id";
    //echo $get_url;
    $result = mysqli_query($con, $get_url)  or die("MySQL ERROR: ".mysqli_error($con));
    $row = mysqli_fetch_array($result);
    $url = $row['platform_url'];
    //echo $url;

    if($url<>""){
       // echo $url;
       echo "<script>document.write(localStorage.setItem('platform_url', '".$url."'))</script>"; 
           }