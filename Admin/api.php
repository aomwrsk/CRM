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
$channel = isset($_GET['channel']) ? $_GET['channel'] : NULL;

if ($year_no <> 0 && $month_no == 0 && $channel == 'N' && $Sales == 'N') {
    $sqlappoint = "SELECT a.appoint_no,FORMAT(a.appoint_date, 'dd-MM-yyy') As appoint_date,a.customer_name, a.qt_no,FORMAT(cs.so_amount, 'N2') AS so_amount,pp.prospect_name,pp.prospect_code, a.remark,ms.status_name,ms.status_code,a.reasoning
                   FROM appoint_head a
                   LEFT JOIN ms_province mp ON a.province_code = mp.province_code
                   LEFT JOIN ms_appoint_status ms ON a.is_status = ms.status_code
				   LEFT JOIN ms_prospect pp ON a.is_prospect = pp.prospect_code
				   LEFT JOIN cost_sheet_head cs ON a.qt_no = cs.qt_no
                   WHERE  a.year_no = ?";
    $params = array($year_no);
} elseif($year_no <> 0 && $month_no == 0 && $channel <> 'N' && $Sales == 'N'){
    $sqlappoint = "SELECT a.appoint_no,FORMAT(a.appoint_date, 'dd-MM-yyy') As appoint_date,a.customer_name, a.qt_no,FORMAT(cs.so_amount, 'N2') AS so_amount,pp.prospect_name,pp.prospect_code, a.remark,ms.status_name,ms.status_code,a.reasoning
                   FROM appoint_head a
                   LEFT JOIN ms_province mp ON a.province_code = mp.province_code
                   LEFT JOIN ms_appoint_status ms ON a.is_status = ms.status_code
				   LEFT JOIN ms_prospect pp ON a.is_prospect = pp.prospect_code
				   LEFT JOIN cost_sheet_head cs ON a.qt_no = cs.qt_no
                   WHERE a.year_no = ? AND a.is_call = ?";
    $params = array($year_no, $channel);
}elseif($year_no <> 0 && $month_no <> 0 && $channel == 'N' && $Sales == 'N'){
    $sqlappoint = "SELECT a.appoint_no,FORMAT(a.appoint_date, 'dd-MM-yyy') As appoint_date,a.customer_name, a.qt_no,FORMAT(cs.so_amount, 'N2') AS so_amount,pp.prospect_name,pp.prospect_code, a.remark,ms.status_name,ms.status_code,a.reasoning
                   FROM appoint_head a
                   LEFT JOIN ms_province mp ON a.province_code = mp.province_code
                   LEFT JOIN ms_appoint_status ms ON a.is_status = ms.status_code
				   LEFT JOIN ms_prospect pp ON a.is_prospect = pp.prospect_code
				   LEFT JOIN cost_sheet_head cs ON a.qt_no = cs.qt_no
                   WHERE a.month_no = ? AND a.year_no = ? ";
                   $params = array($month_no, $year_no);
}elseif($year_no <> 0 && $month_no <> 0 && $channel == 'N' && $Sales <> 'N'){
    $sqlappoint = "SELECT a.appoint_no,FORMAT(a.appoint_date, 'dd-MM-yyy') As appoint_date,a.customer_name, a.qt_no,FORMAT(cs.so_amount, 'N2') AS so_amount,pp.prospect_name,pp.prospect_code, a.remark,ms.status_name,ms.status_code,a.reasoning
                   FROM appoint_head a
                   LEFT JOIN ms_province mp ON a.province_code = mp.province_code
                   LEFT JOIN ms_appoint_status ms ON a.is_status = ms.status_code
				   LEFT JOIN ms_prospect pp ON a.is_prospect = pp.prospect_code
				   LEFT JOIN cost_sheet_head cs ON a.qt_no = cs.qt_no
                   WHERE a.month_no = ? AND a.staff_id = ? AND a.year_no = ? ";
                   $params = array($month_no, $Sales, $year_no);
}else{
    $sqlappoint = "SELECT a.appoint_no,FORMAT(a.appoint_date, 'dd-MM-yyy') As appoint_date,a.customer_name, a.qt_no,FORMAT(cs.so_amount, 'N2') AS so_amount,pp.prospect_name,pp.prospect_code, a.remark,ms.status_name,ms.status_code,a.reasoning
                   FROM appoint_head a
                   LEFT JOIN ms_province mp ON a.province_code = mp.province_code
                   LEFT JOIN ms_appoint_status ms ON a.is_status = ms.status_code
				   LEFT JOIN ms_prospect pp ON a.is_prospect = pp.prospect_code
				   LEFT JOIN cost_sheet_head cs ON a.qt_no = cs.qt_no
                   WHERE a.month_no = ? AND a.year_no = ? AND a.is_call = ? AND a.staff_id = ?";
                   $params = array($month_no, $year_no, $channel,$Sales);
}

$stmt = sqlsrv_query($objCon, $sqlappoint, $params);

if ($stmt === false) {
    die(json_encode(["error" => sqlsrv_errors()]));
}

$data = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $appoint_no = isset($row['appoint_no']) ? htmlspecialchars($row['appoint_no']) : '';
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
        "appoint_no" => htmlspecialchars($row['appoint_no']), 
        "date" => htmlspecialchars($row['appoint_date']), 
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
