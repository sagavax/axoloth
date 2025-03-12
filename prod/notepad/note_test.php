<?php session_start() ?>
<?php include("../include/dbconnect.php"); ?>

<?php
		

		$query  = explode('&', $_SERVER['QUERY_STRING']);
		$params = array();

		foreach( $query as $param )
			{
			  list($name, $value) = explode('=', $param, 2);
			  $params[urldecode($name)][] = urldecode($value);
			}
		
		foreach ($params['note_id'] as $param) {
			$sql="DELETE from tblcustomer_notes WHERE id=$param";
			echo $sql."<br>";
		}

		/*$note_id=$_GET['note_id'];

		/*$sql="DELETE from tblcustomer_notes WHERE id=$note_id";
		$result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
		header('location:index.php',true,301);
        exit; */

	?>

