<?php include "dbconnect.php";?>

<?php

define('APPPATH', dirname(__FILE__) . '/');
define('APPNAME', 'Enterprise informanting system');
define('APPTITLE', 'E.I.S');
define('APPVERSION', '1.0');
global $con;

?>

<?php
function data_uri($file, $mime) {
	$contents = file_get_contents($file);
	$base64 = base64_encode($contents);
	$mime = mime_content_type($file);
	return ('data:' . $mime . ';base64,' . $base64);
}

function GetPlatfomName($platform_id) {
	global $con;
	$sql = "SELECT platform_name from tbltrading_platforms WHERE id=$platform_id";
	$result = mysqli_query($con, $sql) or die("MySQLi ERROR: " . mysqli_error($con));
	$row = mysqli_fetch_array($result);
	$platform_name = $row['platform_name'];
	return $platform_name;

}

function PlatformHasNotes($platform_id) {
	global $con;
	$sql = "SELECT COUNT(*) as nr_notes from tbltrading_platform_notes WHERE platform_id=$platform_id";
	//echo $sql;
	$result = mysqli_query($con, $sql) or die("MySQLi ERROR: " . mysqli_error($con));
	$row = mysqli_fetch_array($result);
	$nr_notes = $row['nr_notes'];
	return $nr_notes;
}

function GetCountNewestNotes() {
	global $con;
	$sql = "SELECT COUNT(*) as nr_notes from tblcustomer_notes WHERE date(date_created)  BETWEEN DATE_SUB(DATE(NOW()), INTERVAL 3 DAY) AND DATE(NOW()) ORDER BY id DESC";
	$result = mysqli_query($con, $sql) or die("MySQLi ERROR: " . mysqli_error($con));
	$row = mysqli_fetch_array($result);
	$nr_notes = $row['nr_notes'];

	return $nr_notes;
}

function GetCountNewestPlatforms() {
	global $con;
	$sql = "SELECT COUNT(*) as nr_platforms from tbltrading_platforms WHERE date(added_date)  BETWEEN DATE_SUB(DATE(NOW()), INTERVAL 3 DAY) AND DATE(NOW()) ORDER BY id DESC";
	$result = mysqli_query($con, $sql) or die("MySQLi ERROR: " . mysqli_error($con));
	$row = mysqli_fetch_array($result);
	$nr_platforms = $row['nr_platforms'];

	return $nr_platforms;
}

function GetCountNewestTasks() {
	global $con;
	$sql = "SELECT COUNT(*) as nr_tasks from tblcustomer_tasks WHERE date(date_created)  BETWEEN DATE_SUB(DATE(NOW()), INTERVAL 3 DAY) AND DATE(NOW()) ORDER BY id DESC";
	$result = mysqli_query($con, $sql) or die("MySQLi ERROR: " . mysqli_error($con));
	$row = mysqli_fetch_array($result);
	$nr_tasks = $row['nr_tasks'];

	return $nr_tasks;
}

function GetCountNewestPasswords() {
	global $con;
	$sql = "SELECT COUNT(*) as nr_passwords from tblpasswords WHERE date(date_added) BETWEEN DATE_SUB(DATE(NOW()), INTERVAL 3 DAY) AND DATE(NOW()) ORDER BY PassID DESC";
	$result = mysqli_query($con, $sql) or die("MySQLi ERROR: " . mysqli_error($con));
	$row = mysqli_fetch_array($result);
	$nr_passwords = $row['nr_passwords'];

	return $nr_passwords;
}

function GetCountNewestEvents() {
	global $con;
	$sql = "SELECT COUNT(*) as nr_events from tblcustomer_events WHERE event_created  BETWEEN DATE_SUB(DATE(NOW()), INTERVAL 3 DAY) AND DATE(NOW()) ORDER BY event_id DESC;";
	$result = mysqli_query($con, $sql) or die("MySQLi ERROR: " . mysqli_error($con));
	$row = mysqli_fetch_array($result);
	$nr_events = $row['nr_events'];

	return $nr_events;
}

function Is_note_synced($note_id) {
	//funkcia detekuje ci je tato poznamka/ text v minecraft is
	global $con;
	$sql = "SELECT title from tblcustomer_notes where id=$note_id";
	$result = mysqli_query($con, $sql) or die("MySQLi ERROR: " . mysqli_error($con));
	$row = mysqli_fetch_array($result);
	$note_name = $row['title'];

	//contect do Minecraft IS
	//$connection =mysqli_connect(null, "minecraft_db", "eTIZAAMcSL", "minecraft_db", null, "/tmp/mariadb101.sock");
	$connection = mysqli_connect("localhost", "root", "", "minecraft_db", 3306);
	$sql = "SELECT * from notes where note_header='$note_name'";
	//echo $sql;
	$result = mysqli_query($connection, $sql) or die("MySQLi ERROR: " . mysqli_error($connection));
	$nr_rows = mysqli_num_rows($result);
	if ($nr_rows > 0) {
		$is_sync = FALSE;
	} elseif ($nr_rows == 1) {
		$is_sync = TRUE;
	}
	return $is_sync;
}

function GetCustomerName($cust_id) {
	global $con;
	$sql = "SELECT customer_name from tblcustomer where cust_id=$cust_id";
	$result = mysqli_query($con, $sql) or die("MySQLi ERROR: " . mysqli_error($con));
	while ($row = mysqli_fetch_array($result)) {
		$customer_name = $row['customer_name'];
	}
	return $customer_name;
}

