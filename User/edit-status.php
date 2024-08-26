<?php
include_once('./connectDB.php');
$objCon = connectDB();
/*
$sales = isset($_GET['channel']) ? $_GET['channel'] : NULL;
$uid = "SELECT * FROM xuser WHERE staff_id LIKE '%$sales%'";
$uid = sqlsrv_query($objCon, $uid);
$uid = sqlsrv_fetch_array($uid, SQLSRV_FETCH_ASSOC);
$uid = $uid['usrid'];
*/
$timezone = new DateTimeZone('Asia/Bangkok'); // You can use 'Asia/Bangkok', 'Asia/Jakarta', etc.

// Create a DateTime object with the specified time zone
$date = new DateTime('now', $timezone);
$record_datetime = $date->format('Y-m-d H:i:s'); // For date and time in YYYY-MM-DD HH:MM:SS format


print_r($_POST);

     
sqlsrv_close($objCon);
?>
