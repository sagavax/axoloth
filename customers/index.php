<?php
session_start();
?>
<?php
include "../include/dbconnect.php";
?>
<?php
include "../include/functions.php";
?>

<?php
//crate a new customer
if (isset($_POST['new_customer'])) {
	//$customer      = $_POST['customer'];
	global $con;
	$customer_name = trim(mysqli_real_escape_string($con, $_POST['customer_name']));

	//skontroluj ci ten zakazni uz neexistuje
	$sql = "SELECT count(*) as cust_count from tblcustomers WHERE customer_name='$customer_name'";
	//echo $sql;
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());
	$row = mysqli_fetch_array($result);
	if ($row['cust_count'] == 1) {
		echo "<script type='text/javascript'>alert('This customer already exists!!!!');
            window.location = 'index.php';
        </script>";
		// header('Location: index.php', true, 303); // presmeruje spat aby sa zbranilo vkladaniu duplicity
	} else {

		echo "<script>alert('new customer has been created');
       window.location='index.php';
       </script>";

	}
}

//make the customer inactivefff
if (isset($_POST['make_inactive'])) {
	$cust_id = intval($_POST['cust_id']);
	$sql = "UPDATE tblcustomers SET customer_status='inactive' where cust_id=$cust_id";
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());

	//zapis do logu
	$curr_date = date('Y-m-d H:i:s');
	$curr_action = "make_customer_inactive";
	$curr_app = "customers";
	$text_logu = "the customer with id $cust_id has been made inactive";

	$sql = "INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";

	$result = mysqli_query($con, $sql); // or die("MySQL ERROR: ".mysqli_error());
	echo "<script>alert('the customer with with id $cust_id has been made inactive');
    location.href='index.php'</script>";

}

//make the customer active
if (isset($_POST['make_active'])) {
	global $con;
	$cust_id = intval($_POST['cust_id']);
	$sql = "UPDATE tblcustomers SET customer_status='active' where cust_id=$cust_id";
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());

	$curr_date = date('Y-m-d H:i:s');
	$curr_action = "make_customer_active";
	$curr_app = "customers";
	$text_logu = "the customer with id $cust_id has been made active";

	$sql = "INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";

	$result = mysqli_query($con, $sql); // or die("MySQL ERROR: ".mysqli_error());
	echo "<script>alert('the customer with with id $cust_id has been made active');
    location.href='index.php'</script>";
}
?>
<!DOCTYPE html>
<head>
   <meta charset="utf-8" />
   <meta name="viewport" content="width=device-width,initial-scale=1">

   <title>axoloth - customers</title>
   <link href="../css/style_new.css?<?php echo time(); ?>" rel="stylesheet" type="text/css">
   <link href="../css/customers.css?<?php echo time(); ?>" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css' rel='stylesheet' type='text/css'>
   <?php include "../include/icon.php"?>
</head>
<body>

<?php
include '../include/header.php';
?>

   </div><!-- header -->
     <div id="layout">
        <div class="fab-container">
        <div class="fab fab-icon-holder">
           <a href="#"><i class="fa fa-plus"></i></a>
      </div>

    </div><!-- fab container -->


     <div id="customer_info_wrap">
        <div class="customers_wrap">
           <header>
              <input type="text" name="search_customer" id="search_customer" autocomplete="off" placeholder="type customer name">
              <span>Filter:</span>
              <div class="filter">
                     <select name="customer_status" id="customer_status" >
                       <option value="active">Active</option>
                       <option value="inactive">Inactive</option>
                       <option value="all">All</option>
                    </select>
              </div>
           </header>
           <div class="customers">
            <table>
                <?php
if (isset($_POST['filer_customer'])) {
	$customer_status = $_POST['choose_customer_status'];

	if ($customer_status == 'active') {
		$sql = "SELECT * from tblcustomers where customer_status='active'  ORDER BY customer_name ASC";
	} elseif ($customer_status == 'inactive') {
		$sql = "SELECT * from tblcustomers where customer_status='inactive' ORDER BY customer_name ASC";
	} elseif ($customer_status == 'all') {
		$sql = "select * from tblcustomers";
	}
} else {
	$sql = "select * from tblcustomers where customer_status='active'";
}

$result = mysqli_query($con, $sql) or die(mysqli_error());

while ($row = mysqli_fetch_array($result)) {
	$customer_name = $row['customer_name'];
	$cust_id = $row['cust_id'];
	$customer_status = $row['customer_status'];

	echo "<tr>";
	//echo "<td onclick='update_status(this.innerText, $id)'>";
	/*echo "<td onclick='show_customers_details(\"$customer_name\")'>$customer_name</td>";*/
	echo "<td><div class='customer'><div class='customer_name'>$customer_name</div><div id='customer_actions'><ul><li><a href='customer_update.php?cust_id=$cust_id' class='btn-small'>Edit</a></li><li>";

	if ($customer_status == 'active') {
		?> <label class="switch"> <input type="checkbox" checked  customer-id='<?php echo $cust_id ?>'> <span class="slider round"></span></label><?php
} else {

		?><label class="switch"><input type="checkbox"  customer-id='$cust_id'><span class="slider round"></span></label>
                    <?php
}

	echo "</li></ul></div></td>";

	echo "</tr>";
}

?>
             </table>
        </div>
        <!-- dvi customers -->
    </div><!-- customers wrapper -->

    <div id="add_new_customer" class="overlay">
            <div class="new_customer_wrap">
                <input type="text" id="customer_name" autocomplete="off" placeholder="customer name...">
                <textarea id="customer_description" placeholder="customer description..."></textarea>
                <input type="text" id="customer_url" autocomplete="off" placeholder="customer url...">
                <section>
                    <button type="button" class="flat-btn" id="hide_add_customer"><i class="fa fa-times"></i></button>
                    <button type="button" class="flat-btn" id="add_customer">Add customer</button>
                </section>
            </div>
     <!-- main container>  -->

     <script type="text/javascript" src="../js/customers.js"></script>
    </body>
</html>
