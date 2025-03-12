<?php include("../include/dbconnect.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sk" lang="sk">
<head>
<meta charset="utf-8" />
    <link href="../css/style.css" rel="stylesheet" type="text/css">
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="" />
    <title>Delete note id=<?php $id ?></title>
<?php 
$sql="DELETE from tblcustomer_notes WHERE id=$id";
$result = mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
echo "<div class='msgbox'>note has been deleted successfully</div>";
?>

<meta HTTP-EQUIV="REFRESH" content="3;url=.">