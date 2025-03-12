<?php 
$con1 =mysqli_connect(null, "minecraft_db", "eTIZAAMcSL", "minecraft_db", null, "/tmp/mariadb101.sock");
//$con1= mysqli_connect("mariadb101.websupport.sk", "minecraft_db", "eTIZAAMcSL", "minecraft_db", 3312);
$sql="SELECT modpack_id, modpack_name from modpacks";
$result = mysqli_query($con1, $sql)  or die("MySQL ERROR: ".mysqli_error($con1));
while ($row = mysqli_fetch_array($result)) {
    $modpack_id=$row['modpack_id'];
    $modpack_name=$row['modpack_name'];
echo "<div class='checkbox_wrap'><input type='radio' name='minecraft_modpack' value='$modpack_id'>$modpack_name</div>";
}    
mysqli_close($con1);