function GetTaskText($task_id) {
	global $con;
	$sql = "SELECT task_text from tblcustomer_tasks where id=$task_id";
	$result = mysqli_query($con, $sql) or die("MySQLi ERROR: " . mysqli_error($con));
	while ($row = mysqli_fetch_array($result)) {
		$task_text = $row['task_text'];
	}
	return $task_text;
}

function Task2Sync2MinecraftIS($task_id) {
	//connect to Minecraft db
	//check if there is eis_id for the tasks
	//if not displat sync button

	//find out this is minecraft tasks
	global $con;
	$sql = "SELECT task_category from tblcustomer_tasks where id=$task_id";
	$result = mysqli_query($con, $sql) or die("MySQLi ERROR: " . mysqli_error($con));
	while ($row = mysqli_fetch_array($result)) {
		$category_id = $row['task_category'];
	}

	if ($category_id == 5) {
//yes it is minecraft task
		//$connection = mysqli_connect("mariadb101.websupport.sk", "minecraft_db", "eTIZAAMcSL", "minecraft_db", 3312); // connection to minecraft db
		$connection = mysqli_connect("localhost", "root", "", "minecraft_db", 3306); // connection to minecraft db
		$sql = "SELECT count(*) as nr_of_recs  from to_do where eis_task_id=$task_id";

		$result = mysqli_query($connection, $sql) or die("MySQLi ERROR: " . mysqli_error($connection));
		while ($row = mysqli_fetch_array($result)) {
			$nr_of_recs = $row['nr_of_recs'];
			if ($nr_of_recs == 0) {
//
				//echo "<form action='sync_task.php' method='post'><button name='task_sync'></button></form>";
				echo "<button name='task_sync' onclick=SyncTask($task_id); id='SyncTask_$task_id class='flat-btn-green'>Sync</button></form>";
			}
		}
		mysqli_close($connection);
	} //end if category == 5

}

function CheckGmailAndNotify() {

	$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
	$username = 'tmisura@gmail.com';
	//$password = 'ccndynxnwwgtcyvk';
	$password = 'Thoma$.pa$$w0rd';

	$inbox = imap_open($hostname, $username, $password) or die('Cannot connect to Gmail: ' . imap_last_error());

	$MC = imap_check($inbox);

	$result = imap_fetch_overview($inbox, "1:{$MC->Nmsgs}", 0);
	$counter = 0;
	$seen = 0;
	$unseen = 0;
	foreach ($result as $overview) {
		if ($overview->seen) {
			$counter++;
		} else {
			$unseen++;
		}
	}

	$result = "<ul><li>Total:</li><li>Read: $seen</li><li>Unread: $unseen</li></ul>";

	if (!isset($_SESSION['cache'])) {
		$_SESSION['cache'] = $unseen; //vytvor neexistujuci cache
		echo "<script>toastr.info('you have received <?php $unseen ?> new emails');</script>"; //posli o tom spravu
	} else {
		$new_emails = 0;
		$prev_state = $_SESSION['cache']; //kolko bolo predtym emailov
		$actual_state = $unseen;
		$new_emails = $unseen - $prev_state; //zisti aky je rozdiel

		$_SESSION['cache'] = $actual_state;
		if ($new_emails > 0) {
			echo "<script>toastr.info('you have received $new_emails new email(s)');</script>";

		}

	}

	return $unseen;
	imap_close($inbox);

}

function GetCountofCatMembers($related_to) {
	global $con;
	$sql = "select count(*) as notes_count from tblnotepad where colRelated_to='$related_to'";
	$result = mysqli_query($con, $sql);
	while ($row = mysqli_fetch_array($result)) {
		$notes_count = $row['notes_count'];
	}
	return $notes_count;

}

function GetCountofAllnotes() {
	global $con;
	$sql = "select count(*) as notes_count from tblcustomer_notes";
	$result = mysqli_query($con, $sql);
	while ($row = mysqli_fetch_array($result)) {
		$notes_count = $row['notes_count'];
	}
	return $notes_count;

}

function GetCountofAllPasswords() {
	global $con;
	$sql = "select count(*) as pass_count from tblpasswords";
	$result = mysqli_query($con, $sql);
	while ($row = mysqli_fetch_array($result)) {
		$pass_count = $row['pass_count'];
	}
	return $pass_count;

}

function GetCountofPassCatMemebers($group_name) {
	global $con;
	$sql = "select count(*) as pass_count from tblpasswords where group_name='$group_name'";
	$result = mysqli_query($con, $sql);
	while ($row = mysqli_fetch_array($result)) {
		$pass_count = $row['pass_count'];
	}
	return $pass_count;
}

function GetTaskNotesCount($task_id) {
	global $con;
	$sql = "select count(*) as notes_count from tblcustomer_tasks_comments where task_id=$task_id";
	//cho $sql;
	$result = mysqli_query($con, $sql);
	while ($row = mysqli_fetch_array($result)) {
		$notes_count = $row['notes_count'];
	}
	return $notes_count;
}

function GetCountofAllTasks() {
	global $con;
	$sql = "select count(*) as task_count from tblcustomer_tasks";

	$result = mysqli_query($con, $sql);
	while ($row = mysqli_fetch_array($result)) {
		$task_count = $row['task_count'];
	}
	return $task_count;

}

function GetCountofActiveTasks() {
	global $con;
	$sql = "select count(*) as task_count from tblcustomer_tasks where task_status<>'complete'";

	$result = mysqli_query($con, $sql);
	while ($row = mysqli_fetch_array($result)) {
		$task_count = $row['task_count'];
	}
	return $task_count;

}

