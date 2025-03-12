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
    global $con;
    $customer_name = trim(mysqli_real_escape_string($con, $_POST['customer_name']));

    //skontroluj ci ten zakazni uz neexistuje
    $sql="SELECT count(*) as cust_count from tblcustomers WHERE customer_name='$customer_name'";
    //echo $sql;
    $result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());
    $row     = mysqli_fetch_array($result);
    if ($row['cust_count']==1) {
        echo "<script type='text/javascript'>alert('This customer already exists!!!!');
            window.location = 'index.php';
        </script>";
       // header('Location: index.php', true, 303); // presmeruje spat aby sa zbranilo vkladaniu duplicity
    } else {
        global $con;
        $sql           = "SELECT MAX(cust_id) as cust_id from tblcustomers";
        $result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());
        $row     = mysqli_fetch_array($result);
        $cust_id = $row['cust_id'];

        $cust_id = $cust_id + 1;

        $sql = "INSERT INTO tblcustomers (cust_id, customer_name, customer_status) VALUES ($cust_id,'$customer_name','active')";
        $result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());


        //zapis do inboxu
        $curr_date=date('Y-m-d H:i:s');
        $curr_action="create_new_customer";
        $curr_app="customers";
        $new_cust_id=GetLatestCustomer();
        $text_logu="new customer with id $new_cust_id has been created";
        $sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
        
        $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error());

        
        //zapis do wallu
        $link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
        $diary_text="EIS: Bola vytvoreny novy zakaznik s id $new_cust_id a nazvom <strong>$csutomer_title</strong>";
        
        $sql="INSERT INTO diary (diary_text, date_added,location,isMobile,is_read) VALUES ('$diary_text','$curr_date','',0,0)";
        $result = mysqli_query($link1, $sql) or die("MySQLi ERROR: ".mysqli_error($link1));
        mysqli_close($link1);


       echo "<script>alert('new customer has been created');
       window.location='index.php';   
       </script>";
        
    }
}


   //make the customer inactivefff
if (isset($_POST['make_inactive'])) {
    $cust_id = intval($_POST['cust_id']);
    $sql     = "UPDATE tblcustomers SET customer_status='inactive' where cust_id=$cust_id";
    $result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());
    
    //zapis do logu
    $curr_date=date('Y-m-d H:i:s');
    $curr_action="make_customer_inactive";
    $curr_app="customers";
    $text_logu="the customer with id $cust_id has been made inactive";
  
    $sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
  
    $result = mysqli_query($con, $sql); // or die("MySQL ERROR: ".mysqli_error());
    echo "<script>alert('the customer with with id $cust_id has been made inactive');
    location.href='index.php'</script>";  
    
}


   //make the customer active
if (isset($_POST['make_active'])) {
    global $con;
    $cust_id = intval($_POST['cust_id']);
    $sql     = "UPDATE tblcustomers SET customer_status='active' where cust_id=$cust_id";
    $result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());
    
    $curr_date=date('Y-m-d H:i:s');
    $curr_action="make_customer_active";
    $curr_app="customers";
    $text_logu="the customer with id $cust_id has been made active";
  
    $sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
  
    $result = mysqli_query($con, $sql); // or die("MySQL ERROR: ".mysqli_error());
    echo "<script>alert('the customer with with id $cust_id has been made active');
    location.href='index.php'</script>";  
}
    ?>
<!DOCTYPE html>
<head>
   <meta charset="utf-8" />
   <meta name="viewport" content="width=device-width,initial-scale=1">

   <title>e.i.s.</title>
   <link href="../css/style_new.css?<?php echo time(); ?>" rel="stylesheet" type="text/css">
   <link href="../css/font-awesome.css" rel="stylesheet" type="text/css" />
   <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
   <link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
   <link rel='shortcut icon' href='../eis.png'>
</head>
<body>
   <div id="header">
      <!--header -->
      <div class="header-logo">e.i.s.</div>
      <!--logo -->
      <div class="header-menu">
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
              <div class="filter">
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
                        $sql = "SELECT * from tblcustomers where customer_status='active'  ORDER BY customer_name ASC";
                    } elseif ($customer_status == 'inactive') {
                        $sql = "SELECT * from tblcustomers where customer_status='inactive' ORDER BY customer_name ASC";
                    } elseif ($customer_status == 'all') {
                        $sql = "select * from tblcustomers";
                    }
                } else {
                    $sql = "select * from tblcustomers where customer_status='active'";
                }
                  
                $i=0;

                $result = mysqli_query($con, $sql) or die(mysqli_error());
                  

                while ($row = mysqli_fetch_array($result)) {
                    $customer_name   = $row['customer_name'];
                    $cust_id         = $row['cust_id'];
                    $customer_status = $row['customer_status'];

                    $i++;

                    echo "<tr>";
                    //echo "<td onclick='update_status(this.innerText, $id)'>";
                    /*echo "<td onclick='show_customers_details(\"$customer_name\")'>$customer_name</td>";*/
                    echo "<td style='width:45%'><a href='customer.php?cust_id=$cust_id' class='customer_name'>$customer_name</a></td><td><ul id='customers_actions'><li></li><li><a href='../contacts/contact_add.php?cust_id=$cust_id' class='btn-small'>+ contact</a></li><li><a href='../events/event.php?cust_id=$cust_id&action=new' class='btn-small'>+ event</a></li><li><a href='../notepad/note_add.php?cust_id=$cust_id' class='btn-small'>+ note</a></li><li><a href='../tasks/task_add.php?cust_id=$cust_id' class='btn-small'>+ task</a></li><li><a href='customer_update.php?cust_id=$cust_id' class='btn-small'>Edit</a></li><li>";
                    
                    if ($customer_status == 'active') {
                        //echo "<button type='submit' name='make_inactive' class='btn-small' title='Make inactive'></button>";
                        echo "<a href='customer2inactive.php?cust_id=$cust_id' class='btn-small'><i class='fa fa-times'></i></a>";
                    } else {
                        //echo "<button type='submit' name='make_active' class='btn-small-red' title='Make active'><i class='fa fa-plus'></i></button>";
                        echo "<a href='customer2active.php?cust_id=$cust_id' class='btn-small'><i class='fa fa-plus'></i></a>";
                    }
                    
                    echo "</li></ul></td>";
                    
                    echo "</tr>";
                }

                    ?>
              <tr><td colspan="2"> <form method="post" name="form" action="index.php">
                    <span class="label">New customer:</span>
                    <input type="text"id="customer_name" name="customer_name"/>
                    <button name="new_customer"type="submit" class="btn-small">+ New</button></td>
                </form></td></tr>      
           </table>
        </div>
        <!-- dvi customers -->


     <!-- main container>  -->
</body>
</html>
