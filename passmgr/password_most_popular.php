<?php
include "../include/dbconnect.php";
include "../include/functions.php";

global $con;
$limit = 20;

error_reporting(E_ERROR | E_WARNING | E_PARSE);
$page_ext = ".php";
$page_ext_qry = $page_ext . "?";
$sql_order = "ORDER BY PassID DESC"; //defaultne zotriedenie
?>

<table id="pass_maintable">

                    <tr>
                        <th class="pass_system_name">System name:</th>
                        <th class="pass_user_name">User name:</th>

                        <th class="pass_favorites">Favorite</th>
                        <th>Actions:</th>
                    </tr>


<?php
$sql = "SELECT * from tblpasswords WHERE hits>0 ORDER BY hits DESC LIMIT 5";
//echo $sql;
$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
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
	echo "<td><div class='pass_actions_wrap'><ul class='pass_actions'><li><a href='password${page_ext_qry}id=$id' class='btn-small'><i class='fas fa-eye'></i></a></li><li><a href='password_edit${page_ext_qry}id=$id' class='btn-small'><i class='fas fa-edit'></i></a></li><li><a href='password_archive${page_ext_qry}id=$id' class='btn-small'><i class='fas fa-archive'></i></a></li><li><a href='password_clone${page_ext_qry}id=$id' class='btn-small'><i class='fas fa-copy'></i></a></li><li><a href='password_delete${page_ext_qry}id=$id' class='btn-small remove'><i class='fas fa-times'></i></a></li></ul><div class='mobile_actions'><button class='btn-small'><i class='fa fa-angle-down'></i></button></div></div></td>";
	echo "</tr>";
}

?>
            </table>