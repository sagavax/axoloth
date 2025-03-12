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
                    <div class="tab_active" tab-data="platforms">Platforms<button id="modal_add_platform" class="btn-small"><i class="fa fa-plus"></i></button></div>
                    <div class="tab_inactive" tab-data="assets">Asset<button id="modal_add_asset" class="btn-small"><i class="fa fa-plus"></i></div>
                    <div class="tab_inactive" tab-data="notes">Notes<button id="modal_add_note" class="btn-small"><i class="fa fa-plus"></i></div>

            </div>



            <div class="trading_platforms">

                <div class="search_wrap">
                    <input type="text" id="search_platform" autocomplete="off" placeholder="Search in platfoms" required>
                </div>

                <ul>
                    <?php
$sql = "SELECT * from tbltrading_platforms ORDER BY platform_name ASC";
$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
while ($row = mysqli_fetch_array($result)) {
	$id = $row['id'];
	$platform = $row['platform_name'];
	$platform_url = $row['platform_url'];

	echo "<li><div class='platform' id=$id platform-url=$platform_url><div class='platform_name'>$platform</div><div class='platf_actions'><button class='btn-small add_platf_note' type='button' title='add note'><i class='fa fa-plus'></i></button><button class='btn-small add_platf_asset' type='button' title='add asset'><i class='fa fa-plus' class=''></i></button><button type='button' class='btn-small action_menu'><i class='fas fa-ellipsis-v'></i></button></div><div class='pl_menu'></div></li>";
	echo "<div class='trading_feeds'></div>";
}

?>
                </ul>
            </div><!-- trading platforms -->

            <div class="trading_assets">
                <div class="search_wrap">
                    <input type="text" id="search_asset" autocomplete="off" placeholder="Search in assets">
                </div>
            </div>

            <div class="trading_notes">
                <div class="search_wrap">
                    <input type="text" id="search_notes" autocomplete="off" placeholder="Search in notes">
                </div>
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

    <!-- add a note for particular platform -->
    <div class="platform_note">
    <input type="text" placeholder="note title.." id="note_title" autocomplete="off">
        <textarea id="platform_note" placeholder="type something ..."></textarea>
        <div class="action_wrap">
            <button type="button" class="flat-btn" id="hide_note"><i class="fa fa-times"></i></button>
            <button type="button" class="flat-btn" id="add_note"><i class="fa fa-plus"></i> Add</button>
        </div>
    </div>

    <!-- add current asset for particular platform -->
    <div class="platform_asset">
        <select id="asset">
        <option value="0">------------ chose the asset --------------------</option>
            <?php
$assets = "SELECT * from tbltrading_asset";
$result = mysqli_query($con, $assets) or die("MySQL ERROR: " . mysqli_error($con));
while ($row = mysqli_fetch_array($result)) {
	$asset_name = $row['asset_name'];
	$asset_symbol = $row['asset_symbol'];
	echo "<option value='$asset_symbol'>$asset_name</option>";
}
?>
        </select>

        <input type="text" placeholder="amount of asset.." id="asset_amount" autocomplete="off">
        <textarea id="asset_note" placeholder="type something here ..."></textarea>

        <div class="action_wrap">
            <button type="button" class="flat-btn" id="hide_modal_asset"><i class="fa fa-times"></i></button>
            <button type="button" class="flat-btn" id="add_asset"><i class="fa fa-plus"></i> Add</button>
        </div>
    </div>

    <div class="context_menu">
        <ul>
            <li>Add note</li>
            <li>Add asset</li>
            <li>Edit platform</li>
            <li>Remove platform</li>
        </ul>
    </div>

    <script src='../js/trading.js?<?php echo time(); ?>'></script>
</body>
</html>
<!--https://codepen.io/sagavax/pen/xyGdav
    https://codepen.io/sagavax/pen/MPwmqP
    https://codepen.io/sagavax/pen/zmGwaX

    --