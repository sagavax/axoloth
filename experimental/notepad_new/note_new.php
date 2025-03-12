<?php session_start() ?>
<?php include("../include/dbconnect.php"); ?>
<?php include("../include/functions.php"); ?>
<?php require_once("parser.php"); ?>
<?php 
   if (isset($_POST['edit_note']))
   {
       $note_id=$_POST['note_id'];
       $note_title=mysql_real_escape_string($_POST['note_title']);
       $note_text=mysql_real_escape_string($_POST['note_text']);
       //$note_id = $_POST['customer']; // if cust_id = moje vlastne poznamky
       //$date_created = date('Y:m:d H:m:s');
   	
   	$sql="UPDATE tblcustomer_notes set title='$note_title', note='$note_text' where id=$note_id";
   	$result = mysql_query($sql)  or die("MySQL ERROR: ".mysql_error());
   	header('Location: index.php'); // presmeruje spat
   	
   }
   
   ?>
<!DOCTYPE html>
<head>
   <meta name="viewport" content="width=device-width,initial-scale=1">
   <meta charset="utf-8" />
   <meta name="description" content="" />
   <meta name="keywords" content="" />
   <meta name="author" content="" />
   <title>ServerIS - Customers</title>
   <link href="../css/style.css" rel="stylesheet" type="text/css">
   <link href="../css/style_new.css" rel="stylesheet" type="text/css">
   <link href="../css/font-awesome.css" rel="stylesheet" type="text/css" />
   <meta name="viewport" content="width=device-width,initial-scale=1">
   <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
   <link rel='shortcut icon' href='../eis.ico'>
</head>
<body>
   <?php
      $note_id=$_GET['note_id'];
      
      ?>	
   <div id="header">
      <!--header -->
      <div class="header-logo">e.i.s. - enterprise informating system</div>
      <!--logo -->
      <div class="header-menu">
         <!--menu-->
         <ul>
            <li><a href="serveris.php" target="_self"><img src="../images/home.png" alt="Home" /></a></li>
            <li><a href="../serveris.php" target="_self">Home</a></li>
            <li><a href="../passmgr/index.php" target="_self">Password manager</a></li>
            <li><a href="../notepad/index.php" target="_self">Notepad</a></li>
            <li><a href="../customers/index.php" target="_self">Customers</a></li>
            <li><a href="../links/index.php" target="_self">Links</a></li>
            <li><a href="../tasks/index.php" target="_self">Tasks</a></li>
            <li><a href="../incidents/index.php" target="_self">Incidents</a></li>
            <li><a href="../servers/index.php" target="_self">Servers</a></li>
            <li><a href="/admin/index.php" target="_self">Admin</a></li>
         </ul>
      </div>
   </div>
   <!--end of header -->
   <div id="layout">
      <div id="main_wrap">
         <div id="left_content">
            <?php 
               $sql="SELECT a.id, a.cust_id, a.cat_id,a.title, a.note, a.note_source, b.cust_id, b.customer_name from tblcustomer_notes a,tblcustomers b where a.id=$note_id and a.cust_id=b.cust_id";
               //echo $sql;
               $result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
               
               $parser = new parser;
               
               while ($row = mysql_fetch_array($result)){
               	$note_id=$row['id'];
                  $cat_id=$row['cat_id'];
               	echo $cat_id;
                  $cat_name=GetCatName($cat_id);
                  $cust_id=$row['cust_id'];
                  $customer_name=$row['customer_name'];
                  $title=htmlspecialchars($row['title']);
                  $note=htmlspecialchars($row['note']);
                  $date_created=$row['date_created'];
                  $source=$row['note_source'];
                  
                  //echo $note;
               ?>	
            <form action="note_edit.php" method="post">
               <h2>Edit the note:</h2>
               <input type="hidden" name="note_id" value="<?php echo $_GET['note_id'] ?>">
               <table id="edit_note">
                  <tr>
                     <td>Title:</td>
                     <td><input type="text" name="note_title" value="<?php echo $title ?>"></td>
                  </tr>
                  <tr>
                     <td>Customer:</td>
                     <td>
                        <input type="text" value="<?php echo $customer_name ?>"> 
                     </td>
                  </tr>
                  <tr>
                     <td>Category:</td>
                     <td>
                        <input type="text" value="<?php echo $cat_name ?>"> 
                     </td>
                  </tr>
                  <tr>
                     <td>Text:</td>
                     <td>
                        <?php
                           $parsed = $parser->p($note);
                           $langs=array('PHP','Javascript','T-SQL','PL-SQL','Powershell');
                           if(in_array($cat_name, $langs)) { //ak ide o zdojovy kod, skript, snippet tak to zobraz v kode editore    
                           echo "<div class='note_wrap'>";
                           echo "<div class='note_text' id='editor'>".htmlspecialchars(nl2br($parsed))."</div>";
                           echo "<div class='note_action'><a href='note_edit.php?note_id=$note_id' class='action_link'>Edit</a> | <a href='note_delete.php?note_id=$note_id' class='action_link'>Delete</a> | <a href='note_clone.php?note_id=$note_id' class='action_link'>Clone</a></div>";
                           echo "<div style='clear:both'></div>";
                           echo "</div>";
                           ?>
                          
                             <?php 
                              } else { //ak to nie je zdrojak, zobraz to ako obycajny text
                             echo "<div class='note_wrap'>";
                             echo "<div class='note_text'>".htmlspecialchars(nl2br($parsed))."</div>";
                              echo "<div class='note_action'><a href='note_edit.php?note_id=$note_id' class='action_link'>Edit</a> | <a href='note_delete.php?note_id=$note_id' class='action_link'>Delete</a> | <a href='note_clone.php?note_id=$note_id' class='action_link'>Clone</a></div>";
                              echo "<div style='clear:both'></div>";
                              echo "</div>";
                           }      
                          ?> 
                     </td>
                  </tr>
                  <tr>
                     <td>Source:</td>
                     <td><input type="text" name="note_source" value="<?php echo $source ?>"></td>
                  </tr>
               </table>
               <?php
                  }
                  ?>	
            </form>
            <a href='index.php' class="action_link">&lt;&lt; Back</a>
         </div>
         <!-- left content-->
      </div>
      <!-- main wrap-->  
   </div>
   <!-- id layout-->		
</body>

</html>