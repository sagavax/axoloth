<?php session_start();

$_SESSION['url'] = $_SERVER['REQUEST_URI'];
$_SESSION['application'] = "notepad";

include "../include/dbconnect.php";
include "../include/functions.php";

if (isset($_POST['add_new_cat'])) {
	global $con;
	$new_cat = mysqli_real_escape_string($con, $_POST['add_new_cat']);

	$sql = "SELECT category_name from tblcustomer_notes_category WHERE category_name='$new_cat'";
	$result = mysqli_query($con, $sql) or die("MySQLi ERROR: " . mysqli_error($con));
	$num_row = mysqli_num_rows($result);
	if ($num_row == 1) {
		/*echo "<script>alert('This category already exists !!');
			        href.location='index.php';
		*/
		echo "<script>alert('This category already exists !!');
      href.location='index.php';
      </script>";
	} else {
		$sql = "INSERT into tblcustomer_notes_category (category_name) VALUES ('$new_cat')";
		$result = mysqli_query($con, $sql) or die("MySQLi ERROR: " . mysqli_error($con));

		/*$sql = "INSERT IGNORE into tblcustomer_categories (cat_name) VALUES ('$new_cat')";
		$result = mysqli_query($con, $sql) or die("MySQLi ERROR: " . mysqli_error($con));*/

		$curr_date = date("Y-m-d");
		$curr_action = "create_category";
		$curr_app = "notepad";
		$text_logu = "new category has been created";

		$sql = "INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
		$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
		//echo "<script>alert('New category has been created sucessfully!');
		//href.location='index.php';
		echo "<script>alert('New category has been created sucessfully!');
        window.location.href='index.php';
       </script>";

		echo "<script>document.getElementById('new_category_name').focus();</script>";
	}
}

?>
<!DOCTYPE html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Axoloth</title>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
     <link href="../css/style_new.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
     <link href="../css/notepad.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
     <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css' rel='stylesheet' type='text/css'>
    <script src="../js/tabs.js?"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
    <?php include "../include/icon.php"?>

</head>

<body>
    <div class="loader"></div>
    <form action="upload_text_file.php" method="post" class="new_note_form" enctype="multipart/form-data">
        <input type="file" accept=".txt" name="note-from-file" id="file-text" onchange="this.form.submit();" hidden="hidden">
  </form>

<div class=" fab-container">
    <div class="fab fab-icon-holder">
        <i class="fas fa-plus"></i>
    </div>

    <ul class="fab-options">
        <li>
           <span class="fab-label">New note</span>
            <div class="fab-icon-holder" onclick="show_add_note();">
            <i class="fa fa-plus"></i>
            </div>
        </li>

        <li>
           <span class="fab-label">New short note</span>
            <div class="fab-icon-holder" onclick="show_add_short_note();">
                <i class="fa fa-plus"></i>
            </div>
        </li>

        <li>
            <span class="fab-label">New link</span>
            <div class="fab-icon-holder"  onclick="show_add_link();">
            <i class="fas fa-link"></i>
            </div>
        </li>

        <li>
            <span class="fab-label">Create note from file</span>
            <div class="fab-icon-holder">
            <i class="fas fa-file-upload" id="upload_note_from_file"></i>
            </div>
        </li>

        <li>
            <span class="fab-label">New video</span>
            <div class="fab-icon-holder"  onclick="show_add_video()">
             <i class="fas fa-video"></i>
            </div>
        </li>

        <li>
            <span class="fab-label">New picture from internet</span>
            <div class="fab-icon-holder"  onclick="show_add_ext_image()">
            <i class="far fa-image"></i>
            </div>
        </li>

        <li>
            <span class="fab-label">Upload image</span>
            <div class="fab-icon-holder">
            <a href="picture_add.php"><i class="far fa-image"></i></a>
            </div>
        </li>

        <li>
            <span class="fab-label">Upload image from clipboard</span>
            <div class="fab-icon-holder">
                <a href="image_from_clipboard.php"><i class="far fa-clipboard"></i></a>
            </div>
        </li>

        <li>
            <span class="fab-label">New source code</span>
            <div class="fab-icon-holder">
                <a href="source_code_add.php"><i class="fa fa-plus"></i></a>
            </div>
        </li>
    </ul>
