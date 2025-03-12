<?php
session_start();
include("../include/dbconnect.php");
include("../include/functions.php");


global $con;

$note_id = $_POST['note_id'];
$task_id = $_POST['task_id'];
$delete_note = "DELETE from tblcustomer_tasks_comments WHERE id=$note_id";
$result = mysqli_query($con, $delete_note) or die("MySQL ERROR: " . mysqli_error($con));

$curr_action="remove_task_note";
 $curr_date=date('Y-m-d H:i:s');

 //zapis do wallu
 //$link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
 $link1=mysqli_connect("localhost", "root", "", "brick_wall");
 $diary_text="axoloth: taks note/ comment s id $note_id bol vymazany>";
 $sql="INSERT INTO diary (diary_text, date_added,location,isMobile,is_read) VALUES ('$diary_text','$curr_date','',0,0)";
 $result = mysqli_query($link1, $sql) or die("MySQLi ERROR: ".mysqli_error($link1));
 mysqli_close($link1);


//zapis to timeliny
 $sql="INSERT INTO tblcustomer_tasks_timeline (task_id, task_action, action_time) VALUES ($task_id, 'removed note with id $note_id','$curr_date' )";
 $result = mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error($con)); 	

 //zapis do logu / inboxu
 $curr_app="task_manager";
 //$new_note_id=GetLatestNote();
 $text_logu="task note/ comment with id $note_id has been changed sucessfully";
       	
 $sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
 	//echo $sql;
       	
 $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error());