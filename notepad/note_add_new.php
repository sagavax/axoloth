<?php session_start();
$_SESSION['url'] = $_SERVER['REQUEST_URI'];
?>

<?php include "../include/dbconnect.php";?>
<?php include "../include/functions.php";?>

<?php

if (isset($_POST['add_cat'])) {
	//pridat novy kategoriu

	AddNewCategory($_POST['cat_name']);

	header('location:note_add.php');

}

if (isset($_POST['add_note'])) {
	global $con;
	$note_title = mysqli_real_escape_string($con, $_POST['note_title']);
	$note_source = mysqli_real_escape_string($con, $_POST['note_source']);
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
		echo $sql;
		$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());

	}

	//zapis to logu
	$curr_date = date('Y-m-d H:i:s');
	$curr_action = "create_note";
	$curr_app = "notepad";

	$text_logu = "new note id $new_note_id has been created";

	$sql = "INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";

	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());
	echo "<script>toastr.success('new note has been created');
    window.location='index.php';
    </script>";
	/*echo "<script>toastr.success('new note has been created');

   </script>";*/

}
?>

<!DOCTYPE html>
<head>
   <meta charset="utf-8" />
   <title>E.I.S - Add new note</title>
   <link rel="stylesheet" href="../css/style_new.css?<?php echo time() ?>" type="text/css">
   <link href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
   <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
   <link href="../css/toastr.min.css" rel="stylesheet" type='text/css'>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
   <script src="../js/ckeditor.js"></script>
   <script src="../js/toastr.min.js"></script>
   <?php include "../include/icon.php"?>
     <script type="text/javascript">
      // <![CDATA[
      var form_name = 'new_note_form';
      var text_name = 'note_text';
      var load_draft = false;
      var upload = false;

      // Define the bbCode tags
      var bbcode = new Array();
      //var bbtags = new Array('<b>','</b>','<i>','</i>','<img src='' >','</img>','<a href=''>','[/a]');
      var bbtags = new Array('[b]','[/b]','[i]','[/i]','[u]','[/u]','[quote]','[/quote]','[code]','[/code]','[list]','[/list]','[list=]','[/list]','[img]','[/img]','[url]','[/url]','[flash=]', '[/flash]');
      var imageTag = false;
      // ]]>

   </script>
   <script type="text/javascript">
      function add_new_cat() {

         document.getElementById('new_cat_form').style.display = "block";
      }
   </script>
</head>
<body>
<div id="header"> <!--header -->
<div class="header-logo">E.I.S.</div> <!--logo -->
 <div class="header-menu">
     <!--menu-->
    <?php include '../include/menu.php'?>
  </div>
</div> <!-- end of header -->
   <!--end of header -->
   <div id="layout">
      <div id="main_wrap">
         <div id="add_new_note_wrap">
            <form action="" method="post" id="new_note_form">
               <table id="new_note">
                  <tr>
                     <td>Title:</td>
                     <td><input type="text" name="note_title"></td>
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
                            <?php
global $con;
$sql = "SELECT * from tblcustomer_categories ORDER BY cat_name ASC";
$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());
while ($row = mysqli_fetch_array($result)) {
	$cat_id = $row['cat_id'];
	$category_name = $row['cat_name'];
	echo "<div class='checkbox_wrap'><input type='checkbox' name='categories[]' id='hovno' value=$cat_id><label for='$category_name'>$category_name</label></div>";
}

?>
                        <div class="row"><button onclick="pridat_kategoriu()" class="flat-btn-small"><i class="fa fa-plus"></i></button></div>

                        <!--<button onclick="add_new_cat(); return false;" class="flat-btn-small">+</button>
                        <div id="new_cat_form" class="skryte-okno"><input type="text" name="cat_name" style="width:300px"><button name="add_cat" type="submit" class="flat-btn-small">New</button></div>-->
                     </td>
                  </tr>
                  <tr>
                     <td>
                        Category:
                     </td>
                     <td>
                        <select name="category">
                           <option value="0">-- Choose the category --</option>
                           <?php
if (isset($_GET['cat_id'])) {
	$cat_id = $_GET['cat_id'];
	$category_name = GetCatName($cat_id);
	echo "<option value='$cat_id' selected='selected'>$category_name</option>";
}
global $con;
$sql = "SELECT * from tblcustomer_categories ORDER BY cat_name ASC";
$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());
while ($row = mysqli_fetch_array($result)) {
	$cat_id = $row['cat_id'];
	$category_name = $row['cat_name'];
	echo "<option value='$cat_id'>$category_name</option>";
}

?>
                        </select>
                        <button onclick="add_new_cat(); return false;" class="flat-btn-small">+</button>
                        <div id="new_cat_form" class="skryte-okno"><input type="text" name="cat_name" style="width:300px"><button name="add_cat" type="submit" class="flat-btn-small">New</button></div>
                     </td>
                  </tr>
                  <tr>
                     <td>Text:</td>
                     <td>
                        <div id="buttons">
                           <a href="javascrip://" onclick="bbstyle(0)" class='btn-small'><i class="fa fa-bold"></i></a>
                           <a href="javascrip://" onclick="bbstyle(2)" class='btn-small'><i class="fa fa-italic"></i></a>
                           <a href="javascrip://" onclick="bbstyle(14)" class='btn-small'><i class="fa fa-picture-o"></i></a>
                           <a href="javascrip://" onclick="bbstyle(16)" class='btn-small'><i class="fa fa-link"></i></a>
                           <!--<button id="b" style="width:30px;height:30px" class='btn-small' onclick="bbstyle(0)">B</button> -->
                        </div>

                        <textarea name="note_text" id="editor"></textarea>
                        <script type="text/javascript">
                           ClassicEditor
                          .create( document.querySelector( '#editor' ) )
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
                     <td colspan="2" style="text-align:right"><button name="add_note" type="submit" class="flat-btn">Add note</button></td>
               </table>
            </form>
         </div>
         <a href='index.php' class="action_link">&lt;&lt; Back to home</a>
      </div>
      <!-- main wrap-->
   </div>
   <!-- id layout-->
</body>
</html>

<?php

?>