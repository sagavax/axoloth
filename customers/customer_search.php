<?php include("../include/dbconnect.php");

$customer = $_GET['text'];
$search_customer = "SELECT * from tblcustomers where customer_name LIKE '%".$customer."%'";
$result = mysqli_query($con, $search_customer) or die(mysqli_error($con));

echo "<table>";

while ($row = mysqli_fetch_array($result)) {
    $customer_name   = $row['customer_name'];
    $cust_id         = $row['cust_id'];
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
        
        echo "</li></ul></div></div></td>";
    
    echo "</tr>";
}

    ?>