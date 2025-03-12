<?php
session_start();
$_SESSION['url'] = $_SERVER['REQUEST_URI'];
include "../include/dbconnect.php";
include "../include/functions.php";
?>

<!DOCTYPE html>
<head>
   <meta name="viewport" content="width=device-width,initial-scale=1">
   <meta charset="utf-8" />
   <link href="../css/style_new.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
   <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
   <link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
   <link rel='shortcut icon' href='../a.png'>
   <!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>-->
   <title>E.I.S. - Task manager</title>
</head>
<body>
   <div id="header">
      <!--header -->
      <div class="header-logo">e.i.s.</div>
      <!--logo -->
      <div class="header-menu">
         <!--menu-->
            <?php
include "../include/menu.php";
?>
      </div>
      <!--menu-->
   </div>
   <!--end of header -->
   <div id="layout">

        <div id="task_notes">
            <div class="row"><h3>Notes / comment for task_id: <?php $_GET['task_id'];?></h3></div>
            <?php
$task_id = $_GET['task_id'];
$sql = "select * from tblcustomer_tasks_comments where task_id=$task_id";
$result = mysqli_query($con, $sql);
while ($row = mysqli_fetch_array($result)) {
	$task_id = $row['task_id'];
	$comment = $row['comment'];
	$date_created = $row['date_created'];
	echo "<div class='task_comment'>";
	echo "<div class='task_comm_text'>$comment</div>";
	echo "<div class='task_comm_created_date'><span>$date_created</span></div>";
	echo "</div>";
}
?>
            <div class="row"><a href="index.php?display_as=list"><< Back</a></div>

       </div>
   </div>
</body>