<?php
session_start();
$_SESSION['url'] = $_SERVER['REQUEST_URI'];
include "../include/dbconnect.php";
include "../include/functions.php";
?>
<?php
if (isset($_POST['mark_as_complete'])) {
	//quick mark task as complete
	global $con;
	$task_id = $_POST['task_id'];

	//$cat_id=$_POST['cat_id'];
	$sql = "UPDATE tblcustomer_tasks SET task_status='complete', date_finished='" . date('Y-m-d') . "' where id=$task_id";
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());

	//ak categoria tasku je minecraft a existuje aj v informacnom systeme pre minecraft tak ukonci task aj tam a vice versa
	if (isset($_POST['min_task_id'])) {
		$min_task_id = $_POST['min_task_id'];
		$conn1 = mysqli_connect(null, "minecraft_db", "eTIZAAMcSL", "minecraft_db", null, "/tmp/mariadb101.sock");
		$sql = "UPDATE todo SET is_completed=1 where task_id=$min_task_id";
		mysqli_close($conn1);
	}

	$curr_date = date('Y-m-d H:i:s');
	$curr_action = "finish_task";
	$curr_app = "task_manager";
	$text_logu = "The task id $task_id is complete";

	$curr_date = date('Y-m-d H:i:s');

	//zapis do timeliny
	$sql = "INSERT INTO tblcustomer_tasks_timeline (task_id, task_action, action_time) VALUES ($task_id, 'the task has been finished','$curr_date' )";
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	$undo_action = "UPDATE tblcustomer_tasks SET task_status='in progress', date_finished='" . date('Y-m-d') . "' where id=$task_id";

	//zapis do logu
	$sql = "INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	$message = "The task id $task_id is complete";

	//zapis do wallu
	//$link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
	$link1 = mysqli_connect("localhost", "root", "", "brick_wall", 3306, null);
	$diary_text = "EIS: Task s id $task_id je kompletny";
	$sql = "INSERT INTO diary (diary_text, date_added,location,isMobile) VALUES ('$diary_text','$curr_date','',0)";
	$result = mysqli_query($link1, $sql) or die("MySQLi ERROR: " . mysqli_error($link1));
	mysqli_close($link1);

	echo "<script>alert('" . $message . "');
               window.location='index.php?display_as=list';

           </script>";
}
if (isset($_POST['add_task_note'])) {
	$task_id = $_POST['task_id'];
	header('location:task_note.php?task_id=' . $_POST['task_id'] . '');
}

if (isset($_POST['view_task'])) {
	$task_id = $_POST['id'];
	//echo "task_id=$task_id";
	header('location:task.php?task_id=' . $_POST['task_id'] . '');
}

if (isset($_POST['edit_task'])) {
	//var_dump($_POST);
	$task_id = $_POST['task_id'];
	header('location:task_edit.php?task_id=' . $_POST['task_id'] . '');
}

if (isset($_POST['remove_task'])) {
	$task_id = $_POST['task_id'];

	header('location:task_delete.php?task_id=' . $_POST['task_id'] . '');
}

?>
<!DOCTYPE html>

<head>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta charset="utf-8" />
    <link href="../css/style_new.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <link href="../css/tasks.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700);" rel='stylesheet' type='text/css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <link rel='shortcut icon' href='../a.png'>
    <!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>-->
    <title>E.I.S. - Task manager</title>
</head>

<body>

    <?php
include '../include/header.php';
?>


    <!--end of header -->
    <div id="layout">

        <!-- <div id="main_wrap">-->
        <div class="tasks_wrap">
            <div class="list_of_cust">
                <?php
echo list_of_task_cust(); //zoznam vsetkych zakaznikov, ktory maju nejaku note
 ?>
            </div>

            <div class="list_of_cats">
                <?php
echo list_of_task_cats(); //zoznam kategorii pri aktivnych taskoch
 ?>
            </div>

            <h2>Tasks:</h2>
            <div id="display_as_header">
                <div class="search_task">
                   <input type="text" name="search_in_tasks" autocomplete="ofF" placeholder="Search tasks..." id="search_task"><button type="submit" class="btn-small"><i class="fa fa-search"></i></button>
                </div>
                <div id="display_as_wrap">
                    <span>Display as:</span>
                    <ul id='display_as'>
                        <li><button type="button" class="display_as" title="display as list"><i class="fa fa-list"></i></button></li>
                        <li><button type="button" class="display_as" title="display a group"><i class="fa fa-th"></i></button></li>
                        <li><button type="button" class="display_as" title="display as scrumboard"><i class="fa fa-th-large"></i></button></li>
                        <li><button type="submit" name="new_task" class="flat-btn new_task">+ Add new task</button></li>
                    </ul>
                </div><!-- display as wrapper -->
            </div><!-- display as header -->

            <?php

