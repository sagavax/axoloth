<?php session_start();?>
<?php include "../include/dbconnect.php";?>
<?php include "../include/functions.php";?>


<?php

if (isset($_POST['update_task'])) {
	//update task
	//var_dump($_POST);
	$task_id = $_POST['task_id'];
	$task_text = mysqli_real_escape_string($con, $_POST['task_text']);
	$date_created = $_POST['date_created'];
	$cat_id = $_POST['task_category'];
	$start_task_date = $_POST['start_task_date'];
	$date_expiration = $_POST['date_expiration'];
	$task_priority = $_POST['task_priority'];
	//$percent_done=$_POST['percent_done'];

	if (isset($_POST['mark_as_complete'])) {
		//dal som checkbox,ze task je coompleted
		$task_status == "complete";
		$percent_done = 100;
	} else {

		$task_status = $_POST['task_status'];
		$percent_done = $_POST['percent_done'];
	}

	$sql = "update tblcustomer_tasks set task_text='$task_text',task_category=$cat_id, percent_done=$percent_done, date_created='$date_created', start_task_date='$start_task_date',date_expiration='$date_expiration',task_priority='$task_priority',task_status='$task_status' where id=$task_id";
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));

	$curr_date = date('Y-m-d H:i:s');

	//zapis do timeliny
	$sql = "INSERT INTO tblcustomer_tasks_timeline (task_id, task_action, action_time) VALUES ($task_id, 'the task has been updated/ modified','$curr_date' )";
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	//zapis do logu

	$curr_action = "modify_task";
	$curr_app = "task_manager";
	//$new_note_id=GetLatestNote();
	$text_logu = "The task id $task_id has been modified";

	$sql = "INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());

	if ($task_status == "complete") {
		$message = "The task id $task_id has been complete";
	} else {
		$message = "The task id $task_id has been modified";
	}

	echo "<script>alert('" . $message . "');
            window.location='index.php';
       </script>";
}

if (isset($_POST['add_cat'])) {
	//pridat novy kategoriu
	global $con;
	$category_name = mysqli_real_escape_string($con, $_POST['cat_name']);
	$sql = "INSERT IGNORE into tblcustomer_categories (cat_name) VALUES ('$category_name')";
	//echo $sql;
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));

	$curr_date = date('Y-m-d H:i:s');
	$curr_action = "create_category";
	$curr_app = "task_manager";
	$new_cat_id = GetLatestCat();
	$undo_action = "DELETE from tblcustomer_categories WHERE id=$new_cat_id";
	$text_logu = "new category $cat_name with id $new_note_id has been created";
	$sql = "INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read,undo_action) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0,'$undo_action')";

	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());
	echo "<script>alert('New category has been created');
      window.location='task_add.php';
      </script>";
}

?>

<!DOCTYPE html>

<head>

  <meta charset="utf-8" />
  <link href="../css/style_new.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <link href="../css/tasks.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
  <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel='shortcut icon' href='../a.png'>
  <title> Axoloth - Task manager</title>
  <script type="text/javascript">
    function add_new_cat() {

      document.getElementById('new_cat_form').style.display = "block";
    }

    function updateTextInput(val) {
      document.getElementById('percent_done').value = val;
      document.getElementById('percent_done').value = val;

    }
  </script>
</head>

<body>
  <?php
include '../include/header.php';
?>


  <?php $task_id = $_GET['task_id'];?>

  <div id="layout">


    <div class="task_view_container">
      <div class="task_wrap">

        <div class="task_preview_header">
          <h2>Detail of task id <?php echo $task_id ?></h2>
        </div>
        <div class="task_preview_body">
          <?php

