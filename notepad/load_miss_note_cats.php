<?php
include("../include/dbconnect.php");
include("../include/functions.php");

$note_id=$_GET['note_id'];
//echo $note_id;
     echo "<input type='hidden' name='note_id' value=$note_id>";
    echo "<select name='rem_cats' onchange='add_tag(this.options[this.selectedIndex].text,this.value)'>";
    echo "<option value='0'> ------- Select category ------ </option>";
    $ids=GetNoteTagsIds($note_id); //zoznam vsetkych ideciek kategorii pre danu note id
    if(intval($ids)==0){//no tags
        $sql="SELECT cat_id, category_name from tblcustomer_notes_category ORDER BY category_name ASC";
    } else {
        $sql="SELECT cat_id, category_name from tblcustomer_notes_category WHERE cat_id NOT IN ($ids) ORDER BY category_name ASC";
    }
        $result=mysqli_query($con,$sql) or die("MySQL ERROR: ".mysqli_error($con));
        while($row = mysqli_fetch_array($result)){
        $cat_id=$row['cat_id'];
        $cat_name=$row['category_name'];
        echo "<option value='$cat_id'>$cat_name</option>";
        }
    echo "</select>";