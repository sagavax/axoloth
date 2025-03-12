<?php ob_start();?>
<?php include("../include/dbconnect.php"); ?>
<?php include("../include/functions.php"); ?>


<?php
      	
		if (isset($_POST['add_new_project'])) {
			header('Location: project_add.php');
		}
		
 ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sk" lang="sk">
<head>
<meta charset="utf-8" />
<meta name="description" content="" />
<meta name="keywords" content="" />
<meta name="author" content="" />
	<title><?php echo APPTITLE.' '.'- projects' ?></title>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <link href="../css/style_new.css" rel="stylesheet" type="text/css" />
    <link href="../css/style.css" rel="stylesheet" type="text/css" />    
    <link href="../css/font-awesome.css" rel="stylesheet" type="text/css" />
    <link rel='shortcut icon' href='../eis.ico'>
    
</head>
<body>
		  		         <div id="header"> <!--header -->
                        <div class="header-logo">e.i.s. - enterprise informating system</div> <!--logo -->
                          <div class="header-menu"> <!--menu-->
                                <ul>
                                  <li><a href="../serveris.php" target="_self">Home</a></li>
                                  <li><a href="../passmgr/index.php" target="_self">Password manager</a></li>
                                  <li><a href="../notepad/index.php" target="_self">Notepad</a></li>
                                  <li><a href="../customers/index.php" target="_self">Customers</a></li>
                                  <li><a href="../tasks/index.php" target="_self">Tasks</a></li>
                                  <li><a href="../index.php" target="_self">Servers</a></li>
                                  <li><a href=" index.php" target="_self">Projects</a></li>
                                  <li><a href="../events/index.php" target="_self">Events</a></li>
                                  <li><a href="../contacts/index.php" target="_self">Contacts</a></li>
                                  
                                </ul>
                         </div><!--menu-->
                 </div> <!--end of header -->
            
			<!-- <div class="logged_user">Tomas Misura</div> -->
			<!-- <div class="circle"></div> -->
			 <div id="layout">
       
          <div id="add_project"> <!--- add task -->
            <form accept-charset="utf-8" method="post" action="index.php">
              <span style="margin-top:10px; margin-left:10px"><button type="submit" name="add_new_project" class="flat-btn">+ Project</button></span>
            </form>   
          </div> <!--- add project -->     
          <div id="server_filter">Display projects: <div id="filter_server">
                        <form action="index.php" method="get">
                          <label for="radio-active">Active<input type="radio" name="active_inactive" value="active" style="width:20px" id="radio-active"></label>
                          <label for="radio-inactive">Inactive<input type="radio" name="active_inactive" value="inactive" style="width:20px;" id="radio-inactive"></label>
                          <label for="radio-my_projects">My projects only<input type="radio" name="active_inactive" value="my_projects" style="width:20px" id="radio-my_projects"></label>
                          <label for="radio-customer_projects">Cust projects only<input type="radio" name="active_inactive" value="customer_projects" style="width:20px" id="radio-customer_projects"></label>
                          <label for="radio-all">All<input type="radio" name="active_inactive" value="all" style="width:20px" id="radio-all"></label>
                          <button class="btn-small-green">OK</button></div>
                        </form> 
          <div><!-- table --> 
                <table id="projects">
                  <th>Project name</th><th>Project description</th><th>Customer</th><th># of tasks</th><th>Established</th><th>Status</th><th></th>
                 
                    <?php
                    if(isset($_GET['active_inactive'])) {
                      $active_inactive=$_GET['active_inactive'];
                      if($active_inactive=='active'){
                        $sql="SELECT a.project_id,a.project_name, a.project_descr, a.project_id, a.project_created, b.cust_id, b.customer_name, a.project_status FROM tblcustomer_projects a, tblcustomers b WHERE a.cust_id=b.cust_id and a.project_status<>'finished' ORDER BY a.project_id DESC";
                      } elseif ($active_inactive=='inactive'){
                          $sql="SELECT a.project_id,a.project_name, a.project_descr, a.project_id, a.project_created, b.cust_id, b.customer_name, a.project_status FROM tblcustomer_projects a, tblcustomers b WHERE a.cust_id=b.cust_id and a.project_status='finished' ORDER BY a.project_id DESC";
                      } elseif ($active_inactive=='my_projects') {
                        $sql="SELECT a.project_id,a.project_name, a.project_descr, a.project_id, a.project_created, b.cust_id, b.customer_name, a.project_status FROM tblcustomer_projects a, tblcustomers b WHERE a.cust_id=b.cust_id and a.cust_id=0 ORDER BY a.project_id DESC";
                      } elseif($active_inactive=='customer_projects') {
                       $sql="SELECT a.project_id,a.project_name, a.project_descr, a.project_id, a.project_created, b.cust_id, b.customer_name, a.project_status FROM tblcustomer_projects a, tblcustomers b WHERE a.cust_id=b.cust_id and a.cust_id<>0 ORDER BY a.project_id DESC";   
                      }elseif ($active_inactive=='All'){
                        $sql="SELECT a.project_id,a.project_name, a.project_descr, a.project_id, a.project_created, b.cust_id, b.customer_name, a.project_status FROM tblcustomer_projects a, tblcustomers b WHERE a.cust_id=b.cust_id ORDER BY a.project_id DESC";
                      } else {
                        $sql="SELECT a.project_id,a.project_name, a.project_descr, a.project_id, a.project_created, b.cust_id, b.customer_name, a.project_status FROM tblcustomer_projects a, tblcustomers b WHERE a.cust_id=b.cust_id ORDER BY a.project_id DESC";
                      }

                    } else {
                      $sql="SELECT a.project_id,a.project_name, a.project_descr, a.project_id, a.project_created, b.cust_id, b.customer_name, a.project_status FROM tblcustomer_projects a, tblcustomers b WHERE a.cust_id=b.cust_id ORDER BY a.project_id DESC";
                    }
                    
                    
                    $result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
                    
                    $alternate = "2"; 
                    
                    while ($row = mysql_fetch_array($result)) {
                        $id=$row['project_id'];
                            $project_name=$row['project_name'];
                            //$project_code = $row['project_code'];
                            $project_descr=$row['project_descr'];
                            $customer_id=$row['cust_id'];
                            $customer_name = $row['customer_name'];
                            $established_date=$row['project_created'];
                            $project_status=$row['project_status'];
                            $tasks = NrofTasks($id);
                        
                            if ($established_date=='0000-00-00') {$established_date='N/A';}

                            if ($alternate == "1") { 
                              $color = "even"; 
                              $alternate = "2"; 
                            } else { 
                              $color = "odd"; 
                              $alternate = "1";
                            }
                             
                         // $NrofTask =  NrofTasks($id);
                         // $customer_name = GetCustomerName($customer_id);
                            
                            echo "<tr class='$color' id='".$id."'>";  
                             echo "<td style='width:150px'>$project_name</td>";
                                echo "<td style='width:400px'>$project_descr</td>";
                                echo "<td><a href='../customers/index.php?view_info=$customer_id'>$customer_name</a></td>";
                                echo "<td><a href='project_tasks.php?project_id=$id'><span class='grey-badge'>$tasks</span></a></td>";
                                echo "<td style='width:80px'>


                                $established_date</td>";
                                echo "<td>$project_status</td>";
                              echo "<td><a href='project_details.php?project_id=$id' class='btn-small'>Enter</a></td>";
                
                                
                         echo "</tr>";
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