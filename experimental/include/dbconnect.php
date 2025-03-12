<?php
/*
 * Core file for library and parameter handling:
 *
 * - $LastChangedDate: 2009-12-09 23:39:18 +0100 (Mi, 09 Dez 2009) $
 * - $Rev: 276 $
 */

// include("config/config.php");
// --- Connect to DB, retry 5 times ---
//$con = mysqli_connect('mariadb55.websupport.sk', 'm91kz3te', 'j3ZKwdShm2A', 'eis',3310);
$con = mysqli_connect('localhost', 'root', '', 'eis',3306);

/* check connection */
if (!$con) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}


date_default_timezone_set('Europe/Bratislava');

//
// Setup the UTF-8 parameters:
// * http://www.phpforum.de/forum/showthread.php?t=217877#PHP
//
// header('Content-type: text/html; charset=utf-8');

mysqli_select_db($con, 'eis');

mysqli_query($con,'set character set utf8;');
mysqli_query($con,"SET NAMES utf8mb4");



?> 