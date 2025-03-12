<?php session_start();
$_SESSION['url'] = $_SERVER['REQUEST_URI'];
?>
<?php include "../include/dbconnect.php";?>
<?php include "../include/functions.php";?>
<?php include "../include/config.php";?>

<!DOCTYPE html>
<head>
   <meta name="viewport" content="width=device-width,initial-scale=1">
   <meta charset="utf-8" />
   <link href="../css/style_new.css?<?php echo time(); ?>" rel="stylesheet" type="text/css">
   <link href="../css/notepad.css?<?php echo time(); ?>" rel="stylesheet" type="text/css">
   <link href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
   <meta name="viewport" content="width=device-width,initial-scale=1">
   <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <?php include "../include/icon.php"?>
</head>
<body>
   <?php
$note_id = $_GET['note_id']; // get the note's ID
?>

  <?php
include '../include/header.php';
?>

   <!--end of header -->
   <div id="layout">
      <div id="view_note">
         <?php
$sql = "SELECT a.id, a.cust_id, a.cat_id,a.title, a.note, a.note_source, b.cust_id, b.customer_name,a.note_type from tblcustomer_notes a,tblcustomers b where a.id=$note_id and a.cust_id=b.cust_id";
//echo $sql;
$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));

while ($row = mysqli_fetch_array($result)) {
	$note_id = $row['id'];
	$cat_id = $row['cat_id'];
	//echo $cat_id;
	$cat_name = GetCatName($cat_id);
	$cust_id = $row['cust_id'];
	$customer_name = $row['customer_name'];
	$title = htmlspecialchars($row['title']);
	echo "<title>axoloth - Note:" . $title . "</title>";
	$note = htmlspecialchars($row['note']);
	//$date_created=$row['date_created'];
	$source = $row['note_source'];
	$note_type = $row['note_type'];

	$source = preg_replace("~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~", "<a href=\"\\0\">\\0</a>", $source);

	//echo $note;
	?>

               <div class="edit_note_customer"><?php echo $customer_name ?></div>
               <div class="note_title"><?php echo $title ?></div>

                  <?php
if ($note_type == "note") {
		echo "<div class='note_text'>" . htmlspecialchars_decode(nl2br($note)) . "</div>";
	} elseif ($note_type = "ext_image") {
		echo "<div class='note_image'><img src='" . $row['note_source'] . "'></div>";
	}
	?>

              <div class="note_footer">
               <div class="view_note_category_wrap">
                  <ul id="myTags">
                     <?php
$sql = "SELECT a.cat_id,b.cat_name from tblcustomer_notes_tags a, tblcustomer_categories b where note_id=$note_id and a.cat_id=b.cat_id";
	// echo $sql;
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	while ($row = mysqli_fetch_array($result)) {
		$cat_name = $row['cat_name'];
		echo "<li><span class='flat-btn-small'>$cat_name</span></li>";
	}
	?>
                        </ul>
                  </div>

                  <div class='note_view_action'><a href='index.php' class="flat-btn-small">&lt;&lt; Back</a> <a href='note_delete.php?note_id=$note_id' class='flat-btn-small'>Delete</a></div>
              </div>

         <?php
}
?>
         <div style="clear:both"></div>
      </div><!--view note -->

   </div>
   <!-- id layout-->
</body>

</html>