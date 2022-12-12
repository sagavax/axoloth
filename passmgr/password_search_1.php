<?php
include "../include/dbconnect.php";
include "../include/functions.php";

$search_string = $_GET['text'];

$search_password = "SELECT * from tblpasswords where system_name LIKE '%$search_string%' OR user_name LIKE '%$search_string%' OR description LIKE '%$search_string%' and is_archived=0";
$result = mysqli_query($con, $search_password) or die("MySQL ERROR: " . mysqli_error($con));

$page_ext = ".php";
$page_ext_qry = $page_ext . "?";

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

	/*  if($url<>'') {//url je zadane
		                   // echo "<tr data-href='$url'>";

		                } else {
		                echo "<tr data-href='password.php?id=$id'>";

		                    }

	*/
	echo "<td class='pass_system_name hasTooltip' data-href='$url'><b>$system_name</b></td>";
	echo "<td>$user_name</td>";
	//echo "<td>$customer_name</td>";
	echo "<td><div id='fav-$id' class='fav_id'>";
	if ($is_favorite == 1) {
		echo "<a href='#' onclick='RemoveFavorite($id);'><i class='fas fa-star'></i></a>";
	} else {
		echo "<a href='#' onclick='AddFavorite($id);'><i class='far fa-star'></i></a>";
	}
	echo "</div></td>";
	echo "<td><ul id='pass_actions'><li><a href='password${page_ext_qry}id=$id' class='btn-small' >View</a></li><li><a href='password_edit${page_ext_qry}id=$id' class='btn-small'>Edit</a></li><li><a href='password_delete${page_ext_qry}id=$id' class='btn-small'><i class='fas fa-times'></i></a></li><li><a href='password_archive${page_ext_qry}id=$id' class='btn-small'><i class='fas fa-archive'></i></a></li></ul></td>";
	echo "</tr>";
}

?>