<?php session_start()?>
<?php include "../include/dbconnect.php";?>
<?php include "../include/functions.php";?>


<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>


<?php
if (isset($_POST['add_event'])) {
	//quick mark task as complete
	$start_event_day = $_POST['start_event_day'];
	header('location:event_add.php?day=' . $_POST['start_event_day']);
	//header('location:event.php?action=new');
}

?>

<!DOCTYPE html>
<html>
  <head>
      <meta charset="utf-8" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="../css/style_new.css?<?php echo time() ?>" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>EIS - Event manager</title>
    <?php include "../include/icon.php"?>
  </head>
      <body>
            <div id="header"> <!--header -->
                <div class="header-logo">e.i.s.</div> <!--logo -->
                    <div class="header-menu"> <!--menu-->
                    <?php
include "../include/menu.php";
?>
                </div><!--menu-->
          </div> <!--end of header -->

          <div class="half_column">

               <ul>
                    <li><a href="index.php?calendar=month_view" class="flat-btn">This month</a></li>
                    <li><a href="index.php?calendar=week_view" class="flat-btn">This week</a></li>
                    <li><a href="index.php?calendar=today" class="flat-btn">Today</a></li>
                    <li><a href="event_add.php" class="flat-btn">New event</a></li>
                </ul>
                 <div style="clear:both"></div>
        </div>
            <div class="half_column"></div>

        </div>


    <div id="layout">

        <div class="half_column">
            <div id="event_calendar">
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

            </div>
        </div>

        <div class="half_column">
                     <div id="view_event_wrap">

                        <?php
