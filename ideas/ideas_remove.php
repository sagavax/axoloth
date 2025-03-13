<?php include "../include/dbconnect.php";
      include "../include/functions.php";
                    
        $idea_id = $_POST['idea_id'];

        //remove idea
        $delete_idea = "DELETE from ideas WHERE idea_id=$idea_id";
        $result = mysqli_query($con, $delete_idea) or die("MySQLi ERROR: ".mysqli_error($con));

        //remove comments
        $delete_comments = "DELETE from ideas_comments WHERE idea_id=$idea_id";
        $result = mysqli_query($con, $delete_comments) or die("MySQLi ERROR: ".mysqli_error($con));


        $diary_text="Minecraft IS: Ideas s id $idea_id bola vymazana ";
            $sql="INSERT INTO tblapp_log (diary_text, date_added) VALUES ('$diary_text',now())";
            $result = mysqli_query($con, $sql) or die("MySQLi ERROR: ".mysqli_error($con));