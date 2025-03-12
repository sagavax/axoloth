<?php session_start();?>
<?php include("include/dbconnect.php"); ?>
<?php include("include/functions.php"); ?>
<?php 

    if(isset($_POST['selected_as_read'])){
        if(isset($_POST['is_read'])){    
        //var_dump($_POST['selected_as_read']);    
        
       $count=count($_POST['is_read']);
        
        foreach ($_POST['is_read'] as $key => $is_read) {
            $query="UPDATE tblcustomer_notes_history set is_read=1 WHERE id=$key";
            $result = mysqli_query($con, $query);
        }
        echo "<script>alert('$count item(s) (has) in total have been marked as read');
        location.href='inbox.php'</script>"; 

        $curr_date=date('Y-m-d H:i:s');
        $curr_action="inbox_item_marked_as_read";
        $curr_app="inbox";
        $text_logu="$count item(s) has (have) been marked as read";
        
        $sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
        
        $result = mysqli_query($con, $sql); // or die("MySQL ERROR: ".mysqli_error());
       } else {
           
           echo "<script>alert('Nothing has been selected !');
           location.href='inbox.php';
           </script>";
       }
    }  

     
     if(isset($_POST['all_as_read'])){
        
       
           

        $query="UPDATE tblcustomer_notes_history set is_read=1";
         $result=mysqli_query($con, $query)  or die("MySQL ERROR: ".mysqli_error($con)); 
         
         $curr_date=date('Y-m-d H:i:s');
         $curr_action="inbox_item_marked_as_read";
         $curr_app="inbox";
         $text_logu="All items have been marked as read";
         
         $sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
         
         $result = mysqli_query($con, $sql); // or die("MySQL ERROR: ".mysqli_error());
       
         echo "<script>
         alert('All has been marked as read !');
           location.href='inbox.php';
         </script>";
         
     } 

     if(isset($_POST['selected_as_archived'])){
        if(isset($_POST['is_read'])){    
            //var_dump($_POST['selected_as_read']);    
            
           $count=count($_POST['is_read']);
            
            foreach ($_POST['is_read'] as $key => $is_read) {
                $query="UPDATE tblcustomer_notes_history set is_archived=1 WHERE id=$key";
            }
            echo "<script>alert('$count item(s) (has) in total have been archived');
            location.href='inbox.php'</script>"; 
    
            $curr_date=date('Y-m-d H:i:s');
            $curr_action="inbox_item_archived";
            $curr_app="inbox";
            $text_logu="$count item(s) has(have) been archived";
            
            $sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
            
            $result = mysqli_query($con, $sql); // or die("MySQL ERROR: ".mysqli_error());
           } else {
               echo "<script>alert('Nothing has been selected !');
               location.href='inbox.php';
               </script>";
           }
     }

     if(isset($_POST['archive_all'])){
        $query="UPDATE tblcustomer_notes_history set is_archived=1 WHERE is_archived=0";
        $result=mysqli_query($con, $query)  or die("MySQL ERROR: ".mysqli_error($con)); 

        $curr_date=date('Y-m-d H:i:s');
        $curr_action="inbox_item_archived";
        $curr_app="inbox";
        $text_logu="All items have been archived";
        
        $sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
        $result = mysqli_query($con, $sql); // or die("MySQL ERROR: ".mysqli_error());

        echo "<script>
        alert('All has been archived !');
          location.href='inbox.php';
        </script>";
     }

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sk" lang="sk">
   <head>
      <meta charset="utf-8" />
      <link rel="stylesheet" href="css/style_new.css?<?php echo time()?>" type="text/css">
      <link href="css/font-awesome.css" rel="stylesheet" type="text/css" />
      <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet" type="text/css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.5/sweetalert2.min.css">
      <script type="text/javascript" src="js/functions.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script> 
      <title>e.i.s. - inbox</title>
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
      <script type="text/javascript">
                  function toggle(source) {
          checkboxes = document.getElementsByName('is_read[]');
          for(var i=0, n=checkboxes.length;i<n;i++) {
            checkboxes[i].checked = source.checked;
          }
        }
      </script>
      
    
   </head>
   <body onload="clock()">

         <div id="header"> <!--header -->
                        <div class="header-logo">e.i.s.</div> <!--logo -->
                         <div class="header-menu"> <!--menu-->
                               <?php
                                include("include/menu.php");
                               ?>
                        </div>       
                    <div>
                   <div id="txt"></div></div>
    </div><!-- header -->
    <div id="layout">
       <div id="search_result_wrap">
         <table>
       <?php
            if(isset($_GET['search_string'])){
              $search_string=$_GET['search_string'];  
              $sql="(SELECT id as id, note COLLATE utf8_general_ci as source, 'note' as type from tblcustomer_notes where note like '%".$search_string."%' or note_source like '%".$search_string."%') UNION (SELECT PassID as id, system_name COLLATE utf8_general_ci as source, 'password' as type from tblpasswords where system_name like '%".$search_string."%') UNION (SELECT id as id, task_text COLLATE utf8_general_ci as source, 'tasks' as type from tblcustomer_tasks where task_text like '%".$search_string."%') UNION (SELECT event_id as id,event_title COLLATE utf8_general_ci as source, 'events' as type from tblcustomer_events where event_title like '%".$search_string."%')";
               // echo $sql;
                $result=mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error());
                $num_row=mysqli_num_rows($result);
                if($num_row==0){
                  echo "Nothing has been found!!!";
                } else {
                while ($row = mysqli_fetch_array($result)) {
                  
                  $id=$row['id'];
                  $source=$row['source'];
                  $type=$row['type'];
                
                  $source = strip_tags($source);


                  if (strlen($source) > 500) {



                  $sourceCut = substr($source, 0, 500);
                  $sourceCut=preg_replace("~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~","<a href=\"\\0\">\\0</a>", $sourceCut);
                  $source = substr($sourceCut, 0, strrpos($sourceCut, ' '))."...'>Read More</a>";
                  
                  if($type=="note"){
                    $app="<a href=''>Notepad</a>";
                  } elseif($type=="password") {
                     $app="<a href=''>Passwords</a>"; 
                  } elseif($type=="events"){
                    $app="<a href=''>Events</a>";
                  } elseif($type=="tasks"){
                    $app="<a href=''>Tasks</a>";
                  }
                  
                  echo "<div class='sr'><div class='sr_id'>$id</div><div class='sr_source'>$source</div><div class='sr_app'>$app</div></div>";
                } 
              }    
             }
            }   
              
       ?>
        </table> 
       </div><!--search result wrap -->
    </div><!--layout -->
        
   </body>
</html>
