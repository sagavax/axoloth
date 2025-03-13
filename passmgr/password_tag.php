<?php
include "../include/dbconnect.php";
include "../include/functions.php";

$pass_id = $_POST['pass_id'];
$tag_name = mysqli_escape_string($link, $_POST['tag_name']);

//check if tag name already exists
$check_if_tag_exists = "SELECT * from tblpasswords_tags WHERE tag_name=$tag_name";
$result = mysqli_query($link, $check_if_tag_exists) or die("MySQLi ERROR: " . mysqli_error($link));

if (mysqli_num_rows($result) == 0) {
	$add_new_pass_tag = "INSERT INTO tblpassword_list (tag_name) VALUE ('tag_name')";
	$result = mysqli_query($link, $add_new_pass_tag) or die("MySQLi ERROR: " . mysqli_error($link));
} 

//get its id
$get_tag_id = "SELECT tag_id from tblpasswords_tags WHERE tag_name='$tag_name";
$tag_id_result = mysqli_query($link, $get_tag_id) or die("MySQLi ERROR: " . mysqli_error($link));
$rows_id = mysqli_fetch_array($tag_id_result);
while ($rows = mysqli_fetch_array($rows_id)){
	$tag_id = $rows['tag_id'];
}

//add_id into the list
//$add_into_pass_tag_list = "INSERT INTO tblpassword_tag_list (pass_id, tag_id, date_created)($pass_id,$row_id['tag_id'],now())";
//mysqli_query($link, $add_into_pass_tag_list) or die("MySQLi ERROR: " . mysqli_error($link));