<?php session_start() ?>
<?php include("../include/dbconnect.php"); ?>


<?php  
	      if(isset($_POST['add_note'])) {
	      	$server_name=$_POST['server_name'];
			$server_id=$_POST['server_id'];  	
	      	$note=mysql_real_escape_string($_POST['note_text']);
	      	//note_title=mysql_real_escape_string($_POST['note_title']);
			$date_created = date('Y-m-d H:m:s');
			
			$sql="INSERT INTO tblserver_notes (server_id, server_name, note, date_created) VALUES ($server_id, '$server_name', '$note', '$date_created')";
			$result = mysql_query($sql);
			header('location:index.php');
	      }
	
	?>

<!DOCTYPE html">

 <head>
      <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sk" lang="sk">
      <meta name="viewport" content="width=device-width,initial-scale=1">
      <meta charset="utf-8" />
      <meta name="description" content="" />
      <meta name="keywords" content="" />
      <meta name="author" content="" />
      <link href="../css/style_new.css" rel="stylesheet" type="text/css" />
      <link href="../css/style.css" rel="stylesheet" type="text/css" />
      <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
      <link href="../css/font-awesome.css" rel="stylesheet" type="text/css" />
      <link rel='shortcut icon' href='../eis.ico'>
      <title>ServerIS - servers</title>

</head>
<body>


	<?php

		$server_id=$_GET['server_id'];

	?>	

	<div id="header"> <!--header -->
	    <div class="header-logo">e.i.s. - enterprise informating system</div> <!--logo -->
	    <div class="header-menu"> <!--menu-->
	        <ul>
	          <li><a href="serveris.php" target="_self"><img src="../images/home.png" alt="Home" /></a></li>
	          <li><a href="../serveris.php" target="_self">Home</a></li>
	          <li><a href="../passmgr/index.php" target="_self">Password manager</a></li>
	          <li><a href="../notepad/index.php" target="_self">Notepad</a></li>
	          <li><a href="../customers/index.php" target="_self">Customers</a></li>
	          <li><a href="../links/index.php" target="_self">Links</a></li>
	          <li><a href="../tasks/index.php" target="_self">Tasks</a></li>
	          <li><a href="../incidents/index.php" target="_self">Incidents</a></li>
	          <li><a href="../servers/index.php" target="_self">Servers</a></li>
	          <li><a href="/admin/index.php" target="_self">Admin</a></li>
	        </ul>
	    </div>
	 </div> <!--end of header -->
	<div id="layout">
		<div id="main_wrap">
	                    <div id="left_content">
							 
	                		<div id="add_new_note_wrap">
	                			
	                			<?php 

	                				$sql="SELECT server_name from tblserver_info where id=$server_id";
	                				//echo $sql;
	                				$result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
	                				$row = mysql_fetch_array($result)
	                				
	                			?>	
		                			<form action="server_note_add.php" method="post">
		                				
		                				<input type="hidden" name="server_id" value="<?php echo $_GET['server_id'] ?>">
		                				<table id="server_note">
		                				<h2>Add new note server: <?php echo $row['server_name'] ?></h2>
		                					
		                					<tr>
		                						<td>Text:</td><td><textarea name="note_text"></textarea></td>
		                					</tr>
		                					<tr>
		                						<td colspan="2" style="text-align:right"><button name="add_note" type="submit" class="flat-btn">Add note</button></td>
		                				</table>
		                		</form>			
	                		</div>	<!-- note wrap -->
							<a href='index.php' class="action_link">&lt;&lt; Back</a>
					</div><!-- left content-->
	 	</div><!-- main wrap-->  
	</div><!-- id layout-->		

</body>
</html>
