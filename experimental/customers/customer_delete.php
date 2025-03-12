<?php include("../include/dbconnect.php"); ?>

<?php
				global $con;
				$sql="DELETE from tblcustomers WHERE customer_name='$customer'";
				$result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error());
				echo "<div class='message'>The customer has been deleted successfully</div>";

?>

<meta HTTP-EQUIV="REFRESH" content="3;url=index.php">
