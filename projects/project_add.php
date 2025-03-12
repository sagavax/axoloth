<?php ob_start();?>
<?php include("../include/dbconnect.php"); ?>



<?php
            if (isset($_POST['new_project'])) {

                    $project_name=$_POST['project_name'];
                    $project_code=$_POST['project_code'];
                    $project_descr=$_POST['project_description'];
                    $project_status=$_POST['project_status'];
					$cust_id=$_POST['project_customer'];
					$curr_date=$_POST['project_created'];
					$project_deadline=$_POST['project_deadline'];
                    /*if ($project_name=='All') { //ak si zvolim vsetky projekty a
                              echo "<script language=javascript>alert('Zvol si spravny projekt')</script>";
                    } else {*/

                    
                    //date=strtotime(date('Y-m-d', strtotime($curr_date)) . " +10 day");
                    //$end_date= date('Y-m-d', $date);
                
                    $sql = "INSERT INTO tblcustomer_projects (project_name,project_code, cust_id,project_descr,project_created, project_deadline,project_status) VALUES ('$project_name','$project_code',$cust_id,'$project_descr','$curr_date', '$project_deadline','$project_status')";
                    echo "$sql";
                    //echo "<span style='position:absolute; top:0px; left:0px'>$sql</span>";
                   // $result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
                    //header('Location: index.php');
            } 
			
			if (isset($_POST['new_customer'])) {//idem zadat noveho zakaznika
				
				header('Location: ../customers/index.php');

			}
 ?>

<html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sk" lang="sk">
<head>
	<meta charset="utf-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="author" content="" />
		<title>Project</title>
	    <link href="../css/style_new.css" rel="stylesheet" type="text/css" />
	    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
	    <link href="../css/font-awesome.css" rel="stylesheet" type="text/css" />
	    <link rel='shortcut icon' href='../eis.ico'>
</head>

<body>
		
		
		    <div id="header"> <!--header -->
                        <div class="header-logo">e.i.s. - enterprise informating system</div> <!--logo -->
                          <div class="header-menu"> <!--menu-->
                                <ul>
                                  	<li><a href="serveris.php" target="_self"><img src="images/home.png" alt="Home" /></a></li>
			                        <li><a href="../serveris.php" target="_self">Home</a></li>
			                        <li><a href="../passmgr/index.php" target="_self">Passwords</a></li>
			                        <li><a href="../notepad/notepad./php" target="_self">Notepad</a></li>
			                        <li><a href="../customers/index.php" target="_self">Customers</a></li>
			                        <li><a href="../tasks/index.php" target="_self">Tasks</a></li>
									<li><a href="index.php" target="_self">Projects</a></li>			                       
			                        <li><a href="../servers/index.php" target="_self">Servers</a></li>
			                        <li><a href="../admin/index.php" target="_self">Admin</a></li>
                                </ul>
                		</div><!--menu-->
            </div> <!--end of header -->   	

			<div id="layout"> <!-- list of projects -->
                         <!-- project managament - adding of a new project -->
					<!---<span style="position:relative; float: left; margin-left: 5px; margin-top: 5px; font-size: 16px; font-weight: bold;">Add new project</span>	-->
					<div id="project_management_wrap">
						
						 <form accept-charset="utf-8" method="post" action="project_add.php">
						  <h2>Create a new project:</h2>
						  <table id="project-management">
							<tr>
								<th>Project name:</th><td><input type="text" name="project_name" id="project_name"/></td> <!-- meno projektu -->
							</tr>
							<tr>
								<th>Project code:</th><td><input type="text" name="project_code" id="project_code"/></td> <!-- kod projectu -->
							</tr>
							
							<tr>
								<th>Customer:</th><td><select name="project_customer"> <!--meno zakaznika -->
									<option value="1000">-- Choose the customer -- </option>
									<?php
											$sql="SELECT cust_id, customer_name from tblcustomers";
											$result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
											while ($row = mysql_fetch_array($result)) {
												$id=$row['cust_id'];
												$customer_name=$row['customer_name'];
												echo "<option value='$id'>$customer_name</option>";
											}
									
								?>
								<select><button type="submit" name="new_customer"  class="btn-small">+</button></td>
								
							</tr>
							
							
							<tr>
								<th>Description:</td><td><textarea name="project_description" id="project_description"></textarea></td><!-- popis projectu -->
							</tr>
							
							
							<tr>
								<th>Project created:</th><td><input type="text" name="project_created" value="<?php echo date('Y-m-d'); ?>"></td>
								
							</tr>

							<tr>
								<th>Project deadline:</th><td><input type="text" name="project_deadline" value="<?php echo date('Y-m-d'); ?>"/></td> <!-- predpokladany project deadline -->
							</tr>
							
							<tr>
								<th>Status:</th><td><select name="project_status">
														<option value="New">New</option>
														<option value="Pending">Pending</option>
														<option value="finished">Finished</option>
														<option value="Canceled">Canceled</option>
														<option value="Postponed">Postponed</option>
													</select>
												</td>
							</tr>
							
							<tr>
								<td colspan="2" style="text-align:right"><button type="submit" name="new_project" class="flat-btn">New project</button></td>
							</tr>
						</table>
					  </form>                                          
					</div> <!-- project mgmt -->
					
            </div> <!-- middle -->
            
            <div style="clear:both;"></div>
			
		</div>
</body>
</html>