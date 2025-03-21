<?php
session_start();
/* if (!isset($_SESSION['login'])) {
    header("location:index.php");
} */

include "../include/dbconnect.php";
include "../include/functions.php";
?>

<?php
if (isset($_POST['update_password'])) {
    global $con;
    $customer = $_POST['customer'];
    /* $group_name=mysqli_real_escape_string($con,$_POST['group_name']); */
    $pass_id = intval($_POST['id']); // Password ID
    $system_name = mysqli_real_escape_string($con, $_POST['system_name']);
    $user_name = mysqli_real_escape_string($con, $_POST['user_name']);
    $password1 = mysqli_real_escape_string($con, $_POST['password']);
    $old_password = mysqli_real_escape_string($con, $_POST['old_password']);
    $url = mysqli_real_escape_string($con, $_POST['url']);
    $category = $_POST['category'];
    $description = mysqli_real_escape_string($con, $_POST['description']);

    if (isset($_POST['is_favorite'])) {
        $is_favorite = 1;
    } else {
        $is_favorite = 0;
    }
    /* var_dump($_POST); */

    /* if($old_password==$password1){
            // No change
            header("location:index.php");
        } else { */
    $sql = "UPDATE tblpasswords SET cust_id=$customer, system_name='$system_name', user_name='$user_name', password='$password1', url='$url', is_favorite=$is_favorite, description='$description' WHERE PassID=$pass_id";

    /* echo $sql; */

    $result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());

    // Write to log
    $curr_date = date('Y-m-d H:i:s');
    $curr_action = "update_password";
    $curr_app = "passmgr";
    $text_logu = "The password with id $pass_id has been updated";

    // Write to historical records
    $sql = "INSERT INTO tblpasswords_history (PassID, old_password, created_date) VALUES ($pass_id, '$old_password', '$curr_date')";
    // echo $sql;
    $result = mysqli_query($con, $sql); // or die("MySQL ERROR: ".mysqli_error());

    // Insert to timeline
    $sql = "INSERT INTO tblpasswords_timeline (pass_id, pass_action, action_time) VALUES ($pass_id, 'password has been modified', '$curr_date')";
    $result = mysqli_query($con, $sql); // or die("MySQL ERROR: ".mysqli_error());

    $sql = "INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action', '$curr_app', '$text_logu', '$curr_date', 0)";
    // echo $sql;

    $result = mysqli_query($con, $sql); // or die("MySQL ERROR: ".mysqli_error());
    echo "<script>alert('The password with id $pass_id has been updated'); location.href='index.php'</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet'>
    <link href="../css/style_new.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../css/passmgr.css?<?php echo time(); ?>" type='text/css'>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/thinline.css">
    <script type="text/javascript" src="../js/pass_gen.js" defer></script>
    <script type="text/javascript" src="../js/pass_note.js" defer></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include "../include/icon.php" ?>
    <title>Axoloth - Passwords Edit</title>
</head>

<body>
     <?php
    include '../include/header.php';
    ?>

    <div id="layout">
        <?php
        /* Chosen server name */
        $id = $_GET['id'];
        $sql = "SELECT * FROM tblpasswords WHERE PassID='$id'";
        global $con;
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($result);
        ?>

        <div class="password">
            <form accept-charset="utf-8" method="post" action="" name="edit_password">
                <input type="hidden" name="id" value="<?php echo $row['PassID']; ?>" />
                <input type="hidden" name="old_password" value="<?php echo $row['password'] ?>">
                <table>
                    <tr>
                        <td>
                            <select name="customer">
                                <?php
                                $cust_id = $row['cust_id'];
                                $customer = CustomerName($cust_id);
                                echo "<option value=$cust_id selected=\"selected\">$customer</option>";
                                $sql1 = "SELECT * FROM tblcustomers WHERE customer_status='active' ORDER BY customer_name ASC";
                                global $con;
                                $result1 = mysqli_query($con, $sql1);
                                while ($row1 = mysqli_fetch_array($result1)) {
                                    $cust_id = $row1['cust_id'];
                                    $customer_name = $row1['customer_name'];
                                    echo "<option value='$cust_id'>$customer_name</option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="text" name="system_name" value="<?php echo $row['system_name'] ?>"></td>
                    </tr>
                    <tr>
                        <td><input type="text" name="user_name" value="<?php echo $row['user_name'] ?>"></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="embed-button">
                                <input type="text" name="password" id="password" value="<?php echo $row['password'] ?>">
                                <button onclick="generate_password()" class="flat-btn" type="button" id="generuj_pass">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                                <button type="button" class="flat-btn" id="copy_to_clip" onclick="copyPassword()">
                                    <i class="fas fa-clipboard"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <select name="category">
                                <option value=<?php echo $row['category'] ?> selected><?php echo $row['category'] ?></option>
                                <option value="Private">Private</option>
                                <option value="Business">Business</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="text" name="url" value="<?php echo $row['url'] ?>"></td>
                    </tr>
                    <tr>
                        <td>
                            <div id="favorite_wrap">
                                <input name="is_favorite" id="is_favorite" type="checkbox" <?php if ($row['is_favorite'] == 1) { ?> checked <?php } ?>>
                                <label for="is_favorite">Favorite</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><textarea name="description"><?php echo $row['description'] ?></textarea></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="pass_edit_action">
                                <a href='index.php' class="flat-btn">&lt;&lt; Back</a>
                                <button type="submit" name="update_password" class="flat-btn">Update</button>
                            </div>
                        </td>
                    </tr>
                </table>
            </form>

            <div class="password_notes">
                <header><span>Notes:</span><button class="flat-btn" type="button" onclick="show_hide_note();"><i class="fa fa-plus"></i></button></header>
                <div class="pass_notes_body">
                    <div class="pass_note_container">
                        <?php
                        $get_notes = "SELECT * FROM tblpassword_notes WHERE pass_id=" . $row['PassID'] . " ORDER BY id DESC";
                        $result_notes = mysqli_query($con, $get_notes);
                        while ($row_notes = mysqli_fetch_array($result_notes)) {
                            $id = $row_notes['id'];
                            $note_text = $row_notes['note_text'];
                            $update_date = $row_notes['created_date'];

                            echo "<div class='pass_note' note-id='$id'>";
                            echo "<button class='close' type='button' onclick='remove_pass_note($id);'><i class='fa fa-times'></i></button>";
                            echo $note_text;
                            echo "</div>";
                        }
                        ?>
                        <textarea id="pass_note_text" placeholder="Enter a note"></textarea>
                        <button type="button" id="save_note" class="flat-btn" onclick="save_note(<?php echo $row['PassID'] ?>)">Add</button>
                    </div><!-- pass_note_container -->
                </div><!-- pass_notes_body -->
            </div><!-- password_notes -->
        </div><!-- password -->
    </div><!-- layout -->
</body>

</html>