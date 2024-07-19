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


if ($year_no == 2024 && $month_no == 0 && $channel == 'N') {
    $year_no = $currentYear;
    $sqlappoint = "SELECT a.appoint_no, a.customer_name, mp.province_name, a.record_date, ms.status_name
                   FROM appoint_head a
                   LEFT JOIN ms_province mp ON a.province_code = mp.province_code
                   LEFT JOIN ms_appoint_status ms ON a.is_status = ms.status_code
                   WHERE  a.year_no = ?";
    $params = array($year_no);
} elseif($year_no <> 0 && $month_no == 0 && $channel <> 'N'){
    $sqlappoint = "SELECT ah.appoint_no, ah.customer_name, mp.province_name, ah.record_date, ms.status_name
                   FROM appoint_head ah
                   LEFT JOIN ms_province mp ON ah.province_code = mp.province_code
                   LEFT JOIN ms_appoint_status ms ON ah.is_status = ms.status_code
                   WHERE ah.year_no = ? AND ah.is_call = ?";
    $params = array($year_no, $channel);
}elseif($year_no <> 0 && $month_no <> 0 && $channel == 'N'){
    $sqlappoint = "SELECT ah.appoint_no, ah.customer_name, mp.province_name, ah.record_date, ms.status_name
                   FROM appoint_head ah
                   LEFT JOIN ms_province mp ON ah.province_code = mp.province_code
                   LEFT JOIN ms_appoint_status ms ON ah.is_status = ms.status_code
                   WHERE ah.month_no = ? AND ah.year_no = ? ";
                   $params = array($month_no, $year_no);
}else{
    $sqlappoint = "SELECT ah.appoint_no, ah.customer_name, mp.province_name, ah.record_date, ms.status_name
                   FROM appoint_head ah
                   LEFT JOIN ms_province mp ON ah.province_code = mp.province_code
                   LEFT JOIN ms_appoint_status ms ON ah.is_status = ms.status_code
                   WHERE ah.month_no = ? AND ah.year_no = ? AND ah.is_call = ?";
                   $params = array($month_no, $year_no, $channel);
}

$stmt = sqlsrv_query($objCon, $sqlappoint, $params);

if ($stmt === false) {
    die(json_encode(["error" => sqlsrv_errors()]));
}

$data = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $appoint = isset($row['appoint_no']) ? htmlspecialchars($row['appoint_no']) : '';
    $provinceName = isset($row['province_name']) ? htmlspecialchars($row['province_name']) : '';
    $customername = isset($row['customer_name']) ? htmlspecialchars($row['customer_name']) : '';
    $status = isset($row['status_name']) ? htmlspecialchars($row['status_name']) : '';
    $data[] = array(
        "ap" =>  $appoint,
        "name" => $customername,
        "city" => $provinceName,
        "date" => htmlspecialchars($row['record_date']), // Format DateTime object to string
        "status" => $status
    );
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($objCon);

header('Content-Type: application/json');
echo json_encode($data);
?>
