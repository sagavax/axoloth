<?php

	include ("../include/dbconnect.php");
	
?>	

<?php  $instance_id=$_GET['instance_id']; ?>


<?php	
	

	$sql="DELETE FROM tblserver_instances WHERE id='$instance_id'";
	$result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());

?>	
	
<meta HTTP-EQUIV="REFRESH" content="3;url=index.php">