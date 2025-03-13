<?php include "../include/dbconnect.php";
      include "../include/functions.php";
      session_start();


     if(isset($_POST['save_idea_comment'])){
        $comment_header = $_POST['idea_comment_header'];
        $comment = $_POST['idea_comment'];
        $idea_id = $_SESSION['idea_id'];

        $save_comment = "INSERT into ideas_comments (idea_id,idea_comm_header, idea_comment, comment_date) VALUES ($idea_id,'$comment_header','$comment',now())";
         //echo $save_comment;
         $result=mysqli_query($con, $save_comment);
      
         header("Location: " . $_SERVER['REQUEST_URI']);
         exit();

      }

      
      if(isset($_POST['delete_comm'])){
        $comm_id = $_POST['comm_id'];
        //var_dump($_POST);
        $delete_comment = "DELETE from ideas_comments WHERE comm_id = $comm_id";
        //echo $delete_comment;
        $result=mysqli_query($con, $delete_comment);
    
         echo "<script>message('Comment deleted','success')</script>";
      }

?>




<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Minecraft IS</title>
    <link rel="stylesheet" href="../css/style_new.css?<?php echo time(); ?>">
    <link rel="stylesheet" href="../css/ideas.css?<?php echo time(); ?>">
    <script src="../js/idea.js?<?php echo time(); ?>" type="text/javascript"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
    <link href='https://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">  
    <link rel='shortcut icon' href='../a.png'>

  </head>
  <body>
        <?php include '../include/header.php'; ?>
        <div id="layout">
              <div class="content">
               <div class="fab fab-icon-holder" onclick="window.location.href='ideas.php';">
                <i class="fa fa-arrow-left"></i>
              </div>
              <div class="list">
               
                  <?php
                        $idea_id = $_SESSION['idea_id'];
                        $is_applied = $_SESSION['is_applied'];

                        $get_idea = "SELECT * from ideas WHERE idea_id =$idea_id";
                        $result=mysqli_query($con, $get_idea);
                        while ($row = mysqli_fetch_array($result)) {
                              $idea_id = $row['idea_id'];
                              $idea_title = $row['idea_title'];
                              $idea_text = $row['idea_text'];
                              $is_applied = $row['is_applied'];
                              $added_date = $row['added_date'];

                             echo "<div class='idea'>";
                                    if(isset($idea_title)){
                                        echo "<div class='idea_title'>$idea_title</div>";    
                                    }
                                    
                                    echo "<div class='idea_text'>$idea_text</div>";
                                    echo "<div class='idea_footer'>";
                                    if ($is_applied == 0) {
                                        echo "<form action='' method='post'>";
                                        echo "<input type='hidden' name='idea_id' value='$idea_id'>";
                                        echo "<button type='submit' name='to_apply' class='flat-btn'><i class='fa fa-check'></i></button>";
                                        echo "</form>";
                                    } elseif ($is_applied == 1) {
                                        echo "<div class='span_modpack'>Idea applied</div>";
                                    }
                                    echo "</div>"; // idea_footer
                                    echo "</div>"; // idea
                                                        }      
                                                  ?>

                    <div class="idea_comments_list">
                              <?php

                                $get_comments = "SELECT * from ideas_comments wHERE idea_id=$idea_id";
                                //echo $get_comments;
                                $result_comment=mysqli_query($con, $get_comments);
                                 while ($row_comment = mysqli_fetch_array($result_comment)) {
                                    $comm_id = $row_comment['comm_id'];
                                    $comm_title = $row_comment['idea_comm_header'];
                                    $comm_text = $row_comment['idea_comment'];
                                    $comm_date = $row_comment['comment_date'];

                                    echo "<div class='idea_comment'>";
                                        echo "<div class='connector-line'></div>";
                                        echo "<div class='idea_top_banner'></div>";
                                        
                                        if($comm_title!=""){
                                            echo "<div class='idea_comm_title'>$comm_title</div>";    
                                        }
                                        echo "<div class='idea_comm_text'>$comm_text</div>";
                                        echo "<div class='idea_comm_action'><form action='' method='post'><input type='hidden' name='comm_id' value=$comm_id>";

                                        if ($is_applied == 1) {
                                                  // If $is_disabled is 1, add the disabled attribute to the button
                                                  echo "<button type='submit' name='delete_comm' class='flat-btn' disabled><i class='fa fa-times'></i></button>";
                                              } else {
                                                  // If $is_disabled is not 1, do not add the disabled attribute
                                                  echo "<button type='submit' name='delete_comm' class='flat-btn'><i class='fa fa-times'></i></button>";
                                              }
                                              echo "</form></div>";
                                    echo "</div>";
                                 }   
                              ?>  

                              
                             <div class="idea_comment_new">
                                <h4>Add a comment</h4>
                                <form action="idea_save_comment.php" method="post">
                                <input type="text" name="idea_comment_header" autocomplete="off" placeholder="type title here">
                                <textarea name="idea_comment" placeholder="type comment here..."></textarea>
                                
                                <div class="idea_comment_action">
                                  <?php
                                        if($is_applied==0){
                                            echo "<button name='save_idea_comment' class='flat-btn'>save</button>";
                                        } else if ($is_applied==1){
                                            echo "<button name='save_idea_comment' disabled class='flat-btn'>save</button>";
                                        }
                                  ?>  
                                  
                                </div>
                            </form>   
                        </div><!--idea comment -->
                    </div><!-- idea comment list-->    
                </div><!-- list-->

        </div><!--content-->
      </div><!--main_wrap-->
    </body>
  </html> 
