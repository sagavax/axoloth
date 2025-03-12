<?php

	include ("../include/dbconnect.php");
	global $con;
	$task_id=$_GET['task_id'];
    $sql="DELETE FROM tblcustomer_tasks WHERE id='$task_id'";
    $result = mysqli_query($con, $sql);
	
	$sql="DELETE from tblcustomer_tasks_timeline WHERE task_id=$task_id";
	$result = mysqli_query($con, $sql);

	$curr_date=date('Y-m-d H:i:s');
    $curr_action="delete_task";
    $curr_app="task_manager";
    //$new_note_id=GetLatestNote();
    $text_logu="The task id $task_id has been deleted";
       	
	
	
	
	$sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
       	$result = mysqli_query($con, $sql)  or die("MySQLi ERROR: ".mysqli_error($con));

	 //$link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
 	 $link1 =  mysqli_connect('localhost', 'root', '', 'eis',3306);
	 $diary_text="EIS: Task s id $task_id bol vymazany";
	 $sql = "INSERT INTO diary (diary_text, date_added,location,isMobile) VALUES ('$diary_text','$curr_date','',0)";
	 $result = mysqli_query($link1, $sql) or die("MySQLi ERROR: ".mysqli_error($link1));
	 mysqli_close($link1);
			  
		   


	   echo "<script>alert('The task id $task_id has been deleted successfuly');
          window.location='index.php?display_as=list';   
        </script>"; 
	 
?>
