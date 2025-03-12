<?php session_start() ?>
<?php include("../include/dbconnect.php"); ?>
<?php include("../include/functions.php"); ?>

<?php

 ?>

<!DOCTYPE html>
 <head>
        <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sk" lang="sk">
		<meta charset="utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<meta name="author" content="" />
    <link href="../css/style_new.css" rel="stylesheet" type="text/css" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <meta name="viewport" content="width=device-width,initial-scale=1">

        <!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>-->
        <title>Enterprise informating system - Contact manager</title>

            </head>
      <body>
           	<div id="header"> <!--header -->
                        <div class="header-logo">e.i.s. - enterprise informating system</div> <!--logo -->
                        	<div class="header-menu"> <!--menu-->
                                <ul>
                                	<li><a href="serveris.php" target="_self"><img src="images/home.png" alt="Home" /></a></li>
          							          <li><a href="../serveris.php" target="_self">Home</a></li>
          							          <li><a href="../passmgr/index.php" target="_self">Password manager</a></li>
          							          <li><a href="../notepad/index.php" target="_self">Notepad</a></li>
          							          <li><a href="../customers/index.php" target="_self">Customers</a></li>
          							          <li><a href="index.php" target="_self">Tasks</a></li>
          							          <li><a href="../servers/index.php" target="_self">Servers</a></li>
          							          <li><a href="../events/index.php" target="_self">Events</a></li>
                                </ul>
  						  </div><!--menu-->
          </div> <!--end of header -->


      <div id="layout">
         <?php
                 $contact_id=$_GET['id'];
                 $action=$_GET['action'];

                 if ($action=='view') { //ak ideme kontakt prezerat

                  ?>

                     <table id="view_contact" border="0" cellspacing="0">

                      <tr>
                        <td>First name:</td><td><input type="text" name="first_name" value=""/></td>
                      </tr>
                      <tr>
                        <td>Last name:</td><td><input type="text" value=""/></td>
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
                                  $rsd=mysqli_query($sql) or die("MySQL ERROR: ".mysqli_error());
                                      while ($row = mysqli_fetch_array($rsd)) {
                                      $customer_name=$row['customer_name'];
                                      $cust_id=$row['cust_id'];
                                      echo "<option value='cust_id'>$customer_name</option>";
                                    }

                               } else { //task je uplne novy a je vytvarany z modulu tasks

                               $sql = "SELECT cust_id,customer_name from tblcustomers where customer_status='active'";
                               global $con;
                                 $rsd=mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error());
                                  while ($row = mysqli_fetch_array($rsd)) {
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


                 </table>

                 <?php

                 } elseif ( $action=='modify') {


                  $sql="SELECT first_name, last_name from tblcustomer_contacts WHERE contact_id=$contact_id";
                  global $con;
                   $rsd=mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error());
                    $row = mysqli_fetch_array($rsd);

                    ?>


                   <form action="contact.php" method="post">
                    <table id="modify_contact" border="0" cellspacing="0">

                      <tr>
                        <td>First name:</td><td><input type="text" name="first_name" value="<?php echo $row['first_name'];?>"/></td>
                      </tr>
                      <tr>
                        <td>Last name:</td><td><input type="text" value="<?php echo $row['last_name'];?>"/></td>
                      </tr>
                      <tr>
                        <td>Role:</td><td><input type="text" value="<?php echo $row['role'];?>"/></td>
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
                                  $rsd=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
                                      while ($row = mysql_fetch_array($rsd)) {
                                      $customer_name=$row['customer_name'];
                                      $cust_id=$row['cust_id'];
                                      echo "<option value='cust_id'>$customer_name</option>";
                                    }

                               } else { //task je uplne novy a je vytvarany z modulu tasks

                               $sql = "SELECT cust_id,customer_name from tblcustomers where customer_status='active'";
                                 $rsd=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
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
                        <td colspan="2"><textarea name="customer_contact_descr"><?php echo $row['description'];?></textarea></td>

                      </tr>

                        <tr>
                       <td style="text-align:right" colspan="2"><button name="modify_contact" class="flat-btn">Update</button></td>
                    </tr>
                   </table>
                  </form>


                  <?php
                 } elseif ($action=='delete') {

                 $sql="DELETE from tblcustomer_contacts WHERE contact_id=$contact_id";
                  $result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
                 header('location:index.php');


                 } elseif ($action=='add_info') { //pridanie samotnych kontaktov

                   $sql="SELECT first_name, last_name from tblcustomer_contacts WHERE contact_id=$contact_id";
                   $rsd=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
                      while ($row = mysql_fetch_array($rsd)) {
                      $customer_full_name=$row['first_name'].' '.$row['last_name'];
                  }

                  ?>

                  <h2><?php echo $customer_full_name;  ?></h2>

                  <form action="contact.php" method="post">
                    <table id="add_contact_info" border="0" cellspacing="0">
                     <input type="hidden" name="contact_id" value="id">
                     <tr>
                       <td>
                        <select name="contact_typpe">
                          <option value="1">Email</option>
                          <option value="2">Phone</option>
                          <option value="3">Mobile</option>
                        </select>
                      </td>
                      <td><input type="text" name="contact_value">
                     </tr>
                     <tr>
                      <td colspan="2" style="text-align:right"><button class="flat-btn" name="add_info">Add new</button></td>

                     </tr>


                    </table>
                  </form>
          <?php
                 }


         ?>
      </div>
