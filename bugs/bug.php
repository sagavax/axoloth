<?php include "../include/dbconnect.php";
      include "../include/functions.php";
      session_start();


      if(isset($_POST['save_comment'])){
        $comment_header = $_POST['bug_comment_header'];
        $comment = $_POST['bug_comment'];
        $bug_id = $_SESSION['bug_id'];

        $save_comment = "INSERT into bugs_comments (bug_id,bug_comm_header, bug_comment, comment_date) VALUES ($bug_id,'$comment_header','$comment',now())";
        //echo $save_comment;
         $result=mysqli_query($link, $save_comment);

      
        $diary_text="Minecraft IS: Bolo pridane novy kommentar k bugu id <b>$bug_id</b>";
        $sql="INSERT INTO app_log (diary_text, date_added) VALUES ('$diary_text',now())";
        $result = mysqli_query($link, $sql) or die("MySQLi ERROR: ".mysqli_error($link));

         header("Location: " . $_SERVER['REQUEST_URI']);
         exit();
      }


      if(isset($_POST['reopen_bug'])){
        $bug_id = $_SESSION['bug_id'];
        $_SESSION['is_fixed']=0;
        $reopen_bug = "UPDATE bugs set is_fixed = 0 wHERE bug_id=$bug_id";
        //echo $reopen_bug;
        $result=mysqli_query($link, $reopen_bug);

       
        $diary_text="Minecraft IS: Bug s id <b>$bug_id</b> bol znovu otvoreny";
        $sql="INSERT INTO app_log (diary_text, date_added) VALUES ('$diary_text',now())";
        $result = mysqli_query($link, $sql) or die("MySQLi ERROR: ".mysqli_error($link));
        
      }

      if(isset($_POST['delete_comm'])){
        $comm_id = $_POST['comm_id'];
        //var_dump($_POST);
        $delete_comment = "DELETE from bugs_comments WHERE comm_id = $comm_id";
        //echo $delete_comment;
        $result=mysqli_query($link, $delete_comment);


       
        $diary_text="Minecraft IS: Komment s id <b>$comm_id</b> bol vymazany";
        $sql="INSERT INTO app_log (diary_text, date_added) VALUES ('$diary_text',now())";
        $result = mysqli_query($link, $sql) or die("MySQLi ERROR: ".mysqli_error($link));
        
      }

?>




<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Minecraft IS</title>
    <link rel="stylesheet" href="css/style.css?<?php echo time(); ?>">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
    <link href='https://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">  
    <script type="text/javascript" defer src="js/bug.js"></script>
    <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">

  </head>
  <body>
        <?php include("includes/header.php") ?>   
      <div class="main_wrap">
      <div class="tab_menu">
          <?php include("includes/menu.php"); ?>
        </div>    
        <div class="main_wrap">
         <div class="content">
               <div class="fab fab-icon-holder" onclick="window.location.href='bugs.php';">
                <i class="fa fa-arrow-left"></i>
              </div>
              <div class="list">
               
                  <?php
                        $bug_id = $_SESSION['bug_id'];
                        echo "<script>sessionStorage.setItem('bug_id',$bug_id)</script>";
                        $is_fixed = $_SESSION['is_fixed'];

                        $get_bug = "SELECT * from bugs WHERE bug_id =$bug_id";
                        $result=mysqli_query($link, $get_bug);
                        while ($row = mysqli_fetch_array($result)) {
                              $bug_id = $row['bug_id'];
                              $bug_title = $row['bug_title'];
                              $bug_text = $row['bug_text'];
                              $is_fixed = $row['is_fixed'];
                              $added_date = $row['added_date'];

                              echo "<div class='bug'>";
                                    echo "<div class='bug_title'>$bug_title</div>";
                                    echo "<div class='bug_text'>$bug_text</div>";
                                    echo "<div class='bug_footer'>";
                                       if($is_fixed==0){
                                                echo "<button type='button' title='mark the bug as fixed' name='bug_set_fixed' class='button'><i class='fa fa-check'></i></button>";
                                          
                                    } elseif ($is_fixed==1){

                                          echo "<button type='button' title='mark the bug as fixed' class='flat-btn' name='reopen_bug'>Reopen</button><div class='span_modpack'>fixed</div>";
                                    }        

                                          
                                    echo "</div>";
                              echo "</div>"; // bug
                        }      
                  ?>

                    <div class="bug_comments_list">
                              <?php

                                $get_comments = "SELECT * from bugs_comments wHERE bug_id=$bug_id";
                                $result_comment=mysqli_query($link, $get_comments);
                                 while ($row_comment = mysqli_fetch_array($result_comment)) {
                                    $comm_id = $row_comment['comm_id'];
                                    $comm_title = $row_comment['bug_comm_header'];
                                    $comm_text = $row_comment['bug_comment'];
                                    $comm_date = $row_comment['comment_date'];

                                    echo "<div class='bug_comment'>";
                                        echo "<div class='connector-line'></div>";
                                        echo "<div class='bug_top_banner'></div>";
                                        if($comm_title!=""){
                                            echo "<div class='bug_title'>$comm_title</div>";    
                                        }
                                        echo "<div class='bug_text'>$comm_text</div>";
                                        echo "<div class='bug_comm_action'><form action='' method='post'><input type='hidden' name='comm_id' value=$comm_id><button type='submit' name='delete_comm' class='flat-btn'><i class='fa fa-times'></i></button></form></div>";
                                    echo "</div>";
                                 }   
                              ?>  

                              <h4>Add a comment</h4>
                             <div class="bug_comment_new">
                                <form action="" method="post">
                                <input type="text" name="bug_comment_header" autocomplete="off" placeholder="type title here">
                                <textarea name="bug_comment" placeholder="type comment here..."></textarea>
                                
                                <div class="bug_comment_action">
                                  <?php
                                        if($is_fixed==0){
                                            echo "<button name='save_comment' class='flat-btn'>save</button>";
                                        } else if ($is_fixed==1){
                                            echo "<button name='save_comment' disabled class='flat-btn'>save</button>";
                                        }
                                  ?>  
                                  
                                </div>
                            </form>   
                        </div><!--bug comment -->
                    </div><!-- bug comment list-->    
                     
                 
              </div><!-- list-->

        </div><!--content-->
      </div><!--main_wrap-->
  </body>
  </html> 