function GetCountofTaskPerCat($task_relation) {
	global $con;
	$sql = "select count(*) as task_count from tbltasks where task_relation='$task_relation'";
	$result = mysqli_query($con, $sql);
	while ($row = mysqli_fetch_array($result)) {
		$task_count = $row['task_count'];
	}
	return $task_count;
}

function GetCountofLinksPerCat($category) {
	global $con;
	$sql = "select count(*) as link_count from tbllinks where category='$category'";
	$result = mysqli_query($con, $sql);
	while ($row = mysqli_fetch_array($result)) {
		$link_count = $row['link_count'];
	}
	return $link_count;
}

function GetCountofAllServers() {
	global $con;
	$sql = "select count(*) as servers_count from tblserver_info";
	$result = mysqli_query($con, $sql);
	while ($row = mysqli_fetch_array($result)) {
		$servers_count = $row['servers_count'];
	}
	return $servers_count;
}

function GetCountofAllCustomers() {
	global $con;
	$sql = "select count(*) as customer_count from tblcustomers";
	$result = mysqli_query($con, $sql);
	while ($row = mysqli_fetch_array($result)) {
		$customer_count = $row['customer_count'];
	}
	return $customer_count;
}

function GetCountofAllTradingPlatforms() {
	global $con;
	$sql = "select count(*) as platform_count from tbltrading_platforms";
	$result = mysqli_query($con, $sql);
	while ($row = mysqli_fetch_array($result)) {
		$platform_count = $row['platform_count'];
	}
	return $platform_count;
}

function CustomerName($cust_id) {
	global $con;
	$sql = "SELECT customer_name from tblcustomers where cust_id=$cust_id";
	$result = mysqli_query($con, $sql);
	while ($row = mysqli_fetch_array($result)) {
		$customer_name = $row['customer_name'];
	}
	return $customer_name;
}

function GetCountAllEvents() {
	global $con;
	$sql = "select count(*) as event_count from tblcustomer_events";
	$result = mysqli_query($con, $sql);
	while ($row = mysqli_fetch_array($result)) {
		$event_count = $row['event_count'];
	}
	return $event_count;

}

function GetCountAllTodaysEvents() {
	global $con;
	$sql = "select count(*) as event_count from tblcustomer_events where start_event_date=now()";
	$result = mysqli_query($sql);
	while ($row = mysqli_fetch_array($result)) {
		$event_count = $row['event_count'];
	}
	return $event_count;
}

function NrOfTasks($project_id) {
	global $con;
	$sql = "SELECT count(*) as nr_tasks from tblcustomer_project_tasks  where project_id=$project_id";
	$result = mysqli_query($con, $sql);
	while ($row = mysqli_fetch_array($result)) {
		$nr_tasks = $row['nr_tasks'];
	}
	return $nr_tasks;
}

function draw_calendar($month, $year) {

	/* draw table */
	$calendar = "<table cellpadding='0' cellspacing='0' id='month_calendar'>";

	/* table headings */
	$headings = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
	$calendar .= "<tr class='calendar-row'><td class='calendar-day-head'>" . implode("</td><td class='calendar-day-head'>", $headings) . "</td></tr>";

	/* days and weeks vars now ... */
	$running_day = date('w', mktime(0, 0, 0, $month, 1, $year));
	$days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));
	$days_in_this_week = 1;
	$day_counter = 0;
	$dates_array = array();

	/* row for week one */
	$calendar .= "<tr class='calendar-row'>";

	/* print "blank" days until the first of the current week */
	for ($x = 0; $x < $running_day; $x++):
		$calendar .= "<td class='calendar-day-np'> </td>";
		$days_in_this_week++;
	endfor;

	/* keep going with days.... */
	for ($list_day = 1; $list_day <= $days_in_month; $list_day++):
		$date = $year . "-" . date('m') . "-" . $list_day;
		$calendar .= "<td class='calendar-day' data-date=" . $date . ">";
		/* add in the day number */
		$calendar .= "<div class='day-number'><a href='../events/index.php?view_day=" . $year . "-" . date('m') . "-" . $list_day . "'>" . $list_day . "</a></div>";

		/** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
		$calendar .= str_repeat('<p> </p>', 2);

		$calendar .= '</td>';
		if ($running_day == 6):
			$calendar .= '</tr>';
			if (($day_counter + 1) != $days_in_month):
				$calendar .= "<tr class='calendar-row'>";
			endif;
			$running_day = -1;
			$days_in_this_week = 0;
		endif;
		$days_in_this_week++;
		$running_day++;
		$day_counter++;
	endfor;

	/* finish the rest of the days in the week */
	if ($days_in_this_week < 8):
		for ($x = 1; $x <= (8 - $days_in_this_week); $x++):
			$calendar .= "<td class='calendar-day-np'> </td>";
		endfor;
	endif;

	/* final row */
	$calendar .= '</tr>';

	/* end the table */
	$calendar .= '</table>';

	/* all done, return result */
	return $calendar;
}

