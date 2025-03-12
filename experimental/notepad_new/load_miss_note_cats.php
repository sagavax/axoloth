<?php
include("../include/dbconnect.php");
include("../include/functions.php");

$note_id=$_GET['note_id'];
//echo $note_id;
echo "<form action='' method='post'>";
    echo "<input type='hidden' name='note_id' value=$note_id>";
    echo "<select name='rem_cats'>";
  
    $ids=GetNoteTagsIds($note_id); //zoznam vsetkych ideciek kategorii pre danu note id
    if(intval($ids)==0){//no tags
        $sql="SELECT cat_id, cat_name from tblcustomer_categories ORDER BY cat_name ASC";
    } else {
        $sql="SELECT cat_id, cat_name from tblcustomer_categories WHERE cat_id NOT IN ($ids) ORDER BY cat_name ASC";
    }
        $result=mysqli_query($con,$sql) or die("MySQL ERROR: ".mysqli_error($con));
        while($row = mysqli_fetch_array($result)){
        $cat_id=$row['cat_id'];
        $cat_name=$row['cat_name'];
        echo "<option value='$cat_id'>$cat_name</option>";
        }
    echo "</select>"; 
    echo "<button name='add_note_tag' class='btn-small'>OK</button>";
echo "</form>";