</div><!-- fab container -->

<?php include '../include/header.php'?>

        <!--end of header -->
<div id="notepad_content">


     <!--       <?php
echo list_of_note_cust(); //zoznam vsetkych zakaznikov, ktory maju nejaku note
 ?>-->
            <div id="left_wrap">

                <div id="category_list_wrap">
                    <input type="text" name="category" autocomplete="off" placeholder="Search category..." onkeyup="search_category(this.value);">
                    <!-- <ul id="category_list" onclick="MakeActiveListItem(event)">-->
                    <ul id="category_list">
                        <?php
///echo "<li><form action='' method='post'><button type='submit' name='add_new_cat' style='width:95%; height:100%; margin-left:2px;margin-top:3px;box-sizing:border-box; border:1px dashed #666;float:left;'>+</button></form></li>";
echo "<li category-id='all'>All</li>";
echo "<li category-id='Unsorted'>Unsorted</li>";
/* global $con;
$sql = "SELECT DISTINCT category_name, cat_id from tblcustomer_notes_category ORDER BY category_name ASC";
$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
while ($row = mysqli_fetch_array($result)) {

	$category_name = $row['category_name'];
	$cat_id = $row['cat_id'];
	echo "<li category-id=$cat_id>$category_name<i class='fa fa-times' onclick='delete_category(this);'></i></li>";
} */
?>
                    </ul>
                </div><!-- category list-->
                <div id="add_new_cat_wrap">
                        <input type="text" name="add_new_cat" id="new_category_name" placeholder="Add new category..." autocomplete="off">
                        <button type="submit" name="add_new_category" id="submit_new_category" class="btn-small">Add</button>
                       </form>
                    <div style="clear:both"></div>
                    </ul>
                </div>

                <div id="timeline_tabs_wrap">
                    <ul id="timeline_tabs">
                        <li><a href="javascript:showtab('tab-1')">this year</a></li>
                        <li><a href="javascript:showtab('tab-2')">last 3 years</a></li>
                        <li><a href="javascript:showtab('tab-3')">All years</a></li>
                    </ul>

                </div>
                <div id="timeline">
                </div>

                <?php
global $con;
//tab v timelinoch

echo "<div id='tab-1' style='visibility:hidden'>"; //zobraz za tento rok
echo "<ul>";
$year = date('Y');
echo "<li><a href='index.php?year=$year' class='action_link'>$year</a></li>";
$sql = "SELECT MONTH(date_created) as months from tblcustomer_notes WHERE YEAR(date_created)=YEAR(CURDATE()) group by MONTH(date_created)";
$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
while ($row = mysqli_fetch_array($result)) {
	$month_created = $row['months'];
	echo "<li class='timeline_month'><a href='index.php?year=$year&month=$month_created' class='action_link'>$month_created</a></li>";
}
echo "</ul>";
echo "</div>";

echo "<div id='tab-2' style='visibility:hidden'>"; //last 3 years

echo "<ul>";
$last_3_years = array();
$sql = "SELECT DISTINCT YEAR(date_created) as year_created from tblcustomer_notes where YEAR(date_created) <= YEAR(NOW()) AND YEAR(date_created) >= YEAR(DATE_SUB(NOW(),INTERVAL 3 YEAR));";
$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
while ($row = mysqli_fetch_array($result)) {
	$year_created = $row['year_created'];
	//echo "<li class='timeline_month'><a href='index.php?year=$year&month=$month_created' class='action_link'>$month_created</a></li>";
	//echo "<li><a href='index.php?year=$year_created' class='action_lilnk'>$year_created</li>";
	array_push($last_3_years, $year_created);
	//print_r($years);
}

