<?php session_start() ?>
<?php include("../include/dbconnect.php"); ?>
<?php include("../include/functions.php"); ?>

<?php
   if (isset($_POST['add_note']))
   {
       global $con;
       $note_title=mysqli_real_escape_string($con, $_POST['note_title']);
       $note_source=mysqli_real_escape_string($con, $_POST['note_source']);
       $note_text=mysqli_real_escape_string($con, $_POST['note_text']);
       $cust_id = (int)$_POST['customer']; // if cust_id = moje vlastne poznamky
       $cat_id=(int)$_POST['category'];
       $note_type="audio_note";   
       $date_created = date('Y-m-d H:m:s');
   	$sql="INSERT INTO tblcustomer_notes (cust_id, cat_id,title, note,note_source,date_created,note_type) VALUES ($cust_id,$cat_id,'$note_title','$note_text','$note_source','$date_created','$note_type')";
   	//var_dump($_POST);
   // echo "$sql";
   	$result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error());
   	
   	$curr_date=date('Y-m-d H:i:s');
   	$curr_action="create_note";
   	$curr_app="notepad";
   	$new_note_id=GetLatestNote();
   	$text_logu="new note id $new_note_id has been created";
   	
   	$sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
   	//echo $sql;
   	
   	$result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error());
      echo "<script>alert('new message has been created');
      window.location='index.php';   
      </script>";
   	
   	//header('Location: index.php'); // presmeruje spat aby sa zbranilo vkladaniu duplicity

   }

   if(isset($_POST['add_cat'])){ //pridat novy kategoriu
    global $con;
    $category_name=mysqli_real_escape_string($con, $_POST['cat_name']);
    $sql="INSERT IGNORE into tblcustomer_notes_category (category_name) VALUES ('$category_name')";
    //echo $sql;
    $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error());
    header('location:note_add.php');
   }
   ?>
   
<!DOCTYPE html>
<head>
   <meta charset="utf-8" />
   <title>E.I.S - Add new note</title>
   <link href="../css/style_new.css" rel="stylesheet" type="text/css">
   <link href="../css/font-awesome.css" rel="stylesheet" type="text/css" />
   <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
   <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
   <script src="../js/recorder.js"></script>
	<script src="../js/Fr.voice.js"></script>
   <script src="../js/app.js"></script>
   <link rel='shortcut icon' href='../eis.ico'>


   <script type="text/javascript">
      // <![CDATA[
      var form_name = 'new_note_form';
      var text_name = 'note_text';
      var load_draft = false;
      var upload = false;

      // Define the bbCode tags
      var bbcode = new Array();
      //var bbtags = new Array('<b>','</b>','<i>','</i>','<img src='' >','</img>','<a href=''>','[/a]');
      var bbtags = new Array('[b]','[/b]','[i]','[/i]','[u]','[/u]','[quote]','[/quote]','[code]','[/code]','[list]','[/list]','[list=]','[/list]','[img]','[/img]','[url]','[/url]','[flash=]', '[/flash]');
      var imageTag = false;


      // ]]>

   </script>
   <script type="text/javascript">
      function add_new_cat() {

         document.getElementById('new_cat_form').style.display = "block";
      }
   </script>
</head>
<body>
   <div id="header">
      <!--header -->
      <div class="header-logo">e.i.s. - enterprise informating system</div>
      <!--logo -->
      <div class="header-menu">
         <!--menu-->
         <ul>
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
         <div id="add_new_note_wrap">
            <form action="note_add.php" method="post" id="new_note_form">
               <table id="new_note">
                  <tr>
                     <td>Title:</td>
                     <td><input type="text" name="note_title"></td>
                  </tr>
                  <tr>
                     <td>Customer:</td>
                     <td>
                        <select name="customer">
                           <?php
                              if(isset($_GET['cust_id'])) {
                                global $con;
                                $cust_id=$_GET['cust_id'];
                              	$sql="SELECT customer_name from tblcustomers where cust_id=$cust_id";
                              	$result=mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error());
                              	while ($row = mysqli_fetch_array($result)) {
                              		$customer_name=$row['customer_name'];
                              		echo "<option value='$cust_id' selected=\"selected\">$customer_name</option>";
                              	}
                              }
                              ?>
                           <option value="0">-- Choose the customer -- </option>
                           <?php
                              global $con;
                              $sql="SELECT cust_id, customer_name from tblcustomers WHERE customer_status='active'";
                              	$result=mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error());
                                               	while ($row = mysqli_fetch_array($result)) {
                                               		$cust_id=$row['cust_id'];
                              	$customer_name=$row['customer_name'];

                              	echo "<option value='$cust_id'>$customer_name</option>";
                              }
                              ?>
                        </select>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        Category:
                     </td>
                     <td>
                        <select name="category">
                           <option value="0">-- Choose the category --</option>
                           <?php
                              global $con;
                              $sql="SELECT * from tblcustomer_notes_category";
                              $result=mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error());
                                     	while ($row = mysqli_fetch_array($result)) {
                                     		$cat_id=$row['cat_id'];
                                     		$category_name=$row['category_name'];
                                     	echo "<option value='$cat_id'>$category_name</option>";
                                 }

                              ?>
                        </select>
                        <button onclick="add_new_cat(); return false;" class="flat-btn-small">+</button>
                        <div id="new_cat_form" class="skryte-okno"><input type="text" name="cat_name" style="width:300px"><button name="add_cat" type="submit" class="flat-btn-small">New</button></div>
                     </td>
                  </tr>
                 <tr>
                    <td colspan=2>
                     <audio controls src="" id="audio"></audio>
	                 </td>
	              </tr>
	              <tr>
	                 <td colspan=2>
               			<a class="btn-small" id="record">Record</a>
                        <a class="btn-small disabled one" id="pause">Pause</a>
               			<a class="btn-small disabled one" id="stop">Reset</a>
               			<a class="btn-small disabled one" id="play">Play</a>
               			<a class="btn-small disabled one" id="download">Download</a>
                        <a class="btn-small disabled one" id="base64">Base64 URL</a>
                        <a class="btn-small disabled one" id="mp3">MP3 URL</a>
                        <a class="btn-small disabled one" id="save">Stop recording and Upload to Server</a>
                     </td>
                  </tr>   
                  <tr> 
                     <td colspan=2>
                        <input class="button" type="checkbox" id="live"/>
		                  <label for="live">Live Output</label>
                   </td>   
		           </tr>
		           <tr>
		              <td colspan=2>
		                  <canvas id="level" height="200" width="800"></canvas>
		              </td>
		           </tr>
                  <tr>
                     <td>Source:</td>
                     <td><input type="text" name="note_source"></td>
                  </tr>
                  <tr>
                  <tr>
                     <td colspan="2" style="text-align:right"><button name="add_note" type="submit" class="flat-btn">Add note</button></td>
               </table>
            </form>
         </div>
         <a href='index.php' class="action_link">&lt;&lt; Back to home</a>
      </div>
      <!-- main wrap-->
   </div>
   <!-- id layout-->
</body>
</html>
