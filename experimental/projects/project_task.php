<?php ob_start();?>
<?php include("../include/dbconnect.php"); ?>

<?php
	if(isset($_POST['add_task'])){
		/*

		project_id	int(5) Auto Increment
		task_text	text	text tasku
		task_status	varchar(8)
		task_created	datetime
		task_planed_date	datetime	datum ked ma byt task spraveny

		*/
		$project_id=$_POST['project_id'];
		$task_text=mysql_real_escape_string($_POST['task_text']);
		$task_priority=$_POST['task_priority'];
		$task_created=date('Y-m-d H:i:s');
		$task_due_date=$_POST['task_planned_date'];
		$sql="INSERT INTO tblcustomer_project_tasks (project_id, task_text,task_status, task_priority,task_created,task_planned_date) VALUES ($project_id,'$task_text','new','$task_priority','$task_created','$task_due_date')";


		$result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
		header('location:project_edit.php?project_id='.$_POST['project_id'].'');
		}

	if(isset($_POST['edit_task'])){

		$project_id=$_POST['project_id'];
		$task_id=$_POST['task_id'];
		$task_planned_date=$_POST['task_planned_date'];
		$task_text=$_POST['task_text'];
		$task_created=$task_created=$_POST['task_created'];


	      if(isset($_POST['mark_as_completed'])) { //dal som checkbox,ze task je coompleted
	        $task_status="Completed";
	        $task_finished=date('Y-m-d H:i:s');
	        $sql="UPDATE tblcustomer_project_tasks SET  task_text='$task_text',task_status='$task_status', task_priority='$task_priority',task_created='$task_created',task_planned_date='$task_planned_date', task_finished='$task_finished' WHERE task_id=$task_id";
	      } else {$task_status=$_POST['task_status'];
	      $sql="UPDATE tblcustomer_project_tasks SET  task_text='$task_text',task_status='$task_status', task_priority='$task_priority',task_created='$task_created',task_planed_date='$task_planned_date' WHERE task_id=$task_id";
	  }
		//echo $sql;
		$result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
		header('location:project_edit.php?project_id='.$_POST['project_id'].'');
	}

?>

<html>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sk" lang="sk">
<head>
	<meta charset="utf-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="author" content="" />
	<title>EIS - add project task</title>
    <link href="../css/style_new.css" rel="stylesheet" type="text/css" />
    <link rel='shortcut icon' href='project.ico'>
     <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <link href="../css/font-awesome.css" rel="stylesheet" type="text/css" />
    <link rel='shortcut icon' href='../eis.ico'>
    </head>
<body>

<?php
	$project_id=$_GET['project_id'];
	$action=$_GET['action'];
