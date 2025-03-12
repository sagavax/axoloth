<?php ob_start();?>
<?php include("../include/dbconnect.php"); ?>
<?php include("../include/functions.php"); ?>

<?php
	if(isset($_POST['save_changes'])){
		$project_id=$_POST['project_id'];
		$project_name=mysql_real_escape_string($_POST['project_name']);
		$project_code=mysql_real_escape_string($_POST['project_code']);
		$project_description=mysql_real_escape_string($_POST['project_description']);
		$customer_id=$_POST['customer'];
		$project_created=$_POST['project_created'];
		$project_deadline=$_POST['project_deadline'];
		$project_status=$_POST['project_status'];



		if($project_status=='finished'){
			$project_finished=date('Y-m-d H:i:s');
			$sql="UPDATE tblcustomer_projects SET cust_id=$customer_id,project_name='$project_name',project_code='$project_code',project_status='$project_status',project_descr='$project_description',project_deadline='$project_deadline',project_created='$project_created', project_finished='$project_finished' WHERE project_id=$project_id";	
		} else {
			$sql="UPDATE tblcustomer_projects SET cust_id=$customer_id,project_name='$project_name',project_code='$project_code',project_status='$project_status',project_descr='$project_description',project_deadline='$project_deadline',project_created='$project_created' WHERE project_id=$project_id";			
		}

		$result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());

		//print_r($customer_id);
		
		
		//echo $sql;
		

	}

	if (isset($_POST['add_task'])){
		$project_id=$_POST['project_id'];
		header('location:project_task.php?action=new&project_id='.$_POST['project_id'].'');
	}

	if (isset($_POST['add_comment'])){
		$project_id=$_POST['project_id'];
		header('location:project_comment.php?action=new&project_id='.$_POST['project_id'].'');
	}
	if (isset($_POST['edit_comment'])){
		$project_id=$_POST['project_id'];
		$comment_id=$_POST['comment_id'];
		header('location:project_comment.php?action=edit&comment_id='.$_POST['comment_id'].'');

	}

	if (isset($_POST['remove_comment'])) {
		$comment_id=$_POST['comment_id'];
		header('location:project_comment.php?action=remove&comment_id='.$_POST['comment_id'].'');
	}

	if(isset($_POST['edit_task'])) { //upravujeme task
		$project_id=$_POST['project_id'];
		$task_id=$_POST['task_id'];
		header('location:project_task.php?action=edit&task_id='.$_POST['task_id'].'&project_id='.$_POST['project_id'].'');

	}
	
	if(isset($_POST['remove_task'])) { //vymazem task
		$project_id=$_POST['project_id'];
		$task_id=$_POST['task_id'];
		header('location:project_task.php?action=remove&task_id='.$_POST['task_id'].'&project_id='.$_POST['project_id'].'');

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
	<title>E.I.S. project details</title>
    <link href="../css/style_new.css" rel="stylesheet" type="text/css" />
    <link rel='shortcut icon' href='../eis.ico'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <link href="../css/font-awesome.css" rel="stylesheet" type="text/css" />
</head>
<body>
		
<?php
	if (!isset($_GET['project_id'])){
			$project_id=$_POST['project_id'];
		} else {	
		$project_id=$_GET['project_id'];
	}	
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

			<div id="layout"> <!-- list of projects -->
				<section class="project_section_wrap">
					
					<?php
						$sql="SELECT a.project_name, a.project_code, a.project_descr,a.project_status, a.project_created, a.project_deadline,b.cust_id, b.customer_name from tblcustomer_projects a,tblcustomers b  where a.cust_id=b.cust_id and a.project_id=$project_id";
						//echo "$sql";
						$result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
						$row = mysql_fetch_array($result) ;
					?>
					<form action="project_edit.php?project_id=<?php echo $project_id; ?>" method="post">
						<input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />
						<table id="project_details">
							<tr>
								<th>Project name:</th><td><input type="text" name="project_name" id="project_name" value="<?php echo $row['project_name']?>"/></td> <!-- meno projektu -->
							</tr>
							<tr>
								<th>Project code:</th><td><input type="text" name="project_code" id="project_code" value="<?php echo $row['project_code']?>"/></td> <!-- kod projectu -->
							</tr>
							
							<tr>
								<th>Customer:</th><td>
										
                							<select name="customer">
                                          <?php 
                                            $cust_id=$row['cust_id'];
                                            $customer=CustomerName($cust_id);
                                            echo "<option value=$cust_id selected=\"selected\">$customer</option>";
                                            $sql1="SELECT * from tblcustomers where customer_status='active'";
                                            $result1 = mysql_query($sql1);
                                            while ($row1 = mysql_fetch_array($result1)) {
                                              $cust_id=$row1['cust_id'];
                                              $customer_name=$row1['customer_name'];
                                              echo "<option value='$cust_id'>$customer_name</option>";
                                            }  
                                          ?> 

                                      </select>

								</tr>
							
							
							<tr>
								<th>Description:</td><td><textarea name="project_description" id="project_description"><?php echo $row['project_descr']?></textarea></td><!-- popis projectu -->
							</tr>
							
							<tr>
								<th>Project created:</th><td><input type="text" name="project_created" value="<?php echo $row['project_created']?>"></td>
								
							</tr>

							<tr>
								<th>Deadline:</th><td><input type="text" name="project_deadline" value="<?php echo $row['project_deadline']?>"/></td> <!-- kod projectu -->
							</tr>
							
							<tr>
								<th>Status:</th><td><select name="project_status">
														<option value="<?php echo $row['project_status'] ?>" selected="selected"><?php echo $row['project_status']?></option>
														<option value="New">New</option>
														<option value="pending">Pending</option>
														<option value="in progress">In progress</option>
														<option value="finished">Finished</option>
														<option value="canceled">Canceled</option>
														<option value="postponed">Postponed</option>
													</select>
												</td>
							</tr>
							


							<tr>
								<td colspan="2" style="text-align:right"><button type="submit" name="save_changes" class="flat-btn">Save changes</button></td>
							</tr>
						</table>
					</form>
				</section>

				<section class="project_section_wrap">
					<form action="" method="post">	
						
						<table id="project_task_list">
							<th colspan="8" style="text-align:left"><span style="text-aling:left">Project tasks:</span><span class="add_button"><form action="" method="post"><input type="hidden" name="project_id" value="<?php echo $project_id; ?>"><button name="add_task" type="submit" class="flat-btn">+</button></form></span></th>
							<?php
								$sql="SELECT * from tblcustomer_project_tasks where project_id=$project_id and task_status not in ('void','Completed') ORDER BY task_id DESC";
								//echo "$sql";
								$result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
								while ($row = mysql_fetch_array($result)) {
								
									$task_id=$row['task_id'];
									$task_text=$row['task_text'];
									$task_status=$row['task_status'];
									$task_priority=$row['task_priority'];
									$task_created=$row['task_created'];
									$task_planed_date=$row['task_planed_date'];
									

									/*$tasks="<tr>";
									$tasks.="<td>$task_text</td><td>$task_status</td><td>$task_created</td><td>$task_planed_date</td><td style='width:90px'><form action='' method='post'><input type='hidden' name='project_id' value='$project_id'
									><input type='hidden' name='task_id' value='$task_id'><button type='submit' name='edit_task' class='btn-small'>Edit</button></td><td><form action='' method='post'><input type='hidden' name='project_id' value='$project_id'
									><input type='hidden' name='task_id' value='$task_id'><button type='submit' name='mark_complete' class='btn-small'><i class='fa fa-check'></i></button></td><td><button type='submit' name='remove_task' class='btn-small-red'>x</button></form></td>";
									$tasks.="</tr>"; */


									$tasks="<tr>";
									$tasks.="<td>$task_text</td><td>$task_status</td><td>$task_created</td><td>$task_planed_date</td>";
									$tasks.="<td><a href='project_task.php?task_id=$task_id&actiom=view' class='btn-small'>View</a></td><td><a href='project_task.php?task_id=$task_id&action=edit' class='btn-small'>Edit</a></td><td><a href='project_task.php?task_id=$task_id&action=remove' class='btn-small'>remove</a></td><td><a href='project_task.php?task_id=$task_id&action=task_completed' class='btn-small'>completed</a></td>";
									$tasks.="</tr>";

									echo $tasks;	


								}	
							?>
						</table>		
					</form>	
				</section>
				<section class="project_section_wrap">
					<form action="" method="post">
						<table id="project_comments_list">
							<th><h3>Comments to projects:</h3><span class="add_button"><form action="" method="post"><input type="hidden" name="project_id" value="<?php echo $project_id; ?>"><button name="add_comment" type="submit" class="flat-btn">+</button></form></span></th>
							<?php
								$sql="SELECT * from tblcustomer_project_comments where project_id=$project_id ORDER BY comment_id DESC";
								$result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
								while ($row = mysql_fetch_array($result)) {

								    /*comment_id	int(11) Auto Increment	 
									project_id	int(11)	 
									task_id	int(11)	 
									comment	tinytext NULL	 
									date_added	varchar(10)	  */
									$comment_id=$row['comment_id'];
									$comment=$row['comment'];
									$date_added=$row['date_added'];
								
								echo "<tr>";
								echo "<td>$comment</td><td>$date_added</td><td style='width:90px'><form action='' method='post'><input type='hidden' name=comment_id value=$comment_id /><button type='submit' name='edit_comment' class='btn-small'>Edit</button><button type='submit' name='remove_comment' class='btn-small-red'>x</button></form></td>";
								echo "</tr>";

								}	
							?>
						</table>
				</section>
				<div style="width:100%; float:left; height:40px"><a href="index.php" class="action_link">&laquo;Back</a> </div>	
			</div>
</body>	