<?php

include ("../include/dbconnect.php");
echo "<ul>";
$sql="SELECT * from tbltrading_platforms ORDER BY platform_name ASC";
$result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($con));
    while ($row = mysqli_fetch_array($result)) {
        $id=$row['id'];
        $platform = $row['platform_name'];
        echo "<li><div class='platform'><div class='platform_name'>$platform</div><div class='platf_actions'><button type='button'><i class='fas fa-ellipsis-v'></i></div></div></li>";
    }    
echo "</ul>";    
?>