<?php session_start()?>
<?php include "../include/dbconnect.php";?>
<?php include "../include/functions.php";?>


<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>


<?php
if (isset($_POST['add_event'])) { //quick mark task as complete
    $start_event_day=$_POST['start_event_day'];
    header('location:event_add.php?day='.$_POST['start_event_day']);
    //header('location:event.php?action=new');
}

?>

<!DOCTYPE html>
<html>
  <head>
      <meta charset="utf-8" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <link rel="stylesheet" href="../css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="../css/style_new.css?<?php echo time() ?>" type="text/css">
    <link rel="stylesheet" href="../css/event_manager.css?<?php echo time() ?>" type="text/css">
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
        <div id="event_wrapper">
            <div id="events_filter">
                <ul>
                    <li><a href="index.php?calendar=month_view" class="flat-btn">This month</a></li>
                    <li><a href="index.php?calendar=week_view" class="flat-btn">This week</a></li>
                    <li><a href="index.php?calendar=today" class="flat-btn">Today</a></li>
                    <li><a href="event_add.php" class="flat-btn">New event</a></li>
                </ul>
            </div>    
            <div id="event_calendar">
                <?php
                    $month = idate('m');
                    $year = date('Y');
                    if ((isset($_GET['date'])) && ($_GET['date'])) {
                        list($month, $year) = explode('-', $_GET['date']);
                    }
                    echo draw_event_calendar($month, $year); //mesacny pohlad
                ?>
            </div>
            <!--<div class="new_event_action_wrap">
                        <div class="fab-icon-holder"><i class="fa fa-plus"></i></div>
            </div>-->
            
            <div class="events">
                
            </div>
        </div>
    </div><!-- layout -->
 </body>
 </html>