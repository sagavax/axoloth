<?php

include "../include/dbconnect.php";

$id = $_POST['id'];
$sql = "DELETE FROM tblpasswords WHERE PassID='$id'";
$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());

//zapis do logu
$curr_date = date('Y-m-d H:i:s');
$curr_action = "delete_password";
$curr_app = "passmgr";
$text_logu = "the password id $id has been deleted";

$sql = "INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
$result = mysqli_query($con, $sql); // or die("MySQL ERROR: ".mysqli_error());
//echo $sql;

//vlozenie  do wallu - zyskat systemove meno
$sql = "SELECT system_name from tblpasswords where PassID=$id";
$result = mysqli_query($con, $sql); // or die("MySQL ERROR: ".mysqli_error());
while ($row = mysqli_fetch_array($result)) {
	$system_name = $row['system_name'];
}

$link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
$diary_text = "EIS: Heslo s id $id a nazvom $system_name bolo vymazane";
$sql = "INSERT INTO diary (diary_text, date_added,location,isMobile,is_read) VALUES ('$diary_text','$curr_date','',0,0)";
$result = mysqli_query($link1, $sql) or die("MySQLi ERROR: " . mysqli_error($link1));
mysqli_close($link1);

//vlozenie to time liny
$sql = "INSERT INTO tblpasswords_timeline (pass_id,pass_action, action_time) VALUES ($id,'password has been deleted','$curr_date')";
$result = mysqli_query($con, $sql); // or die("MySQL ERROR: ".mysqli_error());

$result = mysqli_query($con, $sql); // or die("MySQL ERROR: ".mysqli_error());
echo "<script>alert('the password with id $id has been deleted sucessfully')</script>";
?>
