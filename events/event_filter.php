<?php include("../include/dbconnect.php"); ?>


<?php
  //get list of events for particular day

  
    $event_filter = $_GET['event_filter'];
      if($event_filter =="this_year"){ // tento rok
        $today = date("Y-m-d");
        $sql = "SELECT * from tblcustomer_events where YEAR(event_date)=YEAR(CURRENT_DATE())";
        //echo $sql;
      } 
      else if ($event_filter =="this_month")
      
      { //tento mesiac
          /*$picked_period = date("m-Y");
          list($mesiac, $rok) = explode("-",$picked_period);*/
          
          $sql = "SELECT * from tblcustomer_events where YEAR(event_date)=YEAR(CURRENT_DATE()) AND MONTH(event_date)=MONTH(CURRENT_DATE())";
          //echo $sql;
   
  } else if($event_filter == "this_week") {
         $sql="SELECT * from  tblcustomer_events where YEARWEEK(event_date)=YEARWEEK(CURRENT_DATE())";
         
  } else if ($event_filter == "today"){
    $sql = "SELECT * from tblcustomer_events where event_date=CURRENT_DATE()";
    
  } 
    // $action=$_GET['action'];
    /*$i=0;
    $sql="SELECT * from tblcustomer_events where event_date=YEARWEEK(CURRENT_DATE())";
   */
    
    $result=mysqli_query($con, $sql) or die("MySQLi ERROR: ".mysqli_error($con));
    $nr_of_events=mysqli_num_rows($result);
    
    if ($nr_of_events==0) {
      echo "<h3>No events</h3>";
  }

     $i=0;
    while ($row = mysqli_fetch_array($result)) {
      $event_id = $row['event_id'];
      $event_title = $row['event_title'];
      $start_event_date = $row['start_event_date'];
      $end_event_date = $row['end_event_date'];
      $start_time = $row['start_event_time'];
      $end_time = $row['end_event_time'];
      $event_color = $row['event_color'];

        $i++;
              
        echo "<div class='event'  style='border-left:5px " . $event_color . " solid' id='$event_id'><div class='event_title'>$event_title</div>";

        if ($start_event_date == $end_event_date) { //event je v tom istom dni tak zobraz cas konca

            echo "<div class='event_start'>$start_event_date</div><div class='event_time'>$start_time</div><div class='event_time'>$end_time</div>";
        } else {
            echo "<div class='event_start'>$start_event_date</div><div class='event_end'>$end_event_date</div>";
        }
        //echo "<div class='event_actions'><<ul><li><a href='event.php?event_id=$event_id' class='flat-btn'><i class='fa fa-eye'></i></a></li><li><a href='event_edit.php?event_id=$event_id' class='flat-btn'><i class='fas fa-edit'></i></a></li><li><a href='event_remove.php?event_id=$event_id' class='flat-btn'><i class='fa fa-times'></i></a></li></ul></div>";
        //echo " </div>";
        
        echo "<div class='event_actions'><form action='' method='post'><input type='hidden' name='event_id' value=$event_id><ul><li><button name='view_event' class='flat-btn'><i class='fa fa-eye'></i></button></li><li><button name='edit_event' class='flat-btn'><i class='fas fa-edit'></i></button></li><li><button name='delete_event' class='flat-btn'><i class='fa fa-times'></i></button></li></ul></form></div>";
        echo " </div>";
    }     
  
    //echo "<div class='add_event_for_curr_day'><div class='fab-icon-holder'><a href='event_add.php?day=".$event_day."'><i class='fa fa-plus'></i></a></div></div>"; 