foreach ($last_3_years as $year) {
	echo "<li><a href='index.php?year=$year_created' class='action_link'>$year</li>";
	$sql1 = "SELECT MONTH(date_created) as months from tblcustomer_notes WHERE YEAR(date_created)=$year group by MONTH(date_created)";
	$result1 = mysqli_query($con, $sql1) or die("MySQL ERROR: " . mysqli_error($con));
	while ($row1 = mysqli_fetch_array($result1)) {
		$month_created = $row1['months'];
		echo "<li class='timeline_month'><a href='index.php?year=$year&month=$month_created' class='action_link'>$month_created</a></li>";
	}
}

unset($last_3_years);
echo "</ul>";
echo "</div>";

echo "<div id='tab-3' style='visibility:hidden'>"; // all years
echo "<ul>";
$all_years = array();

//echo "<li><a href='index.php?year=$year_created' class='action_link'>$year</li>";
$sql = "SELECT DISTINCT YEAR(date_created) as year_created from tblcustomer_notes";
$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
while ($row = mysqli_fetch_array($result)) {
	$year_created = $row['year_created'];
	//echo "<li><a href='index.php?year=$year_created' class='action_lilnk'>$year_created</li>";
	array_push($all_years, $year_created);
}

//print_r($all_years);

foreach ($all_years as $year) {
	echo "<li><a href='index.php?year=$year_created' class='action_link'>$year</li>";
	$sql1 = "SELECT MONTH(date_created) as months from tblcustomer_notes WHERE YEAR(date_created)=$year group by MONTH(date_created)";
	$result1 = mysqli_query($con, $sql1) or die("MySQL ERROR: " . mysqli_error($con));
	while ($row1 = mysqli_fetch_array($result1)) {
		$month_created = $row1['months'];
		echo "<li class='timeline_month'><a href='index.php?year=$year&month=$month_created' class='action_link'>$month_created</a></li>";
	}
}

unset($all_yerars);

echo "</ul>";
echo "</div>";
?>

                <!--  </div> -->
                <!--timeline -->


            </div>
            <!--left side -->



            <div id="right_wrap">


                <div id="notes_wrap">

                    <div id="notes_search_box_wrap">
                        <div class="search_box">

                            <form action="" method="GET">
                                <!--<div class="search_note_wrap">-->
                                    <input type="text" name="search_string" placeholder="Search note..." autocomplete="off"/>
                                    <button type="submit" class="flat-btn"><i class="fa fa-search"></i></button>
                                <!--</div>-->
                            </form>

                        </div> <!-- search box -->
                    </div><!-- search box wrap -->
                    <!-- Tabs -->
                    <div id="tabs">
                        <ul>
                            <li class="tab_active">All</li>
                            <li class="tab_inactive">Notes</li>
                            <li class="tab_inactive">Links</li>
                            <li class="tab_inactive">Images</li>
                            <li class="tab_inactive">Videos</li>
                        </ul>
                    </div>


                    <div id="notes">
                        <!-- notes -->
                        <?php