function draw_week_calendar() {
	$dt = new DateTime;
	if (isset($_GET['year']) && isset($_GET['week'])) {
		$dt->setISODate($_GET['year'], $_GET['week']);
	} else {
		$dt->setISODate($dt->format('o'), $dt->format('W'));
	}
	$year = $dt->format('o');
	$week = $dt->format('W');
	?>

  <table id="week_view">
      <tr>
        <td class="calendar-day-head" colspan="8">
        <a href="<?php echo $_SERVER['PHP_SELF'] . '?week=' . ($week - 1) . '&year=' . $year; ?>"><i class="fa fa-chevron-left"></i></a> <!--Previous week-->
        <a href="<?php echo $_SERVER['PHP_SELF'] . '?week=' . ($week + 1) . '&year=' . $year; ?>"><i class="fa fa-chevron-right"></i></a> <!--Next week-->
        </td>
      </tr>
      <tr>
        <td class="calendar-day-head"></td>
      <?php
do {
		echo "<td class='calendar-day-head'>" . $dt->format('l') . "<br>" . $dt->format('d M Y') . "</td>\n";
		$dt->modify('+1 day');
	} while ($week == $dt->format('W'));
	?>
      </tr>
   <?php
for ($i = 8; $i < 18; $i++) {
		echo "<tr><td>$i:00</td><td class='calendar-hour'></td><td class='calendar-hour'></td><td class='calendar-hour'></td><td class='calendar-hour'></td><td class='calendar-hour'></td class='calendar-hour'><td class='calendar-hour'></td><td class='calendar-hour'></td></tr>";
	}
	?>
  </table>

  <?php

}

function draw_event_calendar($month, $year) {

	$running_month = date('F', mktime(0, 0, 0, $month, 1, $year));
	// echo "Rok:".$year;
	//echo "Aktualny mesiac:".$running_month;
	/* draw table */
	$calendar = "<table cellpadding='0' cellspacing='0' id='month_calendar'>";

	$previous_month = ($month - 1) > 0 ? $month - 1 : 12;
	$next_month = ($month % 12) + 1;
	$previous_year = $previous_month > $month ? $year - 1 : $year;
	$next_year = $next_month < $month ? $year + 1 : $year;

	$calendar .= "<tr class='calendar-row'><td class='calendar-day-head' colspan='7'><span style='float:left;'><a  style='font-size:15px !important' href='index.php?date=$previous_month-$previous_year'><i class='fa fa-chevron-left' aria-hidden='true'></i></a></span> " . date('F', mktime(0, 0, 0, $month, 10)) . "," . $year . " <span style='float:right;'><a href='index.php?date=$next_month-$next_year' style='font-size:15px !important'><i class='fa fa-chevron-right' aria-hidden='true'></i></a></span></td></tr>";
	/* table headings */
	$headings = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
	$calendar .= "<tr class='calendar-row'><td class='calendar-day-head'>" . implode("</td><td class='calendar-day-head'>", $headings) . "</td></tr>";

	/* days and weeks vars now ... */
	$running_day = date('w', mktime(0, 0, 0, $month, 1, $year));
	$today = date("Y-m-d");
	$days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));
	$days_in_this_week = 1;
	$day_counter = 0;
	$dates_array = array();

	/* row for week one */
	$calendar .= "<tr class='calendar-row'>";

	/* print "blank" days until the first of the current week */
	for ($x = 0; $x < $running_day; $x++):
		$calendar .= "<td class='calendar-day-np'> </td>";
		$days_in_this_week++;
	endfor;

	/* keep going with days.... */
	for ($list_day = 1; $list_day <= $days_in_month; $list_day++):

		if ($list_day > 0 and $list_day < 10) {$list_day = '0' . $list_day;}
		global $con;
		$sql = "SELECT * from tblcustomer_events where event_date='" . $year . "-" . $month . "-" . $list_day . "'";
		$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
		$num = mysqli_num_rows($result);
		$date = $year . "-" . date('m') . "-" . $list_day;
		$event_date = $year . "-" . $month . "-" . $list_day;

		//$calendar.= "<td class='calendar-day' data-date=".$date.">";
		if ($num > 0) // mame nejaky event planovany na tento den

	{
			//something we have here
			/*$calendar.= '<td class="calendar-event-day"  onclick="alert(\'<?php echo $date; ?>\');" data-date='.$date.'>';*/
			$calendar .= "<td class='calendar-event-day'>";
		} elseif ($date == $today) {
		//just today
		/*$calendar.= '<td class="calendar-today"  onclick="alert(\'<?php echo $date; ?>\');" data-date='.$date.'>';*/
		$calendar .= '<td class="calendar-today">';
	} else {
		//no event for this day
		/*$calendar.= '<td class="calendar-day"  onclick="alert(\'<?php echo $date; ?>\');" data-date='.$date.'>';*/
		$calendar .= "<td class='calendar-day'>";
	}

	/* add in the day number */
	// $calendar.= "<div class='add-event'><a href='event.php?action=new&day=".$year."-".$month."-".$list_day."' class='btn-small'><span style='font-size:18px; font-weight:bold'>+</span></a></div><div class='day-number'><a href='events.php?view_day=".$year."-".$month."-".$list_day."'>".$list_day."</a></div>";
	$calendar .= "<div class='day-number' data-date='$event_date'>" . $list_day . "</div>";
	/** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
	// $calendar.= str_repeat('<p> </p>',2);

	$calendar .= '</td>';
	if ($running_day == 6):
		$calendar .= '</tr>';
		if (($day_counter + 1) != $days_in_month):
			$calendar .= "<tr class='calendar-row'>";
		endif;
		$running_day = -1;
		$days_in_this_week = 0;
	endif;
	$days_in_this_week++;
	$running_day++;
	$day_counter++;
	endfor;

	/* finish the rest of the days in the week */
	if ($days_in_this_week < 8):
		for ($x = 1; $x <= (8 - $days_in_this_week); $x++):
			$calendar .= "<td class='calendar-day-np'> </td>";
		endfor;
	endif;

	/* final row */
	$calendar .= '</tr>';

	/* end the table */
	$calendar .= '</table>';

	/* all done, return result */
	return $calendar;
}

