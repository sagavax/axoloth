<?php
include("../include/dbconnect.php");

$category = $_GET['category'];
$sql="SELECT cat_id, cat_name from tblcustomer_categories WHERE cat_name LIKE '%".$category."%'";
$result=mysqli_query($con,$sql) or die("MySQL ERROR: ".mysqli_error($con));
$num_rows = mysqli_num_rows($result);
while($row = mysqli_fetch_array($result)){
            $cat_id =$row['cat_id'];
            $cat_name =$row['cat_name'];
            echo "<li><div class='category_link'><a href='index.php?cat_id=$cat_id'>$cat_name</a></div></li>";
        }           

