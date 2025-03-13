<?php session_start() ?>
<?php include("../include/dbconnect.php"); ?>
<?php include("../include/functions.php"); ?>

<?php
	if (isset($_POST['add_new_server'])){
		header('location:server_add.php');
	}

	if(isset($_POST['deco_server'])) {
		global $con;
		$id=$_POST['id'];//id servera
	 	$sql="UPDATE tblserver_info SET server_status='inactive' where id=$id";
	 	//echo "$sql";
	 	$result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysql_error());
	 	$sql="UPDATE tblserver_info SET in_production='N' where id=$id";
	 	$result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysql_error());
		header('Location: index.php',true,303); // presmeruje spat aby sa zbranilo vkladaniu duplicity
 }


 if(isset($_POST['undeco_server'])) {
	 global $con;
		$id=$_POST['id'];//id servera
	 	$sql="UPDATE tblserver_info SET server_status='active' where id=$id";
	 	$result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysql_error());
	 	$sql="UPDATE tblserver_info SET in_production='Y' where id=$id";
	 	$result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysql_error());
		header('Location: index.php',true,303); // presmeruje spat aby sa zbranilo vkladaniu duplicity
 }

?>

<!DOCTYPE html>
 <head>
        <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sk" lang="sk">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<meta charset="utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<meta name="author" content="" />
        <link href="../css/style_new.css" rel="stylesheet" type="text/css" />
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
        <link rel='shortcut icon' href='../eis.ico'>

        <!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>-->
        <title><?php echo APPTITLE.' '.'- servers' ?></title>

            <script type="text/javascript">
	            function showords(str)
	            {
	            if (str=="")
	              {
	              document.getElementById("tasks").innerHTML="";
	              return;
	              }
	            if (window.XMLHttpRequest)
	              {// code for IE7+, Firefox, Chrome, Opera, Safari
	              xmlhttp=new XMLHttpRequest();
	              }
	            else
	              {// code for IE6, IE5
	              xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	              }
	            xmlhttp.onreadystatechange=function()
	              {
	              if (xmlhttp.readyState==4 && xmlhttp.status==200)
	                {
	                document.getElementById("tasks").innerHTML=xmlhttp.responseText;
	                }
	              }
	            xmlhttp.open("GET","tasks.php?q="+str,true);
	            xmlhttp.send();
	            }
            </script>


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
										<li><a href="index.php" target="_self">Servers</a></li>
										<li><a href="../projects/index.php" target="_self">Projects</a></li>
										<li><a href="../events/index.php" target="_self">Events</a></li>
										<li><a href="../contacts/index.php" target="_self">Contacts</a></li>

                                </ul>
           				 </div>
           				 <div id="txt" style="float:right; margin-right:5px; color:#fff"></div></div>
    </div><!-- header -->
		  <div id="layout">



                <div id="add_server"><!-- add new  server button -->
                  <form action="index.php" method="post"><button type="submit" name="add_new_server" class="flat-btn">+ Add new</button></form>
                </div>


                <div class="search_box"><form method="get" action="index.php" accept-char="utf-8"><input type="text" name="search_string" value=""><button class="flat-btn">Search</button></form> </div>


                <!-- FILTER THE SERVERS -->

                <div id="main_wrap">
                   <!-- <div id="left_content"> -->

                    	<div id="server_filter">Display servers: <div id="filter_server">
                				<form action="index.php" method="get">
                					<label for="radio-active">Active<input type="radio" name="active_inactive" value="active" style="width:20px" id="radio-active"></label>
                					<label for="radio-inactive">Inactive<input type="radio" name="active_inactive" value="inactive" style="width:20px;" id="radio-inactive"></label>
                					<label for="radio-all">All<input type="radio" name="active_inactive" value="all" style="width:20px" id="radio-all"></label>
                					<button class="btn-small-green">OK</button></div>
                				</form>



                	   </div> <!-- server filter -->

                       <div id="customer_tabz">

					   	<ul>
							  <?php
									global $con;
									$sql_tabs="SELECT * from tblcustomers where customer_status='active'";
								  $result_tabs = mysqli_query($con,$sql_tabs);
								  while ($row_tabs = mysqli_fetch_array($result_tabs)) {
								      $customer_name=$row_tabs['customer_name'];
								      $cust_id=$row_tabs['cust_id'];

									  echo "<li><a href='index.php?customer=$cust_id'>$customer_name</a></li>";
								  }
								echo "<li><a href='index.php?customer=all'>All customers</a></li>";
							  ?>

		  				</ul>
		  				<div style="clear:both"></div>
  					</div><!-- customer_tabs-->



  					<div id="customer_servers">
  						<table>
	  						<?php

	  							if ($_GET['search_string']) { //hladame nieco
									global $con;
									$search_string=$_GET['search_string'];
									$sql="SELECT * from tblserver_info where server_name LIKE '%$search_string%'";

								}	elseif(isset($_GET['active_inactive'])) { //aktivnr ci nektivne servre
                					if($active_inactive=='active'){ //ak aktivne
                					$sql="SELECT SELECT a.id, a.server_name, a.os_id, b.os_title, a.single_cluster, a.in_production, a.mission_critical from tblserver_info a, tblserver_operating_systems b where a.server_status='active' and a.os_id=b.os_id";}
                				} elseif ($active_inactive=='inactive') //ak neaktivne
                					{$sql="SELECT SELECT a.id, a.server_name, a.os_id, b.os_title, a.single_cluster, a.in_production, a.mission_critical from tblserver_info a, tblserver_operating_systems b where a.server_status='inactive' and a.os_id=b.os_id";}
                				  elseif ($active_inactive=='all') { //zobraz vsetky
                				  		{$sql="SELECT * from tblserver_info";}
                				  	}//aktivnr ci nektivne servre
                				  elseif(isset($_GET['customer']))  { //hladanie podla zakaznika
		  							$cust_id=$_GET['customer'];
									if ($customer<>'all') {$sql="SELECT a.id, a.server_name, a.os_id, b.os_title, a.single_cluster, a.in_production, a.mission_critical from tblserver_info a, tblserver_operating_systems b WHERE a.cust_id=$cust_id and a.os_id=b.os_id and a.server_status='active' ORDER BY a.id DESC" ;}
												} else {$sql="SELECT a.id, a.server_name, a.os_id, b.os_title, a.single_cluster, a.in_production, a.mission_critical from tblserver_info a, tblserver_operating_systems b where a.server_status='active' and a.os_id=b.os_id ORDER BY id DESC";}
									//echo "$sql";
									$result=mysqli_query($con, $sql) or die("MySQL ERROR: ".mysql_error());
									$num_rows = mysqli_num_rows($result);
									 if($num_rows==0) {echo "No server found. Whould you like to create a new one? <form action='' method='post'><button name='add_new_server' class='flat-btn' type='submit'>New server</button></form>";} else {
					                  while ($row = mysqli_fetch_array($result)) {
							                        	$id=$row['id'];
														$server_name=$row['server_name'];
							                        	$operating_system=$row['os_title'];
														$single_cluster=$row['single_cluster'];
														$in_production=$row['in_production'];
														$mission_critical=$row['mission_critical'];
														$server_status=$row['server_status'];

											//echo "je to cluster:".isCluster($id);




											echo "<tr>";

												echo "<td style='color:#333;font-weight:bold'><a href='server_view_details.php?server_id=$id'>$server_name</td><td>$operating_system</td>";

												//$is_cluster=isCluster($id);
												//echo "<td>jeeej:".isCluster($id)."</td>";
												echo "<td>$in_production</td><td>$mission_critical</td>";



												//echo "<td>Iscluster: $is_cluster</td>";
												echo "<td><a href='http://eslweb.gre.omc.hp.com/pls/eslp/esl.info?node_name=$server_name'>ESL</a></td>";
												echo "<td><a href='server_note_add.php?server_id=$id' class='btn-small'>+ Note</a></td>";
												echo "<td><a href='server_view_details.php?server_id=$id' class='action_link'>View</a></td><td><a href='server_edit.php?server_id=$id' class='action_link'>Edit</a></td>";
												//echo "<td><a href='server_delete.php?server_id=$id' class='action_link'>Delete</a></td>";
												echo "<td style='width:125px'>";
												if ($server_status=='active') {echo "<form action='index.php' method='post'><input type='hidden' name='id' value='$id'><button type='submit' name='deco_server[$id]' class='btn-small-red'>x</button></form></td>";}
													else {echo "<form action='index.php' method='post'><input type='hidden' name='id' value='$id'><button type='submit' name='undeco_server[$i]' class='btn-small'>+</button></form></td>";}
											echo "</tr>";
										}
									}
							?>
  						</table>
  					</div>

                    <!-- </div> --><!--left content -->


                    <div id="right_content">

                    </div><!--right content -->



              </div><!--main_wrap -->

              <div id="todo" style="float:left;margin-top:10px; min-height:50px; width:980px;padding:5px;border:1px #999 solid">
              	<div id="todo_h" style="float:left;margin-top:10px; min-height:30px; width:100%;line-height:30px;background:#999;color:#fff; font-weight:bold"><span style="margin-left:10px">TO DO:</span></div>
              	<div id="todo_b" style="float:left;margin-top:10px; min-height:50px; width:100%;padding:5px;color:#999;">
              		<ol style="margin:0;padding:0;margin-left:20px">
              			<li>Oddelit editovanie od prezerania - to jest prezeranie bude iba prezeranie, editovanie bude iba editovanie </li>
              			<li>Pri decomissioning servra skontrolovat ci server je cluster ak ano tak decomisnovat aj oba nody</li>


              		</ol>
              	</div>
              </div><!-- todo -->
    	  </div><!--layout -->
