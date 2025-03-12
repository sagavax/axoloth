<?php session_start(); 
 include "../include/dbconnect.php"; 
 include "../include/functions.php"; ?>



<?php
            if (isset($_POST['add_new_task'])) { // novy task


                    
					$project_id=$_POST['project_id'];
					
					$user_id=$_POST['user_id'];
					$user_id=1;
					$note_text=$_POST['add_note'];
                    $curr_date=date('Y-m-d H:i:s');
                    $date=strtotime(date('Y-m-d H:i:s', strtotime($curr_date)) . " +5 day");
                    $end_date= date('Y-m-d H:i:s', $date);
                    
                
					//vytvorenie noveho project tasku
								
                    $sql = "INSERT INTO project_tasks (project_id, user_id, colNoteText, task_status,task_priority, is_completed,task_created, task_finished, task_deadline) VALUES ($project_id,$user_id,'$note_text','New','Normal','0','$curr_date','','$end_date')";
					$result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
					


					//zapisanie do project streamu/historie

					
					//ziskanie max task id z tabulky
					$sql="SELECT MAX(task_id) as task_id from project_tasks where project_id=$project_id";
					
					$result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
					while ($row = mysql_fetch_array($result)) {
								$task_id=$row['task_id'];
					}
					
									
					//pridenie do streamu
					$user_name = GetUserNameById($user_id);
					$text_streamu = "User <a href='project_user_profile.php?user_id=$user_id'> ".$user_name."</a> has created a new task id <a href='project_task_details.php?task_id=$task_id&project_id=$project_id'>".$task_id."</a>";
					$text_streamu=addslashes($text_streamu);
					$datum=date('Y-m-d H:m:s');
					$sql="INSERT INTO project_stream (project_id,user_id,text_of_stream, date_added) VALUES ($project_id,$user_id,'$text_streamu','$datum')";
					//echo "$sql";
					$result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
					//$project_id=$_POST['project_cislo'];
					
					header('Location: project_tasks.php?project_id='.$_POST['project_id'].''); // presmeruje spat aby sa zabranilo vkladaniu duplicity
				
			} // novy task


   		$_SESSION['project_id'] = $project_id;
   		 
 ?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sk" lang="sk">
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<meta name="author" content="" />
		<title>EIS - Prejects - project tasks - tasks</title>
		<link href="../css/style_new.css" rel="stylesheet" type="text/css" />
		<link href="../css/font-awesome.css" rel="stylesheet" type="text/css" />
		<!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>-->
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
		<link rel='shortcut icon' href='../eis.ico'>
		

		   
	</head>
<body>
		  		         <div id="header"> <!--header -->
                        <div class="header-logo">e.i.s. - enterprise informating system</div> <!--logo -->
                          <div class="header-menu"> <!--menu-->
                                <ul>
                                  <li><a href="../serveris.php" target="_self">Home</a></li>
                                  <li><a href="../index.php" target="_self">Password manager</a></li>
                                  <li><a href="../notepad/index.php" target="_self">Notepad</a></li>
                                  <li><a href="../customers/index.php" target="_self">Customers</a></li>
                                  <li><a href="../tasks/index.php" target="_self">Tasks</a></li>
                                  <li><a href="index.php" target="_self">Servers</a></li>
                                  <li><a href="../projects/index.php" target="_self">Projects</a></li>
                                  <li><a href="../events/index.php" target="_self">Events</a></li>
                                  <li><a href="../contacts/index.php" target="_self">Contacts</a></li>
                                  
                                </ul>
                </div><!--menu-->
                 </div> <!--end of header -->
            
			<!-- <div class="logged_user">Tomas Misura</div> -->
			<!-- <div class="circle"></div> -->
			 <div id="layout">
       
           <?php 

                $project_id=$_GET['project_id'];

           ?>
            
          <div> 
               <table class="project_section_list">
              <input type="hidden" name="project_id" value=<?php echo $project_id; ?>
              <th><h3>Project tasks:</h3></th>
              <?php
                //$sql="SELECT * from tblcustomer_project_tasks where project_id=$project_id and task_status not in ('void','complete') ORDER BY task_id DESC";
                $sql="SELECT * from tblcustomer_project_tasks where project_id=$project_id ORDER BY task_id DESC"; //vsetky tasky
                
                $result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
                while ($row = mysql_fetch_array($result)) {
                
                  $task_id=$row['task_id'];
                  $task_text=$row['task_text'];
                  $task_status=$row['task_status'];
                  $task_priority=$row['task_priority'];
                  $task_created=$row['task_created'];
                  $task_planed_date=$row['task_planed_date'];
                  

                  $tasks="<tr>";
                  $tasks.="<td>$task_text</td><td>$task_status</td><td>$task_created</td><td>$task_planed_date</td><td><a href='project_task.php?task_id=$task_id&actiom=view' class='btn-small'>View</a></td><td><a href='project_task.php?task_id=$task_id&action=edit' class='btn-small'>Edit</a></td><td><a href='project_task.php?task_id=$task_id&action=remove' class='btn-small'>remove</a></td><td><a href='project_task.php?task_id=$task_id&action=task_completed' class='btn-small'>completed</a></td>";
                  $tasks.="</tr>";

                  echo $tasks;  


                } 
              ?>
            </table>  
                </div> <!-- list of projects -->  
                <div style="clear:both;"></div> 
                
            </div> <!-- middle -->
            
            <div style="clear:both;"></div>

       </div> <!-- div layouty -->
</body>
</html>       