if (isset($_GET['tab'])) {
	$tab = $_GET['tab'];
	if ($tab == "all") {
		$sql = "SELECT a.id, a.cust_id, a.title, a.note, a.date_created,a.note_type, a.note_source, b.cust_id, b.customer_name,a.cat_id from tblcustomer_notes a, tblcustomers b WHERE a.cust_id=b.cust_id and is_archived=0 ORDER BY a.id DESC";
	} elseif ($tab == "notes") {
		$sql = "SELECT a.id, a.cust_id, a.title, a.note, a.date_created,a.note_type,a.note_source, b.cust_id, b.customer_name,a.cat_id from tblcustomer_notes a, tblcustomers b WHERE a.cust_id=b.cust_id and note_type='note' and is_archived=0 ORDER BY a.id DESC";
	} elseif ($tab == "links") {
		$sql = "SELECT a.id, a.cust_id, a.title, a.note, a.date_created,a.note_type,a.note_source, b.cust_id, b.customer_name,a.cat_id from tblcustomer_notes a, tblcustomers b WHERE a.cust_id=b.cust_id and note_type='link' and is_archived=0 ORDER BY a.id DESC";
	} elseif ($tab == "videos") {
		$sql = "SELECT a.id, a.cust_id, a.title, a.note, a.date_created,a.note_type,a.note_source, b.cust_id, b.customer_name,a.cat_id from tblcustomer_notes a, tblcustomers b WHERE a.cust_id=b.cust_id and note_type='video' and is_archived=0 ORDER BY a.id DESC";

	} elseif ($tab == "images") {
		$sql = "SELECT a.id, a.cust_id, a.title, a.note, a.date_created,a.note_type,a.note_source, b.cust_id, b.customer_name,a.cat_id from tblcustomer_notes a, tblcustomers b WHERE a.cust_id=b.cust_id and note_type IN ('ext_image','image') and is_archived=0 ORDER BY a.id DESC";
		//echo $sql;

	}
} elseif (isset($_GET['month'])) {
	//klikol som na mesiac
	$year = $_GET['year'];
	$month = $_GET['month'];
	//echo 'rok'.$year;
	//echo 'mesiac:'.$month;
	$sql = "SELECT a.id, a.cust_id, a.title, a.note, a.date_created, b.cust_id, b.customer_name,a.cat_id,a.date_created, a.is_archived, a.note_type,a.note_source from tblcustomer_notes a, tblcustomers b WHERE a.cust_id=b.cust_id and YEAR(a.date_created) =$year and MONTH(a.date_created) = $month and a.is_archived=0 ORDER BY a.id DESC";
}

