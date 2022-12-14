<?php
session_start();
include "../include/dbconnect.php";
include "../include/functions.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/style_new.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <link href="../css/trading.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
    <title>Axoloth - trading information</title>
    <link rel='shortcut icon' href='../a.png'>

</head>
<body>
    <?php
include '../include/header.php';
?>

    <div id="layout">
        <div class=" fab-container">
            <div class="fab fab-icon-holder">
                <i class="fas fa-plus"></i>
            </div>
            <ul class="fab-options">
                <li>
                    <span class="fab-label">New note</span>
                    <div class="fab-icon-holder">
                       <i class="fa fa-plus"></i>
                    </div>
                </li>
                <li>
                    <span class="fab-label">New platform</span>
                    <div class="fab-icon-holder">
                         <i class="fa fa-plus"></i></a>
                    </div>
                </li>
            </ul>
        </div><!-- fab container -->

        <div class="trading_info_wrap">
           <div id="tabs">
                <ul>
                    <li class="tab_active">Platforms<button id="modal_add_platform" class="btn-small"><i class="fa fa-plus"></i></button></li>
                    <li class="tab_inactive">Asset<button id="modal_add_asset" class="btn-small"><i class="fa fa-plus"></i></li>
                    <li class="tab_inactive">Notes</li>
                </ul>
            </div>

            <div class="search_wrap">
                <input type="text" id="search_asset" autocomplete="off" placeholder="Search platfom" required>
            </div>

            <div class="trading_platforms">
                <ul>
                    <?php
$sql = "SELECT * from tbltrading_platforms ORDER BY platform_name ASC";
$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
while ($row = mysqli_fetch_array($result)) {
	$id = $row['id'];
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
	echo "<div class='trading_feed'></div>";
}

?>
                </ul>
            </div>
        </div>
    </div>

    <!-- add new asset-->
    <div class="new_asset_platform">
        <input type="text" placeholder="Name of asset" id="asset_name" autocomplete="off" required>
        <input type="text" placeholder="Symbol of asset" id="asset_symbol" autocomplete="off">
        <input type="text" placeholder="Url" id="asset_url" autocomplete="off">

        <select id="asset_type">
            <option value="0">------------ chose a type of asset --------------------</option>
            <option value="fund">fund</option>
            <option value="commodity">commodity</option>
            <option value="crypto">crypto</option>
            <option value="stock">stock</option>
        </select>

        <textarea id="asset_description" placeholder="Asset description..."></textarea>

        <div class="action_wrap">
            <button type="button" class="flat-btn" id="hide_asset"><i class="fa fa-times"></i></button>
            <button type="button" class="flat-btn" id="add_asset"><i class="fa fa-plus"></i> Add</button>
        </div>
    </div>

    <!-- add new trading platform -->
    <div class="new_trading_platform">
        <input type="text" placeholder="Enter name of platform" id="platform_name" autocomplete="off" required>
        <div class="action_wrap">
            <button type="button" class="flat-btn" id="hide_platform"><i class="fa fa-times"></i></button>
            <button type="button" class="flat-btn" id="add_platform"><i class="fa fa-plus"></i> Add</button>
        </div>
    </div>
    <script src='../js/trading.js'></script>
</body>
</html>
<!--https://codepen.io/sagavax/pen/xyGdav
    https://codepen.io/sagavax/pen/MPwmqP
    https://codepen.io/sagavax/pen/zmGwaX

    --