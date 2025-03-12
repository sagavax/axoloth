<?php session_start() ?>
<?php include("../include/dbconnect.php"); ?>
<?php include("../include/functions.php"); ?>

<?php
  if (isset($_POST['update_customer']))
    {
        global $con;
        $cust_id=$_POST['cust_id'];
        $customer_name=mysqli_real_escape_string($con, $_POST['customer_name']);
        $customer_short_details=mysqli_real_escape_string($con, $_POST['customer_description']);
        $customer_url=mysqli_real_escape_string($con, $_POST['customer_url']);
        $customer_status=mysqli_real_escape_string($con, $_POST['customer_status']);

        $sql="UPDATE tblcustomers SET customer_name='$customer_name', customer_short_details='$customer_short_details', customer_status='$customer_status',customer_url='$customer_url'  WHERE cust_id=$cust_id";
        $result = mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error());
        
        $curr_date=date('Y-m-d H:i:s');
        $curr_action="create_note";
        $curr_app="notepad";
        
        $text_logu="the customer with id $cust_id has been updated";
        
        $sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
        $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error());
        
        echo "<script>toastr.success('the customer with id $cust_id has been updated');
        window.location='index.php';   
        </script>";
        
}


?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sk" lang="sk">
<head>
  <meta charset="utf-8" />
  <link href="../css/style_new.css" rel="stylesheet" type="text/css">
  <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
   <script src="../js/toastr.min.js"></script>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Update customer's information</title>
    <style>
    </style>
</head>

<?php

  $cust_id=$_GET['cust_id'];

?>

<body>
<div id="header">
      <!--header -->
      <div class="header-logo">e.i.s.</div>
      <!--logo -->
      <div class="header-menu">
         <!--menu-->
        <?php include('../include/menu.php') ?>
      </div>
   </div>

  <div id="layout">

    <?php
      global $con;
      $sql="SELECT * from tblcustomers where cust_id=$cust_id";
       $result = mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error());
       $row = mysqli_fetch_array($result);
    ?>

    <div id="customer_update_info_wrap">
      <h2>Edit customer information: </h2>
      <form accept-charset="utf-8" method="post" action="">
        <input type="hidden" name="cust_id" value="<?php echo $cust_id ?>" />

        <table id="update_customers_info">
          <tr>
            <td>Customer:</td><td><input type="text" name="customer_name" value="<?php echo $row['customer_name']; ?>" /></td>
          </tr>
          <tr>
            <td>Note:</td><td><textarea id="note" name="customer_description"><?php echo $row['customer_short_details']; ?></textarea></td>
          </tr>
          <tr>
            <td>Url:</td><td><input type="text" name="customer_url" value="<?php echo $row['customer_url']; ?>" /></td>
          </tr>
          <tr>
            <td>Status:</td>
            <td><select name="customer_status">
              <?php
                   $customer_status=$row['customer_status'];
                   echo "<option value=$customer_status selected=\"selected\">$customer_status</option>";
              ?>
              <option value="active">active</option>
              <option value="inactive">inactive</option>
            </select></td>
          </tr>

          <tr>
            <td colspan="2" style="text-align:right"><button type="submit" name="update_customer" class="flat-btn">Update</button></td>
          </tr>
        </table>
      </form>

    </div> <!-- customer_update_info_wrap  -->
    <a href='index.php' class="action_link">&lt;&lt; Back</a>

   </div><!-- layout -->
</body>
</html>