//elseif ((isset($_GET['year'])) && (!isset($_GET['month']))) { //klikol som na rok
elseif (count($_GET) === 1 && isset($_GET['year'])) {
	$year = $_GET['year'];
	$sql = "SELECT a.id, a.cust_id, a.title, a.note, a.date_created, b.cust_id, b.customer_name,a.cat_id,a.date_created,a.is_archived,a.note_type,a.note_source from tblcustomer_notes a, tblcustomers b WHERE a.cust_id=b.cust_id and YEAR(a.date_created) = $year and a.is_archived=0 ORDER BY a.id DESC";

} elseif (isset($_GET['search_string'])) {
	//nieco hladam
	//echo $search_string;
	$search_string = $_GET['search_string'];
	$search_string = mysqli_real_escape_string($con, $search_string);

	$sql = " SELECT * from tblcustomer_notes WHERE note like '%" . $search_string . "%' or title like '%" . $search_string . "%' and is_archived=0 ORDER BY id DESC";
	//echo "$sql";
} elseif (isset($_GET['view'])) {
	$mode = $_GET['view'];
	$cust_id = $_GET['cust_id'];
	$sql = "SELECT a.id, a.cust_id, a.title, a.note, a.date_created,a.note_source, b.cust_id, b.customer_name,a.cat_id, a.note_type, a.url from tblcustomer_notes a, tblcustomers b WHERE a.cust_id=$cust_id and a.cust_id=b.cust_id ORDER BY a.id DESC";
} elseif (isset($_GET['cust_id'])) {
	$cust_id = $_GET['cust_id'];
	if ($cust_id != "all") {
		$sql = "SELECT a.id, a.cust_id, a.title, a.note, a.date_created, b.cust_id, b.customer_name,a.cat_id, a.note_source,a.note_type from tblcustomer_notes a, tblcustomers b WHERE a.cust_id=b.cust_id and a.cust_id=$cust_id ORDER BY a.id DESC";
	} else {
		$sql = "SELECT a.id, a.cust_id, a.title, a.note, a.date_created, b.cust_id, b.customer_name,a.cat_id, a.note_source,a.note_type from tblcustomer_notes a, tblcustomers b WHERE a.cust_id=b.cust_id ORDER BY a.id DESC";
	}
} else {
	$sql = "SELECT a.id, a.cust_id, a.title, a.note, a.date_created,a.note_type, a.note_source, b.cust_id, b.customer_name,a.cat_id from tblcustomer_notes a, tblcustomers b WHERE a.cust_id=b.cust_id and is_archived=0 ORDER BY a.id DESC LIMIT 20";
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

global $con;
//echo $sql;
$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
if (mysqli_num_rows($result) == 0) {
	//echo "Zaznam neexistuje!!!";
	echo "<script>alert('Zaznam neexistuje !!!!')</script>";
} else {
	while ($row = mysqli_fetch_array($result)) {
		$note_id = $row['id'];
		$cust_id = $row['cust_id'];

		$customer_name = GetCustName($cust_id);
		$url = $row['note_source'];
		$title = htmlspecialchars($row['title']);
		$note = $row['note'];
		$date_created = $row['date_created'];
		$cat_id = $row['cat_id'];
		$note_type = $row['note_type'];

		$string = "";

		//$langs = array('PHP', 'Javascript', 'T-SQL', 'PL-SQL', 'Powershell'); //zoznam programovacich jazykov

		echo "<div class='note' note-id=$note_id>";

		//$cat_ids = GetTagList($note_id);

		//zoznam categorii
		$cat_list = "<div class='tag_list_wrap'><ul class='tag_list'>";
		foreach ($cat_ids as $cat_id) {
			//print_r($cat_ids);
			$cat_name = GetCatName($cat_id);
			$cat_list .= "<li><a href='index.php?cat_id=$cat_id' class='btn-small'>$cat_name</a></li>";
		}

		$cat_list = "<div class='tag_list_wrap'>";
		foreach ($cat_ids as $cat_id) {
			//print_r($cat_ids);
			$cat_name = GetCatName($cat_id);
			$cat_list .= "<button cat-id=$cat_id type='button' class='btn-small'>$cat_name</button>";
		}
		$cat_list .= "</div>";

		//poznamky
		echo "<div class='note_title'>";
		if ($title == "") {

			echo "<div class='add_note_title_wrap hidden' id='note-title-$note_id'><input id='input-note-id-$note_id' type=text name='note_title'  class='new_note_title' autocomplete='off'><button class='btn-small type='button' onclick='save_note_title($note_id);'title='save_note_title'>Save</button></div><button type='button' class='btn-small' onclick='add_image_title($note_id)'><i class='fa fa-plus'></i></button>";
		} else {
			echo "<span>" . $title . "</span>";
		}
		echo "</div>";

		if ($string == "") {
			$string = $url;
		}

		if ($note_type == 'note') {
			echo "<div class='note_text'>";
			//echo "<b>".$note_type."</b>";
			$string = html_entity_decode(strip_tags(htmlspecialchars($note)));

			if (strlen($string) > 500) {

				// truncate string
				$stringCut = substr($string, 0, 500);
				$stringCut = preg_replace("~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~", "<a href=\"\\0\">\\0</a>", $stringCut);
				$string = nl2br(substr($stringCut, 0, strrpos($stringCut, ' '))) . "... <a href='note.php?note_id=" . $note_id . "''>Read More</a>";
			}

			echo nl2br($string);
			echo "</div>";

		}

		if ($note_type == "link") {
			echo "<div class='note_text'>";
			$url = preg_replace("~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~", "<a href=\"\\0\">\\0</a>", $url);
			echo nl2br($url);
			echo "</div>";
		}

		if ($note_type == 'video') {

			echo "<div class='note_video'>";

			//echo $note_type;

			$content = preg_replace("~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~", "<a target='_blank' href=\"\\0\">\\0</a>", $url);

			/*require_once 'oembed.php';
				                                    $autoembed = new AutoEmbed();
			*/
			echo $content;

			/*echo $url;

				                /*       <iframe id="ytplayer" type="text/html" width="<?php echo $width ?>" height="<?php echo $height ?>"
				                      src="https://www.youtube.com/embed/<?php echo $id ?>?rel=0&showinfo=0&color=white&iv_load_policy=3"
			*/

			echo "</div>";
		}
		if ($note_type == 'image') {
			// typ obrazok
			echo "<div class='note_image'>";
			echo "<img src='images/$url' loading='lazy'>";
			echo "</div>";
		}
		if ($note_type == 'gallery') {
			//echo $note_type;
			echo "<div class='note_image'>";
			$images = array();
			$get_images = "SELECT * from tblcustomer_notes_gallery where gallery_name='$title'";

			$result_get_images = mysqli_query($con, $get_images) or die("MySQL ERROR: " . mysqli_error($con));
			while ($row = mysqli_fetch_array($result_get_images)) {
				$image_id = $row['image_id'];
				$images[] = $image_id;
			}
			foreach ($images as $image) {
				//echo $image;
				$get_image_source = "SELECT note_source from tblcustomer_notes where id=$image";
				//echo $get_image_source;
				$result_image_source = mysqli_query($con, $get_image_source) or die("MySQL ERROR: " . mysqli_error($con));
				while ($row_image = mysqli_fetch_array($result_image_source)) {
					$image_source = $row_image['note_source'];
					echo "<img src='$image_source'>";
				} //get image
			}

			//print_r($images);
			echo "</div>";
		}

		if ($note_type == 'ext_image') {
			// typ obrazok
			//get gallery

			echo "<div class='note_image'>";

			//$mime=mime_content_type($url);
			//echo $url;
			//echo "<img src=data_uri('$url,'image/png') loading='lazy'>";
			echo "<img src='$url' loading='lazy'>";
			echo "</div>";
		}

		if ($note_type == 'code') {
			echo "<div class='note_text'>";
			highlight_string($note);
			// } else {
			//echo  "<pre>$note</pre>";
			//}
			echo "</div>";
		}
		echo "<div class='note_footer'><div class='note_action'>";
		if ($note_type == "note") {
			echo "<a href='note.php?note_id=$note_id' title='view note' class='btn-small'><i class='fa fa-eye'></i></a><a href='note_edit.php?note_id=$note_id' class='btn-small' title='edit note'><i class='fa fa-edit'></i></a>";
		} elseif ($note_type == "link") {
			echo "<a href='link_edit.php?note_id=$note_id' class='btn-small' title='edit link'><i class='fa fa-edit'></i></a>";
		} elseif ($note_type == "ext_image") {
			echo "<a href='picture_external_edit.php?note_id=$note_id' class='btn-small' title='edit picture'><i class='fa fa-edit'></i></a> <a href='picture_external_add.php?note_id=$note_id' class='btn-small' title='add picture to gallery'><i class='fa fa-plus'></i></a>";
		} elseif ($note_type == "gallery") {
			//echo $note_type;
			echo "<a href='gallery_edit.php?note_id=$note_id' class='btn-small' title='edit gallery'><i class='fa fa-images'></i></a> <a href='picture_external_add.php?note_id=$note_id' class='btn-small' title='add picture to gallery'><i class='fa fa-plus'></i></a>";
		} elseif ($note_type == "video") {
			//echo $note_type;
			echo "<a href='video_edit.php?note_id=$note_id' class='btn-small' title='edit video'><i class='fa fa-edit'></i></a>";
		}
		echo "<a href='note_clone.php?note_id=$note_id' title='clone note' class='btn-small'><i class='fa fa-clone'></i></a><a href='note_archive.php?note_id=$note_id' title='archive note' class='btn-small'><i class='fa fa-archive'></i></a>";

		if ($note_type == 'note') {
			echo "<a href='note_attachment.php?note_id=$note_id' title='attach a file' class='btn-small'><i class='fas fa-paperclip'></i></a>";
		}
		echo "<a href='note_delete.php?note_id=$note_id' title='delete note' class='btn-small delete'><i class='fa fa-times'></i></a></div>"; // note body

		echo "<div class='note_cat_wrap'>";
		echo "<button title='Add new category' type='button' class='btn-small-hollow' note-id=$note_id onclick='ShowModalAddCat($note_id);'><i class='fa fa-plus'></i></button>";
		echo $cat_list;
		echo "<button class='btn-small-green' type='button'>$customer_name</button>";
		echo "</div>"; //note cat wrapper

		//echo "<div class='note_taglist'>".GetTagList($note_id)."</div>";
		echo "</div>";
		echo "</div>"; // class note
	}
} //end while

?>
                    </div> <!-- notes -->

                </div><!-- notes wrap -->

            </div><!-- middle wrap -->
            <!--</div>-->
            <!-- main wrap-->
            <div style="clear:both"></div>

</div><!-- notepad content -->
            <!-- modal add new link -->
           <div id="add_new_link" class="overlay">
                <div>
                    <input type="text" name="note_title" id="note_title" autocomplete="off" placeholder="title...">
                    <input type="text" name="note_source" id="note_source" autocomplete="off" placeholder="url....">
                    <section>
                        <button class="flat-btn"  onclick="hide_add_link();"><i class="fa fa-times"></i></button>
                        <button type="button" class="flat-btn" id="add_link">Add link</button>
                    </section>
                </div>
            </div>

            <!-- modal add new image from link -->
            <div id="add_new_ext_image" class="overlay">
                <div>
                    <input type="text" id="image_title" autocomplete="off" placeholder="title">
                    <input type="text" id="image_source" autocomplete="off"  placeholder="url....">
                    <section>
                        <button type="button"class="flat-btn" onclick="hide_ext_image()"><i class="fa fa-times"></i></button>
                        <button id="add_ext_pic" type="button" class="flat-btn">Add image</button>
                    </section>
                </div>
            </div>

            <!-- modal add new video from link -->
            <div id="modal_add_new_video" class="overlay">
                <div>
                    <input type="text" id="video_title" autocomplete="off" placeholder="title">
                    <input type="text" id="video_source" autocomplete="off"  placeholder="url....">
                    <section>
                        <button type="button"class="flat-btn" onclick="hide_add_video()"><i class="fa fa-times"></i></button>
                        <button id="btnAddvideo" type="button" class="flat-btn">Add video</button>
                    </section>
                </div>
            </div>

            <!-- modal add new tag -->
            <div id="add_new_tag" class="overlay">
                <div>
                    <p>Add new tag</p>
                    <p id="missing_cats"></p>
                    <button type='button' onclick="document.getElementById('add_new_tag').style.display='none'" id="close_tag"><i class="fa fa-times"></i></button>
                </div>
            </div>

            <!-- modal add new short note -->
            <div id="add_new_short_note" class="overlay ck-content">
                <div>
                    <input type="text" id="short_note_title" autocomplete="off" placeholder="title">
                    <textarea id="short_note_text" onkeyup="number_of_chars();" placeholder="type some text here...." maxlength="800"></textarea>
                    <section>
                        <div id="num_chars"></div>
                        <button type="button"class="flat-btn" onclick="hide_add_short_note()"><i class="fa fa-times"></i></button>
                        <button id="add_short_note" type="button" class="flat-btn">Add note</button>
                    </section>
                 </div>
            </div>

        <script src="../js/notepad.js"></script>
        <script src="../js/notepad_video.js"></script>
        <div class="overlay">
            <div>
                <textarea id="pass_note"></textarea>
                <button onclick="add_pass_note($id)"><i class="fas fa-plus"></i></button>
            </div>
        </div>
        <!-- id layout-->
</body>
    <!--
            appending image into a text:
            https://www.webucator.com/article/how-to-insert-images-in-wordpress-text-view/
            https://ultimateblocks.com/add-image-and-text-wordpress/

            generating link preview
            https://www.codexworld.com/generate-web-page-link-preview-with-php/
            https://www.phpclasses.org/blog/package/11314/post/1-How-to-Use-PHP-to-Create-a-Link-Preview-like-Facebook.html
            http://www.99points.info/2010/07/facebook-like-extracting-url-data-with-jquery-ajax-php/
            https://phppot.com/php/extract-content-using-php-and-preview-like-facebook/
    -->
</html>