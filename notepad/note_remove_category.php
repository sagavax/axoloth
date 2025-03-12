<?php

include "../include/dbconnect.php";
include "../include/functions.php";

$cat_id = $_POST['cat_id'];

//delete notes from particulary category
$delete_notes = "DELETE from tblcustomer_notes WHERE cat_id =$cat_id";

$result = mysqli_query($con, $delete_notes) or die("MySQLi ERROR: " . mysqli_error($con));


//delete category itself
$delete_category="DELETE from tblcustomer_notes_category where cat_id=$cat_id";
$result = mysqli_query($con, $delete_category) or die("MySQLi ERROR: " . mysqli_error($con));

//zapis do wallu
$curr_date=date('Y-m-d H:i:s');
       
$link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
//$link1 = mysqli_connect("localhost","root","");
$diary_text="axoloth: bola vymazana kategoria <strong>".GetCatName($cat_id)."</strong>";

$sql="INSERT INTO diary (diary_text, date_added,location,isMobile,is_read) VALUES ('$diary_text','$curr_date','',0,0)";
$result = mysqli_query($link1, $sql) or die("MySQLi ERROR: ".mysqli_error($link1));
    mysqli_close($link1);

//zapis to logu
$curr_action="remove_category";
$curr_app="notepad";
$text_logu="the category with id $cat_id has been removed";

$sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
$result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($con));