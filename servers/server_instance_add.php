	<?php session_start() ?>
	<?php include("../include/dbconnect.php"); ?>


	 <?php
		if (isset($_POST['add_new_instance']))
		{
				   /* server_name,instance_info,instance_name,instance_version,instance_note,DateAdded */	
		        
		        $server_id=$_POST['server_id'];
		        $server_name=$_POST['server_name'];
		        $instance_name=mysql_real_escape_string($_POST['instance_name']);
				$instance_version=mysql_real_escape_string($_POST['instance_version']);
				$instance_name=mysql_real_escape_string($_POST['instance_name']);
				$instance_type=$_POST['instance_type'];
				$instance_technology=$_POST['instance_technology'];
				$instance_short_note=mysql_real_escape_string($_POST['$instance_short_note']);

		        $sql= "INSERT INTO tblserver_instances (server_id, server_name,instance_info,instance_name, instance_type,instance_technology,instance_version,instance_short_note,date_created) VALUES ($server_id,'$server_name','$instance_info','$instance_name','$instance_type','$instance_technology','$instance_version','$instance_short_note',now())";
		
		       	//echo $sql;
		       	$result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
				
				echo "<div class='msgbox'><b>New instance has been added successfully</b></div>";
		}
		
	 ?>


	<!DOCTYPE html>
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
	    <link rel='shortcut icon' href='../eis.ico'>
	    <title>ServerIS - servers</title>
	        
	</head>
      
		
	<body>

			<div id="header"> <!--header -->
                        <div class="header-logo">e.i.s. - enterprise informating system</div> <!--logo -->
                         <div class="header-menu"> <!--menu-->
                                <ul>
	                                  
							          <li><a href="../serveris.php" target="_self">Home</a></li>
							          <li><a href="../passmgr/index.php" target="_self">Password manager</a></li>
							          <li><a href="../notepad/index.php" target="_self">Notepad</a></li>
							          <li><a href="../customers/index.php" target="_self">Customers</a></li>
							          <li><a href="../tasks/index.php" target="_self">Tasks</a></li>
							          <li><a href="index.php" target="_self">Servers</a></li>
							          <li><a href="../projects/index.php" target="_self">Projects</a></li>
							          <li><a href="../events/index.php" target="_self">Events</a></li>
							          <li><a href="../contacts/index.php" target="_self">Contacts</a></li>
							         
                                </ul>
           				 </div>
           				 <div id="txt" style="float:right; margin-right:5px; color:#fff"></div></div>
    </div><!-- header -->

		<?php

		if(isset($_GET['server_id'])){
			$server_id=$_GET['server_id'];
		} else {
			$server_id=$_POST['server_id'];
		}
		
		$id=$_GET['server_id'];

		$sql="select server_name from tblserver_info WHERE id=$server_id";
		$result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
		$row = mysql_fetch_array($result);
		$server_name=$row['server_name'];

	?>	

		<div id="add_new_instance_wrap"> 
			<form accept-charset="utf-8" method="post" action="server_instance_add.php">
				<h2> Create new instance for <?php echo $server_name; ?> </h2>
				<table id="add_instance" border="0" cellspacing="0">
					<input type="hidden" name="server_id" value="<?php echo $server_id;?>">
					<input type="hidden" name="server_name" value="<?php echo $server_name;?>">
					<tr>
					    <th>Instance name:</div></th>
					    <td> <input type="text" name="instance_name" id="instance_name"></td>
					  </tr>
					 <tr>
					    <th>Instance info:</th>
					    <td><input type="text" name="instance_info" value=""></td>
					  </tr>
					  <tr>
					    <th>Version:</th>
					    	<td><input type="text" name="instance_version" value="" /></td>
					  </tr>
					  <tr>
					    	<th>Instance type</th>
					    	<td><select name="instance_type">
					    		<option value="1">Database</option>
					    		<option value="2">Web</option>
					    	</select></td>
					  </tr>
					  <tr>
					   	<th>Instance technology:</th>
					   	<td><select name="instance_technology">
					    		<option value="1">IIS</option>
					    		<option value="1">Apache</option>
					    		<option value="2">MS SQL</option>
					    		<option value="2">Oracle</option>
					    		<option value="2">Sybase</option>
					    	</select></td>
					    </tr>
					 <tr>
					 	<th>Short note:</th>
					 	<td><textarea name="instance_short_note"></textarea></td>
					 </tr> 
					<tr>
						<td colspan="2" style="text-align: right"><button  name="add_new_instance" class="flat-btn">Add new instance</button></td>
					</tr>
				</table>
				
			</form>
		<div>
		<a href='index.php' class="action_link">&lt;&lt; Back</a>	   
	
	
		<?php
		function curPageURL() {
		 $pageURL = 'http';
		 $pageURL .= "://";
		 if ($_SERVER["SERVER_PORT"] != "80") {
		  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		 } else {
		  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		 }
		 return $pageURL;
		}
		?>

</body>
</html>