<?php ob_start();?>
<?php include("../include/dbconnect.php"); ?>

<?php
	if(isset($_POST['add_comment'])){
		/* 

		comment_id	int(11) Auto Increment	 
		project_id	int(11)	 
		comment	tinytext NULL	 
		date_added	varchar(10)y

		*/
		$project_id=$_POST['project_id'];
		$comment=mysql_real_escape_string($_POST['project_comment']);

		$date_added=date('Y-m-d H:i:s');
		
		$sql="INSERT INTO tblcustomer_project_comments (project_id, comment,date_added) VALUES ($project_id,'$comment','$date_added')";
		//echo $sql;
		$result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
		}

	if(isset($_POST['edit_comment'])) {
		$comment_id=$_POST['comment_id'];
		$comment_date=$_POST['comment_date'];
		$project_comment=$_POST['project_comment'];
		$sql="UPDATE tblcustomer_project_comments SET date_created=$comment_date;project_comment=$project_comment WHERE comment_id=$comment_id";
		$result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
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
    <link rel='shortcut icon' href='../eis.ico'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <link href="../css/font-awesome.css" rel="stylesheet" type="text/css" />
    </head>
<body>
		



<?php
	$project_id=$_GET['project_id'];
	$action=$_GET['action']
?>		
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

			<div id="layout"> <!--  -->
				<?php 
						if($action=='new') {
				?>			
				<div id="new_comment_wrap">
					
					<form action="project_comment.php" method="post">
						<input type="hidden" name="project_id" value="<?php echo $project_id ?>">
						<table class="comment_form">
							<tr>
								<td><textarea name="project_comment" placeholder="comment here..."></textarea></td>
							</tr>
							<tr>
								<td><button type="submit" name="add_comment" class="flat-btn">Add comment</button></td>
							</tr>	
						</table>	
					</form>

				</div>
				<?php 
			} elseif ($action=='edit'){
				
			?>
				
				<div id="edit_comment_wrap">
					<?php 
						$comment_id=$_GET['comment_id'];

						$sql="SELECT * from tblcustomer_project_comments WHERE comment_id=$comment_id";
						$result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
						$row = mysql_fetch_array($result);

					?>
					<form action="project_comment.php" method="post">
						<input type="hidden" name="project_id" value="<?php echo $project_id ?>">
						<input type="hidden" name="comment_id" value="<?php echo $comment_id ?>">
						<h3> edit comment </h3>
						<table  class="comment_form">
							<tr>
								<td><input type="text" name="comment_date" value="<?php echo $row['date_added']; ?>"></td>
							</tr>	
							<tr>
								<td><textarea name="project_comment" placeholder="comment here..."><?php echo $row['comment']; ?></textarea></td>
							</tr>
							<tr>
								<td><button type="submit" name="edit_comment" class="flat-btn">Edit comment</button></td>
							</tr>	
						</table>	
					</form>
					<a href="project_details.php?project_id=$project_id" class="action_link">&lt; &lt; Back</a>
				</div>

			<?php	
			} elseif ($action=='remove') { //remove the comment
				$comment_id=$_GET['comment_id'];
				$sql="DELETE from tblcustomer_project_comments WHERE comment_id=$comment_id";
				$result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
		
			}
			
				?>
			</div>		