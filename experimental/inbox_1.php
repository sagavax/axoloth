<?php session_start();
$_SESSION['url'] = $_SERVER['REQUEST_URI'];
?>

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
                $result = mysqli_query($con, $query); // or die("MySQL ERROR: ".mysqli_error());
                $query1="UPDATE tblcustomer_notes_history set is_read=1 WHERE id=$key";
                $result = mysqli_query($con, $query1);
            }
            echo "<script>alert('$count item(s) (has) in total have been archived');
            location.href='inbox.php'</script>"; 
    
            //log the action
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
        
       
        $query="UPDATE tblcustomer_notes_history set is_archived=1 WHERE is_archived=0"; //spravy zarchivujeme
        $result=mysqli_query($con, $query)  or die("MySQL ERROR: ".mysqli_error($con)); 

        $curr_date=date('Y-m-d H:i:s');
        $curr_action="inbox_item_archived";
        $curr_app="inbox";
        $text_logu="All items have been archived";
        
        

        $sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
        $result = mysqli_query($con, $sql); // or die("MySQL ERROR: ".mysqli_error());

        $query="UPDATE tblcustomer_notes_history set is_read=1 where is_read=0"; //najprv sa zaisti, ze vsetky spravy su precitane 
        $result=mysqli_query($con, $query)  or die("MySQL ERROR: ".mysqli_error($con)); 


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
        <form id="bla" action="" method="post">        
          <div id="inbox_content">
              <div id="inbox_menu"><div id="dropdown_menu" onclick="ShowMenu();"><i class="fa fa-bars" aria-hidden="true"></i></div><div id="check_all"><ul><li><button type='submit' name='all_as_read' class="btn-small">All as read</button></li><li><button type='submit' name='selected_as_read' class="btn-small">Selected as read</button></li><li><button type='submit' name='selected_as_archived' class="btn-small">Archive selected</button></li><li><button type='submit' name='archive_all' class="btn-small">Archive All</button></li></ul></div></div>
               
                <div id="con_menu">
                    <ul>
                        <li>Archive selected</li>
                        <li>Archive all</li>
                        <li>Mark selected as read</li>
                        <li>Mark all as read</li>
                    </ul>
                </div>
                <?php 
                           
                    $query = "SELECT  * from tblcustomer_notes_history where app<>'inbox' and is_archived=0 ORDER BY id DESC";
                    $result=mysqli_query($con, $query)  or die("MySQL ERROR: ".mysqli_error($con));
                          //echo $query;
                     while ($row = mysqli_fetch_array($result)) {
                        $date_added=$row['date_added'];
                        $id=$row['id'];
                        $text_logu=$row['text_logu'];
                        $app = $row['app'];
                        $is_read=$row['is_read'];
                        $undo=$row['undo_action'];
                             
                                if($is_read==0){
                                $text_logu="<b>".$text_logu."</b>";
                                $value=0;
                                } else {
                                    $value=1;
                            
                                }
                        echo "<div class='log_wrap'> 
                               <div class='log_checkbox'><input type='checkbox' name='is_read[$id]'><div style='clear: both'></div>
                               </div><div class='log_text'>$text_logu</div>";
                               if (!empty($undo)) { echo "<div class='log_action'><form action='' method='post'>                               
                                <button name='revert_action' class='btn-small'>Revert</button></form></div>";
                               } else {echo "<div class='log_action'></div>";}
                        echo "</div>";
                            //<div class='log_header'><span>$date_added<span></div> 
                            } 
                        ?>
                
              
          </div><!-- inbox_content -->
      </form><div style='clear:both'></div>
    </div><!--layout -->
       <script type="text/javascript">
            function ShowMenu(){
                var menu = document.getElementById("con_menu");
                menu.classList.toggle("show");
            }                                                                
        </script>  
        
   </body>
</html>
