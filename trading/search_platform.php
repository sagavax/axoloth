<?php
    session_start();
    include("../include/dbconnect.php");
    include("../include/functions.php");

    global $con;


    $platform = $_GET['platform'];

    $search_platform = "SELECT * from tbltrading_platforms WHERE platform_name LIKE'%$platform%' ORDER BY platform_name ASC";
    $result = mysqli_query($con, $search_platform)  or die("MySQL ERROR: ".mysqli_error($con));
    while ($row = mysqli_fetch_array($result)) {
        $id=$row['id'];
        $platform = $row['platform_name'];
        $platform_url = $row['platform_url'];
        $platform_name = GetPlatfomName($id);
        $platform_logo = $row['platform_logo'];
        echo "<div class='platform' id=$id platform-url=$platform_logo>";
            echo "<div class='platform_logo'>";
            if($platform_logo<>""){
                echo "<img src='$platform_logo'>";
            }                                        
            echo "</div><div class='platform_name'>$platform</div>";
            echo "<div class='platf_actions'><button class='btn-small add_platf_note' type='button' title='add note'><i class='fa fa-plus'></i></button><button class='btn-small add_platf_asset' type='button' title='add asset'><i class='fa fa-plus' class=''></i></button><button class='btn-small remove_platform' type='button title='delete platform'><i class='fa fa-times'></i></button></div>";
        echo "</div>";

        echo "<div class='trading_feeds' platfotm-id=$id platform-name=$platform_name>";
            $get_platf_notes = "SELECT * from tbltrading_platform_notes where platform_id=$id";
            //echo $get_platf_notes;
            $result_platf_notes = mysqli_query($con, $get_platf_notes)  or die("MySQL ERROR: ".mysqli_error($con));
            while ($row_note = mysqli_fetch_array($result_platf_notes)){
                $note_id = $row_note['note_id'];
                $note_text = $row_note['note_text'];

                echo "<div class='note' note-id=$note_id platform-id=$id>";
                    echo "<div class='note_text'>$note_text</div>";
                    echo "<div class='note_action'><button type='button' class='btn-small note_edit'><i class='fa fa-edit'></i></button><button type='button' class='btn-small note_delete'><i class='fa fa-times'></i></button></div>";
                echo "</div>";
            }

        echo "</div>";
        }   

?>
</ul>