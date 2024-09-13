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
$Sales = isset($_GET['Sales']) ? $_GET['Sales'] : NULL;
$year_no = isset($_GET['year_no']) ? $_GET['year_no'] : $currentYear;
$month_no = isset($_GET['month_no']) ? $_GET['month_no'] : $currentMonth;
/*$channel = isset($_GET['channel']) ? $_GET['channel'] : NULL;*/

if($year_no <> 0 && $month_no <> 0 && $Sales == 'N'){
    $sqlappoint = "SELECT FORMAT(A.appoint_date, 'dd-MM-yyy') As appoint_date,A.customer_name, A.qt_no,FORMAT(A.so_amount, 'N2') AS so_amount,pp.prospect_name,pp.prospect_code, A.remark,ms.status_name,ms.status_code,A.reasoning
                   FROM cost_sheet_head A
                   LEFT JOIN ms_appoint_status ms ON a.is_tracking = ms.status_code
				   LEFT JOIN ms_prospect pp ON a.is_prospect = pp.prospect_code
                   LEFT JOIN  so_customer_status B ON A.qt_no = B.qt_no
                   WHERE MONTH(A.qt_date) = ? AND YEAR(A.qt_date) = ? AND is_status <> 'C'  AND B.so_no IS NULL
                   ORDER BY qt_date DESC";
                   $params = array($month_no, $year_no);
}else{
    $sqlappoint = "SELECT FORMAT(A.appoint_date, 'dd-MM-yyy') As appoint_date,A.customer_name, A.qt_no,FORMAT(A.so_amount, 'N2') AS so_amount,pp.prospect_name,pp.prospect_code, A.remark,ms.status_name,ms.status_code,A.reasoning
                   FROM cost_sheet_head A
                   LEFT JOIN ms_appoint_status ms ON a.is_tracking = ms.status_code
				   LEFT JOIN ms_prospect pp ON a.is_prospect = pp.prospect_code
                   LEFT JOIN  so_customer_status B ON A.qt_no = B.qt_no
                   WHERE MONTH(A.qt_date) = ? AND YEAR(A.qt_date) = ? AND A.staff_id = ?  AND is_status <> 'C'  AND B.so_no IS NULL
                   ORDER BY qt_date DESC";
                   $params = array($month_no, $year_no, $Sales);
}

$stmt = sqlsrv_query($objCon, $sqlappoint, $params);

if ($stmt === false) {
    die(json_encode(["error" => sqlsrv_errors()]));
}

$data = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $appoint_date = isset($row['appoint_date']) ? htmlspecialchars($row['appoint_date']) : '';
    $customername = isset($row['customer_name']) ? htmlspecialchars($row['customer_name']) : '';
    $qt_no = isset($row['qt_no']) ? htmlspecialchars($row['qt_no']) : '';
    $so_amount = isset($row['so_amount']) ? htmlspecialchars($row['so_amount']) : '';
    $prospect_name = isset($row['prospect_name']) ? htmlspecialchars($row['prospect_name']) : '';
    $prospect_code = isset($row['prospect_code']) ? htmlspecialchars($row['prospect_code']) : '';
    $remark = isset($row['remark']) ? htmlspecialchars($row['remark']) : '';
    $status_name = isset($row['status_name']) ? htmlspecialchars($row['status_name']) : '';
    $status_code = isset($row['status_code']) ? htmlspecialchars($row['status_code']) : '';
    $reasoning = isset($row['reasoning']) ? htmlspecialchars($row['reasoning']) : '';
    $data[] = array(
        "date" => $appoint_date,
        "name" =>  $customername,
        "qt_no" => $qt_no,
        "so_amount" => $so_amount,
        "prospect_name" => $prospect_name,
        "prospect_code" => $prospect_code,
        "remark" => $remark,
        "status_name" => $status_name,
        "status_code" => $status_code,
        "reasoning" => $reasoning
    );
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($objCon);

header('Content-Type: application/json');
echo json_encode($data);
?>
