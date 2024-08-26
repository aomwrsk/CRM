<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once('./connectDB.php'); // Include your database connection script
$objCon = connectDB(); // Connect to the database

if ($objCon === false) {
    die(json_encode(["error" => sqlsrv_errors()]));
}

$currentYear = date("Y");
$currentMonth = date("m");
$year_no = isset($_GET['year_no']) ? $_GET['year_no'] : $currentYear;
$month_no = isset($_GET['month_no']) ? $_GET['month_no'] : $currentMonth;
$channel = isset($_GET['channel']) ? $_GET['channel'] : NULL;


$sql = "SELECT qt_no,qt_date,customer_name,province_code,so_amount	 FROM cost_sheet_head
WHERE YEAR(qt_date) = ? AND MONTH(qt_date) = ?";
$params = array($currentYear,$currentMonth);


$stmt = sqlsrv_query($objCon, $sql, $params);

if ($stmt === false) {
    die(json_encode(["error" => sqlsrv_errors()]));
}

$QTData = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $QTData[] = $row;
}

$data = [
    'QTData' => $QTData
];
sqlsrv_free_stmt($stmt);
sqlsrv_close($objCon);

header('Content-Type: application/json');
echo json_encode($data);
?>
