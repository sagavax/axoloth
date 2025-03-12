<?php
include "../include/dbconnect.php";

if (isset($_POST['view_event'])) {
	$event = $_POST['event_id'];
	header("location:event.php?event_id=" . $event);
}

if (isset($_POST['edit_event'])) {
	$event = $_POST['event_id'];
	header("location:event_edit.php?event_id=" . $event);
}

if (isset($_POST['delete_event'])) {
	$event = $_POST['event_id'];
	$curr_date = date('Y-m-d H:i:s');
	$sql = "DELETE from tblcustomer_events where event_id=$event";
	mysqli_query($con, $sql) or die("MySQLi ERROR: " . mysqli_error($con));

	//$link1 = mysqli_connect("localhost","root","","brick_wall");
	$link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
	$diary_text = "EIS: Event s id $event bol vymazany";
	$sql = "INSERT INTO diary (diary_text, date_added,location,isMobile,is_read) VALUES ('$diary_text','$curr_date','',0,0)";
	$result = mysqli_query($link1, $sql) or die("MySQLi ERROR: " . mysqli_error($link1));
	mysqli_close($link1);

	echo "<script>alert(' The event has been removed');
        window.location='index.php';
        </script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8" />
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="../css/style_new.css?<?php echo time() ?>" type="text/css">
    <link rel="stylesheet" href="../css/event.css?<?php echo time() ?>" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Axoloth - Event manager</title>
    <?php include "../include/icon.php"?>
</head>

<body>
    <?php
include '../include/header.php';
?>


    <div id="layout">
        <div class=" fab-container">
            <div class="fab fab-icon-holder">
                <a href="event_add.php"><i class="fa fa-plus"></i></a>
            </div>
        </div><!-- fab container -->

        <div class="events_container">
            <div class="events_wrapper">
                <div class="events_filter">
                    <ul>
                        <li><button class="flat-btn" id="filter_this_year" type="button">This year</button></li>
                        <li><button class="flat-btn" id="filter_this_month" type="button">This month</button></li>
                        <li><button class="flat-btn" id="filter_this_week" type="button">This week</button></li>
                        <li><button class="flat-btn" id="filter_today"type="button">Today</button></li>
                    </ul>
                </div><!-- event filter -->

                <div class="events_calender">
                    <?php
if (isset($_GET['calendar'])) {
	$view = $_GET['calendar'];
	if ($view == "month_view") {
		$month = idate('m');
		$year = date('Y');
		if ((isset($_GET['date'])) && ($_GET['date'])) {
			list($month, $year) = explode('-', $_GET['date']);
		}
		echo draw_event_calendar($month, $year); //mesacny pohlad
	} elseif ($view == "week_view") {
		echo draw_week_calendar();
	}
} else {
	$month = idate('m');
	$year = date('Y');
	if ((isset($_GET['date'])) && ($_GET['date'])) {
		list($month, $year) = explode('-', $_GET['date']);
	}
	echo draw_event_calendar($month, $year); //mesacny pohlad
}
?>

                </div><!-- calendar -->

                <div class="event_list">
                    <?php
//zoznam vsetkych eventov za aktualny mesiac
$sql = "SELECT * from tblcustomer_events where YEAR(event_date)=YEAR(CURRENT_DATE()) AND MONTH(event_date)=MONTH(CURRENT_DATE()) ORDER BY event_id DESC";

$i = 0;
$result = mysqli_query($con, $sql) or die("MySQLi ERROR: " . mysqli_error($con));
$nr_of_events = mysqli_num_rows($result);

//echo $sql;

if ($nr_of_events == 0) {
	echo "<h3>No events for this day</h3>";
}

while ($row = mysqli_fetch_array($result)) {
	$event_id = $row['event_id'];
	$event_title = $row['event_title'];
	$start_event_date = $row['start_event_date'];
	$end_event_date = $row['end_event_date'];
	$start_time = $row['start_event_time'];
	$end_time = $row['end_event_time'];
	$event_color = $row['event_color'];

	echo "<div class='event'  style='border-left:5px " . $event_color . " solid' id='$event_id'><div class='event_title'>$event_title</div>";

	if ($start_event_date == $end_event_date) {
		//event je v tom istom dni tak zobraz cas konca

		echo "<div class='event_start'>$start_event_date</div><div class='event_time'>$start_time</div><div class='event_time'>$end_time</div>";
	} else {
		echo "<div class='event_start'>$start_event_date</div><div class='event_end'>$end_event_date</div>";
	}
	//echo "<div class='event_actions'><<ul><li><a href='event.php?event_id=$event_id' class='flat-btn'><i class='fa fa-eye'></i></a></li><li><a href='event_edit.php?event_id=$event_id' class='flat-btn'><i class='fas fa-edit'></i></a></li><li><a href='event_remove.php?event_id=$event_id' class='flat-btn'><i class='fa fa-times'></i></a></li></ul></div>";
	//echo " </div>";

	echo "<div class='event_actions'><form action='' method='post'><input type='hidden' name='event_id' value=$event_id><ul><li><button name='view_event' class='flat-btn'><i class='fa fa-eye'></i></button></li><li><button name='edit_event' class='flat-btn'><i class='fas fa-edit'></i></button></li><li><button name='delete_event' class='flat-btn'><i class='fa fa-times'></i></button></li></ul></form></div>";
	echo " </div>";

}

if (isset($_GET['view_day'])) {
	echo "<div class='add_event_for_curr_day'><div class='fab-icon-holder'><a href='event_add.php?day=" . $_GET['view_day'] . "'><i class='fa fa-plus'></i></a></div></div>";
}

?>
                </div><!-- event_list -->
            </div><!--events wrapper -->
        </div>
    </div><!--layout -->
    <script src="../js/events.js"></script>
</body>
</html>
<!-- Copyright

https://codepen.io/just_monk/full/ZavoQq



                            -->