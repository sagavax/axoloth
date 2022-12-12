<?php session_start();
$_SESSION['url'] = $_SERVER['REQUEST_URI'];
include "../include/dbconnect.php";
include "../include/functions.php";

?>

<!DOCTYPE html>

<head>
    <meta charset="utf-8" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="misura" />
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="../css/style_new.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <link href="../css/passmgr.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>-->
    <!-- <script src="Chart.js"></script> -->



    <link rel='shortcut icon' href='../a.png'>
    <title>axoloth - Passwords</title>

</head>

<body>



   <?php
include '../include/header.php';
?>



    <div id="layout">
        <div id="passwords_wrap">
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

                <div id="pass_tabs">
                	<ul>
                		<li class="tab">All</li>
                		<li class="tab">Favorites</li>
                		<li class="tab">Most popular</li>
                		<li class="tab">Newest</li>
                	</ul>
                </div>


                <div id="passwords">
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

$sql = "SELECT DISTINCT * FROM tblpasswords WHERE is_archived=0 ORDER BY PassID DESC";

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
		echo "<td class='hasTooltip' data-href='$url' pass-id='$id'><b>$system_name</b></td>";
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

		//https: //stackoverflow.com/questions/35579569/hide-show-menu-onclick-javascript
	}
}
?>
                                </table>
                    <?php echo $paginate; ?>
                </div><!-- passwords -->
          </div><!-- passwords wrapper -->
        <div style="clear:both"></div><!-- clearfix -->


    </div><!-- layout --> <div style="clear:both"></div>

    <script src="../js/index.js"></script>
    <script src="../js/passmgr.js"></script>
    <script type="text/javascript" src="../js/pass_note.js"></script>


</body>

</html>

<div id="show_pass_details" class="overlay"><div id="pass_details"></div></div>

<div class="show_add_note">
	<buton class="close" type="button" onclick="hide_add_note()"><i class="fa fa-times"></i></buton>
    <textarea id="pass_note_text"></textarea>
    <div class='wrap'>
    	<button onclick="save_modal_note()"; class='btn-small'><i class="fas fa-plus"></i></button>
    </div>
</div>