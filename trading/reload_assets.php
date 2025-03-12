<?php
    session_start();
    include("../include/dbconnect.php");
    include("../include/functions.php");

global $con;


    $get_assets = "SELECT * from tbltrading_asset";
    $result = mysqli_query($con, $get_assets)  or die("MySQL ERROR: ".mysqli_error($con));
    while ($row = mysqli_fetch_array($result)) {
        $asset_name = $row['asset_name'];
        $asset_symbol = $row['asset_symbol'];

        echo "<div class='asset'>";
            echo "<div class='asset_name'>$asset_name</div>";
            echo "<div class='asset_symbol'>$asset_symbol</div>";
            echo "<div class='asset_action'><button type='button' class='btn-small remove_asset'><i class='fa fa-plus'></i></button></div>";
        echo "</div>";
    }
