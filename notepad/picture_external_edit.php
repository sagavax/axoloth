<?php session_start()?>
<?php include "../include/dbconnect.php";?>
<?php include "../include/functions.php";?>
<?php

if (isset($_POST['edit_ext_image'])) {
	$note_id = $_POST['note_id'];
	$note_title = mysqli_real_escape_string($con, $_POST['note_title']);
	$note_source = mysqli_real_escape_string($con, $_POST['note_source']);
	$customer = mysqli_real_escape_string($con, $_POST['customer_id']);
	//$cat_id=intval($_POST['category']);

	//zmazem vsetky existujuce tagy
	$sql = "DELETE from tblcustomer_notes_tags WHERE note_id=$note_id";
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($db));

	//nanovo ich nacitam
	if (!empty($_POST['categories'])) {
		//mam priradenu kategoriu
		$tags_ids = $_POST['categories'];

		foreach ($tags_ids as $tag_id) {
			$sql = "INSERT INTO tblcustomer_notes_tags (note_id,cat_id) VALUES($note_id,$tag_id)";
			$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
		}
	} else {
		unset($_POST['categories']);
	}

	//updatnem samotnu poznamku

	$sql = "UPDATE tblcustomer_notes set cust_id=$customer,title='$note_title', note_source='$note_source',note_type='ext_image' where id=$note_id";
	//echo $sql;

	global $con;
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));

	//zapisem do logu
	$curr_date = date('Y-m-d H:i:s');
	$curr_action = "modify_picture";
	$curr_app = "notepad";
	//$new_note_id=GetLatestNote();
	$text_logu = "The image with id $note_id has been modified";

	$sql = "INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
	//echo $sql;

	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	echo "<script>toastr.success('The image id $note_id has been modified');
       window.location='index.php';
        </script>";
}
?>

<!DOCTYPE html>

<head>
   <meta charset="utf-8" />
   <title>E.I.S - Edit note</title>
   <link rel="stylesheet" href="../css/style_new.css?<?php echo time() ?>" type="text/css">
   <link href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
   <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
   <?php include "../include/icon.php"?>


   <script type="text/javascript">
      function add_new_cat() {

         document.getElementById('new_cat_form').style.display = "block";
      }
   </script>
</head>

<body>
   <?php
$note_id = $_GET['note_id'];

?>
   <div id="header">
      <!--header -->
      <div class="header-logo">e.i.s.</div>
      <!--logo -->
      <div class="header-menu">
         <!--menu-->
         <?php
include "../include/menu.php";

?>
      </div>
   </div>
   <!--end of header -->
   <div id="layout">
      <div class="edit_note_wrap">
         <?php
global $con;
$sql = "SELECT a.id, a.cust_id, a.cat_id, a.title, a.note,a.note_source, b.cust_id, b.customer_name from tblcustomer_notes a,tblcustomers b where a.id=$note_id and a.cust_id=b.cust_id";
//echo $sql;
$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
while ($row = mysqli_fetch_array($result)) {
	$note_id = $row['id'];
	$cust_id = $row['cust_id'];
	$cat_id = $row['cat_id'];
	$customer_name = $row['customer_name'];
	$category_name = GetCatName($cat_id);
	$title = $row['title'];
	$source = $row['note_source'];

	//$date_created=$row['date_created'];
	?>
            <form action="" method="post" id="edit_note_form">

               <h2>Edit the note:</h2>

               <input type="hidden" name="note_id" value="<?php echo $_GET['note_id'] ?>">

               <div class="edit_note_title"><input type="text" name="note_title" value="<?php echo $title ?>" placeholder="Title..." autocomplete="off"></div>

               <div class="edit_note_customer">
                  <select name="customer_id">
                     <option value="0">-- Choose the customer -- </option>
                     <?php
echo "<option value='$cust_id' selected=\"selected\">$customer_name</option>";

	global $con;
	$sql = "SELECT cust_id, customer_name from tblcustomers WHERE customer_status='active'";
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	while ($row = mysqli_fetch_array($result)) {
		$cust_id = $row['cust_id'];
		$customer_name = $row['customer_name'];

		echo "<option value='$cust_id'>$customer_name</option>";
	}
	?>
                  </select>

               </div><!-- edit note customer -->

               <div class="note_category_wrap">
                  <div class="note_categories_header">
                     <h3>Categories</h3>
                  </div>
                  <div class="note_categories_list">
                     <?php
global $con;
	$sql = "SELECT a.cat_id, b.cat_name from tblcustomer_notes_tags a, tblcustomer_categories b where a.cat_id=b.cat_id and note_id=$note_id";
	$existing_cats = GetTagList($note_id);
	$existing_cats = implode(',', $existing_cats);
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	while ($row = mysqli_fetch_array($result)) {
		$cat_id = $row['cat_id'];

		$category_name = $row['cat_name'];
		//echo $cat_id;
		echo "<div class='checkbox_wrap'><input type='checkbox' name='categories[]' id='hovno' value=$cat_id checked='checked'><label for='$category_name'>$category_name</label></div>";
	}

	if (empty($existing_cats)) {
		$sql = "SELECT * from tblcustomer_categories";
	} else {
		$sql = "SELECT * from tblcustomer_categories where cat_id not in ($existing_cats)";
	}
	//vyberiem kategorie, ktore nie su este priradene

	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	while ($row = mysqli_fetch_array($result)) {
		$cat_id = $row['cat_id'];

		$category_name = $row['cat_name'];
		//echo $cat_id;
		echo "<div class='checkbox_wrap'><input type='checkbox' name='categories[]' id='hovno' value=$cat_id ><label for='$category_name'>$category_name</label></div>";
	}

	?>
                  </div>
                  <div class="note_categories_footer">
                     <button onclick="pridat_kategoriu()" class="flat-btn-small"><i class="fa fa-plus"></i></button>
                  </div>

               </div><!-- note category wrap -->

               <div class="edit_note_url"><input type="text" name="note_source" value="<?php echo $source ?>"></div>
               <div class="edit_note_action">
                  <button name="edit_ext_image" type="submit" class="flat-btn">Save changes</button>
               </div>

               <div class="edit_note_back_link">
                  <a href='index.php' class="action_link">&lt;&lt; Back to home</a>
               </div>
          </div>

         <?php
}
?>
       </form>
   </div>
   <!-- id layout-->
</body>

</html>
<?php

?>