function draw_event_calendar_view($month, $year) {
	global $con;
	$running_month = date('F', mktime(0, 0, 0, $month, 1, $year));
	// echo "Rok:".$year;
	//echo "Aktualny mesiac:".$running_month;

	/* draw table */
	$calendar = "<table cellpadding='0' cellspacing='0' id='month_calendar'>";

	$previous_month = ($month - 1) > 0 ? $month - 1 : 12;
	$next_month = ($month % 12) + 1;
	$previous_year = $previous_month > $month ? $year - 1 : $year;
	$next_year = $next_month < $month ? $year + 1 : $year;

	/*$prev_month=$month-1;
 $next_moth=$month+1;*/

// echo "$next_moth";

	$calendar .= "<tr class='calendar-row'><td class='calendar-day-head' colspan='7'><span style='float:left;'><a  style='font-size:15px !important' href='serveris.php?date=$previous_month-$previous_year'>&laquo;</a></span> " . date('F', mktime(0, 0, 0, $month, 10)) . "," . $year . " <span style='float:right;'><a href='serveris.php?date=$next_month-$next_year' style='font-size:15px !important'>&raquo;</a></span></td></tr>";
	/* table headings */
	$headings = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
	$calendar .= "<tr class='calendar-row'><td class='calendar-day-head'>" . implode("</td><td class='calendar-day-head'>", $headings) . "</td></tr>";

	/* days and weeks vars now ... */
	$running_day = date('w', mktime(0, 0, 0, $month, 1, $year));
	$today = date("Y-m-d");
	$days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));
	$days_in_this_week = 1;
	$day_counter = 0;
	$dates_array = array();

	/* row for week one */
	$calendar .= "<tr class='calendar-row'>";

	/* print "blank" days until the first of the current week */
	for ($x = 0; $x < $running_day; $x++):
		$calendar .= "<td class='calendar-day-np'> </td>";
		$days_in_this_week++;
	endfor;

	/* keep going with days.... */
	for ($list_day = 1; $list_day <= $days_in_month; $list_day++):

		if ($list_day > 0 and $list_day < 10) {$list_day = '0' . $list_day;}
		global $con;
		$sql = "SELECT * from tblcustomer_events where event_date='" . $year . "-" . $month . "-" . $list_day . "'";
		$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));

		$num = mysqli_num_rows($result);
		$date = $year . "-" . date('m') . "-" . $list_day;
		//echo $today;
		if ($num > 0) // mame nejaky event planovany na tento den

	//$calendar.= "<td class='calendar-day' data-date=".$date.">";
	{

			$calendar .= '<td class="calendar-event-day"  onclick="alert();" data-date=' . $date . '>';
		} elseif ($date == $today) {

		$calendar .= '<td class="calendar-today"  onclick="alert();" data-date=' . $date . '>';
	} else {
		$calendar .= '<td class="calendar-day"  onclick="alert();" data-date=' . $date . '>';
	}

	/* add in the day number */
	$calendar .= "<div class='day-number'><a href='events/day_events.php?view_day=" . $year . "-" . $month . "-" . $list_day . "'>" . $list_day . "</div>";

	/** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
	// $calendar.= str_repeat('<p> </p>',2);

	$calendar .= '</td>';
	if ($running_day == 6):
		$calendar .= '</tr>';
		if (($day_counter + 1) != $days_in_month):
			$calendar .= "<tr class='calendar-row'>";
		endif;
		$running_day = -1;
		$days_in_this_week = 0;
	endif;
	$days_in_this_week++;
	$running_day++;
	$day_counter++;
	endfor;

	/* finish the rest of the days in the week */
	if ($days_in_this_week < 8):
		for ($x = 1; $x <= (8 - $days_in_this_week); $x++):
			$calendar .= "<td class='calendar-day-np'> </td>";
		endfor;
	endif;

	/* final row */
	$calendar .= '</tr>';

	/* end the table */
	$calendar .= '</table>';

	/* all done, return result */
	return $calendar;
}

function dateDiff($d1, $d2) {
	// Return the number of days between the two dates:

	return round(abs(strtotime($d1) - strtotime($d2)) / 86400);

} // end function dateDiff

function list_of_note_cust() {
	//zoznam vsetkych zakaznikov, ktory maju nejaku note
	$note_cust = "<ul class='list_of_cust'>";
	global $con;
	$sql = "SELECT DISTINCT a.cust_id,b.customer_name from tblcustomer_notes a, tblcustomers b WHERE a.cust_id = b.cust_id and b.customer_status='active'";
	//echo $sql;
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	while ($row = mysqli_fetch_array($result)) {
		$cust_id = $row['cust_id'];
		$customer_name = $row['customer_name'];
		$note_cust .= "<li><a href='index.php?cust_id=$cust_id' class='btn-small'>$customer_name</a></li>";
	}
	$note_cust .= "<li><a href='index.php?&cust_id=all' class='btn-small'>All</a></li>";
	$note_cust .= "</ul>";

	echo $note_cust;
}

function list_of_task_cust() {
	//zoznam vsetkych zakaznikov, ktory maju nejaku note
	global $con;
	$task_cust = "<ul>";
	$sql = "SELECT DISTINCT a.cust_id,b.customer_name,b.customer_status from tblcustomer_tasks a, tblcustomers b WHERE a.cust_id = b.cust_id and b.customer_status='active'";
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	while ($row = mysqli_fetch_array($result)) {
		$cust_id = $row['cust_id'];
		$customer_name = $row['customer_name'];
		$task_cust .= "<li><a href='index.php?view=all_tasks&cust_id=$cust_id' class='btn-small'>$customer_name</a></li>";
	}
	$task_cust .= "</ul>";

	echo $task_cust;
}

