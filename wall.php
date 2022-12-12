<?php session_start();?>
<?php include("include/dbconnect.php"); ?>
<?php include("include/functions.php"); ?>
<?php require_once ("include/Mobile_Detect.php"); ?>


<?php
   date_default_timezone_set('Europe/Bratislava');

   if (isset($_POST['Add'])) {

    $location=mysqli_real_escape_string($con, $_POST['location']); //koordinaty

    $diary_text = $_POST['comment'];


    //$useragent=$_SERVER['HTTP_USER_AGENT'];

    $detect = new Mobile_Detect;

    if ( $detect->isMobile() ) {
      $isMobile=1;
    } else {
      $isMobile=0;
    }
      //$diary_text.="<span style='float:right; margin-right=5p;font-style:italic'>Sent from mobile<span>";
      global $con;
      $diary_text=mysqli_real_escape_string($con,$diary_text);
      $regex = "/(#)+[a-zA-Z0-9]+/";
      preg_match_all($regex, $diary_text,$matched_hashtag);
         $hashtag='';
         if(!empty($matched_hashtag[0])){
          //fetch hastag from the array
         foreach ($matched_hashtag[0] as $matched) {
        //  echo $matched;
        //append every hastag to a string
        //remove the # by preg_replace function
        $regex='/(#)/';
        $hashtag = preg_replace($regex, '', $matched);

        $sql_hashtag="SELECT * from hashtags where tag_name='$hashtag'";
         $result_hashtag=mysqli_query($con, $sql_hashtag) or die("MySQL ERROR: ".mysqli_error($con));
         $num_rows=mysqli_num_rows($result_hashtag);
         if($num_rows==0){ // ak si nenasiel hashtag v databaze
            $query="INSERT INTO hashtags (tag_name) VALUES ('$hashtag')";
            //echo $query;
             $result=mysqli_query($con, $query) or die("MySQL ERROR: ".mysqli_error($con));
            }
          }
         }
        //echo $hashtag;

    $sql="INSERT INTO diary (diary_text, date_added,location,isMobile) VALUES ('$diary_text','".date('Y-m-d H:i:s')."','$location',$isMobile)";

    $result=mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error($con));
    header('Location: wall.php'); // presmeruje spat aby sa zbranilo vkladaniu duplicity

  }


   ?>



<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sk" lang="sk">
   <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width,initial-scale=1">
      <link rel="stylesheet" type="text/css" href="css/style_new.css?v2.0">
      <link href="css/font-awesome.css" rel="stylesheet" type="text/css" />
      <link href="css/wall.css?v4.0.0" rel="stylesheet" type="text/css" />
      <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
      <script src="http://maps.google.com/maps/api/js"></script>

      <title>e.i.s. - wall</title>
      <link rel='shortcut icon' href='eis.ico'>

      <script language="JavaScript">
         function checklength(i){
                if (i<10){
                 i="0"+i;}
                 return i;
         }
         function clock(){
           var now = new Date();
           var hours = checklength(now.getHours());
           var minutes = checklength(now.getMinutes());
           var seconds = checklength(now.getSeconds());
           var format = 1;  //0=24 hour format, 1=12 hour format
           var time;

           if (format == 1){
             if (hours >= 12){
               if (hours ==12){
                 hours = 12;
               }else {
                 hours = hours-12;
               }
              time=hours+':'+minutes+':'+seconds+' PM';
             }else if(hours < 12){
                  if (hours ==0){
                    hours=12;
                  }
              time=hours+':'+minutes+':'+seconds+' AM';
             }
           }
          if (format == 0){
             time= hours+':'+minutes+':'+seconds;
          }
          document.getElementById("txt").innerHTML=time;
          setTimeout("clock();", 500);
         }
      </script>
   </head>
   <body onload="clock(); getLocation(); ">

     <div id="header"> <!--header -->
        <div class="header-logo">e.i.s.</div> <!--logo -->
          <div class="header-menu"> <!--menu-->
            <?php include("include/menu.php"); ?>
        </div>
    <div id="txt"></div></div>

    </div><!-- header -->
      <div id="layout">
        <div id="wall-wrap">
          <?php
                  $today = date('Y-m-d');
                  $date = date('Y-m-d');
                  $date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
                  $previous_date = date('Y-m-d', strtotime($date .' -1 day'));
                  $next_date = date('Y-m-d', strtotime($date .' +1 day'));
                  ?>
               <div id="date"></div>
               <!--- aktualny datum  -->

                  <div id="wall-comment">
                    <form accept-charset="utf-8" method="post" action="">
                      <textarea name="comment" id="comment" onkeyup="resizeTextarea('comment')" data-resizable="true"></textarea>

                     <div id="wall-comment-footer">
                        <div id="wall-comment-marker" onclick="getLocation();"><i class="fa fa-map-marker"></i></div><div id="wall-comment-loc"></div>
                        <div id="wall-comment-button"><button type="submit" name="Add" class="send_button">Send</button></div>
                     </div>
                   </form>
                 </div>

                <div class="table">
                    <?php

                    echo "<div class='diary_content'>";
                      global $con;
                      $query = "SELECT  date_added,diary_text,isMobile,location FROM diary ORDER BY ID DESC";
                      $result=mysqli_query($con, $query)  or die("MySQL ERROR: ".mysqli_error($con));
                      //echo $query;
                      while ($row = mysqli_fetch_array($result)) {
                        $date_added=$row['date_added'];
                        $diary_text=$row['diary_text'];
                        $isMobile=$row['isMobile'];
                        $location=$row['location'];

                        if($isMobile==0) {
                          $isMobile="";
                        } else {
                          $isMobile="<i class='fa fa-mobile'></i> Sent from mobile/tablet";
                        }

                        $date_added_tmst = strtotime($date_added);
                        $hour=date('H', $date_added_tmst);

                        //indentifikacia hyperlinku
                        //$diary_text = preg_replace("~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~","<a href=\"\\0\">\\0</a>", $diary_text);

                        echo "<div class='diary-record'>
                            <div class='diary-record-header'><span><i class='fa fa-clock-o'></i> $date_added<span></div>
                            <article>".nl2br(convertHashtags($diary_text))."</article>
                            <div class='diary-record-footer'>
                              <div class='diary-record-footer-location'><i class='fa fa-map-marker'></i> $location</div><div class='diary-record-footer-sent-from-mobile'>$isMobile</span></div>
                            </div>";
                        echo "</div>"; //diary record

                        }

                    echo "</div>"; //diary content


                      ?>
                  </div><!-- table -->
           </div><!--wall --wrap -->
           <div style="clear:both"></div>
      </div><!--layout -->


   </body>
   <script language="JavaScript">
        var x=document.getElementById("wall-comment-loc");

        function test() {
          alert('Ahoj');
        }

        function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            x.innerHTML = "Geolocation is not supported by this browser.";
        }
          }

          function showPosition(position) {

              //zobrazenie koordiant na webe
              x.innerHTML = position.coords.latitude+","+position.coords.longitude


              //vytvorene koordinaty prida do sktryteho inputu aby sa dali poslat vo vormate
              var coordinates=x.innerHTML; //ulozime si koordinaty
              var input = document.createElement("input"); //vytvorime si element inputbox
              input.type = "hidden"; //dame mu atribut hidden
              input.name="location"; // pomenujeme ho
              input.value=coordinates; // dame mu hodnoty
              document.getElementById('wall-comment-loc').appendChild(input);

          }
        </script>

</html>
