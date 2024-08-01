<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once('./connectDB.php'); // Include your database connection script
$objCon = connectDB(); // Connect to the database

if ($objCon === false) {
    die(print_r(sqlsrv_errors(), true));
}
$currentYear = date("Y");
$currentMonth = date("m");
$year_no = isset($_GET['year_no']) ? $_GET['year_no'] : $currentYear;
$month_no = isset($_GET['month_no']) ? $_GET['month_no'] : $currentMonth;
$channel = isset($_GET['channel']) ? $_GET['channel'] : NULL;
$Sales = isset($_GET['Sales']) ? $_GET['Sales'] : NULL;
$is_new = isset($_GET['is_new']) ? $_GET['is_new'] : NULL;

$ap = "SELECT month_no, COUNT(appoint_no) AS appoint_no
FROM appoint_head
WHERE year_no = YEAR(GETDATE())
GROUP BY month_no
ORDER BY month_no ASC";
$stmt = sqlsrv_query($objCon, $ap);
$APData = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $APData[] = $row;
}

$qt = "SELECT MONTH(qt_date) AS month, SUM(so_amount) AS cost_amount,COUNT(qt_no) AS qt_no
FROM cost_sheet_head
WHERE YEAR(qt_date) = YEAR(GETDATE()) AND is_status <> 'C'
GROUP BY MONTH(qt_date)
ORDER BY MONTH(qt_date) ASC";
$stmt1 = sqlsrv_query($objCon, $qt);
$QTData = [];
while ($row1 = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
    $QTData[] = $row1;
}

$so = "SELECT month_no, COUNT(so_no) AS so_no
FROM so_head
WHERE year_no = YEAR(GETDATE())
GROUP BY month_no
ORDER BY month_no ASC";
$stmt2 = sqlsrv_query($objCon, $so);
$SOData = [];
while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
    $SOData[] = $row2;
}

$data = [
    'APData' => $APData,
    'QTData' => $QTData,
    'SOData' => $SOData
];
sqlsrv_close($objCon);
header('Content-Type: application/json');
echo json_encode($data);
?>