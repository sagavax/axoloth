<?php

include "../include/dbconnect.php";
include "../include/functions.php";


$new_note_id=GetLatestNote();

echo $new_note_id;