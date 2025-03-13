<?php include("../include/dbconnect.php"); ?>
<?php include("../include/functions.php"); ?>

  <?php
if (isset($_POST['add_contact']))
{
  $first_name=mysqli_real_escape_string($_POST['first_name']);
  $last_name=mysqli_real_escape_string($_POST['last_name']);
  $customer_contact_descr=mysqli_real_escape_string($_POST['customer_contact_descr']);
  $cust_id=$_POST['customer_name'];
  $date_created=date('Y-m-d');
  $role=$_POST['role'];
  //if(isset($_POST['is_customer'])){$is_customer=1;} else {$is_customer=0;}


  $sql= "INSERT INTO tblcustomer_contacts (cust_id, first_name, last_name, role, active, description,date_created ) VALUES ($cust_id,'$first_name','$last_name',$role,1,'$customer_contact_descr','$date_created')";
  global $con;
  $result = mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error());

  header('location:index.php');

}


?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sk" lang="sk">
<head>
    <meta charset="utf-8" />
    <link href="../css/style.css" rel="stylesheet" type="text/css">
	<link href="../css/style_new.css" rel="stylesheet" type="text/css">
	<link href="../css/jquery.autocomplete.css" rel="stylesheet" type="text/css">
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="" />
    <title>E.I.S. - Add new task</title>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script> -->


</head>
<body>



		<?php
			$cust_id=$_GET['cust_id'];

		?>

		<div id="header"> <!--header -->
                        <div class="header-logo">e.i.s. - enterprise informating system</div> <!--logo -->
                        	<div class="header-menu"> <!--menu-->
                                <ul>
                                  <!--<li><a href="serveris.php" target="_self"><img src="images/home.png" alt="Home" /></a></li>-->
                                  <li><a href="../serveris.php" target="_self">Home</a></li>
                                  <li><a href="../passmgr/index.php" target="_self">Password manager</a></li>
                                  <li><a href="../notepad.php" target="_self">Notepad</a></li>
                                  <li><a href="../customers/index.php" target="_self">Customers</a></li>
                                  <li><a href="../links/index.php" target="_self">Links</a></li>
                                  <li><a href="index.php" target="_self">Tasks</a></li>
                                  <li><a href="../incidents/index.php" target="_self">Incidents</a></li>
                                  <li><a href="../servers/index.php" target="_self">Servers</a></li>
                                  <li><a href="../admin/index.php" target="_self">Admin</a></li>
                                </ul>
  						  </div><!--menu-->
                 </div> <!--end of header -->


         <div id="layout">
         	<div id="new_contact_wrap">
         		<H2>Add new contact:</H2>
					  <form accept-charset="utf-8" method="post" action="contact_add.php">
					      <input type="hidden" name="id">
					      <table id="new_contact" border="0" border-spacing="0">

					          <tr>
					           	<td>First name:</td><td><input type="text" name="first_name" /></td>
					          </tr>
					          <tr>
					           	<td>Last name:</td><td><input type="text" name="last_name" /></td>
					          </tr>
					           <tr>
					           	<td>Role:</td><td><input type="text" name="role" /></td>
					          </tr>
					          <tr>
					            <td>Customer:</td>
					            <td>
					              	<select name="customer_name">
					              		<option value=0>-- Choose the customer --</option>
					                	 <?php

					                	 if (isset($_GET['cust_id'])) // task pridavam z externej stranky
					                	 {
					                	 	$cust_id=$_GET['cust_id']; //mame id
					                	 	$customer_name=CustomerName($cust_id); //mame meno zakaznika
					                	 	echo "<option value=$cust_id selected='selected'>$customer_name</option>";

					                	 	$sql = "SELECT cust_id,customer_name from tblcustomers where customer_status='active'";
					                    	$rsd=mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error());
					                      		while ($row = mysqli_fetch_array($rsd)) {
					                      		$customer_name=$row['customer_name'];
					                      		$cust_id=$row['cust_id'];
					                      		echo "<option value='cust_id'>$customer_name</option>";
					                      	}

					                	 } else { //task je uplne novy a je vytvarany z modulu tasks

					                	 $sql = "SELECT cust_id,customer_name from tblcustomers where customer_status='active'";
					                     $rsd=mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error());
					                      while ($row = mysql_fetch_array($rsd)) {
					                      	$customer_name=$row['customer_name'];
					                      	$cust_id=$row['cust_id'];
					                      	echo "<option value='".$row['cust_id']."'>$customer_name</option>";
					                      }
					                	 }
					                  ?>
					                  </select>


					            </td>
					        </tr>
					        <tr>
					        	<td colspan="2"><textarea name="customer_contact_descr"></textarea></td>

					        </tr>

					        <tr>
					           <td style="text-align:right" colspan="2"><button name="add_contact" class="flat-btn">Add new</button></td>
					        </tr>
					    </table>
					</form>
         	</div>
        </div>


		<div id="container">
		  	<div id="center" class="column">


				<div style="background-color: #D7E2F2; border: 1px #ccc solid; width: 450px;padding:5px; border-radius: 3px; padding: 10px;
				 margin: 40px auto 30px auto; height: auto"><!-- form -->


				</div><!-- form -->


		</div><!--center-->
	</div><!--container-->
</body>
</html>