global $con;
$sql = "SELECT a.id, a.cust_id, a.task_text,a.task_category, a.percent_done, a.task_priority,a.task_status, a.start_task_date,a.date_created,a.date_expiration, b.cust_id, b.customer_name FROM tblcustomer_tasks a, tblcustomers b WHERE a.id=$task_id and a.cust_id=b.cust_id;";
//echo $sql;
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_array($result);
//print_r($row);
$start_task_date = $row['start_task_date'];
$date_expiration = $row['date_expiration'];
$percent_done = $row['percent_done'];
?>
          <form action="" method="post">
          <input type="hidden" name="task_id" value="<?php echo $row['id'] ?>" />
            <table class="task_details">
              
              <tr>
                <td>Task text:</td>
                <td><textarea name="task_text"><?php echo $row['task_text'] ?></textarea></td>
              </tr>
              <tr>
                <td>Customer:</td>
                <td><input type="text" name="task_customer" value="<?php echo $row['customer_name'] ?>"></td>
              </tr>
              <tr>
                <td>Date created:</td>
                <td><input type="text" name="date_created" value="<?php echo $row['date_created'] ?>"></td>
              </tr>
              <tr>
                <td>Task priority:</td>
                <td>
                  <select name="task_priority">
                    <?php
$task_priority = $row['task_priority'];
echo "<option value=$task_priority selected=\"selected\">$task_priority</option>";
?>

                    <option value="low">Low</option>
                    <option value="normal">Normal</option>
                    <option value="high">High</option>
                  </select>
                <td>
              </tr>
              <tr>
                <td>Task status:</td>
                <td>
                  <select name="task_status">
                    <?php
$task_status = $row['task_status'];
echo "<option value='$task_status' selected=\"selected\">$task_status</option>";
?>
                    <option value="new">New</option>
                    <option value="in progress">In progress</option>
                    <option value="pending">Pending</option>

                  </select>
                </td>
              </tr>
              <tr>
                <td>Task category:</td>
                <td>
                  <select name="task_category">

                    <?php
$task_category = $row['task_category'];

if ($task_category != 0) {
	$task_category_name = GetTaskCategoryName($task_category);
} else {
	$task_category_name = "-- No category --";
}

echo "<option value='$task_category' selected=\"selected\">$task_category_name</option>";

$sql = "SELECT cat_id, cat_name from tblcustomer_categories";
$rsd = mysqli_query($con, $sql) or die(mysqli_error($con));
//echo "<option value=0 selected=\"selected\">--No category --</option>";
while ($row = mysqli_fetch_array($rsd)) {
	$cat_name = $row['cat_name'];
	$cat_id = $row['cat_id'];
	echo "<option value='$cat_id'>$cat_name</option>";
}

?>


                  </select>
                </td>
                <!-- <button onclick="add_new_cat(); return false;" class="flat-btn-small">+</button>-->
            </tr>
              <tr>
                <td>Percent done:</td>
                <td></td>
              </tr>
              <tr>
                <td>Percent done:</td>
                <td>
                  <input type="range" min=0 max=100 step="5" name="percent_done" value="<?php echo $percent_done; ?>" onchange="updateTextInput(this.value);" />
                  <input type="number"id="percent_done" value="<?php echo $percent_done; ?>"/>

                </td>

              </tr>
              <tr>

                <td>Start task date:</td>
                <td><input type="text" id="datepickr1" name="start_task_date" placeholder="Start date" class="date_input" value="<?php echo $start_task_date; ?>"></td>

              </tr>
              <tr>
                <td> Task will expire:</td>
                <td><input type="text" id="datepickr2" name="date_expiration" placeholder="Date of expiration" value="<?php echo $date_expiration; ?>" class="date_input"></td>

              </tr>
              <tr>
                <td colspan="2"><input type="checkbox" name="mark_as_complete" title="mark as complete" id="mark_as_complete"><label for="mark_as_complete">Mark as complete</label></td>
              </tr>

            </table>
        </div>
        <div class="task_preview_footer"><a href="index.php" class="flat-btn">&lt;&lt; Back</a><button name="update_task" class="flat-btn">Update task</button></div>
      </div> <!-- edit task wrap -->
      </form>
    </div>
    <!-- end of layout -->

  </body>