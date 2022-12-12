<?php
session_start();
?>
<?php
include "../include/dbconnect.php";
?>
<?php
include "../include/functions.php";
?>


<!DOCTYPE html>
<head>
   <meta charset="utf-8" />
   <meta name="viewport" content="width=device-width,initial-scale=1">

   <title>E.I.S. - Customers</title>
   <link href="../css/style.css" rel="stylesheet" type="text/css">
   <link href="../css/style_new.css" rel="stylesheet" type="text/css">
   <link href="../css/font-awesome.css" rel="stylesheet" type="text/css" />
   <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
   <link rel='shortcut icon' href='../a.png'>
</head>
<body>
   <div id="header">
      <!--header -->
      <div class="header-logo">e.i.s.</div>
      <!--logo -->
      <div class="header-menu">
         <!--menu-->
         <ul>
            <li><a href="../serveris.php" target="_self">Home</a></li>
            <li><a href="../passmgr/index.php" target="_self">Password manager</a></li>
            <li><a href="../notepad/index.php" target="_self">Notepad</a></li>
            <li><a href="index.php" target="_self">Customers</a></li>
            <li><a href="../tasks/index.php" target="_self">Tasks</a></li>
            <li><a href="../servers/index.php" target="_self">Servers</a></li>
            <li><a href="../projects/index.php" target="_self">Projects</a></li>
            <li><a href="../events/index.php" target="_self">Events</a></li>
            <li><a href="../contacts/index.php" target="_self">Contacts</a></li>
            <?php
if (isset($_SESSION["login"])) // som prihlaseny
{
	echo "<a href='logout.php'><i class='icon-signout'></i></a>";
} //zobrz ikonu prihlaseneho
else {
	echo "<a href='logout.php'><i class='icon-sigin'></i></a>";
} // inac zobrz ikonu na prihlasenie
?>
            </li>
         </ul>
      </div>
      <div id="txt" style="float:right; margin-right:5px; color:#fff"></div>
   </div>
   </div><!-- header -->

   <div id="layout">
   	<div id="customer_info">
         <h2></h2>
         <?php
echo "<table id='view_customer_details'>";
$cust_id = $_GET['cust_id'];
$sql = "SELECT * from tblcustomers where cust_id=$cust_id";
global $con;
$result = mysqli_query($con, $sql) or die(mysqli_error());
while ($row = mysqli_fetch_array($result)) {
	$customer_name = $row['customer_name'];
	$cust_id = $row['cust_id'];
	$customer_info = $row['customer_short_details'];
	$customer_status = $row['customer_status'];
	$customer_url = $row['customer_url'];

	echo "<tr><th>Name:</th><td>$customer_name</td></tr>";
	echo "<tr><th>Description:</th><td>$customer_info</td></tr>";
	echo "<tr><th>Url:</th><td>$customer_url</td></tr>";
	echo "<tr><th>Contacts:</th><td></td></tr>";
}
echo "</table>";

?>
      </div>

      <div id="customer_notes_wrap">
         <div id="customer_note_header" style="width: 100%; height: 40px; line-height:40px;float:left; background: #4072b4/*#4072b4*/">
            <span style="font-size:14px; color: #fff; margin-left:10px; font-weight:bold; position: relative;float: left">Notes:</span>
         </div>
         <div id="customer_notes">
            <ul>
                  <?php
global $con;
$cust_id = $_GET['cust_id'];
$sql = "SELECT * from tblcustomer_notes where cust_id=$cust_id ORDER BY id DESC LIMIT 5";
//echo "$sql";

$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());
while ($row = mysqli_fetch_array($result)) {

	$id = $row['id'];
	//$customer_name = $row['customer_name'];
	$note = $row['note'];
	$date_created = $row['date_created'];
	$title = $row['title'];

	echo "<li>";
	echo "<td>" . nl2br($note) . "</td><td>$title</td><td style='width:100px'>$date_created</td>";
	echo "</li>";

}
echo "<tr><td colspan='4'><a href='index.php?view=all_notes&cust_id=$cust_id' class='action_link'>More...</a></td></tr>";
?>
               </ul>
            <!-- note -->
         </div>
      </div>
      <!-- customer_notes_wrap -->
      <div id="customer_tasks_wrap">
         <!-- task wrap -->
         <div id="customer_tasks_header">
            <span style="font-size:14px; color: #fff; margin-left:10px; font-weight:bold; position: relative;float: left">Tasks:</span>
         </div>
                  <!-- 1 -->
            <table id="tasks">
               <?php
if (isset($_GET['cust_id'])) {
	$cust_id = $_GET['cust_id'];
	$sql = "SELECT * from tblcustomer_tasks where cust_id=$cust_id";
}
// else {$sql="SELECT * from tblcustomer_tasks";}
global $con;
$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());
$num = mysqli_num_rows($result);

while ($row = mysqli_fetch_array($result)) {
	$task_id = $row['TaskID'];
	$cust_id = $row['cust_id']; //id zakaznika
	//$todo_topic=$row['task_name'];
	$todo_category = $row['task_customer'];
	$todo_description = $row['task_text'];
	$todo_status = $row['task_status'];
	$todo_date_created = $row['date_created'];
	$todo_end_date = $row['date_expiration'];
	$todo_piority = $row['task_priority'];
	//todo_topic	todo_category	todo_status	todo_date_created	todo_end_date	todo_priority
	echo "<tr>";
	echo "<td><i class='fa fa-tasks'></i></td><td>$todo_description</td><td>$todo_category</td><td style='width:100px'>$todo_status<td><td style='width:75px'>$todo_date_created</td><td>$todo_piority</td><td style='width:20px'><a href='cust_note_delete.php?id=$id' class='action_link'><i class='fa fa-times'></i></a></td>";
	echo "</tr>";
}

