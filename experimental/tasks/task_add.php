<?php session_start(); ?>
<?php include("../include/dbconnect.php"); ?>
<?php include("../include/functions.php"); ?>

  <?php
if (isset($_POST['add_task']))
{
  
  
   global $con;
  $task_text=mysqli_real_escape_string($con, $_POST['task_text']);
  $cust_id=$_POST['customer_name'];
  $task_priority=$_POST['task_priority'];
  $start_task_date=$_POST['start_task_date'];
  $task_category=intval($_POST['task_category']);
  $min_mod=$_POST['minecraft_mod'];
  $min_modpack=$_POST['minecraft_modpack'];

  if(!isset($_POST['min_mod'])){
	  $min_mod=0;
  }

  if(!isset($_POST['min_modpack'])){
	  $min_modpack=0;
  }


  if($start_task_date==''){
  	$start_task_date=date('Y-m-d');
  }
  
   


  global $con;
  $sql= "INSERT INTO tblcustomer_tasks (task_text,task_category, cust_id, date_created, start_task_date,date_expiration,task_status,task_priority ) VALUES ('$task_text',$task_category,$cust_id,now(),'$start_task_date',DATE_ADD(now(), INTERVAL 10 DAY),'new','$task_priority')";
  $result = mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error($con));
  
	$curr_date = date('Y-m-d H:m:s');
   	 $curr_action="create_task";
   	 $curr_app="task_manager";
   	 $new_task_id=GetLatestTask();
   	 $undo_action="DELETE from tblcustomer_tasks WHERE id=$new_task_id";
   	 $text_logu="new task id $new_task_id has been created";
	   
  
		if($task_category==5){ //task sa tyka minecraftu tak presunie do ineho informcneho systemu
			//$connection =mysqli_connect(null, "minecraft_db", "eTIZAAMcSL", "minecraft_db", null, "/tmp/mariadb101.sock");
			$connection = mysqli_connect("mariadb101.websupport.sk", "minecraft_db", "eTIZAAMcSL", "minecraft_db", 3312);
			
			$sql="INSERT INTO to_do (eis_task_id, cat_id, modpack_id, task_text,added_date) VALUES ($new_task_id,$min_mod,$min_modpack,'$task_text','$start_task_date')";
			//echo $sql;
			$result = mysqli_query($connection, $sql) or die("MySQLi ERROR: ".mysqli_error($connection));
			mysqli_close($connection);
		  }
		  
	//zapis do wallu
	$link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
	$diary_text="EIS: Bol vytvoreny novy task s id $new_task_id s nazvom <strong>$task_text</strong> ";
	$sql="INSERT INTO diary (diary_text, date_added,location,isMobile,is_read) VALUES ('$diary_text','$curr_date','',0,0)";
	$result = mysqli_query($link1, $sql) or die("MySQLi ERROR: ".mysqli_error($link1));
	mysqli_close($link1);


	//zapis do timeline   
	$sql="INSERT INTO tblcustomer_tasks_timeline (task_id, task_action, action_time) VALUES ($new_task_id,'the task has been created','$curr_date')";
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error($con)); 	

	//zapis do logu
	$sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read,undo_action) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0,'$undo_action')";
   	$result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($con));
   
	echo "<script>alert('new task $new_task_id has been created');
      window.location='index.php?display_as=list';   
      </script>";
}

if(isset($_POST['add_cat'])){ //pridat novy kategoriu
    global $con;
    $category_name=mysqli_real_escape_string($con, $_POST['cat_name']);
    $sql="INSERT IGNORE into tblcustomer_categories (cat_name) VALUES ('$category_name')";
    //echo $sql;
    $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error());
    
       $curr_date=date('Y-m-d H:i:s');
   	 $curr_action="create_category";
   	 $curr_app="task_manager";
   	 $new_cat_id=GetLatestCat();
   	 $undo_action="DELETE from tblcustomer_categories WHERE id=$new_cat_id";
   	 $text_logu="new category $cat_name with id $new_note_id has been created";
    $sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read,undo_action) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0,'$undo_action')";
    
    $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error());
      echo "<script>alert('New category has been created');
      window.location='task_add.php';   
	  </script>";

   }

?>


<html>
<!DOCTYPE>

