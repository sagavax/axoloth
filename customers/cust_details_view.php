<?php include("../include/dbconnect.php"); ?>

<?php

$customer=$_GET["q"];
$sql= "SELECT customer_short_details FROM tblcustomers WHERE customer_name='$customer'";
/*connecetion to the database */
$result = mysql_query($sql) or die ("MySQL ERROR: ".mysql_error());
$num_rows = mysql_num_rows($result);

if ($num_rows = 0) //no info 
{ echo "no information for this customer";}
while ($row = mysql_fetch_array($result)) {
	$customer_short_details=$row["customer_short_details"];

	echo "$customer_short_details";


}
?>
