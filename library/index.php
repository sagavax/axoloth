<?php session_start() ?>    
<?php include("../include/dbconnect.php"); ?>
<?php include("../include/functions.php"); ?>


<?php 
  
define('ROOT_DIRECTORY', dirname(__FILE__).'/');  

?>

<?php 
if(isset($_POST['new_file'])){ //quick mark task as completed
    
  header('location:new_file.php');


 }
 ?> 

<!DOCTYPE html>
 <head>
        <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sk" lang="sk">
		<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1">
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<meta name="author" content="" />
    <link href="../css/style_new.css" rel="stylesheet" type="text/css" />
    <link href="../css/font-awesome.css" rel="stylesheet" type="text/css" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
		<link rel='shortcut icon' href='../eis.ico'>
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
                
                 <h2>File library</h2>
                 <div id="searchbox">
                    <!-- <input id="text_searchstring" type="text" value="<?php echo $searchstring; ?>" onKeyUp="getarticles('notes','text_searchstring')" title="Search for any string" /> -->
                </div> 
                <div id="add_task">
                  <form action="index.php" method="post"><button type="submit" name="new_file" class="flat-btn">+ Add new</button></form>
                </div> 
                <div id="main_wrap">
                    <div id="left_content">
                      <?php 
                       /* $dir    = './documents/';
                        $files = scandir($dir);
                       // print_r($files);
                        echo "<table>";
                          echo "<tbody>";
                            foreach ($files as $file) {
                          //  print_r($file); 
                             $file_path=$SERVER['DOCUMENT_ROOT'].'/eis/library/documents'.$file;
                             echo "<tr>";
                              echo "<td><a href='$file_path'>$file</a></td>";
                              //$file=$_SERVER["DOCUMENT_ROOT"]."files/library/".$file;
                              //echo $file."</br>";
                              //$size =filesize($file);
                             // echo "<td>$size</td>";
                              //echo "<td>".pathinfo($file,PATHINFO_EXTENSION)."</td>";
                              //echo "<td>",date('r', $file['lastmod']),"</td>";
                             echo "</tr>"; 
                            }
                          echo "</tbody>";
                        echo "</table>"; */

                         echo "<table id='files_uploaded'>";
                          echo "<tbody>";
                            $sql="SELECT * from tblfile_library";
                            $result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());

                             while ($row = mysql_fetch_array($result)) {
                                $file_id=$row['file_id'];
                                $file_name=$row['file_name'];
                                $file_size=$row['file_size'];
                                $file_ext=$row['file_ext'];
                                $file_type=$row['file_type'];
                                $file_added=$row['file_added'];

                               //echo file_exists("c:\wamp\www\eis\library\documents\eucharistia.doc");


                                echo "<tr>";
                                  if(file_exists('./documents/'.$file_name)){
                                    echo "<td style='width:20px'><div class='small-green-circle'></div></td>";
                                  } else {echo "<td style='width:20px'><div class='small-red-circle'></div></td>";}

                                  echo "<td style='font-size:16px;width:20px'><i class='fa fa-file-o'></i></td><td style='width:600px;font-weight:bold'>$file_name</td><td>$file_size</td><td>$file_ext</td><td>$file_added</td><td><a href='remove_file.php?file_id=$file_id' class='btn-small'><i class='fa fa-trash'></i></a></td>";
                                echo "</tr>";  

                             } 
                         echo "</tbody>";
                        echo "</table>";    
                      ?>
                      </div><!-- left content -->
                    </div><!-- main wrap -->
                </div>    
           </div>
 