function list_of_task_cats() {
	//zoznam vsetkych zakaznikov, ktory maju nejaku note
	global $con;
	$task_cats = "<ul>";
	$sql = "SELECT DISTINCT b.cat_name,  b.cat_id, a.task_category from tblcustomer_tasks a, tblcustomer_categories b WHERE a.task_category = b.cat_id and a.task_status not in ('complete', 'void')";
	//$sql="SELECT DISTINCT a.task_category,a.task_status, b.cat_name from tblcustomer_tasks a, tblcustomer_categories b WHERE a.task_category = b.cat_id and a.task_status not in ('complete', 'void')";
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	while ($row = mysqli_fetch_array($result)) {
		$cat_id = $row['task_category'];
		$category_name = $row['cat_name'];
		$task_cats .= "<li><a href='index.php?view=all_tasks&cat_id=$cat_id' class='btn-small'>$category_name</a></li>";
	}
	$task_cats .= "</ul>";

	echo $task_cats;
}

function list_of_events_by_cust() {
	global $con;
	$event_cust = "<ul>";
	$sql = "SELECT DISTINCT a.cust_id,b.customer_name from tblcustomer_events a, tblcustomers b WHERE a.cust_id = b.cust_id";
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	while ($row = mysqli_fetch_array($result)) {
		$cust_id = $row['cust_id'];
		$customer_name = $row['customer_name'];
		$event_cust .= "<li><a href='index.php?view=all_events&cust_id=$cust_id' class='btn-small'>$customer_name</a></li>";
	}
	$event_cust .= "<li><a href='index.php' class='btn-small'>All</a></li>";
	$event_cust .= "</ul>";

	echo $event_cust;

}

function show_event_detail($event_id) {
	global $con;
	$sql = "SELECT a.event_id, a.cust_id, a.event_title, a.event_description, a.is_meeting,a.start_event_date,a.end_event_date,a.start_event_time,a.end_event_time,a.event_date, b.cust_id, b.customer_name FROM tblcustomer_events a, tblcustomers b WHERE a.event_id=$event_id and a.cust_id=b.cust_id;";
	//echo "$sql";
	$result = mysqli_query($con, $sql) or die('MySQL ERROR: ' . mysqli_error($con));
	$row = mysqli_fetch_array($result);{

		$customer_name = $row['customer_name'];
		$event_date = $row['event_date'];
		$start_event_date = $row['start_event_date'];
		$end_event_date = $row['end_event_date'];
		$event_title = $row['event_title'];
		$end_event_time = $row['end_event_time'];
		$start_event_time = $row['start_event_time'];
		$event_description = $row['event_description'];

		$d1 = $row['start_event_date'];
		$d2 = $row['end_event_date'];

		$show_event = "<table id='view_event'>";
		$show_event .= "<tr>";
		$show_event .= "<td>Customer:</td><td><input type='text' value='$customer_name';'></td>";
		$show_event .= "</tr>";
		$show_event .= "<tr>";
		$show_event .= "<td>Event date (start):</td><td><input type='text' value='$start_event_date'></td>";
		$show_event .= "</tr>";
		$show_event .= "<tr>";
		$show_event .= "<td>Event date (end):</td><td><input type='text' value='$end_event_date'></td>";
		$show_event .= "</tr>";
		$show_event .= "<tr>";
		$show_event .= "<td>Duration (days):</td><td><input type='text' value='" . dateDiff($d2, $d1) . "' class='yesno-input'></td>";
		$show_event .= "</tr>";
		$show_event .= "<tr>";
		$show_event .= "<td>Event time (start):</td><td><input type='text' value='$start_event_time' class='time-input'></td>";
		$show_event .= "</tr>";
		$show_event .= "<tr>";
		$show_event .= "<td>Event time (end):</td><td><input type='text' value='$end_event_time' class='time-input'></td>";
		$show_event .= "</tr>";
		$show_event .= "<tr>";
		$show_event .= "<td>Event description:</td><td><div class='textarea'>" . nl2br($event_description) . "</div></td>";
		$show_event .= "</tr>";
		$show_event .= "<tr>";

		$is_meeting = $row['is_meeting'];
		if ($is_meeting == 1) {$is_meeting = 'yes';} else { $is_meeting = 'no';}

		$show_event .= "<td>Meeting:</td><td><input type='text' value='$is_meeting' class='yesno-input'></td>";
		$show_event .= "</tr>";
		$show_event .= "</table>";

		echo $show_event;

	}

}

function makeLinks($str) {
	$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
	$urls = array();
	$urlsToReplace = array();
	if (preg_match_all($reg_exUrl, $str, $urls)) {
		$numOfMatches = count($urls[0]);
		$numOfUrlsToReplace = 0;
		for ($i = 0; $i < $numOfMatches; $i++) {
			$alreadyAdded = false;
			$numOfUrlsToReplace = count($urlsToReplace);
			for ($j = 0; $j < $numOfUrlsToReplace; $j++) {
				if ($urlsToReplace[$j] == $urls[0][$i]) {
					$alreadyAdded = true;
				}
			}
			if (!$alreadyAdded) {
				array_push($urlsToReplace, $urls[0][$i]);
			}
		}
		$numOfUrlsToReplace = count($urlsToReplace);
		for ($i = 0; $i < $numOfUrlsToReplace; $i++) {
			$str = str_replace($urlsToReplace[$i], "<a href=\"" . $urlsToReplace[$i] . "\">" . $urlsToReplace[$i] . "</a> ", $str);
		}
		return $str;
	} else {
		return $str;
	}
}

