<?php
  include ("../include/dbconnect.php");
  global $con;
  
  $id=$_GET['id'];
  $status=$_GET['status'];
   
  if($status=='complete'){
    $sql="UPDATE tblcustomer_tasks SET task_status='$status' WHERE id=$id AND percent_done=100";
    $sql1="UPDATE todo SET is_completed=1 where $min_task_id=$min_task_id";
  }elseif ($status=='cancelled'){
    $sql="UPDATE tblcustomer_tasks SET task_status='$status' WHERE id=$id AND percent_done=100";
  }else{
    $sql="UPDATE tblcustomer_tasks SET task_status='$status' WHERE id=$id";
  }

 $result = mysqli_query($con, $sql);
 
 //zapiseme aj v Minecraft IS
 //$conn1 = mysqli_connect("mariadb101.websupport.sk", "minecraft_db", "eTIZAAMcSL", "minecraft_db", 3312);

// $con1=mysqli_connect(null, "minecraft_db", "eTIZAAMcSL", "minecraft_db", null, "/tmp/mariadb101.sock"); 
 
// $result = mysqli_query($con1, $sql1) or die("MySQL ERROR: " . mysqli_error($con1)); 
 //mysqli_close($con1);

 
 $curr_action="change_task_status";
 $curr_date=date('Y-m-d H:i:s');

 //zapis do wallu
 $link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
 //$link1=mysqli_connect("localhost", "root", "", "brick_wall");
 $diary_text="EIS: Status tasku s id $id bol zmeneny na <strong>$status</strong>";
 $sql="INSERT INTO diary (diary_text, date_added,location,isMobile,is_read) VALUES ('$diary_text','$curr_date','',0,0)";
 $result = mysqli_query($link1, $sql) or die("MySQLi ERROR: ".mysqli_error($link1));
 mysqli_close($link1);


//zapis to timeliny
 $sql="INSERT INTO tblcustomer_tasks_timeline (task_id, task_action, action_time) VALUES ($id, 'the status has been changed to <b>$status</b>','$curr_date' )";
 $result = mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error($con)); 	

 //zapis do logu / inboxu
 $curr_app="task_manager";
 //$new_note_id=GetLatestNote();
 $text_logu="The status for the task with id $id has been changed sucessfully";
       	
 $sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
 	//echo $sql;
       	
 $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error());
	   
echo "<script>alert('The status for the task with id $id has been changed sucessfully');
          window.location='index.php?display_as=list';   
        </script>";