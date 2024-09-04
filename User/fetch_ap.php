<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once('./connectDB.php'); // Include your database connection script
$objCon = connectDB(); // Connect to the database

if ($objCon === false) {
    die(print_r(sqlsrv_errors(), true));
}

$appoint_no = filter_var($_GET['appoint_no'], FILTER_SANITIZE_STRING);
$sql = "SELECT appoint_no FROM appoint_head WHERE appoint_no = ?";
$params = array($appoint_no);

$stmt = sqlsrv_query($objCon, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$ap_data = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $ap_data[] = $row;
}


$data = [
    'ap_data' => $ap_data
];

sqlsrv_close($objCon);
header('Content-Type: application/json');
echo json_encode($data);
?>