function all_project_task_complete($project_id) {
	global $con;
	$sql = "SELECT task_status from tblcustomer_project_tasks where project_id=$project_id";
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	while ($row = mysqli_fetch_array($result)) {
		$task_status = $row['task_status'];
	}
}

function GetCatList() {
	global $con;
	$list = "<ul>";
	$sql = "SELECT DISTINCT cat_name, cat_id from tblcustomer_categories ORDER BY cat_name ASC";
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	while ($row = mysqli_fetch_array($result)) {
		$list .= "<li>" . $row['cat_name'] . "</li>";

	}
	$list .= "</ul>";
	return $list;
}

function GetNoteIdsByTags($cat_id) {
	//zoznam vsetkych note id pre danu kateogriu
	global $con;
	$sql = "SELECT GROUP_CONCAT(note_id) as ids from tblcustomer_notes_tags where cat_id=$cat_id";
	//echo $sql;
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	while ($row = mysqli_fetch_array($result)) {
		$ids = $row['ids'];
	}
	return $ids;
}

function GetNoteTagsIds($note_id) {
//zoznam vsetkych ideciek kategorii pre danu note id
	global $con;
	$sql = "SELECT GROUP_CONCAT(cat_id) as cat_ids from tblcustomer_notes_tags where note_id=$note_id";
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	while ($row = mysqli_fetch_array($result)) {
		$cat_ids = $row['cat_ids'];
	}
	return $cat_ids;
}

function GetTagList($note_id) {
	global $con;
	$sql = "SELECT cat_id from tblcustomer_notes_tags where note_id=$note_id";
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	$tags_cats = array();
	while ($row = mysqli_fetch_array($result)) {
		$cat_id = $row['cat_id'];
		array_push($tags_cats, $cat_id);
	}
	return $tags_cats;
}

function GetCatName($cat_id) {
	if ($cat_id == 0) {
		$category_name = "myself";
	} else {
		$sql = "SELECT category_name from tblcustomer_notes_category WHERE cat_id=$cat_id";
		global $con;
		$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
		while ($row = mysqli_fetch_array($result)) {
			$category_name = $row['category_name'];
		}
	}
	return $category_name;

}

function GetCustName($cust_id) {
	global $con;
	$sql = "SELECT customer_name from tblcustomers where cust_id=$cust_id";
	//echo $sql;
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	while ($row = mysqli_fetch_array($result)) {
		$customer_name = $row['customer_name'];
	}
	return $customer_name;
}

function convertHashtags_old($str) {
	$regex = "/(#)+[a-zA-Z0-9]+/";
	$str = preg_replace($regex, '<a href="wall.php?tag=$2">#$2</a>', $str);
	return ($str);
}

function convertHashtags($str) {
	{
		$parsedMessage = preg_replace(array('/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?Â«Â»â€śâ€ťâ€â€™]))/', '/(^|[^a-z0-9_])@([a-z0-9_]+)/i', '/(^|[^a-z0-9_])#([a-z0-9_]+)/i'), array('<a href="$1" target="_blank">$1</a>', '$1<a href="">@$2</a>', '$1<a class="hashtag" href="wall.php?hashtag=$2">#$2</a>'), $str);
		return $parsedMessage;
	}
}

function GetLatestTradingPlatformNote() {
	global $con;
	$sql = "SELECT MAX(note_id) as max_id from tbltrading_platform_notes";
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	while ($row = mysqli_fetch_array($result)) {
		$max_id = $row['max_id'];

	}
	return $max_id;
}

function GetLatestPasswordNote() {
	global $con;
	$sql = "SELECT MAX(id) as max_id from tblpassword_notes";
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	while ($row = mysqli_fetch_array($result)) {
		$max_id = $row['max_id'];

	}
	return $max_id;
}

function GetLatestTradingPlatform() {
	global $con;
	$sql = "SELECT MAX(id) as max_id from tbltrading_platforms";
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	while ($row = mysqli_fetch_array($result)) {
		$max_id = $row['max_id'];

	}
	return $max_id;
}

function GetLatestCategoryId() {
	global $con;
	$sql = "SELECT MAX(cat_id) as max_id from tblcustomer_notes_category";
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	while ($row = mysqli_fetch_array($result)) {
		$max_id = $row['max_id'];

	}
	return $max_id;
}

function GetLatestCustomer() {
	$sql = "SELECT MAX(id) as max_id from tblcustomers";
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	while ($row = mysqli_fetch_array($result)) {
		$max_id = $row['max_id'];

	}
	return $max_id;
}

function GetLatestNote() {
	$sql = "SELECT MAX(id) as max_id from tblcustomer_notes";
	global $con;
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	while ($row = mysqli_fetch_array($result)) {
		$max_id = $row['max_id'];

	}
	return $max_id;
}

function GetLatestPassword() {
	$sql = "SELECT MAX(PassID) as max_id from tblpasswords";
	global $con;
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	while ($row = mysqli_fetch_array($result)) {
		$max_id = $row['max_id'];

	}
	return $max_id;
}

function GetLatestTask() {
	$sql = "SELECT MAX(id) as max_id from tblcustomer_tasks";
	global $con;
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	while ($row = mysqli_fetch_array($result)) {
		$max_id = $row['max_id'];

	}
	return $max_id;
}

function GetLatestTaskNote() {
	$sql = "SELECT MAX(id) as max_id from tblcustomer_tasks";
	global $con;
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	while ($row = mysqli_fetch_array($result)) {
		$max_id = $row['max_id'];

	}
	return $max_id;
}

