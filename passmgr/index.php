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
	<script src="../js/index.js?<?php echo time(); ?>" defer></script>
    <script src="../js/passwords.js?<?php echo time(); ?>" defer></script>
    <script src="../js/pass_note.js?<?php echo time(); ?>" defer></script>
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

				<div class="char_list">
					<?php 
						 foreach (range('A', 'Z') as $char) {
							echo "<button type='button' class='btn-small'>$char</button>";
  
						  }
							//echo "<button type='button' name='all''>All</button>";
							//echo "<button type='button' name='dupes'>Find dupes</a></li>"											
					?>
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


$limit = 20; // Počet záznamov na stránku
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$start = ($page - 1) * $limit;

// Najskôr získaj počet všetkých záznamov
$sql_count = "SELECT COUNT(*) as total FROM tblpasswords"; // Zmeň "table_name" na správnu tabuľku
$result_count = mysqli_query($con, $sql_count);
$row_count = mysqli_fetch_assoc($result_count);
$total_pages = $row_count['total'];

if ($total_pages == 0) {
    echo "<script>toastr.error('Záznam neexistuje')</script>";
} else {
    $sql = "SELECT * FROM tblpasswords ORDER BY PassID DESC LIMIT $start, $limit"; // Pôvodný SQL dotaz s LIMIT
    $result = mysqli_query($con, $sql);

    $stages = 3;
    $prev = $page - 1;
    $next = $page + 1;
    $lastpage = ceil($total_pages / $limit);
    $LastPagem1 = $lastpage - 1;
    $targetpage = "index.php"; // Alebo nastav relevantnú hodnotu

    $paginate = "";
    if ($lastpage > 1) {
        $paginate .= "<div class='paginate'>";

        // Predošlá stránka
        if ($page > 1) {
            $paginate .= "<a href='{$targetpage}?page=$prev'>« Predošlá</a>";
        } else {
            $paginate .= "<span class='disabled'>« Predošlá</span>";
        }

        // Stránkovanie
        for ($counter = 1; $counter <= $lastpage; $counter++) {
            if ($counter == $page) {
                $paginate .= "<span class='current'>$counter</span>";
            } else {
                $paginate .= "<a href='{$targetpage}?page=$counter'>$counter</a>";
            }
        }

        // Nasledujúca stránka
        if ($page < $lastpage) {
            $paginate .= "<a href='{$targetpage}?page=$next'>Nasledujúca »</a>";
        } else {
            $paginate .= "<span class='disabled'>Nasledujúca »</span>";
        }

        $paginate .= "</div>";
    }
}
	//echo $total_pages.' Results';
	// pagination
	// echo $paginate;

	while ($row = mysqli_fetch_array($result)) {
		$id = (int) $row["PassID"];
		$cust_id = (int) $row['cust_id'];
		$customer_name = CustomerName($cust_id);
		$group_name = $row['group_name'];
		$system_name = $row['system_name'];
		$user_name = $row['user_name'];
		$password = $row['password'];
		$category = $row['category'];
		$url = $row['url'];
		$description = $row['description'];
		$is_favorite = (int) $row['is_favorite'];
	
		echo "<tr>";
		echo "<td class='hasTooltip' data-href='{$url}' pass-id='{$id}'><b>{$system_name}</b></td>";
		echo "<td>{$user_name}</td>";
	
		// Obľúbené položky
		echo "<td>
				<div id='fav-{$id}' class='fav_id'>
					<a href='#' onclick='" . ($is_favorite ? "RemoveFavorite({$id})" : "AddFavorite({$id})") . "'>
						<i class='fa" . ($is_favorite ? "s" : "r") . " fa-star'></i>
					</a>
				</div>
			  </td>";
	
		// Akcie
		echo "<td>
				<div class='pass_actions_wrap'>
					<button type='button' class='btn-small' onclick='view_pass({$id})' title='View'><i class='fas fa-eye'></i></button>
					<button type='button' class='btn-small' onclick='show_add_note({$id})' title='Add note'><i class='fas fa-plus'></i></button>
					<button type='button' class='btn-small' onclick='edit_pass({$id})' title='Edit'><i class='fas fa-edit'></i></button>
					<button type='button' class='btn-small' onclick='archive_pass({$id})' title='Archive'><i class='fas fa-archive'></i></button>
					<button type='button' class='btn-small' onclick='clone_pass({$id})' title='Clone'><i class='fas fa-copy'></i></button>
					<button type='button' class='btn-small remove' onclick='remove_pass({$id})' title='Remove'><i class='fas fa-times'></i></button>
				</div>
			  </td>";
	
		echo "</tr>";
	}
	
?>
                                </table>
                    <?php echo $paginate; ?>
                </div><!-- passwords -->
          </div><!-- passwords wrapper -->
        <div style="clear:both"></div><!-- clearfix -->


    </div><!-- layout --> <div style="clear:both"></div>

  


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