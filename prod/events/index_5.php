<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <meta charset="utf-8" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="../css/style_new.css?<?php echo time() ?>" type="text/css">
    <link rel="stylesheet" href="../css/event.css?<?php echo time() ?>" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>EIS - Event manager</title>
    <link rel='shortcut icon' href='../eis.png'>
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
                        <li><a href="index.php?calendar=month_view" class="flat-btn">This month</a></li>
                        <li><a href="index.php?calendar=week_view" class="flat-btn">This week</a></li>
                        <li><a href="index.php?calendar=today" class="flat-btn">Today</a></li>
                        <li><a href="event_add.php" class="flat-btn">New event</a></li>
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
                        if (isset($_GET['view_day'])) {
                            $event_day = $_GET['view_day'];
                        } else {
                            $event_day = date("Y-m-d");
                        
                                // $action=$_GET['action'];
                                $i = 0;
                                $sql = "SELECT * from tblcustomer_events where event_date='$event_day'";
                                //echo $sql;

                                $result = mysqli_query($con, $sql) or die("MySQLi ERROR: " . mysqli_error($con));
                                $nr_of_events = mysqli_num_rows($result);

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

                                    if ($start_event_date == $end_event_date) { //event je v tom istom dni tak zobraz cas konca

                                        echo "<div class='event_start'>$start_event_date</div><div class='event_time'>$start_time</div><div class='event_time'>$end_time</div>";
                                    } else {
                                        echo "<div class='event_start'>$start_event_date</div><div class='event_end'>$end_event_date</div>";
                                    }
                                    //echo "<div class='event_actions'><<ul><li><a href='event.php?event_id=$event_id' class='flat-btn'><i class='fa fa-eye'></i></a></li><li><a href='event_edit.php?event_id=$event_id' class='flat-btn'><i class='fas fa-edit'></i></a></li><li><a href='event_remove.php?event_id=$event_id' class='flat-btn'><i class='fa fa-times'></i></a></li></ul></div>";
                                    //echo " </div>";
                                    
                                    echo "<div class='event_actions'><form action='' method='post'><ul><li><a href='event.php?event_id=$event_id' class='flat-btn'><i class='fa fa-eye'></i></a></li><li><a href='event_edit.php?event_id=$event_id' class='flat-btn'><i class='fas fa-edit'></i></a></li><li><a href='event_remove.php?event_id=$event_id' class='flat-btn'><i class='fa fa-times'></i></a></li></ul></form></div>";
                                    echo " </div>";
                                    

                                }

                                echo "</div>";
                            }
                           
                            echo "<div class='add_event_for_curr_day'><div class='fab-icon-holder'><a href='event_add.php?day=$event_day'><i class='fa fa-plus'></i></a></div></div>";;    
                            ?>
                </div><!-- event_list -->
            </div><!--events wrapper -->
        </div>
    </div><!--layout -->
</body>
</html>