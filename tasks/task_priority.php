<?php
  include ("../include/dbconnect.php");
  global $con;
  
  $id=$_GET['id'];
  $priority=$_GET['priority'];
  
  $sql="UPDATE tblcustomer_tasks SET task_priority='$priority' WHERE id=$id";
  //echo $sql;
 $result = mysqli_query($con, $sql);
 
 
 $curr_date=date('Y-m-d H:i:s');

 //zapis do wallu
 $link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
 $diary_text="EIS: Priorita tasku s id $id bol zmenena na <strong>$prioritu</strong>";
 $sql="INSERT INTO diary (diary_text, date_added,location,isMobile,is_read) VALUES ('$diary_text','$curr_date','',0,0)";
 $result = mysqli_query($link1, $sql) or die("MySQLi ERROR: ".mysqli_error($link1));
 mysqli_close($link1);

 //zapis do timeliny
 $sql="INSERT INTO tblcustomer_tasks_timeline (task_id, task_action, action_time) VALUES ($id, 'the priority od the task has been changed to <b>$priority</b>','$curr_date' )";
 $result = mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error($con)); 	

 //zapis do logu / inboxu
 $curr_action="change_task_status";
 $curr_app="task_manager";
	$text_logu="The priority for the task with id $id has been changed sucessfully";
	$sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
	$result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error());
	   
	   echo "<script>alert('The priority for the task with id $task_id has been changed sucessfully');
          window.location='index.php?display_as=list';   
        </script>";