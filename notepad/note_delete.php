<?php session_start() ?>
<?php include("../include/dbconnect.php"); ?>

<?php
		global $con;	

		$note_id=$_GET['note_id'];
		
		//delete all tags for this note 
		$sql="DELETE from tblcustomer_notes_tags where note_id=$note_id";
		$result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($con));
        
		
        //delete note itself
        
		$sql="DELETE from tblcustomer_notes WHERE id=$note_id";
		$result=mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error($con));
		
		 //write it down into the log
	    $curr_date=date('Y-m-d H:i:s');
       	$curr_action="delete_note";
       	$curr_app="notepad";
       	
       	$text_logu="The note id $note_id has been deleted";
        $sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
		$result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($con));
		
		//zapis do wallu
		$link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
		//$link1=mysqli_connect('localhost','root',null,'brick_wall',null, null);
		
        $diary_text="EIS: Poznamka s id $note_id bola zmazana";
        $sql="INSERT INTO diary (diary_text, date_added,location,isMobile) VALUES ('$diary_text','$curr_date','',0)";
        $result = mysqli_query($link1, $sql) or die("MySQLi ERROR: ".mysqli_error($link1));
        mysqli_close($link1);
	   
		//and will see the message about it
		echo "<script>alert('The note id $note_id has been deleted');
          window.location='index.php';   
        </script>";    
	?>

