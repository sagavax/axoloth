<?php include "../include/dbconnect.php";
      include "../include/functions.php";
      
      session_start();
           
           $idea_title = mysqli_real_escape_string($con, $_POST['idea_title']);
            $idea_text = mysqli_real_escape_string($con,$_POST['idea_text']);
            $idea_priority = (isset($_POST['idea_priority']) && $_POST['idea_priority'] != 0) ? $_POST['idea_priority'] : 'low';
            $idea_status = (isset($_POST['idea_status']) && $_POST['idea_status'] != 0) ? $_POST['idea_status'] : 'new';
        
            $is_applied = 0;

           //var_dump($_POST);

            $save_idea = "INSERT INTO ideas (idea_title, idea_text, priority, status, is_applied, added_date) VALUES ('$idea_title','$idea_text', '$idea_priority','$idea_status', $is_applied,now())";
            $result=mysqli_query($con, $save_idea);

            
      
        $diary_text="Minecraft IS: Bola vytvorena nova idea "; 
        $sql="INSERT INTO app_log (diary_text, date_added) VALUES ('$diary_text',now())";
        $result = mysqli_query($con, $sql) or die("MySQLi ERROR: ".mysqli_error($con));
        
        echo "<script>alert('Minecraft IS: Bola vtytvorena nova idea');
        window.location.href='ideas.php';
        </script>";