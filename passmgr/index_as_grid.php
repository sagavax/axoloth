<?php session_start();
$_SESSION['url'] = $_SERVER['REQUEST_URI'];

include "../include/dbconnect.php";
include "../include/functions.php";

if (isset($_POST['favorite_add'])) {
	//print_r($_POST['favorite_add']);
	$id = key($_POST['favorite_add']);
	$sql = "update tblpasswords set is_favorite=1 where PassID=$id";
	//echo $sql;
	$result = mysqli_query($con, $sql);

	$curr_date = date('Y-m-d H:i:s');
	$curr_action = "update_password";
	$curr_app = "passmgr";
	$text_logu = "Password with id $id has been added into favorites";

	$sql = "INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
	//$sql;

	$result = mysqli_query($con, $sql); // or die("MySQL ERROR: ".mysqli_error());

	echo "<script>alert('The password has been added into favorites');
    location.href='index.php';
    </script>";
}

if (isset($_POST['favorite_remove'])) {
	$id = key($_POST['favorite_remove']);
	$sql = "update tblpasswords set is_favorite=0 where PassID=$id";
	$result = mysqli_query($con, $sql);

	$curr_date = date('Y-m-d H:i:s');
	$curr_action = "update_password";
	$curr_app = "passmgr";

	$text_logu = "Password with id $id has been removed from favorites";

	$sql = "INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";

	$result = mysqli_query($con, $sql); // or die("MySQL ERROR: ".mysqli_error());

	echo "<script>alert('The password has been removed from favorites');
    location.href='index.php';
    </script>";
}

?>


<!DOCTYPE html>

<head>
    <meta charset="utf-8" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="misura" />
    <link href="../css/style_new.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <link href="../css/passmgr.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css' rel='stylesheet' type='text/css'>
    <link href="../css/tabs.css" rel="stylesheet" type="text/css" />
    <link href="../css/toastr.min.css" rel="stylesheet" type="text/css" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <!-- <script src="Chart.js"></script> -->

    <script src="../js/toastr.min.js"></script>
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <link rel='shortcut icon' href='../a.png'>
    <title>axoloth - Passwords</title>

</head>

<body>



   <?php
include '../include/header.php';
?>



    <div id="layout">
        <div id="passwords_wrap">
          <div id="left_panel">

          <div class=" fab-container">
                    <div class="fab fab-icon-holder">
                      <a href="password_add.php"><i class="fas fa-plus"></i></a>
                    </div>
                </div>
              <!--letter list -->
                <div class="search_box">
                        <input type="text" name="search_string" value="" placeholder="System name or  user name" autocomplete="off" id="search_password">
                        <button class="flat-btn"><i class="fas fa-search"></i></button>
                    <!--<span><a href="password_add.php" class='flat-btn'>+ Add new</a></span> -->
                </div>

                <div id="pass_view"><i class="fas fa-list"></i><i class="fas fa-th-large"></i></div>

                <div id="show_as_list">
                    <table id="pass_maintable">

                        <tr>
                            <th class="pass_system_name">System name:</th>
                            <th class="pass_user_name">User name:</th>

                            <th class="pass_favorites">Favorite</th>
                            <th>Actions:</th>
                        </tr>

                        <?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
$page_ext = ".php";
$page_ext_qry = $page_ext . "?";
$sql_order = "ORDER BY PassID DESC"; //defaultne zotriedenie

//sekcia kde osetrujem stavy ked kliknem na nejaky link

if (isset($_GET['search_string'])) {
	//nieco hladam
	$search_string = $_GET['search_string'];

	$search_string = mysqli_real_escape_string($con, $search_string);

	$sql = "SELECT * from tblpasswords where system_name LIKE '%$search_string%' OR user_name LIKE '%$search_string%' OR description LIKE '%$search_string%' and is_archived=0";
	//echo "$sql";
} elseif (isset($_GET['alphabet'])) {
	//kliknem na pismeno

	$letter = $_GET['alphabet'];
	$sql = "SELECT DISTINCT * FROM tblpasswords WHERE LEFT(system_name,1) = '$letter' and is_archived=0 " . $sql_order;
} elseif (isset($_GET['cust_id'])) {
	//kliknem na skupinu

	$group = $_GET['cust_id'];
	$sql = "SELECT * from tblpasswords WHERE cust_id='$group' and is_archived=0 ORDER BY system_name ASC";
} elseif (isset($_GET['category'])) {
	//klignema na jednu z hlavnych kategorii

	$category = $_GET['category'];
	$sql = "SELECT DISTINCT * from tblpasswords where category='$category'";
} elseif (isset($_GET['show'])) {
	if ($_GET['show'] == 'favorites') {
		$sql = "SELECT * from tblpasswords where is_favorite=1";
	}
} else {
	//alebo necham tak alebo stlacim na all

	//  $sql="SELECT DISTINCT * FROM tblpasswords WHERE is_archived=0 and is_favorite=1 union all
	//  SELECT DISTINCT * FROM tblpasswords WHERE is_archived=0 and is_favorite=0 ORDER BY PassID DESC";

	$sql = "SELECT DISTINCT * FROM tblpasswords WHERE is_archived=0 ORDER BY PassID DESC";
}