<head>
<meta charset="utf-8" />
    <link href="../css/style_new.css?<?php echo time();?>" rel="stylesheet" type="text/css">
	<title>E.I.S. - Add new task</title>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
	<link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel='shortcut icon' href='../eis.png'>
	<script>
		function get_minecraft_mod_modpacks(){
			var xhttp = new XMLHttpRequest();
			var e = document.getElementById("task_category");
			var category_id = e.options[e.selectedIndex].value;
			
			if(category_id==5){
				
				//loding  the list of the mods
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
			    
				if (this.readyState == 4 && this.status == 200) {
					document.getElementById("minecraft_mods_wrap").innerHTML =this.responseText;
					}
				};
				  xhttp.open("GET", "../external/load_min_mods.php", true);
				xhttp.send();
             
			  //loading the list of the modpacks
			  var xhr = new XMLHttpRequest();
				xhr.onreadystatechange = function() {
				
				if (this.readyState == 4 && this.status == 200) {
					document.getElementById("minecraft_modpacks_wrap").innerHTML = this.responseText;
						}
			   };
               
               xhr.open("GET", "../external/load_min_modpacks.php", true);
					xhr.send();

			}// end if 	

			if (category_id!=5){
					document.getElementById("minecraft_mods_wrap").innerHTML ="";
					document.getElementById("minecraft_modpacks_wrap").innerHTML ="";
				}				
		}		
	</script>
									
	<script type="text/javascript">
      function add_new_cat() {

         document.getElementById('new_cat_form').style.display = "block";
      }
   </script>
   
   
</head>
<body>
	<?php
			if(isset($_GET['cust_id'])){
				$cust_id=$_GET['cust_id'];
			}
		?>

	<div id="header"> <!--header -->
       <div class="header-logo">e.i.s. - enterprise informating system</div> <!--logo -->
           	<div class="header-menu"> <!--menu-->
               <?php 
               		include("../include/menu.php");
                ?>
  	       </div><!--menu-->
    </div> <!--end of header -->


         <div id="layout">
         	<div id="new_task_wrap">
         		<H2>Add new task:</H2>
					  <form accept-charset="utf-8" method="post" action="task_add.php">
					      <input type="hidden" name="id">
					      <table id="new_task" border="0" cellspacing="0">

					          <tr>
					           	<td><textarea name="task_text" /></textarea></td>
					          </tr>
					          <tr>

					            <td>
					              	<select name="customer_name">
					              		<option value=0>-- Choose the customer --</option>
					                	 <?php

					                	 if (isset($_GET['cust_id'])) // task pridavam z externej stranky
					                	 {
					                	 	$cust_id=$_GET['cust_id']; //mame id
					                	 	$customer_name=CustomerName($cust_id); //mame meno zakaznika
					                	 	echo "<option value=$cust_id selected='selected'>$customer_name</option>";
                            				 global $con;
					                	 	$sql = "SELECT cust_id,customer_name from tblcustomers where customer_status='active'";
					                    	$rsd=mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error());
					                      		while ($row = mysqli_fetch_array($rsd)) {
					                      		$customer_name=$row['customer_name'];
					                      		$cust_id=$row['cust_id'];
					                      		echo "<option value='cust_id'>$customer_name</option>";
					                      	}

					                	 } else { //task je uplne novy a je vytvarany z modulu tasks
                             
					                	 $sql = "SELECT cust_id,customer_name from tblcustomers where customer_status='active'";
					                     $rsd=mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error());
					                      while ($row = mysqli_fetch_array($rsd)) {
					                      	$customer_name=$row['customer_name'];
					                      	$cust_id=$row['cust_id'];
					                      	echo "<option value='".$row['cust_id']."'>$customer_name</option>";
					                      }
					                	 }
					                  ?>
					                  </select>


					            </td>
					        </tr>
					        <tr>
					        	<td>
					        	<input type="text" name="start_task_date" id="datepickr" placeholder="Start date" class="date_input" value="<?php echo date('Y-m-d'); ?>">
                            </tr>
					        <tr>
					        	<td><select name="task_priority">
							        		<option value="normal" selected="selected">Normal</option>
							        		<option value="low">Low</option>
							        		<option value="normal">Normal</option>
							        		<option value="high">High</option>
					        		</select></td>

					        </tr>
							<tr>
					        	<td><select name="task_category"  onchange="get_minecraft_mod_modpacks();" id="task_category">
					        			<option value="0" selected="selected">-- No category --</option>
					        			<?php
							        		$sql = "SELECT cat_id, cat_name from tblcustomer_categories ORDER BY cat_name ASC";
							        		global $con;
					                    	$rsd=mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error($con));
					                      		while ($row = mysqli_fetch_array($rsd)) {
					                      		$cat_name=$row['cat_name'];
					                      		$cat_id=$row['cat_id'];
					                      		echo "<option value='$cat_id'>$cat_name</option>";
					                      		}	
							        	?>	
								</select>
								<div id="minecraft_mods_wrap"></div>
                            	<div id="minecraft_modpacks_wrap"></div>   	        	
							</tr>	
							
					        <tr>
					           <td stye="text-align:right"><button name="add_task" class="flat-btn">Add new</button></td>
					        </tr>
					    </table>
					</form>
					<div><a href="index.php?display_as=list" class="action_link">&lt;&lt; Back</a></div>
         	</div>
        </div>


		<div id="container">
		  	<div id="center" class="column">


				<div style="background-color: #D7E2F2; border: 1px #ccc solid; width: 450px;padding:5px; border-radius: 3px; padding: 10px;
				 margin: 40px auto 30px auto; height: auto"><!-- form -->


				</div><!-- form -->


		</div><!--center-->
	</div><!--container-->
</body>
</html>
