<?php 


$con1 =mysqli_connect(null, "minecraft_db", "eTIZAAMcSL", "minecraft_db", null, "/tmp/mariadb101.sock");
//$con1 =mysqli_connect("mariadb101.websupport.sk", "minecraft_db", "eTIZAAMcSL", "minecraft_db", 3312);
$con1= mysqli_connect("mariadb101.websupport.sk", "minecraft_db", "eTIZAAMcSL", "minecraft_db", 3312);
$sql="SELECT cat_id, cat_name from category ORDER BY cat_name ASC";
$result = mysqli_query($con1, $sql)  or die("MySQL ERROR: ".mysqli_error($con1));
while ($row = mysqli_fetch_array($result)) {
    $cat_id=$row['cat_id'];
    $cat_name=$row['cat_name'];
echo "<div class='checkbox_wrap'><input type='radio' name='minecraft_mod' value=$cat_id>$cat_name</div>";
}    
mysqli_close($con1);