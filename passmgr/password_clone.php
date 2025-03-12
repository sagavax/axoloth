<?php
session_start();
include "../include/dbconnect.php";
include "../include/functions.php";

$pass_id = $_POST['id'];
$clone_account = "INSERT INTO tblpasswords (cust_id, group_name, system_name, user_name, password,logon_type, category, url, description,hits, date_added, date_hit,is_archived, is_favorite) SELECT cust_id, group_name, concat(system_name,'-cloned'), user_name, password,logon_type, category, url, description,hits, date_added, date_hit,is_archived, is_favorite from tblpasswords WHERE PassID=$pass_id";
//echo $clone_account;
$result_notes = mysqli_query($con, $clone_account);

$curr_date = date('Y-m-d H:i:s');
$new_pass_id = GetLatestPassword();

//$link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
$link1 = mysqli_connect("localhost", "root", "", "brick_wall");
$diary_text = "EIS: Bolo vytvorene nove heslo s id $new_pass_id";
$sql = "INSERT INTO diary (diary_text, date_added,location,isMobile,is_read) VALUES ('$diary_text','$curr_date','',0,0)";
$result = mysqli_query($link1, $sql) or die("MySQLi ERROR: " . mysqli_error($link1));
mysqli_close($link1);

$curr_action = "create_password";
$curr_app = "passmgr";
$text_logu = "new password id $new_pass_id has been created";

//vlozenie to timeliny
$sql_timeline = "INSERT INTO tblpasswords_timeline (pass_id,pass_action, action_time) VALUES ($new_pass_id,'password was created','$curr_date')";
//echo $sql_timeline;
$result = mysqli_query($con, $sql_timeline); // or die("MySQL ERROR: ".mysqli_error());

$sql_history = "INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
//echo $sql_history;

$result = mysqli_query($con, $sql_history) or die("MySQL ERROR: " . mysqli_error($con));
//echo "<script>location.href='password_edit.php?id='" . $pass_id . "</script>";
echo $pass_id;
//header("location:password_edit.php?id=$new_pass_id");