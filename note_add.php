<?php session_start();

?>

<?php include "../include/dbconnect.php";?>
<?php include "../include/functions.php";?>

<?php

if (isset($_POST['add_cat'])) {
	//pridat novy kategoriu

	AddNewCategory($_POST['cat_name']);

	//header('location:note_add.php');

}

if (isset($_POST['add_note'])) {
	global $con;
	$note_title = mysqli_real_escape_string($con, $_POST['note_title']);
	$note_source = htmlentities(mysqli_real_escape_string($con, $_POST['note_source']));
	$note_text = mysqli_real_escape_string($con, $_POST['note_text']);
	$cust_id = (int) $_POST['customer']; // if cust_id = moje vlastne poznamky
	$tags_ids = $_POST['categories'];
	$note_type = "note";
	$date_created = date('Y-m-d H:m:s');

	//$sql="INSERT INTO tblcustomer_notes (cust_id, cat_id,title, note,note_source,date_created, note_type) VALUES ($cust_id,$cat_id,'$note_title','$note_text','$note_source','$date_created','$note_type')";
	$sql = "INSERT INTO tblcustomer_notes (cust_id, title, note,note_source,date_created, note_type) VALUES ($cust_id,'$note_title','$note_text','$note_source','$date_created','$note_type')";
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());

	$new_note_id = GetLatestNote();

	foreach ($tags_ids as $tag_id) {
		$sql = "INSERT INTO tblcustomer_notes_tags (note_id,cat_id) VALUES($new_note_id,$tag_id)";
		//echo $sql;
		$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());
	}

	if (in_array(5, $tags_ids)) {
		//mincraft tak presuniem do minecraft IS
		$note_cat = intval($_POST['minecraft_mod']);
		$note_modpack = intval($_POST['minecraft_modpack']);
		$connection = mysqli_connect(null, "minecraft_db", "eTIZAAMcSL", "minecraft_db", null, "/tmp/mariadb101.sock");
		//$connection = mysqli_connect("mariadb101.websupport.sk", "minecraft_db", "eTIZAAMcSL", "minecraft_db", 3312);
		//$connection = mysqli_connect("localhost", "root", "", "minecraft_db", 3306);
		$note_text = $note_text . " " . $note_source;
		$sql = "INSERT INTO notes (eis_note_id, note_header, note_text,cat_id,modpack_id,added_date) VALUES ($new_note_id,'$note_title','$note_text',$note_cat, $note_modpack,'$date_created')";
		//echo $sql;
		$result = mysqli_query($connection, $sql) or die("MySQLi ERROR: " . mysqli_error($connection));
		mysqli_close($connection);
	}

	//zapis do wallu
	$curr_date = date('Y-m-d H:i:s');

	$link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
	$diary_text = "EIS: Bola vytvorena nova poznamka s id $new_note_id a nazvom <strong>$note_title</strong>";

	$sql = "INSERT INTO diary (diary_text, date_added,location,isMobile,is_read) VALUES ('$diary_text','$curr_date','',0,0)";
	$result = mysqli_query($link1, $sql) or die("MySQLi ERROR: " . mysqli_error($link1));
	mysqli_close($link1);

	//zapis to logu
	$curr_action = "create_note";
	$curr_app = "notepad";
	$text_logu = "new note id $new_note_id has been created";

	$sql = "INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());
	echo "<script>alert('new note has been created');
    window.location.href='index.php';
    </script>";

}
?>

<!DOCTYPE html>
<head>
   <meta charset="utf-8" />
   <title>E.I.S - Add new note</title>
   <link rel="stylesheet" href="../css/style_new.css?<?php echo time() ?>" type="text/css">
   <link rel="stylesheet" href="../css/notepad.css?<?php echo time() ?>" type="text/css">
   <link href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
   <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
   <link href="../css/toastr.min.css" rel="stylesheet" type='text/css'>
   <script src="https://cdn.ckeditor.com/ckeditor5/31.1.0/classic/ckeditor.js"></script>
   <script src="../js/toastr.min.js"></script>
   <?php include "../include/icon.php"?>

</head>
<body>

<?php include '../include/header.php'?>

   <!--end of header -->
   <div id="layout">
        <div class="edit_note_wrap">
            <form action="" method="post" id="new_note_form">
               <table id="new_note">
                  <tr>
                     <td>Title:</td>
                     <td><input type="text" name="note_title" autocomplete="off" placeholder="Title..."></td>
                  </tr>
                  <tr>
                     <td>Customer:</td>
                     <td>
                        <select name="customer">
                           <?php
if (isset($_GET['cust_id'])) {
	global $con;
	$cust_id = $_GET['cust_id'];
	$sql = "SELECT customer_name from tblcustomers where cust_id=$cust_id";
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());
	while ($row = mysqli_fetch_array($result)) {
		$customer_name = $row['customer_name'];
		echo "<option value='$cust_id' selected=\"selected\">$customer_name</option>";
	}
}
?>
                           <option value="0">-- Choose the customer -- </option>
                           <?php
global $con;
$sql = "SELECT cust_id, customer_name from tblcustomers WHERE customer_status='active'";
$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());
while ($row = mysqli_fetch_array($result)) {
	$cust_id = $row['cust_id'];
	$customer_name = $row['customer_name'];

	echo "<option value='$cust_id'>$customer_name</option>";
}
?>
                        </select>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        Category:
                     </td>
                     <td>
                        <div class="note_category_wrap">
                           <div class="note_categories_header">
                               <h3>Categories</h3>
                           </div>
                           <div class="note_categories_list">
                              <?php
global $con;
$sql = "SELECT * from tblcustomer_categories ORDER BY cat_name ASC";
$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());
while ($row = mysqli_fetch_array($result)) {
	$cat_id = $row['cat_id'];
	$category_name = $row['cat_name'];
	echo "<div class='checkbox_wrap'><input type='checkbox' name='categories[]' class='cats' value=$cat_id onchange='load_min_mods();'><label for='$category_name'>$category_name</label></div>";
}

?>
                              </div>
                              <div class="note_categories_footer">
                                 <button onclick="pridat_kategoriu()" class="flat-btn-small"><i class="fa fa-plus"></i></button>
                              </div>
                        </div>
                     </td>
                  </tr>

                  <tr>
                     <td>Text:</td>
                     <td>

                        <textarea name="note_text" id="editor"></textarea>
                        <script type="text/javascript">
                           ClassicEditor
                          .create( document.querySelector( '#editor' ))
                          .catch( error => {
                              console.error( error );
                          } );
                        </script>
                     </td>
                  </tr>
                  <tr>
                     <td>Source:</td>
                     <td><input type="text" name="note_source"></td>
                  </tr>
                  <tr>
                  <tr>
                    <td colspan="2" style="text-align:right">
                        <div class="edit_note_action">
                           <button name="add_note" type="submit" class="flat-btn"><i class="fa fa-plus"></i> Add</button>
                           <a href='index.php' class="flat-btn"><i class="fa fa-chevron-left"></i> Back</a>
                        </div>
                     </td>
                  </tr>
               </table>
            </form>
         </div>
    </div>
   <!-- id layout-->

</body>
</html>