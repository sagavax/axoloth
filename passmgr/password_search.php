<?php
include "../include/dbconnect.php";
include "../include/functions.php";

$search_string = $_GET['text'];

$search_password = "SELECT * from tblpasswords where system_name LIKE '%$search_string%' OR user_name LIKE '%$search_string%' OR description LIKE '%$search_string%' and is_archived=0";
$result = mysqli_query($con, $search_password) or die("MySQL ERROR: " . mysqli_error($con));

$page_ext = ".php";
$page_ext_qry = $page_ext . "?";
?>
<table id="pass_maintable">

                    <tr>
                        <th class="pass_system_name">System name:</th>
                        <th class="pass_user_name">User name:</th>

                        <th class="pass_favorites">Favorite</th>
                        <th>Actions:</th>
                    </tr>
<?php
while ($row = mysqli_fetch_array($result)) {

	$id = $row["PassID"];
	$cust_id = $row['cust_id'];
	$customer_name = CustomerName($cust_id);
	$group_name = $row['group_name'];
	$system_name = $row['system_name'];
	$user_name = $row['user_name'];
	$password = $row['password'];
	$category = $row['category'];
	$url = $row['url'];
	$description = $row['description'];
	$is_favorite = $row['is_favorite'];

	echo "<td class='hasTooltip' data-href='$url'><b>$system_name</b></td>";
	echo "<td>$user_name</td>";
	//echo "<td>$customer_name</td>";
	echo "<td><div id='fav-$id' class='fav_id'>";
	if ($is_favorite == 1) {
		echo "<a href='#' onclick='RemoveFavorite($id);'><i class='fas fa-star'></i></a>";
	} else {
		echo "<a href='#' onclick='AddFavorite($id);'><i class='far fa-star'></i></a>";
	}
	echo "</div></td>";
	echo "<td>
				<div class='pass_actions_wrap'>
					<button type='button' class='btn-small' onclick='view_pass($id)' title='View'><i class='fas fa-eye'></i></button>
					<button type='button' class='btn-small' onclick='show_add_note($id)' title='Add note'><i class='fas fa-plus'></i></button>
					<button type='button' class='btn-small' onclick='edit_pass($id)' title='Edit' ><i class='fas fa-edit'></i></button>
					<button type='button' class='btn-small' onclick='archive_pass($id)' title='Archive'><i class='fas fa-archive'></i></button>
					<button type='button' class='btn-small' onclick='clone_pass($id)' title='Clone'><i class='fas fa-copy'></i></button>
					<button type='button' class='btn-small remove' onclick='remove_pass($id)' title='Remove'><i class='fas fa-times'></i></button>
				</div>
			</td>";
	echo "</tr>";
}

//https://code-boxx.com/php-mysql-search/#sec-ajax

?>

