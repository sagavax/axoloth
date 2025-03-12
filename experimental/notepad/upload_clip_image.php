<?php
 include("../include/dbconnect.php");
 
$uploads_dir = "images/";
$img = $_POST['data'];
$img = str_replace('data:image/png;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$fileData = base64_decode($img);
//saving
$file_name = 'photo'.time().'.png';

$note_text="";
$note_source="";
$cust_id = 0; // if cust_id = moje vlastne poznamky
//$tags_ids=$_POST['categories']; 
$note_type="image";
$date_created = date('Y-m-d H:m:s');
$sql="INSERT INTO tblcustomer_notes (cust_id, title, note,note_source,date_created, note_type) VALUES ($cust_id,'$note_title','$note_text','$file_name','$date_created','$note_type')"; 
$result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($con));
file_put_contents("$uploads_dir/$file_name", $fileData);