?>
		    		<div id="header"> <!--header -->
                        <div class="header-logo">e.i.s. - enterprise informating system</div> <!--logo -->
                          <div class="header-menu"> <!--menu-->
                                <ul>
                                  	<li><a href="serveris.php" target="_self"><img src="images/home.png" alt="Home" /></a></li>
			                        <li><a href="../serveris.php" target="_self">Home</a></li>
			                        <li><a href="../passmgr/index.php" target="_self">Passwords</a></li>
			                        <li><a href="../notepad/index.php" target="_self">Notepad</a></li>
			                        <li><a href="../customers/index.php" target="_self">Customers</a></li>
			                        <li><a href="../tasks/index.php" target="_self">Tasks</a></li>
															<li><a href="index.php" target="_self">Projects</a></li>
			                        <li><a href="../servers/index.php" target="_self">Servers</a></li>
			                        <li><a href="../admin/index.php" target="_self">Admin</a></li>
                                </ul>
                		</div><!--menu-->
            </div> <!--end of header -->

			<div id="layout"> <!-- layout  -->
				<div id="new_task_wrap"><!-- wrapper -->


         		<?php
         		if ($action=='new') {
         		?>

         			<H2>Add new task:</H2>
				    <form accept-charset="utf-8" method="post" action="project_task.php">
					      <input type="hidden" name="project_id" value="<?php echo $project_id ?>">
					      <table id="new_project_task" border="0" cellspacing="0" bgcolor="#D7E2F2">

					          <tr>
					           	<td><textarea name="task_text" placeholder="task text..."/></textarea></td>
					          </tr>

					          <tr>
					        	<td><select name="task_priority">
							        		<option value="normal" selected="selected">Normal</option>
							        		<option value="low">Low</option>
							        		<option value="normal">Normal</option>
							        		<option value="high">High</option>
					        		</select></td>

					        </tr>
					         <tr>
					           	<td><input name="task_due_date" type="text" value="<?php echo date('Y-m-d'); ?>"/></td> <!-- dorobit datapicker -->
					          </tr>

					        <tr>
					           <td stye="text-align:right"><button name="add_task" class="flat-btn">Add new task</button></td>
					        </tr>
					    </table>
					</form>


					<?php
					} elseif ($action=='edit') { //ak chcem editovat

						$task_id=$_GET['task_id'];
					?>
					<H2>Edit tasks:</H2>
					  <form accept-charset="utf-8" method="post" action="project_task.php">
					      <input type="hidden" name="project_id" value="<?php echo $project_id ?>">
					      <input type="hidden" name="task_id" value="<?php echo $task_id ?>">
					      <table id="new_project_task" border="0" cellspacing="0" bgcolor="#D7E2F2">

					      <?php

					      	$sql="SELECT * from tblcustomer_project_tasks WHERE  task_id=$task_id";
					      	$result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
					      	$row = mysql_fetch_array($result);

					      ?>


					          <tr>
					           	<td><textarea name="task_text"/><?php echo $row['task_text'] ?></textarea></td>
					          </tr>

					          <tr>
					        	<td><select name="task_priority">
							        		<?php
							        			$task_priority=$row['task_priority'];
							        			echo "<option value=$task_priority selected='selected'>$task_priority</option>"
							        		?>
							        		<option value="low">Low</option>
							        		<option value="normal">Normal</option>
							        		<option value="high">High</option>
					        		</select></td>

					        </tr>
					         <tr>
					           	<td><input name="task_created" type="text" value="<?php echo $row['task_created'] ?>"/></td>
					          </tr>
					         <tr>
					           	<td><input name="task_planed_date" type="text" value="<?php echo $row['task_planed_date'] ?>"/></td>
					          </tr>
					          <tr>
					          	<td>
					          		<select name="task_status">
                                              <?php
                                                 $task_status=$row['task_status'];
                                                 echo "<option value='$task_status' selected=\"selected\">$task_status</option>";
                                              ?>
                                                <option value="New">New</option>
                                                <option value="In progress">In progress</option>
                                                <option value="Pending">Pending</option>

                      </select>
					        	</td>
					        </tr>
					        <tr>
                        		<td style="width:20px">Completed:<input type="checkbox" name="mark_as_completed" value="1" style="width:25px;float:right"></td>
                      		<tr>
					        <tr>
					           <td stye="text-align:right"><button name="edit_task" class="flat-btn">Edit task</button></td>
					        </tr>
					    </table>
					</form>

					<?php
						} elseif ($action=='remove') { //void task
							$project_id=$_GET['project_id'];
							$task_finished=date('Y-m-d H:i:s');
							$task_id=$_GET['task_id'];
							$sql="UPDATE tblcustomer_project_tasks set task_status='void', task_finished='$task_finished' where task_id=$task_id";
							//echo $sql;
							$result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
							header('location:project_details.php?project_id='.$_GET['project_id'].'',true,303);
						} elseif
								 ($action=='task_completed'){ //mark task as completed
									$task_id=$_GET['task_id'];
									$task_finished=date('Y-m-d H:i:s');
									$sql="UPDATE tblcustomer_project_tasks set task_status='finished',task_finished='$task_finished' where task_id=$task_id";
									$result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
									header('location:project_details.php?project_id='.$_GET['project_id'].'',true,303);
								}
						?>
         		</div>
			</div>
