<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once('./connectDB.php'); // Include your database connection script
$objCon = connectDB(); // Connect to the database

if ($objCon === false) {
    die(print_r(sqlsrv_errors(), true));
}


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

$qt = "SELECT MONTH(qt_date) AS month, COUNT(qt_no) AS qt_no
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