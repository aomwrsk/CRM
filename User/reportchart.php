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


if ($Sales <> 'N') {
    $condition =  "year_no = ? AND staff_id = ?";
    $conditionqt =  "is_status <> 'C' AND  YEAR(qt_date) = ? AND staff_id = ?";
}else{
    $condition =  "year_no = ?";
    $conditionqt =  "is_status <> 'C' AND  YEAR(qt_date) = ?";
}
    $ap = "SELECT 
    FORMAT(DATEFROMPARTS(year_no, month_no, 1), 'yyyy-MM') AS format_date,
    COUNT(appoint_no) AS appoint_no
FROM 
    appoint_head
WHERE 
    $condition
GROUP BY 
    FORMAT(DATEFROMPARTS(year_no, month_no, 1), 'yyyy-MM')
ORDER BY 
    format_date ASC
";

$qt = "	SELECT FORMAT(DATEFROMPARTS(YEAR(qt_date), MONTH(qt_date), 1), 'yyyy-MM') AS format_date, SUM(so_amount) AS cost_amount,COUNT(qt_no) AS qt_no
FROM cost_sheet_head
WHERE $conditionqt
GROUP BY FORMAT(DATEFROMPARTS(YEAR(qt_date), MONTH(qt_date), 1), 'yyyy-MM')
ORDER BY format_date ASC";

$so = "SELECT  FORMAT(DATEFROMPARTS(year_no, month_no, 1), 'yyyy-MM') AS format_date, COUNT(so_no) AS so_no
FROM View_SO_SUM
WHERE $condition
GROUP BY  FORMAT(DATEFROMPARTS(year_no, month_no, 1), 'yyyy-MM')
ORDER BY format_date ASC";

   
    if ($Sales <> 'N') {
        $params = array($year_no, $Sales);
    }else{
        $params = array($year_no);
    }


$stmt = sqlsrv_query($objCon, $ap, $params);
$APData = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $APData[] = $row;
}


$stmt1 = sqlsrv_query($objCon, $qt, $params);
$QTData = [];
while ($row1 = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
    $QTData[] = $row1;
}


$stmt2 = sqlsrv_query($objCon, $so, $params);
$SOData = [];
while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
    $SOData[] = $row2;
}

$report = [
    'APData' => $APData,
    'QTData' => $QTData,
    'SOData' => $SOData
];
sqlsrv_close($objCon);
header('Content-Type: application/json');
echo json_encode($report);
?>