function GetUnreadMessages() {
	$sql = "SELECT COUNT(*) unread_mess from tblcustomer_notes_history where is_read=0";
	global $con;
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	while ($row = mysqli_fetch_array($result)) {
		$unread_mess = $row['unread_mess'];
	}
	if ($unread_mess > 99) {
		$unread_mess = "9+";
	}
	return $unread_mess;
}

function GetTaskCategoryName($cat_id) {
	$sql = "SELECT cat_name from tblcustomer_categories where cat_id=$cat_id";
	//echo $sql;
	global $con;
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	while ($row = mysqli_fetch_array($result)) {
		$category_name = $row['cat_name'];

	}
	return $category_name;
}

function GetLatestCat() {
	$sql = "SELECT MAX(cat_id) as max_id from tblcustomer_categories";
	global $con;
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	while ($row = mysqli_fetch_array($result)) {
		$max_id = $row['max_id'];

	}
	return $max_id;
}

function GetLatestEvent() {
	$sql = "SELECT MAX(event_id) as max_id from tblcustomer_events";
	global $con;
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	while ($row = mysqli_fetch_array($result)) {
		$max_id = $row['max_id'];

	}
	return $max_id;
}

function GetLatestReminder() {
	$sql = "SELECT MAX(reminder_id) as max_id from tblcustomer_reminders";
	global $con;
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	while ($row = mysqli_fetch_array($result)) {
		$max_id = $row['max_id'];

	}
	return $max_id;
}

/**
 * Get working days between two dates, with custom off day
 *
 * @param Date $startDate End Date
 * @param Date $endDate To date
 * @param Array $holidays Week Number in Single dimension array
 * @return integer
 */

function getWorkingDays_customOffDay($startDate, $endDate, $holidays) {

	$day = 86400; // Day in seconds

	$sTime = strtotime($startDate); // Start as time
	$eTime = strtotime($endDate); // End as time
	$numDays = round(($eTime - $sTime) / $day) + 1;

	$days = array();

	for ($d = 0; $d < $numDays; $d++) {
		$day_number = date('N', ($sTime + ($d * $day)));
		if (!in_array($day_number, $holidays)) {
			$days[] = date('Y-m-d N', ($sTime + ($d * $day)));
		}
	}

	return count($days);

}

function returnDates($fromdate, $todate) {
	$fromdate = \DateTime::createFromFormat('Y-m-d', $fromdate);
	$todate = \DateTime::createFromFormat('Y-m-d', $todate);
	return new \DatePeriod(
		$fromdate,
		new \DateInterval('P1D'),
		$todate->modify('+1 day')
	);
}

/*function nr_of_articles($cat_id) {
$sql="SELECT count(*) as total_nr from tblcustomer_notes where cat_id=$cat_id";
global $con;
$result=mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error($con));
while ($row = mysqli_fetch_array($result)) {
$total_nr=$row['total_nr'];

}
return $total_nr;

}*/

function nr_of_articles($cat_id) {
	//zoznam ideciek pre danu kategoriu
	global $con;
	$sql = "SELECT GROUP_CONCAT(note_id) as ids from tblcustomer_notes_tags where cat_id=$cat_id";
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	while ($row = mysqli_fetch_array($result)) {
		$ids = $row['ids'];
	}

	if ($ids == "") {
		$sql = "SELECT count(*) as nr_of_articles from tblcustomer_notes where id IN (0)";
	} else {
		$sql = "SELECT count(*) as nr_of_articles from tblcustomer_notes where id IN ($ids)";
	}
	//teraz ich spocitame
	//$sql="SELECT count(*) as nr_of_articles from tblcustomer_notes where id IN ($ids)";
	// echo $sql;
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	while ($row = mysqli_fetch_array($result)) {
		$nr_of_articles = $row['nr_of_articles'];

	}
	return $nr_of_articles;
}

function Tasks($task_status) {
//echo $task_status;
	$sql = "SELECT * from tblcustomer_tasks where task_status='" . $task_status . "'";
//echo $sql;
	global $con;
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	while ($row = mysqli_fetch_array($result)) {
		$task_text = $row['task_text'];
		$task_id = $row['id'];
		echo "<div class='scrum_item' id='scrum_item_$task_id' draggable='true'>$task_text</div>";
	}
}

function AddNewCategory($cat_name) {
	global $con;
	$category_name = mysqli_real_escape_string($cat_name);
	$sql = "INSERT IGNORE into tblcustomer_notes_category (category_name) VALUES ('$category_name')";
	//echo $sql;
	$result = mysqli_query($con, $sql) or die("MySQLi ERROR: " . mysqli_error($con));

	$app_name = "notepad";
	$curr_action = "create_category";
	$text_logu = "new category has been created";

	$sql = "INSERT IGNORE into tblcustomer_categories (cat_name,cat_description,cat_app) VALUES ('$category_name','','$app_name')";
	$result = mysqli_query($con, $sql) or die("MySQLi ERROR: " . mysqli_error($con));

	$sql = "INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$app_name','$text_logu','$curr_date',0)";
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
}

function GetMobileMenu() {
	echo "<ul class='menu-box'><li><a href='password${page_ext_qry}id=$id'>Show</a></li><li><a href='password_edit${page_ext_qry}id=$id'>Edit</a></li><li><a href='password_archive${page_ext_qry}id=$id'>Archive</a></li><li><a href='password_clone${page_ext_qry}id=$id'>Clone</a></li><li><a href='password_delete${page_ext_qry}id=$id' class='remove'>Delete</a></li></ul>";
}