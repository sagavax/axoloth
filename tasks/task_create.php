<?php session_start();?>
<?php include "../include/dbconnect.php";
	  include "../include/functions.php";

	
	$task_text = mysqli_real_escape_string($con, $_POST['task_text']);
	$cust_id = $_POST['customer_name'];
	$task_priority = mysqli_real_escape_string($con,$_POST['task_priority']) ?? 'low';
	$start_task_date = $_POST['start_task_date'];
	$task_category = isset($_POST['task_category']) ? intval($_POST['task_category']) : 0;	
	
	

	$sql = "INSERT INTO tblcustomer_tasks (task_text,task_category, cust_id, date_created, start_task_date,date_expiration,task_status,task_priority ) VALUES ('$task_text',$task_category,$cust_id,'$start_task_date',DATE_'ADD($start_task_date), INTERVAL 10 DAY),'new','$task_priority')";
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));

	$curr_action = "create_task";
	$curr_app = "task_manager";
	$new_task_id = GetLatestTask();
	$undo_action = "DELETE from tblcustomer_tasks WHERE id=$new_task_id";
	$text_logu = "new task id $new_task_id has been created";


	//zapis do wallu
	/* $diary_text = "EIS: Bol vytvoreny novy task s id $new_task_id s nazvom <strong>$task_text</strong> ";
	$sql = "INSERT INTO diary (diary_text, date_added,location,isMobile,is_read) VALUES ('$diary_text','$curr_date','',0,0)";
	$result = mysqli_query($con, $sql) or die("MySQLi ERROR: " . mysqli_error($con)); */
	

	//zapis do timeline
	$sql = "INSERT INTO tblcustomer_tasks_timeline (task_id, task_action, action_time) VALUES ($new_task_id,'the task has been created','$curr_date')";
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));

	//zapis do logu
	$sql = "INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read,undo_action) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0,'$undo_action')";
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));

	echo "<script>alert('new task $new_task_id has been created');
      window.location='index.php?display_as=list';
      </script>";


?>