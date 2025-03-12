<?php
   session_start();
   ?>
<?php
   include("../include/dbconnect.php");
   ?>
<?php
   include("../include/functions.php");
   ?>

<?php
   //crate a new customer
   if (isset($_POST['new_customer'])) {
       //$customer      = $_POST['customer'];
       $customer_name = mysqli_real_escape_string($_POST['customer_name']);

       //skontroluj ci ten zakazni uz neexistuje
       $sql="SELECT count(*) as cust_count from tblcustomers WHERE customer_name='$customer_name'";
       global $con;
       $result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());
       $row     = mysqli_fetch_array($result);
       if($row['cust_count']==1) {
        echo '<script type="text/javascript">alert("This customer already exists!!!!");
            window.location = "index.php";
        </script>';
       // header('Location: index.php', true, 303); // presmeruje spat aby sa zbranilo vkladaniu duplicity
       } else {

       global $con;
       $sql           = "SELECT MAX(cust_id) as cust_id from tblcustomers";
       $result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());
       $row     = mysqli_fetch_array($result);
       $cust_id = $row['cust_id'];

       $cust_id = $cust_id + 1;

       $sql = "INSERT INTO tblcustomers (cust_id, customer_name, customer_status) VALUES ($cust_id,'$customer_name','active')";
       global $con;
       $result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());
       header('Location: index.php', true, 303); // presmeruje spat aby sa zbranilo vkladaniu duplicity
     }

   }


   //make the customer inactive
   if (isset($_POST['make_inactive'])) {
       $cust_id = $_POST['cust_id'];
       $sql     = "UPDATE tblcustomers SET customer_status='inactive' where cust_id=$cust_id";
       global $con;
       $result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());
       header('Location: index.php', true, 303); // presmeruje spat aby sa zbranilo vkladaniu duplicity
   }


   //make the customer active
   if (isset($_POST['make_active'])) {
       $cust_id = $_POST['cust_id'];
       global $con;
       $sql     = "UPDATE tblcustomers SET customer_status='active' where cust_id=$cust_id";
       $result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());
       header('Location: index.php', true, 303); // presmeruje spat aby sa zbranilo vkladaniu duplicity
   }
   ?>
<!DOCTYPE html>
<head>
   <meta charset="utf-8" />
   <meta name="viewport" content="width=device-width,initial-scale=1">

   <title>E.I.S. - Customers</title>
   <link href="../css/style.css" rel="stylesheet" type="text/css">
   <link href="../css/style_new.css" rel="stylesheet" type="text/css">
   <link href="../css/font-awesome.css" rel="stylesheet" type="text/css" />
   <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
   <link rel='shortcut icon' href='../eis.ico'>
</head>
<body>
   <div id="header">
      <!--header -->
      <div class="header-logo">e.i.s. - enterprise informating system</div>
      <!--logo -->
      <div class="header-menu">
         <!--menu-->
         <?php include("../include/menu.php"); ?>
      </div>
      <div id="txt" style="float:right; margin-right:5px; color:#fff"></div>
   </div>
   </div><!-- header -->
     <div id="layout">
     <h2>Customers:</h2>
     <div id="customer_info_wrap">
        <div id="div_customers">
           <header>
              <span>Filter:</span>
              <div id="filter">
                 <form action="index.php" method="post">
                    <select name="choose_customer_status">
                       <option value="active">Active</option>
                       <option value="inactive">Inactive</option>
                       <option value="all">All</option>
                    </select>
                    <button type="submit" name="filer_customer" class="btn-small">Select</button>
                 </form>
              </div>
           </header>
           <table class="customers">
              <?php
                 if (isset($_POST['filer_customer'])) {
                     $customer_status = $_POST['choose_customer_status'];

                     if ($customer_status == 'active') {
                         $sql = "SELECT * from tblcustomers where customer_status='active'";
                     } elseif ($customer_status == 'inactive') {
                         $sql = "SELECT * from tblcustomers where customer_status='inactive'";
                     } elseif ($customer_status == 'all') {
                         $sql = "select * from tblcustomers";
                     }
                 } else {
                     $sql = "select * from tblcustomers where customer_status='active'";
                 }


                 $result = mysqli_query($con, $sql) or die(mysqli_error());
                    
                 $i=0;
                 
                 while ($row = mysqli_fetch_array($result)) {
                     $customer_name   = $row['customer_name'];
                     $cust_id         = $row['cust_id'];
                     $customer_status = $row['customer_status'];

                     $i++;

                     echo "<tr>";
                     //echo "<td onclick='update_status(this.innerText, $id)'>";
                     /*echo "<td onclick='show_customers_details(\"$customer_name\")'>$customer_name</td>";*/
                     echo "<td style='width:500px'><a href='customer.php?cust_id=$cust_id' class='customer_name'>$customer_name</a></td>";
                     echo "<td style='width:85px'><a href='../contacts/contact_add.php?cust_id=$cust_id' class='btn-small'>+ contact</a></td>";
                     echo "<td style='width:75px'><a href='../events/event.php?cust_id=$cust_id&action=new' class='btn-small'>+ event</a></td>";
                     echo "<td style='width:65px'><a href='../notepad/note_add.php?cust_id=$cust_id' class='btn-small'>+ note</a></td>";
                     echo "<td style='width:65px'><a href='../tasks/task_add.php?cust_id=$cust_id' class='btn-small'>+ task</a></td>";
                     echo "<td style='width:45px'><a href='customer_update.php?cust_id=$cust_id' class='btn-small'>Edit</a></td>";
                     echo "<td style='width:35px'>";
                     echo "<form action=index.php method='post'><input type='hidden' name='cust_id' value='$cust_id'>";
                     if ($customer_status == 'active') {
                         echo "<button type='submit' name='make_inactive' class='btn-small' title='Make inactive'>x</button>";
                     } else {
                         echo "<button type='submit' name='make_active' class='btn-small-red' title='Make active'>+</button>";
                     }
                     echo "</form>";
                     echo "</td>";

                     echo "</tr>";

                 }
                 ?>
           </table>
           <div style="width:100%,float:left; margin-top:10px;" >
              <form method="post" name="form" action="index.php">
                 <table>
                    <tr>
                       <td><span class="label">New customer:</span></td>
                       <td><input type="text" name="customer_name"/>
                       <td>
                       <td colspan="2"><button name="new_customer" type="submit" class="btn-small">+ New</button></td>
                       </td>
                    </tr>
                 </table>
              </form>
           </div>
        </div>
        <!-- dvi customers -->


     <!-- main container>  -->
</body>
</html>
