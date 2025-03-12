<?php 
	  session_start();	
	  include("../include/dbconnect.php");
	  include("../include/functions.php"); ?>

<?php

	if (isset($_POST['add_note'])) {
		global $con;
		$task_id=$_POST['id'];
		$note_text=mysqli_real_escape_string($con, $_POST['note_text']);
		$date_created=date('Y-m-d H:i:s');
		$sql="INSERT INTO tblcustomer_tasks_comments (task_id, comment, date_created) VALUES ($task_id,'$note_text', '$date_created')";
		//echo $sql;
		$result=mysqli_query($con,$sql) or die("MySQLi ERROR: ".mysqli_error($con));
		
		
		 $date_created = date('Y-m-d H:m:s');
	   	 $curr_action="add_task_note";
	   	 $curr_app="task_manager";
	   	 $new_task_note_id=GetLatestTaskNote();
	   	 $undo_action="DELETE from tblcustomer_tasks_comments WHERE id=$new_task_note_id";
	   	 $text_logu="New note id $new_task_note_id for task id $task_id has been created";
		   
		 //zapis do timeliny
		 $sql="INSERT INTO tblcustomer_tasks_timeline (task_id, task_action, action_time) VALUES ($task_id, 'the task note has been created ','$date_created' )";
		 $result = mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error($con)); 	



	   	$sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read,undo_action) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0,'$undo_action')";
	   	//echo $sql;
	   	
	   	$result = mysqli_query($con, $sql)  or die("MySQLi ERROR: ".mysqli_error($con));
	      echo "<script>alert('new note id $new_task_note_id for task $task_id has been created');
	      window.location='index.php?display_as=list';   
      </script>";

	}

?>


<!DOCTYPE html>
 <head>
        
		<meta charset="utf-8" />
		<link href="../css/style_new.css" rel="stylesheet" type="text/css" />
    	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    	<meta name="viewport" content="width=device-width,initial-scale=1">
    	<link rel='shortcut icon' href='../eis.png'>
</head>

<body>
	<?php 
		if(isset($_GET['task_id'])) { $task_id=$_GET['task_id']; }
	?>

	<div id="header">
      <!--header -->
      <div class="header-logo">e.i.s. - enterprise informating system</div>
      <!--logo -->
      <div class="header-menu">
         <!--menu-->
         <?php include("../include/menu.php") ?>
      </div>
      <div id="txt" style="float:right; margin-right:5px; color:#fff"></div>
   </div>
   </div><!-- header -->
		

	<div id="layout">
		<div id="task_note_wrap">
			<form action="task_note.php" method="post">
				<input type="hidden" name="id" value="<?php echo $task_id; ?>">  
				<h2> Add the task note: </h2>
				<table>
					<tr>
						<td><textarea name="note_text"></textarea></td>
					</tr>
					<tr>
						<td style="text-align: right"><button type="submit" name="add_note" class="flat-btn">Add note</button></td>
					</tr>
					
				</table>
			</form>
			<div><a href="index.php?display_as=list" class="action_link"><< Back</a></div>	
		</div>	
	</div>
</body>