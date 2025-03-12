<?php session_start() ?>    
<?php include("../include/dbconnect.php"); ?>
<?php include("../include/functions.php"); ?>

<?php
if (isset($_POST['add_new_server']))
{
        	 //$lastID = mysql_insert_id();   
		 //if (isset($_GET["prod_in_out"]))
		if(!isset($_POST['prod_in_out']))
		{
		$prod_in_out="N"; 
	        } else  {
		$prod_in_out="Y";
		}
                //echo "$prod_in_out<br>";

		if(!isset($_POST['mission_critical']))
		{
		$mission_critical="N";
		} else {
		$mission_critical="Y";
		}
		//echo "$mission_critical<br>";
	
		// add new seerver
 		$cust_id=$_POST['customer_name'];
 		$description=mysql_real_escape_string($_POST['description']);

 		
 		$sql= "INSERT INTO tblserver_info (cust_id,server_name,operating_system,single_cluster,in_production,mission_critical,description,date_added) VALUES ('$customer_name','$server_name','$operating_system','$single_cluster','$prod_in_out','$mission_critical','$description', now())";
        //echo "$sql";
		$result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());

		/*$sql="SE#LECT MAX(id) as server_id from tblserver_info"; //ziskame posledne id vytvoreneho servra
		$result = mysql_query($sql);*/
		
		/*server_id =$row['server_id'];*/
		
		// added new instance
		/*$sql="INSERT INTO tblserver_instances (server_id,instance_info,instance_name,instance_version,DateAdded) VALUE ('$server_name','$general_note','$sql_instance','$sql_version', now())";
		$result = mysql_query($sql);*/

        
        	echo "<div class='msgbox'><b>New server has been added successfully !!!</b><a href='index.php'></a></div>";
        	header('location:index.php');

}		
     
?>



<!DOCTYPE html>
 <head>
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sk" lang="sk">
		<meta charset="utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<meta name="author" content="" />
        <link href="../css/style_new.css" rel="stylesheet" type="text/css" />
        <link href="../css/style.css" rel="stylesheet" type="text/css" />
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
		<!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>-->
        <title>Enterprise informating system - Server manager</title>
        <link rel='shortcut icon' href='../eis.ico'>
		<script type="text/javascript">
		function upperCase()
		{
		var x=document.getElementById("sql_instance").value
		document.getElementById("sql_instance").value=x.toUpperCase()
		}
		</script>
		  
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

  <div id="container">
  	<div id="center" class="column">
  
	    <div id="new_server_wrap">
	    	<H2>Add new server</h2>
	  		<form accept-charset="utf-8" method="post" action="server_add.php">  
	   			<table id="new_server">
	  
				  <tr>
				    <th>Servername :</th>
				    <td> <input type="text" name="server_name" value=""></td>
				  </tr>
				   <tr>
				    <th>OS:</th>
				    <td><select name="operating_system" id="operating_system">
				        <?php 
				        	$sql="SELECT os_id,os_title from tblserver_operating_systems";
				        	$result = mysql_query($sql);
				        	 while ($row = mysql_fetch_array($result)) {
				        	 	$id=$row['os_id'];
				        	 	$os_title=$row['os_title'];
				        	 	echo "<option value='$id'>$os_title</option>";
				        	 }
						?>				        
					</select></td>
				  </tr> 
				  
				  <tr>
				    <th>S/C:</th>
				    <td><select name="single_cluster" id="single_cluster">
				        <option value='single'>Single</option>
						<option value='cluster'>Cluster</option>
						<option value='cluster node'>cluster node</option>
					</select>
				   </td>
				  </tr>
				  <tr> 
				  <th>In production:</div></th>
				   <td>
				    <input type="checkbox" id="prod_in_out" name="prod_in_out" value="1">
				   </td>	
				  </tr>
				 <th>Mission critical:</th>
				   <td>
				    <input type="checkbox" id="mission_critical" name="mission_critical" value="1">
				   </td>	
				  </tr> 
				  <tr>
				    <th>Customer name:</th>
				    <td>
				             
					      <?php
					      
					      $sql="select cust_id, customer_name from tblcustomers";   
					      $result = mysql_query($sql);
					      
					      echo "<select name='customer_name'>";        
					
					      while ($row = mysql_fetch_array($result)) {
					      	
					      $cust_id=$row['cust_id'];
					      $customer_name=$row['customer_name'] ; 
					      
					      echo "<option value='$cust_id'>$customer_name</option>";
					      } 
					      echo "</select></td>";
					      ?>
					 </td>
				  </tr>
				  
				  <tr>
				    <th>Description:</th>
				    <td><textarea id="note_text" name="description"></textarea></td>
				  </tr>
				  
				  <tr>
				    <td colspan="2" style="text-align: right"><button type="submit" name="add_new_server" class="flat-btn">Add new server</button></td>	
				  </tr>
			</table>
	
	</div>
</div>
<b><a href='index.php' class="action_link">&lt;&lt; Back</a></b>
</BODY>
</HTML>