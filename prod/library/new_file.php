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

			<form id="upload" action="upload.php" method="POST" enctype="multipart/form-data">
				<table id="file_upload">		
	               <tr>
	               		<td>
							<legend><h3>File Uploader<h3></legend>
						</td>
					</tr>
					<tr>
						<td>
							<label for="fileselect">File to upload:</label>
							<input type="file" name="file" id="file">
						</td>
					<tr>
						<td>
							
							<input type="text" name="file_title" id="file_title" placeholder="file title">
						</td>
					</tr>
					<tr>
						<td>
							
							<textarea name="file_description" id="file_description" placeholder="description"></textarea>
						</td>
					</tr>				
					<!--<input type="hidden" id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" value="300000" /> -->
					<tr>
						<td style="text-align:right"><button type="submit" class="flat-btn">Upload Files</button></td>
					</tr>	
				</table>

			</form>

		</div> <!-- layout -->	