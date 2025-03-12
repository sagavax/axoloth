<?php
include "../include/dbconnect.php";
include "../include/functions.php";

$tag_id = $_POST['tag_id'];

$remove_tag = "DELETE from tblpassword_tag_list WHERE tag_id=$tag_id";
$result = mysqli_query($link, $remove_tag) or die("MySQLi ERROR: " . mysqli_error($link));