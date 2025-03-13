<?php include "includes/dbconnect.php";
      include "includes/functions.php";
      session_start();

        $comment_header = $_POST['idea_comment_header'];
        $comment = $_POST['idea_comment'];
        $idea_id = $_SESSION['idea_id'];
        //var_dump($_POST);


        $save_comment = "INSERT into ideas_comments (idea_id,idea_comm_header, idea_comment, comment_date) VALUES ($idea_id,'$comment_header','$comment',now())";
         echo $save_comment;
         $result=mysqli_query($con, $save_comment);
         
			// Open the file for writing
			$fp = fopen('example.txt', 'w');

			// Write content to the file
			fwrite($fp, $save_comment);

			// Close the file
			fclose($fp);
      
        $diary_text="Minecraft IS: Bolo pridany novy kommentar k idei id <b>$idea_id</b>";
        $sql="INSERT INTO app_log (diary_text, date_added) VALUES ('$diary_text',now())";
        $result = mysqli_query($con, $sql) or die("MySQLi ERROR: ".mysqli_error($con));
        //echo "<script>message('Comment added','success')</script>";
        header("location:idea.php");
        exit;