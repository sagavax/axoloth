<?php include("../include/dbconnect.php"); ?>
<?php include("../include/functions.php"); ?>

<?php
        //var_dump($_POST);
        $note_source=trim(mysqli_real_escape_string($con, $_POST['video_source']));
        
        //zisti duplicitu
        $sql="SELECT * from tblcustomer_notes where note_source='$note_source'";
        $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($con));
        $nr_of_videos=mysqli_num_rows($result);
        if($nr_of_videos==1){
           echo "video_exists=1";
        } else {
        $note_title=trim(mysqli_real_escape_string($con, $_POST['video_title']));      
        
        $note_text="";
        //$cust_id = (int)$_POST['customer']; // if cust_id = moje vlastne poznamky
        $cust_id = 0;
        //$tags_ids=$_POST['categories']; 
        $note_type="video"; 
        $date_created = date('Y-m-d H:m:s');
        $sql="INSERT INTO tblcustomer_notes (cust_id, title, note,note_source,date_created, note_type) VALUES ($cust_id,'$note_title','$note_text','$note_source','$date_created','$note_type')";
              $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($con));
      
        $curr_date=date('Y-m-d H:i:s');
        $curr_action="create_video";
        $curr_app="notepad";
        $new_note_id=GetLatestNote();
        
             
        if(isset($_POST['categories'])){
    
         $tags_ids=$_POST['categories']; 
         foreach($tags_ids as $tag_id){
            $sql="INSERT INTO tblcustomer_notes_tags (note_id,cat_id) VALUES($new_note_id,$tag_id)";
            //echo $sql;
            $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($con));
            }
           }
       
               
            $link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
            //$link1=mysqli_connect('localhost','root',null,'brick_wall',null,null);
            $diary_text="EIS: Bolo pridane nove video s nazvom <strong>$note_title</strong>";
            $sql="INSERT INTO diary (diary_text, date_added,location,isMobile,is_read) VALUES ('$diary_text','$curr_date','',0,0)";
            $result = mysqli_query($link1, $sql) or die("MySQLi ERROR: ".mysqli_error($link1));
            mysqli_close($link1);
       
        $text_logu="new video $new_note_id has been added";
        
        $sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
        $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($link1));
       
            //header('Location: index.php'); // presmeruje spat aby sa zbranilo vkladaniu duplicity
   }