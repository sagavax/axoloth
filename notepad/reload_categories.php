<?php include("../include/dbconnect.php"); 
      



///echo "<li><form action='' method='post'><button type='submit' name='add_new_cat' style='width:95%; height:100%; margin-left:2px;margin-top:3px;box-sizing:border-box; border:1px dashed #666;float:left;'>+</button></form></li>";
echo "<li category-id='all'>All</li>";
echo "<li category-id='Unsorted'>Unsorted</li>";
global $con;
$sql = "SELECT DISTINCT category_name, cat_id from tblcustomer_notes_category ORDER BY category_name ASC";
$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
while ($row = mysqli_fetch_array($result)) {

    $category_name = $row['category_name'];
    $cat_id = $row['cat_id'];
    //echo "<li><a href='index.php?cat_id=$cat_id' class='action_link'>$category_name</a><span class='nr_of_items'>".nr_of_articles($cat_id)."</span></li>";
    //echo "<li><div class='category_link'><a href='index.php?cat_id=$cat_id' class='action_link'>$category_name</a><div class='nr_of_items'>" . nr_of_articles($cat_id) . "</div></div></li>";
    echo "<li category-id=$cat_id>$category_name<i class='fa fa-times' onclick='delete_category(this);'></i></li>";
}
?>

