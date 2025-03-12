<?php include("../include/dbconnect.php"); ?>
<?php include("../include/functions.php"); ?>

<!DOCTYPE html>
 <head>
        <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sk" lang="sk">
		<meta charset="utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<meta name="author" content="" />
    <link href="../css/style_new.css" rel="stylesheet" type="text/css" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
		
        <!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>-->
        <title>Enterprise informating system - Task manager</title>
                                      
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
                                  <li><a href="../servers/index.php" target="_self">Servers</a></li>
                                  <li><a href="../projects/index.php" target="_self">Projects</a></li>
                                  <li><a href="index.php" target="_self">Events</a></li>
                                  <li><a href="../contacts/index.php" target="_self">Contacts</a></li>
                                </ul>
  						  </div><!--menu-->
          </div> <!--end of header -->
		

      <div id="layout">
			<?php
			 //var_dump($_POST); 
			//echo "Subor: ".$_FILES['file']['name'];
			if ($_FILES['file']["error"] > 0) {
			  echo "Error: " . $_FILES['file']['error'] . "<br>";
			} else {
			 
			 $file_title=mysql_real_escape_string($_POST['file_title']);
			 $file_description=mysql_real_escape_string($_POST['file_description']);
			 $file_name=$_FILES['file']['name'];
			 $file_size=$_FILES['file']['size']; //Bytes
			 $file_type=$_FILES['file']['type']; //extension
             
			 $temp = explode(".", $_FILES['file']['name']);
			 $file_ext = end($temp);


			 move_uploaded_file($_FILES['file']['tmp_name'],"../library/documents/". $_FILES['file']['name']);

			 $file_path=$_FILES['file']['name'];

			 $date_added = date('Y-m-d H:m:s');

			 $sql="INSERT INTO tblfile_library (file_title,file_name, file_size, file_ext, file_type, file_path, file_added,file_description ) VALUES ('$file_title','$file_name','$file_size','$file_ext','$file_type','$file_path','$date_added','$file_description')";
			 echo $sql;
			 

			 $result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());

			}
            
            header ('location:index.php');

			?>
	</div>		