global $con;
$limit = 20;
$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
$total_pages = mysqli_num_rows($result);

if (mysqli_num_rows($result) == 0) {
	// echo "Zaznam neexistuje!!!!";
	echo "<script>toastr.error('Zaznam neexistuje')</script>";
} else {
	$stages = 3;
	$page = mysqli_escape_string($con, $_GET['page']);
	if ($page) {
		$start = ($page - 1) * $limit;
	} else {
		$start = 0;
	}
	global $con;
	$query1 = $sql . " LIMIT $start, $limit";
	$result = mysqli_query($con, $query1);

	// Initial page num setup
	if ($page == 0) {
		$page = 1;
	}
	$prev = $page - 1;
	$next = $page + 1;
	$lastpage = ceil($total_pages / $limit);
	$LastPagem1 = $lastpage - 1;

	$paginate = '';
	if ($lastpage > 1) {

		$paginate .= "<div class='paginate'>";
		// Previous
		if ($page > 1) {
			$paginate .= "<a href='$targetpage?page=$prev'>previous</a>";
		} else {
			$paginate .= "<span class='disabled'>previous</span>";
		}

		// Pages
		if ($lastpage < 7 + ($stages * 2)) // Not enough pages to breaking it up
		{
			for ($counter = 1; $counter <= $lastpage; $counter++) {
				if ($counter == $page) {
					$paginate .= "<span class='current'>$counter</span>";
				} else {
					$paginate .= "<a href='$targetpage?page=$counter'>$counter</a>";
				}
			}
		} elseif ($lastpage > 5 + ($stages * 2)) // Enough pages to hide a few?
		{
			// Beginning only hide later pages
			if ($page < 1 + ($stages * 2)) {
				for ($counter = 1; $counter < 4 + ($stages * 2); $counter++) {
					if ($counter == $page) {
						$paginate .= "<span class='current'>$counter</span>";
					} else {
						$paginate .= "<a href='$targetpage?page=$counter'>$counter</a>";
					}
				}
				$paginate .= "...";
				$paginate .= "<a href='$targetpage?page=$LastPagem1'>$LastPagem1</a>";
				$paginate .= "<a href='$targetpage?page=$lastpage'>$lastpage</a>";
			}
			// Middle hide some front and some back
			elseif ($lastpage - ($stages * 2) > $page && $page > ($stages * 2)) {
				$paginate .= "<a href='$targetpage?page=1'>1</a>";
				$paginate .= "<a href='$targetpage?page=2'>2</a>";
				$paginate .= "...";
				for ($counter = $page - $stages; $counter <= $page + $stages; $counter++) {
					if ($counter == $page) {
						$paginate .= "<span class='current'>$counter</span>";
					} else {
						$paginate .= "<a href='$targetpage?page=$counter'>$counter</a>";
					}
				}
				$paginate .= "...";
				$paginate .= "<a href='$targetpage?page=$LastPagem1'>$LastPagem1</a>";
				$paginate .= "<a href='$targetpage?page=$lastpage'>$lastpage</a>";
			}
			// End only hide early pages
			else {
				$paginate .= "<a href='$targetpage?page=1'>1</a>";
				$paginate .= "<a href='$targetpage?page=2'>2</a>";
				$paginate .= "...";
				for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++) {
					if ($counter == $page) {
						$paginate .= "<span class='current'>$counter</span>";
					} else {
						$paginate .= "<a href='$targetpage?page=$counter'>$counter</a>";
					}
				}
			}
		}

		// Next
		if ($page < $counter - 1) {
			$paginate .= "<a href='$targetpage?page=$next'>next</a>";
		} else {
			$paginate .= "<span class='disabled'>next</span>";
		}

		$paginate .= "</div>";
	}
	//echo $total_pages.' Results';
	// pagination
	// echo $paginate;

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
}
?>
                                    </table>
            <?php echo $paginate; ?>
            </div><!-- show as list -->

            <div id="show_as_grid">
                <?php
$sql = "SELECT DISTINCT * FROM tblpasswords WHERE is_archived=0 ORDER BY PassID DESC";
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

	/*  if($url<>'') {//url je zadane
		                                    // echo "<tr data-href='$url'>";

		                                    } else {
		                                    echo "<tr data-href='password.php?id=$id'>";

		                                        }

	*/
	echo "<div class='pass_card' data-href='$url' pass-id='$id'>";
	echo "<div class='card_top'><span>$system_name</span></div>";
	echo "<div class='view'><i class='fa fa-eye'></i></div><div class='edit'><i class='fas fa-edit'></i></i></div><div class='delete'><i class='fa fa-times'></i></div>";
	echo "<div class='card_bottom'></div>";
	echo "</div>"; //card
}
?>
            </div>
           </div><!-- lelf panel -->

         </div><!-- passwords wrapper -->
        <div style="clear:both"></div><!-- clearfix -->


    </div><!-- layout --> <div style="clear:both"></div>

    <script src="../js/index.js"></script>
    <script src="../js/passmgr.js"></script>


</body>

</html>