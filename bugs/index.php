<?php include "../include/dbconnect.php";
      include "../include/functions.php";
      session_start();



      if(isset($_POST['save_bug'])){
        $bug_title = $_POST['bug_title'] ?? '';
        $bug_text = $_POST['bug_text'] ?? '';
        
        $bug_priority = (isset($_POST['bug_priority']) && $_POST['bug_priority'] != 0) ? $_POST['bug_priority'] : 'low';
        $bug_status = (isset($_POST['bug_status']) && $_POST['bug_status'] != 0) ? $_POST['bug_status'] : 'new';
        
        $is_fixed = 0;
    
        // Použitie pripraveného SQL dotazu na bezpečné vloženie
        $save_bug = "INSERT INTO bugs (bug_title, bug_text, priority, status, is_fixed, added_date) 
                     VALUES (?, ?, ?, ?, ?, now())";
        
        $stmt = mysqli_prepare($con, $save_bug);
        mysqli_stmt_bind_param($stmt, "ssssi", $bug_title, $bug_text, $bug_priority, $bug_status, $is_fixed);
        mysqli_stmt_execute($stmt);
        
        // Získanie posledného ID bezpečne
        $max_id = mysqli_insert_id($con);
    
        // Logovanie do app_log
        $application = "bugs";
        $diary_text = "Minecraft IS: Bol zaznamenaný nový bug s ID $max_id";
        $create_record = "INSERT INTO tblapp_log (application, note, date_created) VALUES (?, ?, now())";
        
        // Prepare the statement
        $log_stmt = mysqli_prepare($con, $create_record);
        
        // Bind the parameters
        mysqli_stmt_bind_param($log_stmt, "ss", $application, $diary_text);  // "ss" indicates two string parameters
        
        // Execute the statement
        mysqli_stmt_execute($log_stmt);
    }


      if(isset($_POST['see_bug_details'])){
        $bug_id = $_POST['bug_id'];
        $_SESSION['bug_id']=$bug_id;
        $_SESSION['is_fixed']=$is_fixed;
        header("location:bug.php");
      }

      if (isset($_POST['bug_remove'])) {
        $bug_id = intval($_POST['bug_id']); // Ošetrenie vstupu
    
        if ($bug_id > 0) {
            // Spustiť transakciu
            mysqli_begin_transaction($con);
    
            try {
                // Odstrániť bug
                $remove_bug = "DELETE FROM bugs WHERE bug_id=?";
                $stmt = mysqli_prepare($con, $remove_bug);
                mysqli_stmt_bind_param($stmt, "i", $bug_id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
    
                // Odstrániť komentáre k bugom
                $delete_comments = "DELETE FROM bugs_comments WHERE bug_id=?";
                $stmt = mysqli_prepare($con, $delete_comments);
                mysqli_stmt_bind_param($stmt, "i", $bug_id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
    
                // Logovanie do denníka
                $diary_text = "Minecraft IS: Bol vymazaný bug s ID $bug_id";
                $sql = "INSERT INTO app_log (diary_text, date_added) VALUES (?, NOW())";
                $stmt = mysqli_prepare($con, $sql);
                mysqli_stmt_bind_param($stmt, "s", $diary_text);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
    
                // Commit transakcie
                mysqli_commit($con);
    
            } catch (Exception $e) {
                mysqli_rollback($con); // Ak niečo zlyhá, vráti zmeny späť
                die("MySQLi ERROR: " . mysqli_error($con));
            }
        }
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
    <link rel="stylesheet" href="../css/bugs.css?<?php echo time(); ?>">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
    <link href='https://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">  
    <script type="text/javascript" defer src="../js/bugs.js?<?php echo time(); ?>"></script>
    <link rel='shortcut icon' href='../a.png'>

  </head>
  <body>
      <?php include '../include/header.php'; ?>   
        <div class="main_wrap">
         <div class="content">
              <div class="list">
              
              <div class="new_bug">
                <h3> Report a new bug </h3>
                <form action="" method="post">
                      <input type="text" name="bug_title" placeholder="bug title here" id="bug_title" autocomplete="off">
                      <textarea name="bug_text" placeholder="Put a bug / error text here" id="bug_text"></textarea>
                      <div class="mew_bug_select_wrapper">
                        <select name="bug_priority">
                          <option value="0">--- choose priority --- </option>
                          <option value = "low">low</option>
                          <option value = "medium">medium</option>
                          <option value = "high">high</option>
                          <option value = "critical">critical</option>
                        </select>

                        <select name="bug_status">
                            <option value="0">--- choose status --- </option>
                            <option value = "new">new</option>
                            <option value = "in progress">in progress</option>
                            <option value = "pending">pending</option>
                            <option value = "fixed">fixed</option>
                            <option value = "reopened">reopened</option>
                        </select>
                      </div>
                      <div class="new_bug_action">
                        <button type="submit" name="save_bug" class="flat-btn">Save</button>
                      </div>
               </form>
              </div><!-- new bug-->
              
              <div class="bug_list">
                  <?php

                          $itemsPerPage = 10;

                     $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
                     $offset = ($current_page - 1) * $itemsPerPage;


                        $get_bugs = "SELECT * from bugs ORDER BY bug_id DESC LIMIT $itemsPerPage OFFSET $offset";
                        $result=mysqli_query($con, $get_bugs);
                        while ($row = mysqli_fetch_array($result)) {
                          // Sanitizácia údajov na ochranu pred XSS
                          $bug_id = (int) ($row['bug_id'] ?? 0); // ID musí byť číslo
                          $bug_title = htmlspecialchars($row['bug_title'] ?? '', ENT_QUOTES, 'UTF-8');
                          $bug_text = htmlspecialchars($row['bug_text'] ?? '', ENT_QUOTES, 'UTF-8');
                          $bug_priority = htmlspecialchars($row['priority'] ?? '', ENT_QUOTES, 'UTF-8');
                          $bug_status = htmlspecialchars($row['status'] ?? '', ENT_QUOTES, 'UTF-8');
                          $is_fixed = (int) ($row['is_fixed'] ?? 0);
                          $added_date = htmlspecialchars($row['added_date'] ?? '', ENT_QUOTES, 'UTF-8');

                      
                          // Počet komentárov
                          $nr_of_comments = GetCountBugComments($bug_id);
                      
                          // Ak je bug FIXED, zobrazí štítok + mení akčné tlačidlá
                          $add_comment = "<button type='submit' name='add_comment' class='flat-btn')><i class='fa fa-comment'></i></button>";
                          $fixed_label = $is_fixed ? "<div class='span_fixed'>fixed</div>" : "";
                          $action_buttons = $is_fixed ? 
                              "<button type='submit' name='see_details' class='flat-btn'><i class='fa fa-eye'></i></button>
                               <button type='submit' name='bug_remove' class='flat-btn'><i class='fa fa-times'></i></button>
                               {$add_comment}" : // Pridanie komentára aj pre fixed stav
                              "<button type='submit' name='see_details' class='flat-btn'><i class='fa fa-eye'></i></button>
                               <button type='submit' name='mark_fixed' class='flat-btn'><i class='fa fa-check'></i></button>
                               <button type='submit' name='bug_remove' class='flat-btn'><i class='fa fa-times'></i></button>
                               {$add_comment}"; // Pridanie komentára aj pre nefixed stav
                          
                      
                          // Generovanie HTML výstupu
                         
                          echo "<div class='bug' bug-id='{$bug_id}'>
                          <div class='bug_title'>{$bug_title} {$fixed_label}</div>
                          <div class='bug_text'>{$bug_text}</div>
                          <div class='bug_footer'>
                              <div class='bug_status {$bug_status}'>{$bug_status}</div>
                              <div class='bug_priority {$bug_priority}'>{$bug_priority}</div>
                              <div class='nr_of_comments'>{$nr_of_comments} comments</div>
                              <div class='bug_action'>
                                  <input type='hidden' name='bug_id' value='{$bug_id}'>
                                  <input type='hidden' name='is_fixed' value='{$is_fixed}'>
                                  {$action_buttons}
                              </div>
                          </div>
                      </div>";
                      
                      }
                           
                  ?>
              </div>
                  <?php
                    // Calculate the total number of pages
                    $sql = "SELECT COUNT(*) as total FROM bugs";
                    $result = mysqli_query($con, $sql);
                    
                    $totalItems = 0; // Predvolene nulová hodnota
                    
                    if ($row = mysqli_fetch_array($result)) {
                        $totalItems = (int) $row['total']; // Zaistenie, že hodnota je celé číslo
                    }
                    
                    // Výpočet počtu strán
                    $totalPages = ($totalItems > 0) ? ceil($totalItems / $itemsPerPage) : 1;
                    
                    // Zobrazenie stránkovania
                    echo '<div class="pagination">';
                    for ($i = 1; $i <= $totalPages; $i++) {
                        echo '<a class="flat-btn" href="?page=' . $i . '">' . $i . '</a>'; // Opravené úvodzovky
                    }
                    echo '</div>';
                  ?> 
            </div><!-- list-->

        </div><!--content-->
      </div><!--main_wrap-->
      
     <dialog class="modal_show_status">
        <ul>
          <li>new</li>
          <li>in progress</li>
          <li>pending</li>
          <li>fixed</li>
          <li>reopened</li>
        </ul>
    </dialog>

    <dialog class="modal_show_priority">
      <ul>
        <li>low</li>
        <li>medium</li>
        <li>high</li>
        <li>critical</li>
      </ul> 
    </dialog>

    <dialog class="modal_add_comment">
      <textarea name="comment_text" placeholder="Add a comment here"></textarea>
      <button type="submit" name="add_comment" class="flat-btn">Add</button>
    </dialog>                  


  </body>
  </html> 
