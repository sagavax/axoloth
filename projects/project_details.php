<?php ob_start();?>
<?php include("../include/dbconnect.php"); ?>



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
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <link href="../css/font-awesome.css" rel="stylesheet" type="text/css" />
    <link rel='shortcut icon' href='../eis.ico'>
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
						
						$result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
						$row = mysql_fetch_array($result) ;
					?>
					<form action="project_edit.php?project_id=<?php echo $project_id; ?>" method="get">
						<input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />
						<table id="project_details">
							<tr>
								<th>Project name:</th><td><input type="text" name="project_name" id="project_name" value="<?php echo $row['project_name']?>"/></td> <!-- meno projektu -->
							</tr>
							<tr>
								<th>Project code:</th><td><input type="text" name="project_code" id="project_code" value="<?php echo $row['project_code']?>"/></td> <!-- kod projectu -->
							</tr>
							
							<tr>
								<th>Customer:</th><td><input type="text" name="project_customer" value="<?php echo $row['customer_name']?>"></td>
								
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
								<th>Status:</th><td><input type="text" name="project_deadline" value="<?php echo $row['project_status'];?>"></td>
							</tr>
							


							<tr>
								<td colspan="2" style="text-align:right"><button type="submit" name="edit_project" class="flat-btn">Edit project details</button></td>
							</tr>
						</table>
					</form>
				</section>

				<section class="project_section_wrap">
					<form action='' method='get'>
					<input type="hidden" name="project_id" value=<?php echo $project_id; ?>>						
						<table class="project_section_list">
							
							<th><h3>Project tasks:</h3></th>
							<?php
								//$sql="SELECT * from tblcustomer_project_tasks where project_id=$project_id and task_status not in ('void','complete') ORDER BY task_id DESC";
								$sql="SELECT * from tblcustomer_project_tasks where project_id=$project_id ORDER BY task_id DESC"; //vsetky tasky
								
								$result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
								while ($row = mysql_fetch_array($result)) {
								
									$task_id=$row['task_id'];
									$task_text=$row['task_text'];
									$task_status=$row['task_status'];
									$task_priority=$row['task_priority'];
									$task_created=$row['task_created'];
									$task_planed_date=$row['task_planed_date'];
									

									$tasks="<tr>";
									$tasks.="<td>$task_text</td><td>$task_status</td><td>$task_created</td><td>$task_planed_date</td>";
									$tasks.="</tr>";

									echo $tasks;	


								}	
							?>
						</table>		
					</form>
				</section>
				<section class="project_section_wrap">
					
						<table class="project_section_list">
							<th><h3>Comments to projects:</h3></th>
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
								echo "<td>$comment</td><td>$date_added</td>";
								echo "</tr>";

								}	
							?>
						</table>
				</section>	
			</div>
</body>	