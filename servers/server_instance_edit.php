<?php include("../include/dbconnect.php"); ?>


 <?php

if(isset($_POST['update_instance_info']))
{
        $instance_id=$_POST['instance_id'];
        $instance_name=mysql_real_escape_string($_POST['instance_name']);
        $instance_info=mysql_real_escape_string($_POST['instance_info']);
        $instance_version=mysql_real_escape_string($_POST['instance_version']);
        $instance_type=$_POST['instance_type'];
        $instance_technology=$POST['instance_technology'];

        $sql = "UPDATE tblinstances SET instance_name='$instance_name', instance_info='$instance_info', instance_version='$instance_version',instance_type=$instance_type,instance_technology=$instance_technology,instance_note='$instance_note' WHERE instance_id='$id'";

	       //echo "$sql";
		    $result = mysql_query($sql);

        echo "<br/><div class='msgbox'><b>Information about the instance has been updated successfully</b><a href='server_instance_all.php'><img src='icons/home.png' title='Home page' alt='Home page'></a></div>";
		// header("Location: view?id=$id");

		}

	?>

<!--- <meta HTTP-EQUIV="REFRESH" content="10;url=curPageURL()"> -->

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
      <link rel='shortcut icon' href='../eis.ico'>
      <title>ServerIS - servers</title>
          
  </head>


<body>
    <div id="header"> <!--header -->
      <div class="header-logo">e.i.s. - enterprise informating system</div> <!--logo -->
      <div class="header-menu"> <!--menu-->
          <ul>
            <li><a href="../serveris.php" target="_self"><img src="images/home.png" alt="Home" /></a></li>
            <li><a href="../serveris.php" target="_self">Home</a></li>
            <li><a href="../passmgr/index.php" target="_self">Password manager</a></li>
            <li><a href="../notepad/index.php" target="_self">Notepad</a></li>
            <li><a href="../customers/index.php" target="_self">Customers</a></li>
            <li><a href="../links/index.php" target="_self">Links</a></li>
            <li><a href="../tasks/index.php" target="_self">Tasks</a></li>
            <li><a href="../incidents/index.php" target="_self">Incidents</a></li>
            <li><a href="../servers/index.php" target="_self">Servers</a></li>
          </ul>
      </div>
   </div> <!--end of header -->
  <div id="layout">
    <div id="main_wrap">
                      <div id="left_content">

                              <?php
                          
                              $inst_id=$_GET['id'];


                              $sql="SELECT * from tblserver_instances WHERE id=$id";
                              $result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
                              $row = mysql_fetch_array($result) ;

                              ?>

                              

                              <form accept-charset="utf-8" method="post" action="server_instance_edit.php">
                              <input type="hidden" name="id" value="<?php echo $inst_id;?>"  />
                                <h2> Edit instance <?php echo $row['instance_name']?> info:</h2>
                                <table id="edit_instance">
                                  
                                  <tr>
                                     <td style="width:100px">Instance name:</td>
                                     <td><input type="text" name="instance_name" value="<?php echo $row['instance_name']?>" ></td>
                                  </tr>
                                  
                                  <tr>
                                      <td style="width:100px">General note:</td>
                                      <td><input type="text" name="instance_info" value="<?php echo $row['instance_info']?>"></td>
                                  </tr>
                                   <tr>
                                    <td style="width:100px">Version:</td>
                                    <td>
                                      <input type="text" name="instance_version" id="instance_version" value="<?php echo $row['instance_version']?>"> 
                                    </td>
                                  </tr>
                                   <tr>
                                    <td style="width:100px">Type:</td>
                                    <td>
                                      <select name="instance_type" id="instance_type"> 
                                          <?php
                                          $instance_type=$row['instance_type'];
                                          if($instance_type=='1') {$instance_type_title='Database';} elseif ($instance_type=='2') {
                                            $instance_type_title='Web';
                                          }
                                          echo "<option value='$instance_type' selected=\"selected\">$instance_type_title</option>";
                                          ?>
                                          <option value="1">Database</option>
                                          <option value="2">Web</option>
                                        
                                        </select>
                                    </td>
                                  </tr>
                                  <tr>
                                  <tr>
                                    <td style="width:100px">Technology:</td>
                                    <td>
                                      <select name="instance_technology" id="instance_technology"> 
                                          <?php
                                          $instance_technology=$row['instance_technology'];
                                          if($instance_technology==1){$instance_technology_title='IIS';} elseif ($instance_technology==2) {
                                           $instance_technology_title='Apache';} elseif ($instance_technology==3) {$instance_technology_title='MS SQL';
                                          } elseif ($instance_technology==4) {$instance_technology_title=='Sybase';}

                                          echo "<option value='$instance_technology' selected=\"selected\">$instance_technology_title</option>";
                                          ?>
                                          <option value="1">IIS</option>
                                          <option value="2">Apache</option>
                                          <option value="3">MS SQL</option>
                                          <option value="4">Oracle</option>
                                          <option value="5">Sybase</option>
                                        </select>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td style="width:100px">instance note:</td>
                                    <td><textarea id="instance_note" name="instance_note"></textarea></td>
                                  </tr>
                                  <tr>
                                        <td colspan="2" style="text-align:right"><button type="submit" name="update_instance_info" class="flat-btn">Update instance</button></td>
                                  </tr>  
                                 </table> 
                              </form>
                  </div><!-- left content -->
                  <a href='index.php' class="action_link">&lt;&lt; Back</a>
    </div><!-- main wrap -->
  </div><!--layout -->             