<?php session_start() ?>    
<?php include("../include/dbconnect.php"); ?>
<?php include("../include/functions.php"); ?>

<?php


if (isset($_POST['update_server']))
{
        	  	$id=$_POST['id']; //server id
        	  	$cust_id=$_POST['customer_name'];
        	    $in_production=$_POST['in_production'];
				$mission_critical=$_POST['mission_critical'];
				$operating_system=$_POST['operating_system'];
				$single_cluster=$_POST['single_cluster'];
				
				if(isset($_POST['in_production'])){
					$in_production='Y';
				} else {$in_production='N';}
				
				if(isset($_POST['mission_critical'])){
					$mission_critical='Y';} else {$mission_critical='N';}

				$note_text=mysql_real_escape_string($_POST['note_text']);

        $sql = "UPDATE tblserver_info SET Server_name='$server_name'
 					, cust_id=$cust_id
 					, operating_system='$operating_system'
					, single_cluster='$single_cluster'
					, in_production='$in_production'
					, mission_critical='$mission_critical'
					, description='$note_text'
		                     WHERE id='$id'";
        
       	$result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
       	header('location:index.php');

        //	echo "<div class='msgbox'><b>the server has been updated !!!</b><a href='index.php'>Home</a></div>";
}		
    
if (isset($_POST['add_instance'])) //klikol som na vytvorenie novej instancie	
	 header('location:server_instance_add.php'); //tak ma to tam presmeruje
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
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
        <link rel='shortcut icon' href='../eis.ico'>
		<!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>-->
        <title>Enterprise informating system - Server manager</title>
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
  <?php
  	
  	$id=$_GET['server_id'];
  ?> 
  
  <div id="container">
  	<div id="center" class="column">
  
		<?php	
			  	$sql= "SELECT * FROM tblserver_info WHERE id='$id'";
				
				 $result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
				$row = mysql_fetch_array($result);
				$server_name=$row['server_name'];
				$cust_id=$row['cust_id'];

				$customer_name = CustomerName($cust_id);

				 $single_cluster=$row['single_cluster'];
				  $in_production= $row['in_production'];
				$mission_critical= $row['mission_critical'];
				$os_id=$row['os_id'];	
	    ?>


	    <div id="edit_server_wrap">
	    	<H2>Edit server details (<?php echo $row['server_name']?>):</h2>
	  		<form accept-charset="utf-8" method="post" action="server_edit.php">  
	   			<input type="hidden" name="id" value="<?php echo $row['id']?>" />
	   			<input type="hidden" name="cust_id" value="<?php echo $row['cust_id']?>" />
	   			<input type="hidden" name="os_id" value="<?php echo $row['os_id']?>" />

	   			<table id="server_edit">
	  
				  <tr>
				    <th>Servername :</th>
				    <td> <input type="text" name="server_name" value="<?php echo $row['server_name']; ?>"></td>
				  </tr>
				   <tr>
				    <th>OS:</th>
				    <td><select name="operating_system" id="operating_system">
				         <?php  
          			           //ziskat meno operacneho systemu z id cka
          			           $os_id=$row['os_id'];

                               $sql="select os_title from tblserver_operating_systems where os_id=$os_id";

                               $result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
				      			 while ($row = mysql_fetch_array($result)) {		
				      			$os_title=$row['os_title'];
				      		}
					           echo "<option value='$os_id' selected='selected'>$os_title</option>";
					      ?> 
				        
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
				    <th>Customer name:</th>
				    <td>
				             
				      <?php
				      
				      $sql="select customer_name, cust_id from tblcustomers where customer_status='active'";   
				      $result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
				      
				      echo "<select name='customer_name'>";        
					  echo "<option value='$cust_id' selected='selected'>$customer_name</option>";
				      while ($row = mysql_fetch_array($result)) {
				      	
					      $cust_id=$row['cust_id']; 
					      $customer_name=$row['customer_name'] ; 
				      
				      
				      echo "<option value='$cust_id'>$customer_name</option>";
				      } 
				      echo "</select></td>";
				      ?></td>
				  </tr>
				  <tr>
				    <th>S/C:</th>
				    <td><select name="single_cluster" id="single_cluster">
				        <?php  
          
					         /*   $single_cluster=$row['single_cluster'];

					           echo $single_cluster;*/

					           echo "<option value='$single_cluster' selected=\"selected\">$single_cluster</option>";
					      ?>
				        <option value='single'>Single</option>
						<option value='cluster'>Cluster</option>
						<option value='cluster node'>Cluster node</option>
					</select>
				   </td>
				  </tr>
				  <tr> 
				  <th>In production:</div></th>
				   <td>
				   	   <?php
						
						if($in_production=='Y'){ echo "<input type='checkbox' checked='true' name='in_production' style='width:20px'>"; } else
						{echo "<input type='checkbox' name='in_production' style='width:20px'>";}

						?>
				       
				   </td>	
				  </tr>
				 <tr>
				 	<th>Mission critical:</th>
				   <td> 
				    	<?php 
							if($mission_critical=='Y'){ echo "<input type='checkbox' name='mission_critical' checked='checked' style='width:20px'>"; } else {echo "<input type='checkbox' name='mission_critical' style='width:20px'>";}
						?>
					 </td>	
				  </tr> 
				  
				  <tr>
				  	<th>Server status:</th><td>
				  		<select name="server_status">
				  			<option value="active">active</option>
				  			<option value="inactive">inactive</option>
				  		</select>
				  </td>
				  <tr>
				  	<th>Note text:</th>
				    <td><textarea id="note_text" name="note_text"><?php echo $row['note'] ?></textarea></td>
				  </tr>
				  
				  <tr>
				    <td colspan="2" style="text-align: right"><button type="submit" name="update_server" class="flat-btn">Update</button></td>	
				  </tr>
			</table>
			<a href='index.php' class="action_link">&lt;&lt; Back</a>
	</div>
</div>