if (isset($_GET['view_day'])) {
	$event_day = $_GET['view_day'];
	// $action=$_GET['action'];
	$i = 0;
	$sql = "SELECT * from tblcustomer_events where event_date='$event_day'";
	//echo $sql;

	$result = mysqli_query($con, $sql) or die("MySQLi ERROR: " . mysqli_error($con));
	$nr_of_events = mysqli_num_rows($result);

	if ($nr_of_events == 0) {
		echo "<h2>No events for this day. Would you like to create a new one ?<form action='' method='post'><input type='hidden' name='start_event_day' value='$event_day'><button type='submit' name='add_event' class='flat-btn'><i class='fa fa-plus'></i></button></form></h2>";
	}
	echo "<ul>";
	while ($row = mysqli_fetch_array($result)) {
		$event_id = $row['event_id'];
		$event_title = $row['event_title'];
		$start_event_date = $row['start_event_date'];
		$end_event_date = $row['end_event_date'];
		$start_time = $row['start_event_time'];
		$end_time = $row['end_event_time'];
		$event_color = $row['event_color'];

		echo "<li>";
		echo "<div class='event' style='border-left:5px " . $event_color . " solid' id='$event_id'><div class='event_title'>$event_title</div>";

		if ($start_event_date == $end_event_date) {
			//event je v tom istom dni tak zobraz cas konca

			echo "<div class='event_start'>$start_event_date</div><div class='event_time'>$start_time</div><div class='event_time'>$end_time</div>";
		} else {
			echo "<div class='event_start'>$start_event_date</div><div class='event_end'>$end_event_date</div>";
		}
		echo "<div class='event_actions'><ul><li><a href='event.php?event_id=$event_id' class='flat-btn'>View</a></li><li><a href='event_edit.php?event_id=$event_id' class='flat-btn'>Edit</a></li><li><a href='event_remove.php?event_id=$event_id' class='flat-btn'>Remove</a></li></ul></div>";
		echo " </div>";
		echo "</li>";

	}

	echo "</ul>";

	echo "</div>";

} elseif (isset($_GET['calendar'])) {
	if ($_GET['calendar'] == 'month_view') {

		$curr_month = date('m');
		$curr_year = date('Y');

		$sql = "select * from tblcustomer_events where MONTH(start_event_date)=$curr_month and YEAR(start_event_date)=$curr_year";
		//echo $sql;
		$result = mysqli_query($con, $sql) or die("MySQLi ERROR: " . mysqli_error($con));
		$nr_of_events = mysqli_num_rows($result);
		if ($nr_of_events == 0) {
			echo "<h2>No events for this month</h2>";
		} else {

			echo "<ul>";
			while ($row = mysqli_fetch_array($result)) {
				$event_id = $row['event_id'];
				$event_title = $row['event_title'];
				$start_event_date = $row['start_event_date'];
				$end_event_date = $row['end_event_date'];
				$event_color = $row['event_color'];
				$start_time = $row['start_event_time'];
				$end_time = $row['end_event_time'];

				echo "<li>";
				echo "<div class='event' style='border-left:5px " . $event_color . " solid' id='$event_id'><div class='event_title'>$event_title</div>";

				if ($start_event_date == $end_event_date) {
					//event je v tom istom dni tak zobraz cas konca

					echo "<div class='event_start'>$start_event_date</div><div class='event_time'>$start_time</div><div class='event_time'>$end_time</div>";
				} else {
					echo "<div class='event_start'>$start_event_date</div><div class='event_end'>$end_event_date</div>";
				}
				echo "<div class='event_actions'><ul><li><a href='event.php?event_id=$event_id' class='flat-btn'>View</a></li><li><a href='event_edit.php?event_id=$event_id' class='flat-btn'>Edit</a></li><li><a href='event_remove.php?event_id=$event_id' class='flat-btn'>Remove</a></li></ul></div>";
				echo " </div>";
				echo "</li>";

			}

			echo "</ul>";

			echo "<div class='add_new_event_wrap'><div class='new_event_action_wrap'><span>Add new event</span><form action='' method='post'><button type='submit' name='add_event' class='flat-btn'><i class='fa fa-plus'></i></button></form></div></div>";
		}
	} elseif ($_GET['calendar'] == 'week_view') {
		//week view
		// echo get_list_of_week_events();
		$today = date('Y-m-d');
		$dt = new DateTime();
		$dates = [];
		$dates = array();
		for ($d = 1; $d <= 7; $d++) {
			$dt->setISODate($dt->format('o'), $dt->format('W'), $d);
			$dates[$dt->format('D')] = $dt->format('Y-m-d');
		}

		foreach ($dates as $key => $value) {
			$dates[$key] = $value;
			// echo $value."<br>";
			global $con;
			$sql = "SELECT * FROM tblcustomer_events WHERE start_event_date='$value'";
			//echo $sql."<br>";
			$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
			//echo "<li>$sql</li>";
			echo "<ul>";
			while ($row = mysqli_fetch_array($result)) {
				$event_id = $row['event_id'];
				$event_title = $row['event_title'];
				$start_event_date = $row['start_event_date'];
				$end_event_date = $row['end_event_date'];
				$event_color = $row['event_color'];
				$start_time = $row['start_event_time'];
				$end_time = $row['end_event_time'];

				echo "<li>";
				echo "<div class='event' style='border-left:5px " . $event_color . " solid' id='$event_id'><div class='event_title'>$event_title</div>";

				if ($start_event_date == $end_event_date) {
					//event je v tom istom dni tak zobraz cas konca

					echo "<div class='event_start'>$start_event_date</div><div class='event_time'>$start_time</div><div class='event_time'>$end_time</div>";
				} else {
					echo "<div class='event_start'>$start_event_date</div><div class='event_end'>$end_event_date</div>";
				}
				echo "<div class='event_actions'><ul><li><a href='event.php?event_id=$event_id' class='flat-btn'>View</a></li><li><a href='event_edit.php?event_id=$event_id' class='flat-btn'>Edit</a></li><li><a href='event_remove.php?event_id=$event_id' class='flat-btn'>Remove</a></li></ul></div>";
				echo " </div>";
				echo "</li>";

			}

			echo "</ul>";

		}

		echo "<div class='add_new_event_wrap'><div class='new_event_action_wrap'><span>Add new event</span><form action='' method='post'><input type='hidden' name='start_event_day' value='$today'><button type='submit' name='add_event' class='flat-btn'><i class='fa fa-plus'></i></button></form></div></div>";

		// } //foreach

	} //end of veek view
} else {
	//load all events for today
	$today = date('Y-m-d');
	//$sql="SELECT a.event_id, a.cust_id,a.event_title, a.start_event_date,b.cust_id, b.customer_name FROM tblcustomer_events a, tblcustomers b WHERE a.cust_id=b.cust_id and a.start_event_date='$today' ORDER BY a.start_event_date DESC";
	$sql = "SELECT * FROM tblcustomer_events WHERE start_event_date='$today'";
	//echo $sql;
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
	$nr_of_events = mysqli_num_rows($result);

	if ($nr_of_events == 0) {
		echo "<div id='add_new_event_wrap'><span>Add new event</span><form action='' method='post'><button type='submit' name='add_event' class='flat-btn'><i class='fa fa-plus'></i></button></form></div>";
	} else {
		echo "<ul>";
		while ($row = mysqli_fetch_array($result)) {
			$event_id = $row['event_id'];
			$event_title = $row['event_title'];
			$start_event_date = $row['start_event_date'];
			$end_event_date = $row['end_event_date'];
			$event_color = $row['event_color'];
			$start_time = $row['start_event_time'];
			$end_time = $row['end_event_time'];

			echo "<li>";
			echo "<div class='event' style='border-left:5px " . $event_color . " solid' id='$event_id'><div class='event_title'>$event_title</div>";

			if ($start_event_date == $end_event_date) {
				//event je v tom istom dni tak zobraz cas konca

				echo "<div class='event_start'>$start_event_date</div><div class='event_time'>$start_time</div><div class='event_time'>$end_time</div>";
			} else {
				echo "<div class='event_start'>$start_event_date</div><div class='event_end'>$end_event_date</div>";
			}
			echo "<div class='event_actions'><ul><li><a href='event.php?event_id=$event_id' class='flat-btn'>View</a></li><li><a href='event_edit.php?event_id=$event_id' class='flat-btn'>Edit</a></li><li><a href='event_remove.php?event_id=$event_id' class='flat-btn'>Remove</a></li></ul></div>";
			echo " </div>";
			echo "</li>";

		}

		echo "</ul>";

		echo "<div class='add_new_event_wrap'><div class='new_event_action_wrap'><span>Add new event</span><form action='' method='post'><input type='hidden' name='start_event_day' value='$today'><button type='submit' name='add_event' class='flat-btn'><i class='fa fa-plus'></i></button></form></div></div>";
	}
	mysqli_free_result($result);

}
?>

                       </div><!-- view_event_wrap -->
                    </div>
                </div>
            </div>
        </div>
 </body>
 </html>