<?php
    session_start();
    include("../include/dbconnect.php");
    include("../include/functions.php");

    global $con;


    $platform = $_GET['platform'];

    $search_platform = "SELECT * from tbltrading_platforms WHERE platform_name LIKE'%$platform%' ORDER BY platform_name ASC";
    $result = mysqli_query($con, $search_platform)  or die("MySQL ERROR: ".mysqli_error($con));
    echo "<ul>";
    while ($row = mysqli_fetch_array($result)) {
        $id=$row['id'];
        $platform = $row['platform_name'];

        echo "<li><div class='platform'><div class='platform_name'>$platform</div><div class='platf_actions'><button type='button' class='action_menu'><i class='fas fa-ellipsis-v'></i></button></div><div class='pl_menu'>";

        echo "<div class='menu'>
        <ul>
            <li>Edit</li>
            <li>Remove</li>
            <li>Add note</li>
            <li>Add asset</li>
        <ul>
    </div>";
        
        echo "</div></li>";
    }    

?>
</ul>