//$sql = "SELECT a.id, a.cust_id,a.task_text, a.task_category,a.task_priority,a.date_created,a.task_status,a.percent_done,b.cust_id, b.customer_name FROM tblcustomer_tasks a, tblcustomers b WHERE a.cust_id=b.cust_id  and a.task_status<>'complete'  and task_text like '%" . $string . "%' ORDER BY a.id DESC";
$sql = "SELECT a.id, a.cust_id,a.task_text, a.task_category,a.task_priority,a.date_created,a.task_status,a.percent_done,b.cust_id, b.customer_name FROM tblcustomer_tasks a, tblcustomers b WHERE a.cust_id=b.cust_id  and a.task_status<>'complete' ORDER BY a.id DESC";
echo "<ul id='tasks'>";
$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
$num = mysqli_num_rows($result);
if ($num == 0) {
	echo "<script>alert('Nic nenaslo')</script>";
} else {
	while ($row = mysqli_fetch_array($result)) {
		//SELECT `id`, `task_text`, `date_created`, `date_expiration`, `task_relation`

		$id = $row["id"];
		$task_text = $row['task_text'];
		$date_created = $row['date_created'];
		//$date_expiration=$row['date_expiration'];
		$task_relation = $row['customer_name'];
		$task_status = $row['task_status'];
		$task_priority = $row['task_priority'];
		$task_category = $row['task_category'];
		$percent_done = $row['percent_done'];

		$current_day = date("Y-m-d");

		if ($task_category != 0) {
			$task_category_name = GetTaskCategoryName($task_category);
		} else {
			$task_category_name = "";
		}

		echo "<li><div class='task'>";

		$task_text = preg_replace("~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~", "<a href=\"\\0\">\\0</a>", $task_text);
		$notes_count = GetTaskNotesCount($id); //pocet poznamok pre task

		echo "<div class='task_text'>$task_text</div>";
		//echo "<div class='task_note_count'><a href='view_notes.php?task_id=$id'><span class='nr_of_items'>$notes_count</span></a></div>";
		echo "<div class='task_date'><span>$date_created</span></div>";
		//echo "<div class='task_category'>$task_category_name</div>";
		//echo "<td><span><b>$percent_done%</b></span></td>";
		echo "<div class='task_progress'><span><progress max='100' value=$percent_done></progress></span></div>";
		echo "<div class='task_priority'>";
		echo "<select choose_task_priority onchange='ChangePriority($id);' id='task_priority-$id'>";
		echo "<option value='$task_priority' selected='selected'>$task_priority</option>";
		echo "<option value='low'>low</option>
                        <option value='normal'>normal</option>
                        <option value='high'>high</option>
                        </select></div>";
		echo "<div class='task_status'>";
		echo "<select name='choose_task_status' onchange='ChangeStatus($id);' id='task_status-$id'>";
		echo "<option value='$task_status' selected='selected'>$task_status</option>";
		echo "<option value='new'>new</option>
                        <option value='in progress'>in progress</option>
                        <option value='pending'>pending</option>
                       </select></div>";

		echo "<div class='task_actions'>";
		echo "<form action='' method='post'><ul>";

		$task_status_alive = array('new', 'in progress', 'pending');
		$task_status_not_alive = array('complete', 'void', 'cancelled');

		if (in_array($task_status, $task_status_alive)) {
			// ak je status stale aktivny

			echo "<input type='hidden' name=task_id value=$id><li><button type='submit' name='view_task[$id]' class='btn-small title='view task'><i class='fa fa-eye'></i></button></li><li><button type='submit' name='mark_as_complete[$id]' class='btn-small' title='mark as complete'><i class='fa fa-check'></i></button></li><li><button type='submit' name='edit_task[$id]' class='btn-small' title='Edit Task'><i class='fa fa-pencil-square-o'></i></button></li><li><button type='submit' name='add_task_note[$id]' title='add note to task' class='btn-small'><i class='fa fa-plus'></i></button></li><li><button type='submit' name='remove_task[$id]' title='Remove task' class='btn-small'> <i class='fa fa-times'></i></button></li>";
		} elseif (in_array($task_status, $task_status_not_alive)) {
			echo "<input type='hidden' name=task_id value=$id><li><button type='submit' name='view_task[$id]' class='btn-small title='view task'><i class='fa fa-eye'></i></button></li>";
		}

		echo "</ul></form></div>";
		echo "</div></li>";
	}

	echo "</ul>"; //table tasks wrap
}

?>


        </div>
        <!-- csutomer related tasks -->
        <!--</div>-->
        <!--main_wrap -->
    </div>
    </div>
    </div>
    <script type="text/javascript" src="../js/tasks.js"></script>
</body>

</html>