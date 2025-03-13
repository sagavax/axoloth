	<?php include("../include/dbconnect.php"); ?>

	<?php
	      if(isset($_POST['add_note'])) {
	      	$server_name=$_POST['server_name'];
			$server_id=$_POST['server_id'];
	      	$note=mysql_real_escape_string($_POST['server_note']);
			$date_created = date('Y-m-d H:m:s');

			$sql="INSERT INTO tblserver_notes (server_id, server_name, note, date_created) VALUES ($server_id, '$server_name', '$note', '$date_created')";
			echo "$sql";
			$result = mysql_query($sql);
			header('location:server_view_details.php?server_id='.$_POST['server_id'].'',true,303);
	      }

		if(isset($_POST['remove_server'])){
			//skontrovat ci je server cluster

			// ak je cluster tak - decomisnovat aj nody
			// ak je clustrova noda tak decomisnovat
			//napisat funkciu

			$id=$_POST['id']; // id servra
			$sql="UPDATE tblserver_info SET server_status='inactive' WHERE id=$id";
			$result = mysql_query($sql);
			header('index.php',true,303);
		}
		if (isset($_POST['quick_add_note'])){
			$id=$_POST['id'];
			header('location:server_note_add.php?server_id='.$_POST['server_id'].'',true,303);

		}
		if (isset($_POST['modify_server'])) {
			$id=$_POST['id'];
			header('location:server_edit.php?server_id='.$_POST['server_id'].'',true,303);
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
      <link href="../css/style.css" rel="stylesheet" type="text/css" />
      <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
      <link href="../css/font-awesome.css" rel="stylesheet" type="text/css" />
      <link rel='shortcut icon' href='../eis.ico'>
      <title>ServerIS - servers</title>

</head>


	<?php $id=$_GET['server_id']; ?>

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
	<div id="container">
	  <div id="center" class="column">

		<div id="server_info_wrap"><!-- sserver info -->

			<div class="basic_server_information"><!--basic server information -->
				<table id="ViewServerDetails" border-spacing="0">
					<input type="hidden" name="server_id" value="<?php echo $id; ?>" />
					<h2>Basic server information:</h2>

					<?php

						$sql= "SELECT a.server_name, a.cust_id,  a.os_id,c.os_title,a.single_cluster,a.in_production,a.mission_critical,b.customer_name FROM tblserver_info a, tblcustomers b, tblserver_operating_systems c WHERE id=$id and a.cust_id=b.cust_id and a.os_id=c.os_id";
						//echo "$sql";

						 $result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
						if (!$result) {
						    die('Invalid query: ' . mysql_error());
						}

						        while ($row = mysql_fetch_array($result)) {

						        // $id = $row['id'];
						        $server_name=$row['server_name'];
						        $customer=$row['customer_name'];
						 	    $os_title=$row['os_title'];
						        $single_cluster=$row['single_cluster'];
						  		$in_production=$row['in_production'];
						  		$mission_critical=$row['mission_critical'];
						        $cust_id=$row['cust_id'];


						  echo "<tr>";
							  echo "<th>Server name:</th>";
							  echo "<td><b>$server_name</b></td>";
						  echo "</tr>";
						  echo "<tr>";
							  echo "<th>Customer:</th>";
							  echo "<td>$customer</td>";
						  echo "</tr>";
						  echo "<tr>";
							  echo "<th>Operating system:</th>";
							  echo "<td>$os_title</td>";
						  echo "</tr>";
						  echo "<tr>";
							  echo "<th>Single server\cluster:</th>";
							  echo "<td>$single_cluster</td>";
						  echo "</tr>";
						  echo "<tr>";
							  echo "<th>In production:</th>";
							  echo "<td>$in_production</td>";
						  echo "</tr>";
						  echo "<tr>";
							  echo "<th>Mission critical:</th>";
							  echo "<td>$mission_critical</td>";
						  echo "</tr>";

						        }
					?>
				</table>


			</div><!-- basic server information -->
			<div class="server_quick_ations">
				<form action="server_view_details.php" method="post">
				<input type="hidden" name="server_id" value="<?php echo $id; ?>">
					<ul id="quick_action_menu">
						<li><form action="server_view_details.php" method="post"><input type="hidden" name="server_id" value="<?php echo $id; ?>" /><button type="submit" class="flat-btn" name="modify_server">Modify</button></form></li>
						<li><form action="server_view_details.php" method="post"><input type="hidden" name="server_id" value="<?php echo $id; ?>" /><button type="submit" name="remove_server" class="flat-btn">Remove</button></fomr></li>
						<li><form action="server_view_details.php" method="post"><input type="hidden" name="server_id" value="<?php echo $id; ?>" /><button type="submit" name="duplicate_server" class="flat-btn">Duplicate</button></fomr></li>
					</ul>
				</form>
			</div>

			<div id="instance_info">
			    <div style="text-transform:uppercase; background:#555; color:#fff; position:relative;float:left;margin:0; width: 100%;height:30px;line-height: 30px;font-family:'Open Sans', Arial, sans-serif; font-weight:bold"><span style="font-size:13px;margin-left:5px">Server's instances:</span></div>

			    	<table id="ViewInstanceDetails" border="0" border-spacing="0">

						<?php

	     				        $i=0;

	     				        echo "<tr>";
							        echo "<th>#</th>";
							        echo "<th>Instance name</th>";
							        echo "<th>Description</th>";
							        echo "<th>Version</th>";
							        echo "<th><a href='server_instance_add.php?server_id=$id' title=\"Add new server's instance \" class='btn-small'>+</a></th>";
						        echo "</tr>";


						        $sql="SELECT * FROM tblserver_instances WHERE server_name='$server_name'";

								 $result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
							    $alternate = "2";
							    while ($row = mysql_fetch_array($result)) {

									$i++;
							        $id=$row['id'];
							        $instance_info=$row['instance_info'];
							        $instance_name=$row['instance_name'];
							        $instance_version=$row['instance_version'];


							       if ($instance_version=="") {
							           $instance_version="-";
							        } elseif ($general_note=="") {
							           $general_note="-";
							        }

							        if ($alternate == "1") {
								      $color = "even";
								      $alternate = "2";
								    }
								    else {
								      $color = "odd";
								      $alternate = "1";
								    }

						        echo "<tr class='$color'>";
							        echo "<td style='width:20px'>$i</td>";
								    echo "<td>$instance_name</td>";
								    echo "<td>$instance_info</td>";
							        echo "<td>$instance_version</td>";
							        echo "<td class='center'><a href='server_instance_edit${page_ext_qry}id=$id' class='action_link'>Edit</a></td>";
							        echo "<td class='center'><a href='server_instance_delete${page_ext_qry}instance_id=$id' class='action_link'>Delete</a></td>";
						        echo "</tr>";

						     }  //cyklus

						?>
					</table>
			</div> <!-- instance info -->

		<div id="server_relation">
				<div style="text-transform:uppercase; background:#555; color:#fff; position:relative;float:left;margin:0; width: 100%;height:30px;line-height: 30px;font-family:'Open Sans', Arial, sans-serif; font-weight:bold"><span style="font-size:13px;margin-left:5px">Server's relations:</span></div>
                     
							<form action="" method="post" accept-charset="utf-8">
						  	  	<input type="hidden" name="server_id" value="<?php echo $_GET['server_id']; ?>" >
						  	  	<input type="hidden" name="server_name" value="<?php echo $server_name; ?>" >
						  	  		<?php 
						  	  			if ($single_cluster =='cluster') { //server je cluster - mozem mu pridat nodu
						  	  			echo "Server name:<select name='child_server'>";

						  	  			$sql="SELECT server_name,id,server_status from tblserver_info WHERE server_name not in ('".$server_name."') and server_status='active'"; //zabranenie aby som mohol zadat ako child node sameho seba
						  	  			//echo "$sql";
						  	  		 	$result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
						  	  		  		while ($row = mysql_fetch_array($result)) {
						  	  		  			$id=$row['id'];
						  	  		  			$server_name=$row['server_name'];

						  	  		  			echo "<option value='$id'>$server_name</option>";
						  	  		  	 } //cyklus pre naplenenie zoznamu servrov option
						  	  			
						  	  			echo "</select><button name='add_node' class='btn-small'>Add</button></form>"; 

						  	  				if (isset($_POST['add_node'])) { //kliknem na tlacitko pridat nodu
						  	  			 	
						  	  			 	$child_server=$_POST['child_server'];
											
											$sql="update tblserver_info set parent_server=$server_id WHERE id=$child_server";
											
											$url="http://localhost".$_SERVER['REQUEST_URI'];
											
										    //echo "$url";
											$result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
											header('location:'.$url.'');
										} //kliknem na tlacitko pridat nodu a vrati ma to na server view
						  	  			
						  	  			//zoznam child nodov 

										echo "<table id='list_child_nodes'>";
						  	  		
						  	  		 $sql="SELECT id, single_cluster,server_name from tblserver_info where parent_server=$server_id";		

						  	  		 echo "$sql";

						  	  		 $result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
						  	  		  		while ($row = mysql_fetch_array($result)) {
						  	  		  			$server_name=$row['server_name'];
						  	  		  			$id=$row['id'];
						  	  		  			$single_cluster=$row['single_cluster'];

						  	  		  	echo "<tr><td><a href='server_view_details.php?server_id=$id'>$server_name</a></td><td>$single_cluster</td><td><form action='' method='post'>
						  	  		  				<input type='hidden' name=child_server_id value=$id><button name='remove_node' class='btn-small' title='remove this child'>x</button></form></td></tr>";

						  	  		  	if (isset($_POST['remove_node'])) { //removnem nodu z clustru
						  	  		  		$server_id=$_POST['child_server_id'];
						  	  		  		//echo '<script language="javascript">';
											//echo 'alert("")';
											//echo 'alert("message successfully sent")';
											//echo '</script>';

						  	  		  		$url="http://localhost".$_SERVER['REQUEST_URI'];
						  	  		  		$sql="UPDATE tblserver_info SET parent_server=0 WHERE id=$server_id";
						  	  		  		//echo "$sql";
						  	  		  		$result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
						  	  		  		//echo $url;
						  	  		  		header('location:'.$url.'');
						  	  		  	}

						  	  		  }			
						  	  		echo "</table>"; //zoznam child nodov 

						  	  			}//server je cluster
						  	  		elseif ($single_cluster =='cluster node')
						  	  			 { // ak je to clustrova noda ta vypis cluster
						  	  				echo "<table id='list_parent_server'>";
						  	  				    $sql="SELECT  parent_server from tblserver_info where id='$server_id'";
						  	  				    $result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
						  	  		  			while ($row = mysql_fetch_array($result)) {
						  	  		  				//$server_name=$row['server_name'];
						  	  		  				$parent_server=$row['parent_server']; // id parent servra
						  	  		  				$sql1="select server_name from tblserver_info where id='$parent_server'";
						  	  		  				//echo $sql1;
						  	  		  				$result1=mysql_query($sql1) or die("MySQL ERROR: ".mysql_error());
						  	  		  				$row1 = mysql_fetch_array($result1);
															$server_name_parent=$row1['server_name'];
                                                        
						  	  		  				//$single_cluster=$row['single_cluster'];
						  	  		  		echo "<tr><td><a href='server_view_details.php?server_id=$parent_server'>$server_name_parent</a></td></tr>";
							  	  			} // koniec vypisu parent servra
												
							  	  			echo "</table>"; //zoznam parent servra 
						  	  		}//server je cluster noda	

						  	  	    // ak je to cluster tak vypis child servre

						  	  		 
						  	  	?>
						  	
						 	  
		  	
		</div>

		<div id="server_notes"><!-- server notes -->
			    <div class="server_info_header"><span style="font-size:13px;margin-left:5px;font-family:'Open Sans', Arial, sans-serif; font-weight:bold">Server's notes:</span></div>
					<div id="server_notes_list">
						<?php

	     				        $id=$_GET['server_id'];
	     				        $i=0;
								$sql="SELECT * FROM tblserver_notes WHERE server_id='$id'";
								//echo $sql;
								$result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
							    $alternate = "2";
							    while ($row = mysql_fetch_array($result)) {

									$i++;
							        $id=$row['intance_id'];
							        $note=$row['note'];
							        $date_created=$row['date_created'];


									echo "<div class='server_note_wrap'>";
										echo "<div class='server_note_date'>$date_created</div>";
										echo "<div class='server_note_text'>".nl2br(htmlspecialchars($note))."</div>";
									echo "</div>";

						    }
						?>
						  <form action="server_view_details.php" method="post" accept-charset="utf-8">
						  	  <input type="hidden" name="server_id" value="<?php echo $_GET['server_id']; ?>" >
						  	  <input type="hidden" name="server_name" value="<?php echo $server_name; ?>" >
							  <div class="server_note_form_wrap">
							  	<div class="server_note_textarea"><textarea name="server_note"></textarea></div>
							  	<div class="server_note_button"><button name="add_note" class="btn-small">Add note</button></div>
							  </div>
						  </form>
					</div>
			</div> <!-- server_notes  -->

			<div  style="font-weight:bold; margin-top:10px; position:relative; float:left;">
	    			<a href='index.php' class="action_link">&raquo; Back</a>
	  		</div>

		</div>

	</div>


	 <div id="left" class="column">

	</div>
