<?php include "../include/dbconnect.php";
      include "../include/functions.php";

$idea_priority=$_POST['idea_priority'];
$idea_id = $_POST['idea_id'];

$update_prioty = "UPDATE ideas SET priority='$idea_priority' WHERE idea_id=$idea_id";
$result = mysqli_query($con, $update_prioty);

//add to audit log
$application = "ideas";
$diary_text="Minecraft IS: Idea s id $idea_id bola priorita zmenena na $idea_priority";
$create_record="INSERT INTO tblapp_log (application, note, date_created) VALUES ('$application','$diary_text', now())";
$result = mysqli_query($con, $create_record) or die("MySQLi ERROR: ".mysqli_error($con));