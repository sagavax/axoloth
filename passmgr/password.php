<?php session_start();
/* $_SESSION['url'] = $_SERVER['REQUEST_URI'];
if (!isset($_SESSION['login'])) {
	header("location:index.php");
} */

?>

<?php include "../include/dbconnect.php";?>
<?php include_once "../include/config.php";?>

<!DOCTYPE html>

<head>
    <meta charset="utf-8" />
    <link href="../css/style_new.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <link href="../css/passmgr.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css' rel='stylesheet' type='text/css'>
    <link href="../css/tabs.css" rel="stylesheet" type="text/css" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <script type="text/javascript" src="../js/pass_gen.js" defer></script>
    <script src="../js/password.js"></script>
    <link rel='shortcut icon' href='../a.png'>
    <title>axoloth - Passwords</title>

</head>

<body>



   <?php
include '../include/header.php';
?>



    <div id="layout">
        <div id="passwords_wrap">



 <?php
$id = $_GET['id'];
/* chosen server name */
$sql = "SELECT * FROM tblpasswords WHERE PassID='$id'";
global $con;
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_array($result);
?>

     <div class="password">
       <div class="password_body">
        <table>

            <td><input type="text" value="<?php echo $row['group_name'] ?>"> </td>
           </tr>

          <tr>
            <td><input type="text" value="<?php echo $row['system_name'] ?>"></td>
          </tr>
          <tr>
            <td><input id="username_id" type="text" value="<?php echo $row['user_name'] ?>"></td>
          </tr>
          <tr>
            <td><div class="embed-button"><input id="password" type="text" value="<?php echo $row['password'] ?>"><button type="button" class="flat-btn" id="copy_to_clip" onclick="copyPassword()"><i class="fas fa-clipboard"></i></button></div></td>
          </tr>
           <tr>
             <td> <input type="text" value="<?php echo $row['category'] ?>"></td><td></td>
          </tr>
          <tr>
             <td><div class="embed-button"><input type="text" placeholder="Url" value="<?php echo $row['url'] ?>" id="pass_url">

                <?php
if (!empty($row['url'])) {
	$url = $row['url'];
	echo "<button type='button' class='flat-btn' onclick='goURL();'><i class='fa fa-arrow-right'></i></button>";
}
?>
                </div>
             </td>
          </tr>
          <tr>
             <td><div id="favorite_wrap"><input id="is_favorite" type="checkbox" <?php
if ($row['is_favorite'] == 1) {
	?> checked <?php
}

?>><label for="is_favorite">Favorite</label></div></td>
          </tr>
          <tr>
          <td><input type="text" value="<?php echo $row['date_added'] ?>"></td>
          </tr>

          <tr>
          <td><input type="text" value="<?php echo nl2br(htmlspecialchars($row['description'])) ?>"></td>
          </tr>
          <tr>
          <td style="text-align:right"><a  class="flat-btn" href="password_edit.php?id=<?php echo $row['PassID'] ?>">Edit</a> <a href='index.php' class="flat-btn">&lt;&lt; Back</a></td>
          </tr>
       </table>
       </div><!--password body-->
       <div class="password_notes">
          <?php
$get_notes = "SELECT * from tblpassword_notes WHERE pass_id=$id";
$result_notes = mysqli_query($con, $get_notes);
while ($row_notes = mysqli_fetch_array($result_notes)) {
	$id = $row_notes['id'];
	$note_text = $row_notes['note_text'];
	$update_date = $row_notes['updated_date'];

	echo "<div class='pass_note'>";
	echo $note_text;
	echo "</div>";
}
?>
        </div><!-- password notes -->
        <div class="password_tags">

        </div>
      </div><!-- password -->


      <?php
$actual_date = date('Y-m-d H:i:s');
global $con;
$sql = "UPDATE tblpasswords SET hits=hits+1, date_hit=now() WHERE PassID=" . $row['PassID'];
$result = mysqli_query($con, $sql);

//logovanie visitora
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
	//check ip from share internet
	$ip_address = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	//to check ip is pass from proxy
	$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
	$ip_address = $_SERVER['REMOTE_ADDR'];
}

//$sql = "INSERT INTO tblpasswords_visitors (pass_id, visitor_ip_address,visited_date) VALUES (" . $row['PassID'] . ",'$ip_address','$actual_date')";
//$result = mysqli_query($con, $sql);

//vlozenie to time liny
$sql = "INSERT INTO tblpasswords_timeline (pass_id,pass_action, action_time) VALUES (" . $row['PassID'] . ",'password has been visited','$actual_date')";
$result = mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error());

//echo $sql;
?>

<div class="password_notes">
        <header><span>Notes:</span><button class="flat-btn" type="button" onclick="show_hide_note()";><i class="fa fa-plus"></i></button></header>
        <div class="pass_notes_body">
          <div class="pass_note_container">
          <?php
              $get_notes = "SELECT * from tblpassword_notes WHERE pass_id=" . $row['PassID'] . " ORDER BY id DESC";
              $result_notes = mysqli_query($con, $get_notes);
              while ($row_notes = mysqli_fetch_array($result_notes)) {
                $id = $row_notes['id'];
                $note_text = $row_notes['note_text'];
                $update_date = $row_notes['updated_date'];

                echo "<div class='pass_note' note-id='$id'>";
                echo "<button class='close' type='button' onclick='remove_pass_note($id);'><i class='fa fa-times'></i></button>";
                echo $note_text;
                echo "</div>";
              }
              ?>
          </div><!--pass_note_container -->
      </div><!--pass_notes_body-->
      </div><!--password_notes-->
  </div><!-- password_wrap -->
</div><!--layout-->

<script>
  
</script>