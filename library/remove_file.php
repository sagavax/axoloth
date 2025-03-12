<?php session_start() ?>    
<?php include("../include/dbconnect.php"); ?>
<?php include("../include/functions.php"); ?>




<?php 


$file_id=$_GET['file_id'];

$sql="SELECT file_name from tblfile_library WHERE file_id=$file_id";
       echo $sql;
       $result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
                             while ($row = mysql_fetch_array($result)) {
                             	 $file_name=$row['file_name'];
                             	 	echo "Subor:".$file_name;
                             	 }

//echo "Id:".$file_id;
 //echo "Subor:".$file_name;
 
 if(file_exists(ROOT_DIRECTORY.'/documents/'.$file_name)){ //ak subor existuje
    unlink(ROOT_DIRECTORY.'/documents/'.$file_name);
    $sql="DELETE from tblfile_library WHERE file_id=$file_id"; //vymazat zaznam z managera
    $result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
}else{ //ak neexistuje s tym,ze som subor vymazal nejako inac
    //echo "subpor neexistuje"
    $sql="DELETE from tblfile_library WHERE file_id=$file_id"; //vymazat zaznam z managera
    $result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
}

header('location:index.php');
