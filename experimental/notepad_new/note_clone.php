<?php include("../include/dbconnect.php"); ?>
<?php include("../include/functions.php"); ?>

<?php
        global $con;
		$note_id=$_GET['note_id'];
		$sql="INSERT INTO tblcustomer_notes (cust_id,cat_id, title,note,date_created) SELECT cust_id, cat_id,title, note,now() from tblcustomer_notes WHERE id=$note_id";
       	$result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error());
        
        $curr_date=date('Y-m-d H:i:s');
       	$curr_action="clone_note";
       	$curr_app="notepad";
       	$new_note_id=GetLatestNote();
       	$text_logu="The note id $note_id has been cloned into note with id $new_note_id";
       	
       	$sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
       	//echo $sql;
       	
       	$result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error());
          echo "<script>alert('The note id $note_id has been cloned into note with id $new_note_id');
          window.location='index.php';   
        </script>";    
        
	?>