<?php session_start();
$_SESSION['url'] = $_SERVER['REQUEST_URI'];

if (!isset($_SESSION['login'])) {
	header("location:index.php");
}

include "../include/dbconnect.php";
include "../include/functions.php";
include_once "../include/config.php";
?>

<?php
if (isset($_POST['new_password'])) {

	//var_dump($_POST);

	if ($_POST['customer_id'] == "-- Choose the customer --") {
		$cust_id = 0;
	} else {
		$cust_id = $_POST['customer_id'];
	}
	//echo "<script>alert('No customer has been chosen');</script>";

	$group_name = CustomerName($cust_id);
	$system_name = trim(mysqli_real_escape_string($con, $_POST['system_name']));

	$user_name = mysqli_real_escape_string($con, $_POST['user_name']);
	$password1 = mysqli_real_escape_string($con, $_POST['password']);
	$url = mysqli_real_escape_string($con, $_POST['url']);
	$category = $_POST['category'];
	$date_added = date('Y-m-d');
	$description = mysqli_real_escape_string($con, $_POST['description']);
	global $con;

	$sql = "INSERT INTO tblpasswords (cust_id,group_name,system_name,User_name, Password, Category, URL, description,date_added ) VALUES ($cust_id,'$group_name','$system_name','$user_name','$password1','$category','$url','$description','$date_added')";
	//echo $sql;
	$result = mysqli_query($con, $sql) or die("MySQLi ERROR: " . mysqli_error($con));

	$curr_date = date('Y-m-d H:i:s');
	$new_pass_id = GetLatestPassword();

	$link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
	//$link1 = mysqli_connect("localhost","root","","brick_wall");
	$diary_text = "EIS: Bolo vytvorene nove heslo s id $new_pass_id a systemovym menom <strong>$system_name</strong>";
	$sql = "INSERT INTO diary (diary_text, date_added,location,isMobile,is_read) VALUES ('$diary_text','$curr_date','',0,0)";
	$result = mysqli_query($link1, $sql) or die("MySQLi ERROR: " . mysqli_error($link1));
	mysqli_close($link1);

	$curr_action = "create_password";
	$curr_app = "passmgr";
	$text_logu = "new password id $new_pass_id has been created";

	//vlozenie to timeliny
	$sql = "INSERT INTO tblpasswords_timeline (pass_id,pass_action, action_time) VALUES ($new_pass_id,'password was created','$curr_date')";
	$result = mysqli_query($con, $sql); // or die("MySQL ERROR: ".mysqli_error());

	$sql = "INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
	//echo $sql;

	$result = mysqli_query($con, $sql); // or die("MySQL ERROR: ".mysqli_error());
	echo "<script>alert('new password $new_pass_id is  has been created');
      location.href='index.php'</script>";
}

?>


<!DOCTYPE html>
<head>
   <meta charset="utf-8" />
   <link href="../css/style_new.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
   <link href="../css/passmgr.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
   <script defer src="https://use.fontawesome.com/releases/v5.0.9/js/all.js" integrity="sha384-8iPTk2s/jMVj81dnzb/iFR2sdA7u06vHJyyLlAd4snFpCl/SnyUjRrbdJsw1pGIl" crossorigin="anonymous"></script>
   <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>

   <meta name="viewport" content="width=device-width,initial-scale=1">
   <?php include "../include/icon.php"?>
   <title>Axoloth - Passwords</title>
</head>
<body>
   <?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
   <script>

      //Random password generator- by javascriptkit.com
      //Visit JavaScript Kit (http://javascriptkit.com) for script
      //Credit must stay intact for use

            function LogonType(type_id){
              //alert(type_id);
              if(type_id==0){
                document.new_password.user_name.value="";
                document.new_password.password.value="";
              } else if(type_id==1){
                document.new_password.user_name.value="tmisura@gmail.com";
                document.new_password.password.value="Thomas$.pa$$w0rd";
              } else if (type_id==2){
                document.new_password.user_name.value="tmisura@gmail.com";
                document.new_password.password.value="YTOKaQHOhXO";
              } else if (type_id==3) {
                document.new_password.user_name.value="ftb_axoloth";
                document.new_password.password.value="g1sqrrprfwb5yhh";
              } else if (type_id==4) {
                document.new_password.user_name.value="tmisura";
                document.new_password.password.value="Toma$.pa$$w0rd";
            } else if (type_id==5) {
                document.new_password.user_name.value="tmisura@gmail.com";
                document.new_password.password.value="28d72uvyesn2eka";
            }   else if (type_id==6) {
                document.new_password.user_name.value="sagavax";
                document.new_password.password.value="sxzec4yytboelcj";
            }   else if(type_id==7){
                document.new_password.user_name.value="tmisura@gmail.com";
                document.new_password.password.value="642vol5mmedfrxs";
            }
            }
      </script>

   <?php
include '../include/header.php';
?>

   <div id="layout">
      <div class="password">
          <form accept-charset="utf-8" method="post" action="" name="new_password">
            <table>
               <tr>
                  <td>
                     <select name="customer_id">
                        <option>-- Choose the customer -- </option>
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
                     <input type="text" name="system_name" id="system_name" autocomplete="off" placeholder="System name" value="" required>
                  </td>
               </tr>
               <tr>
                    <td><input type="text" autocomplete="off" name="user_name" placeholder="User name" value=""></td>
               </tr>

               <tr>
                  <td>
                     <div class="embed-button">
                        <input type="text" name="password" id="password" placeholder="Password" value="">
                        <button onclick="generate_password()" class="flat-btn" type="button"  id="generuj_pass"><i class="fas fa-sync-alt"></i></button>
                        <button type="button" class="flat-btn" id="copy_to_clip" onclick="copyPassword()"><i class="fas fa-clipboard"></i></button>
                     </div>
                  </td>
               </tr>

               <tr>
                  <td><select name="logon_type" onchange="LogonType(this.value)";>
                      <option value=0>internal</option>
                      <option value=1>Google</option>
                      <option value=2>Facebook</option>
                      <option value=3>Twich</option>
                      <option value=4>Twitter</option>
                      <option value=5>LinkedIn</option>
                      <option value=6>Github</option>
                      <option value=7>Atlanssian</option>
                  </select></td>
               </tr>

               <tr>
                 <td>
                     <select name='category'>
                        <option value="Private">Private</option>
                        <option value="Business">Business</option>
                     </select>
                  </td>
               </tr>

               <tr>
                  <td><input type="text" value="" name="url" placeholder="Url" oninput="get_host_name(this.value);"></td>
               </tr>

               <tr>
                  <td><textarea name="description" placeholder="Description"></textarea></td>
               </tr>

               <tr>
                  <td colspan="2" style="text-align:right"><div class="add_password_action"><a href='index.php' class="flat-btn">&lt;&lt; Back</a><button type="submit" name="new_password" class="flat-btn">New password</button></div></td>
               </tr>
            </table>
         </form>
         </div>
      <!-- wrap -->
   </div>
   <!-- layout-->
   <script src="../js/pass_gen.js"></script>
</body>
