<?php
 include_once("../include/functions.php");
 $new_task_id=$_POST['task_id'];
 
 $min_mod=0;
 $min_modpack=0;
 $task_text=GetTaskText($new_task_id);
 $start_task_date=date("Y-m-d H:i:s");

 $connection = mysqli_connect("mariadb101.websupport.sk", "minecraft_db", "eTIZAAMcSL", "minecraft_db", 3312);
 $sql="INSERT INTO to_do (eis_task_id, cat_id, modpack_id, task_text,added_date) VALUES ($new_task_id,$min_mod,$min_modpack,'$task_text','$start_task_date')";
 echo $sql;
 
 $myfile = fopen("log.txt", "w") or die("Unable to open file!");
 fwrite($myfile, $sql);
fclose($myfile);
 
$result = mysqli_query($connection, $sql) or die("MySQLi ERROR: ".mysqli_error($connection));
mysqli_close($connection);

echo "<script>document.getElementById('SyncTask').style.display=none;</script>";
?>