?>
            </table>

      </div>
      <!-- task wrap -->
      <div id="customer_meetings_wrap" style="width:960px; padding: 5px; margin-top:20px; position: relative; float: left; background:#f3f3f3;">
         <div id="customer_meetings_header" style="width: 100%; height: 40px; line-height:40px; float: left; background:#4072b4">
            <span style="font-size:14px; color: #fff; margin-left:10px; font-weight:bold; position: relative;float: left">Meetings:</span>
         </div>
         <div id="customer_meetings_content" style="width: 100%; position: relative; float: left;margin-top: 10px;">
              <table>
                  <!-- <ul style="list-style: none; margin:0; padding: 0">-->
                  <?php
if (isset($_GET['cust_id'])) {
	$cust_id = $_GET['cust_id'];
	$sql = "SELECT * from tblcustomer_meetings where cust_id=$cust_id ORDER BY id  DESC";
}
/*else {
                     $sql="SELECT * from tblcustomer_meetings ORDER BY id DESC"; */

$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());
while ($row = mysqli_fetch_array($result)) {

	$id = $row['id'];
	$cust_id = $row['cust_id'];
	$meeting_name = $row['meeting_name'];
	$meeting_date = $row['meeting_date'];
	$start_time = $row['start_time'];
	$date_created = $row['date_created'];
	//$title =$row['title'];

	echo "<tr>";
	echo "<td><i class='fa fa-pencil'></i></td><td>$meeting_name</td><td>$meeting_date</td><td>$start_time</td>";
	echo "</tr>";

}

?>
               </table>
            </div>
            <!-- note -->
         </div>
      </div>
      <!-- customer_meetings_wrap -->
      <div id="customer_passwords_wrap">
         <div id="customer_passwords_header">
            <span style="font-size:14px; color: #fff;margin-left:10px; font-weight:bold;float: left">Passwords:</span>
         </div>

               <table id="customer_passwords">
                  <!-- <ul style="list-style: none; margin:0; padding: 0">-->
                  <?php
if (isset($_GET['cust_id'])) {
	$cust_id = $_GET['cust_id'];
	$sql = "SELECT * from tblpasswords where cust_id=$cust_id ORDER BY PassID DESC LIMIT 10";
}

/*else {
                     $sql="SELECT * from  tblpasswords ORDER BY PassID DESC LIMIT 10"; */
global $con;
$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());
while ($row = mysqli_fetch_array($result)) {

	$id = $row['PassID'];
	$cust_id = $row['cust_id'];
	$system_name = $row['system_name'];
	$user_name = $row['user_name'];
	$password = $row['password'];
	$date_created = $row['date_added'];
	//$title =$row['title'];

	echo "<tr>";
	echo "<td style='width:15px'><i class='fa fa-lock'></i></td><td  style='width:400px'>$system_name</td><td style='width:150px'>$user_name</td><td style='width:160px'>$password</td><td style='width:80px'>$date_created</td><td style='width:30px'><a href='../passmgr/password_edit.php?id=$id' class='action_link'>edit</a></td><td style='width:10px'><a href='../passmrg/password_delete.php?id=$id' class='action_link'>x</a></td>";
	echo "</tr>";

}

?>
                  <tr>
                     <td colspan="7"><a href="../passmgr/index.php" class="more_link">More...</a></td>
                  <tr>
               </table>

            <!-- passwords -->
         </div>
      </div>
      <!-- customer_meetings_wrap -->
      <div id="customer_servers_wrap">
         <div id="customer_servers_header">
            <span style="font-size:14px; color: #fff; margin-left:10px; font-weight:bold; position: relative;float: left">Servers:</span>
         </div>
         <div id="customer_servers_content">
                <table>
                  <!-- <ul style="list-style: none; margin:0; padding: 0">-->
                  <?php
if (isset($_GET['cust_id'])) {
	$cust_id = $_GET['cust_id'];
	$sql = "SELECT * from tblserver_info where cust_id=$cust_id ORDER BY id DESC LIMIT 10";
} else {
	$sql = "SELECT * from tblserver_info ORDER BY  id DESC LIMIT 10";
}
global $con;
$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());
while ($row = mysqli_fetch_array($result)) {

	$id = $row['id'];
	$server_name = $row['server_name'];
	$operating_system = $row['operating_system'];
	$single_cluster = $row['single_cluster'];
	$in_production = $row['in_production'];
	$mission_critical = $row['mission_critical'];

	echo "<tr>";
	echo "<td style='color:#333;font-weight:bold'><a href='../servers/server_view_details.php?server_id=$id'>$server_name</td><td>$operating_system</td><td>$single_cluster</td>";
	echo "<td><a href='http://eslweb.gre.omc.hp.com/pls/eslp/esl.info?node_name=$server_name'>ESL</a></td>";
	echo "<td><a href='../servers/server_view_details.php?server_id=$id' class='action_link'>View</a></td><td><a href='../servers/server_edit.php?server_id=$id' class='action_link'>Edit</a></td>";
	echo "</tr>";

}

?>
                  <tr>
                     <td colspan="10"><a href="../servers/index.php" class="more_link">More...</a></td>
                  <tr>
               </table>
           <!-- servers-->
         </div>
   </div>


      <!-- customer_issues_wrap-->
