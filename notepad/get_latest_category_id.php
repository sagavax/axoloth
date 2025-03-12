<?php

include "../include/dbconnect.php";
include "../include/functions.php";


$new_category_id=GetLatestCategoryId();

echo $new_category_id;