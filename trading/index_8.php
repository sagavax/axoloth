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
            <div class="trading_wrap">
                <div id="tabs">
                            <div class="tab_active" tab-data="platforms">Platforms<button id="modal_add_platform" class="btn-small"><i class="fa fa-plus"></i></button></div>
                            <div class="tab_inactive" tab-data="assets">Asset<button id="modal_add_asset" class="btn-small"><i class="fa fa-plus"></i></div>
                            <div class="tab_inactive" tab-data="notes">Notes<button id="modal_add_note" class="btn-small"><i class="fa fa-plus"></i></div>

                 </div>



                 <div class="trading_platforms">

                    <div class="search_wrap">
                            <input type="search" id="search_platform" autocomplete="off" placeholder="Search in platfoms" required>
                    </div>
                    <div class="platforms">
                        <ul>
                            <?php
$sql = "SELECT * from tbltrading_platforms ORDER BY platform_name ASC";
$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
while ($row = mysqli_fetch_array($result)) {
	$id = $row['id'];
	$platform = $row['platform_name'];
	$platform_url = $row['platform_url'];

	echo "<li><div class='platform' id=$id platform-url=$platform_url><div class='platform_name'>$platform</div><div class='platf_actions'><button class='btn-small add_platf_note' type='button' title='add note'><i class='fa fa-plus'></i></button><button class='btn-small add_platf_asset' type='button' title='add asset'><i class='fa fa-plus' class=''></i></button><button type='button' class='btn-small action_menu'><i class='fas fa-ellipsis-v'></i></button></div><div class='pl_menu'></div></li>";
	echo "<div class='trading_feeds'>";
	$get_platf_notes = "SELECT * from tbltrading_platform_notes where platform_id=$id";
	//echo $get_platf_notes;
	$result_platf_notes = mysqli_query($con, $get_platf_notes) or die("MySQL ERROR: " . mysqli_error($con));
	while ($row_note = mysqli_fetch_array($result_platf_notes)) {
		$note_id = $row_note['note_id'];
		$note_text = $row_note['note_text'];

		echo "<div class='note' note-id=$note_id platform-id=$id>";
		echo "<div class='note_text'>$note_text</div>";
		echo "<div class='note_action'><button type='button' class='btn-small note_delete'><i class='fa fa-times'></i></button></div>";
		echo "</div>";
	}

	echo "</div>";
}

?>
                        </ul>
                    </div>
                </div><!-- trading platforms -->

                    <div class="trading_assets">
                        <div class="search_wrap">
                            <input type="search" id="search_asset" autocomplete="off" placeholder="Search in assets">
                            <div class="asset_display_as"><button class="flat-btn" type="button" title="display as grid"><i class="fas fa-th"></i></button><button class="flat-btn" type="button" title="display as list"><i class="fas fa-th-list"></i></button></div>
                            <div class='assets_grid'>
                                <?php
$get_assets = "SELECT * from tbltrading_asset";
//echo $get_assets;
$result = mysqli_query($con, $get_assets) or die("MySQL ERROR: " . mysqli_error($con));
while ($row = mysqli_fetch_array($result)) {
	$asset_id = $row['asset_id'];
	$asset_name = $row['asset_name'];
	$asset_symbol = $row['asset_symbol'];

	/* echo "<div class='asset id=$asset_id'>";
		                                            echo "<div class='asset_name'>$asset_name</div>";
		                                            echo "<div class='asset_symbol'>$asset_symbol</div>";
		                                            echo "<div class='asset_action'><button type='button' class='btn-small remove_asset'><i class='fa fa-plus'></i></button></div>";
	*/
	// echo "<div class='asset_grid'>";
	echo "<div class='asset_card id=$asset_id'>";
	echo "<div class='asset_top'><span class='asset_symbol'>$asset_symbol</span></div>";
	echo "<div class='asset_info'>";
	echo "<div class='asset_card_name'>$asset_name</div>";
	echo "<div class='asset_info_block'>";
	echo "<dl>
                                                                <dt>28.44</dt>
                                                                <dd>Market Cap</dd>
                                                            </dl>";
	echo "</div>";
	echo "<div class='asset_price_change'><span>0</span><span>0</span><span>0</span></div>";
	echo "</div>";
	echo "</div>";
	//  echo "</div>";
}
?>
                            </div>

                        </div>
                    </div>

                    <div class="trading_notes">
                        <div class="search_wrap">
                            <input type="search" id="search_notes" autocomplete="off" placeholder="Search in notes">
                            <div class="notes">
                            <?php
$get_assets = "SELECT * from tbltrading_platform_notes";
$result = mysqli_query($con, $get_assets) or die("MySQL ERROR: " . mysqli_error($con));
while ($row = mysqli_fetch_array($result)) {
	$note_title = $row['note_title'];
	$note_text = $row['note_text'];

	echo "<div class='note'>";
	echo "<div class='note_text'>$note_text</div>";
	echo "<div class='note_action'><button type='button' class='btn-small remove_asset'><i class='fa fa-plus'></i></button></div>";
	echo "</div>";
}
?>
                            </div>
                        </div>
                    </div>
            </div><!-- trading wrapper -->
        </div><!-- trading info wrapper -->
    </div><!--layot -->

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
        <textarea id="note_text" placeholder="type something ..."></textarea>
        <div class="action_wrap">
            <button type="button" class="flat-btn" id="hide_note"><i class="fa fa-times"></i></button>
            <button type="button" class="flat-btn" id="add_note"><i class="fa fa-plus"></i> Add</button>
        </div>
    </div>

    <!-- add current asset for particular platform -->
    <div class="platform_asset">
        <select id="asset"><button class="btn-small" type="button"><i class="fa fa-refresh"></i></button>
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