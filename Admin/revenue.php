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


if ($year_no <> 0 && $month_no == 0 && $channel == 'N' && $Sales == 'N' && $is_new == 0) {
    $sqlrevenue = "SELECT so_no,total_before_vat FROM View_SO_SUM WHERE year_no = ?";
    $sqlappoint = "SELECT appoint_no,customer_name,province_name FROM appoint_head WHERE year_no = ?";
    $sqlsegment = "SELECT b.customer_segment_name, COUNT(a.customer_segment_code) AS segment_count 
                   FROM appoint_head a
                   LEFT JOIN ms_customer_segment b ON a.customer_segment_code = b.customer_segment_code
                   WHERE a.year_no = ?
                   GROUP BY b.customer_segment_name";
    $sqlcostsheet = "SELECT 
    sales_channels_group_code,
    is_new,
    qt_no,
    A.so_amount AS amount,
    MONTH(A.qt_date) AS month
FROM cost_sheet_head A
    WHERE 
        A.is_status <> 'C' 
        AND YEAR(A.qt_date) = ?";
    $params = array($year_no);
}elseif($year_no <> 0 && $month_no <> 0 && $channel == 'N' && $Sales == 'N' && $is_new == 0){
    $sqlrevenue = "SELECT so_no,total_before_vat FROM View_SO_SUM WHERE year_no = ? AND month_no = ?";
    $sqlappoint = "SELECT appoint_no,customer_name,province_name FROM appoint_head WHERE year_no = ? AND month_no = ?";
    $sqlsegment = "SELECT b.customer_segment_name, COUNT(a.customer_segment_code) AS segment_count 
                   FROM appoint_head a
                   LEFT JOIN ms_customer_segment b ON a.customer_segment_code = b.customer_segment_code
                   WHERE a.year_no = ? AND month_no = ?
                   GROUP BY b.customer_segment_name";
    $sqlcostsheet = "SELECT 
    sales_channels_group_code,
    is_new,
    qt_no,
    A.so_amount AS amount,
    MONTH(A.qt_date) AS month
FROM cost_sheet_head A
    WHERE 
        A.is_status <> 'C' 
        AND YEAR(A.qt_date) = ? AND MONTH(A.qt_date) = ?";
    $params = array($year_no, $month_no);
}elseif($year_no <> 0 && $month_no == 0 && $channel <> 'N' && $Sales == 'N' && $is_new == 0){
    $sqlrevenue = "SELECT so_no,total_before_vat FROM View_SO_SUM WHERE year_no = ? AND ระบบ = ?";
    $sqlappoint = "SELECT appoint_no,customer_name,province_name FROM appoint_head WHERE year_no = ? AND is_call = ?";
    $sqlsegment = "SELECT b.customer_segment_name, COUNT(a.customer_segment_code) AS segment_count 
                   FROM appoint_head a
                   LEFT JOIN ms_customer_segment b ON a.customer_segment_code = b.customer_segment_code
                   WHERE a.year_no = ? AND is_call = ?
                   GROUP BY b.customer_segment_name";
    $sqlcostsheet = "SELECT 
    sales_channels_group_code,
    is_new,
    qt_no,
    A.so_amount AS amount,
    MONTH(A.qt_date) AS month
FROM cost_sheet_head A
    WHERE 
        A.is_status <> 'C' 
        AND YEAR(A.qt_date) = ? AND sales_channels_group_code = ?";
    $params = array($year_no, $channel);
}elseif($year_no <> 0 && $month_no == 0 && $channel == 'N' && $Sales <> 'N' && $is_new == 0){
    $sqlrevenue = "SELECT so_no,total_before_vat FROM View_SO_SUM WHERE year_no = ? AND staff_id = ?";
    $sqlappoint = "SELECT appoint_no,customer_name,province_name FROM appoint_head WHERE year_no = ? AND staff_id = ?";
    $sqlsegment = "SELECT b.customer_segment_name, COUNT(a.customer_segment_code) AS segment_count 
                   FROM appoint_head a
                   LEFT JOIN ms_customer_segment b ON a.customer_segment_code = b.customer_segment_code
                   WHERE a.year_no = ? AND staff_id = ?
                   GROUP BY b.customer_segment_name";
    $sqlcostsheet = "SELECT 
    sales_channels_group_code,
    is_new,
    qt_no,
    A.so_amount AS amount,
    MONTH(A.qt_date) AS month
FROM cost_sheet_head A
    WHERE 
        A.is_status <> 'C' 
        AND YEAR(A.qt_date) = ? AND staff_id = ?";
    $params = array($year_no, $Sales);
}elseif($year_no <> 0 && $month_no == 0 && $channel == 'N' && $Sales == 'N' && $is_new <> 0){
    $sqlrevenue = "SELECT so_no,total_before_vat FROM View_SO_SUM WHERE year_no = ? AND is_new = ?";
    $sqlappoint = "SELECT appoint_no,customer_name,province_name FROM appoint_head WHERE year_no = ?";
    $sqlsegment = "SELECT b.customer_segment_name, COUNT(a.customer_segment_code) AS segment_count 
                   FROM appoint_head a
                   LEFT JOIN ms_customer_segment b ON a.customer_segment_code = b.customer_segment_code
                   WHERE a.year_no = ?
                   GROUP BY b.customer_segment_name";
    $sqlcostsheet = "SELECT 
    sales_channels_group_code,
    is_new,
    qt_no,
    A.so_amount AS amount,
    MONTH(A.qt_date) AS month
FROM cost_sheet_head A
    WHERE 
        A.is_status <> 'C' 
        AND YEAR(A.qt_date) = ? AND is_new = ?";
    $params = array($year_no, $is_new);
}elseif($year_no <> 0 && $month_no <> 0 && $channel <> 'N' && $Sales == 'N' && $is_new == 0){
    $sqlrevenue = "SELECT so_no,total_before_vat FROM View_SO_SUM WHERE year_no = ? AND month_no = ? AND ระบบ = ?";
    $sqlappoint = "SELECT appoint_no,customer_name,province_name FROM appoint_head WHERE year_no = ? AND month_no = ? AND is_call = ?";
    $sqlsegment = "SELECT b.customer_segment_name, COUNT(a.customer_segment_code) AS segment_count 
                   FROM appoint_head a
                   LEFT JOIN ms_customer_segment b ON a.customer_segment_code = b.customer_segment_code
                   WHERE a.year_no = ? AND month_no = ? AND is_call = ?
                   GROUP BY b.customer_segment_name";
    $sqlcostsheet = "SELECT 
    sales_channels_group_code,
    is_new,
    qt_no,
    A.so_amount AS amount,
    MONTH(A.qt_date) AS month
FROM cost_sheet_head A
    WHERE 
        A.is_status <> 'C' 
        AND YEAR(A.qt_date) = ? AND MONTH(A.qt_date) = ? AND sales_channels_group_code = ?";
    $params = array($year_no, $month_no, $channel);
}elseif($year_no <> 0 && $month_no <> 0 && $channel == 'N' && $Sales <> 'N' && $is_new == 0){
    $sqlrevenue = "SELECT so_no,total_before_vat FROM View_SO_SUM WHERE year_no = ? AND month_no = ? AND staff_id = ?";
    $sqlappoint = "SELECT appoint_no,customer_name,province_name FROM appoint_head WHERE year_no = ? AND month_no = ? AND staff_id = ?";
    $sqlsegment = "SELECT b.customer_segment_name, COUNT(a.customer_segment_code) AS segment_count 
                   FROM appoint_head a
                   LEFT JOIN ms_customer_segment b ON a.customer_segment_code = b.customer_segment_code
                   WHERE a.year_no = ? AND month_no = ? AND staff_id = ?
                   GROUP BY b.customer_segment_name";
    $sqlcostsheet = "SELECT 
    sales_channels_group_code,
    is_new,
    qt_no,
    A.so_amount AS amount,
    MONTH(A.qt_date) AS month
FROM cost_sheet_head A
    WHERE 
        A.is_status <> 'C' 
        AND YEAR(A.qt_date) = ? AND MONTH(A.qt_date) = ? AND staff_id = ?";
    $params = array($year_no, $month_no, $Sales);
}elseif($year_no <> 0 && $month_no <> 0 && $channel == 'N' && $Sales == 'N' && $is_new <> 0){
    $sqlrevenue = "SELECT so_no,total_before_vat FROM View_SO_SUM WHERE year_no = ? AND month_no = ? AND is_new = ?";
    $sqlappoint = "SELECT appoint_no,customer_name,province_name FROM appoint_head WHERE year_no = ? AND month_no = ?";
    $sqlsegment = "SELECT b.customer_segment_name, COUNT(a.customer_segment_code) AS segment_count 
                   FROM appoint_head a
                   LEFT JOIN ms_customer_segment b ON a.customer_segment_code = b.customer_segment_code
                   WHERE a.year_no = ? AND month_no = ?
                   GROUP BY b.customer_segment_name";
    $sqlcostsheet = "SELECT 
    sales_channels_group_code,
    is_new,
    qt_no,
    A.so_amount AS amount,
    MONTH(A.qt_date) AS month
FROM cost_sheet_head A
    WHERE 
        A.is_status <> 'C' 
        AND YEAR(A.qt_date) = ? AND MONTH(A.qt_date) = ? AND is_new = ?";
    $params = array($year_no, $month_no, $is_new);
}elseif($year_no <> 0 && $month_no == 0 && $channel <> 'N' && $Sales <> 'N' && $is_new == 0){
    $sqlrevenue = "SELECT so_no,total_before_vat FROM View_SO_SUM WHERE year_no = ? AND ระบบ = ? AND staff_id = ?";
    $sqlappoint = "SELECT appoint_no,customer_name,province_name FROM appoint_head WHERE year_no = ? AND is_call = ? AND staff_id = ?";
    $sqlsegment = "SELECT b.customer_segment_name, COUNT(a.customer_segment_code) AS segment_count 
                   FROM appoint_head a
                   LEFT JOIN ms_customer_segment b ON a.customer_segment_code = b.customer_segment_code
                   WHERE a.year_no = ? AND is_call = ? AND staff_id = ?
                   GROUP BY b.customer_segment_name";
    $sqlcostsheet = "SELECT 
    sales_channels_group_code,
    is_new,
    qt_no,
    A.so_amount AS amount,
    MONTH(A.qt_date) AS month
FROM cost_sheet_head A
    WHERE 
        A.is_status <> 'C' 
        AND YEAR(A.qt_date) = ? AND sales_channels_group_code = ? AND staff_id = ?";
    $params = array($year_no, $channel, $Sales);
}elseif($year_no <> 0 && $month_no == 0 && $channel <> 'N' && $Sales == 'N' && $is_new <> 0){
    $sqlrevenue = "SELECT so_no,total_before_vat FROM View_SO_SUM WHERE year_no = ? AND ระบบ = ? AND is_new = ?";
    $sqlappoint = "SELECT appoint_no,customer_name,province_name FROM appoint_head WHERE year_no = ? AND is_call = ?";
    $sqlsegment = "SELECT b.customer_segment_name, COUNT(a.customer_segment_code) AS segment_count 
                   FROM appoint_head a
                   LEFT JOIN ms_customer_segment b ON a.customer_segment_code = b.customer_segment_code
                   WHERE a.year_no = ? AND is_call = ?
                   GROUP BY b.customer_segment_name";
    $sqlcostsheet = "SELECT 
    sales_channels_group_code,
    is_new,
    qt_no,
    A.so_amount AS amount,
    MONTH(A.qt_date) AS month
FROM cost_sheet_head A
    WHERE 
        A.is_status <> 'C' 
        AND YEAR(A.qt_date) = ? AND sales_channels_group_code = ? AND is_new = ?";
    $params = array($year_no, $channel, $is_new);
}elseif($year_no <> 0 && $month_no == 0 && $channel == 'N' && $Sales <> 'N' && $is_new <> 0){
    $sqlrevenue = "SELECT so_no,total_before_vat FROM View_SO_SUM WHERE year_no = ? AND staff_id = ? AND is_new = ?";
    $sqlappoint = "SELECT appoint_no,customer_name,province_name FROM appoint_head WHERE year_no = ? AND staff_id = ?";
    $sqlsegment = "SELECT b.customer_segment_name, COUNT(a.customer_segment_code) AS segment_count 
                   FROM appoint_head a
                   LEFT JOIN ms_customer_segment b ON a.customer_segment_code = b.customer_segment_code
                   WHERE a.year_no = ? AND staff_id = ?
                   GROUP BY b.customer_segment_name";
    $sqlcostsheet = "SELECT 
    sales_channels_group_code,
    is_new,
    qt_no,
    A.so_amount AS amount,
    MONTH(A.qt_date) AS month
FROM cost_sheet_head A
    WHERE 
        A.is_status <> 'C' 
        AND YEAR(A.qt_date) = ? AND staff_id = ? AND sales_channels_group_code = ?";
    $params = array($year_no, $Sales, $is_new);   
}elseif($year_no <> 0 && $month_no <> 0 && $channel <> 'N' && $Sales <> 'N' && $is_new == 0){
    $sqlrevenue = "SELECT so_no,total_before_vat FROM View_SO_SUM WHERE year_no = ? AND month_no = ? AND ระบบ = ? AND staff_id = ?";
    $sqlappoint = "SELECT appoint_no,customer_name,province_name FROM appoint_head WHERE year_no = ? AND month_no = ? AND is_call = ? AND staff_id = ?";
    $sqlsegment = "SELECT b.customer_segment_name, COUNT(a.customer_segment_code) AS segment_count 
                   FROM appoint_head a
                   LEFT JOIN ms_customer_segment b ON a.customer_segment_code = b.customer_segment_code
                   WHERE a.year_no = ? AND month_no = ? AND is_call = ? AND staff_id = ?
                   GROUP BY b.customer_segment_name";
    $sqlcostsheet = "SELECT 
    sales_channels_group_code,
    is_new,
    qt_no,
    A.so_amount AS amount,
    MONTH(A.qt_date) AS month
FROM cost_sheet_head A
    WHERE 
        A.is_status <> 'C' 
        AND YEAR(A.qt_date) = ? AND MONTH(A.qt_date) = ? AND sales_channels_group_code = ? AND staff_id = ?";
    $params = array($year_no, $month_no, $channel, $Sales);
}elseif($year_no <> 0 && $month_no <> 0 && $channel <> 'N' && $Sales == 'N' && $is_new <> 0){
    $sqlrevenue = "SELECT so_no,total_before_vat FROM View_SO_SUM WHERE year_no = ? AND month_no = ? AND ระบบ = ? AND is_new = ?";
    $sqlappoint = "SELECT appoint_no,customer_name,province_name FROM appoint_head WHERE year_no = ? AND month_no = ? AND is_call = ?";
    $sqlsegment = "SELECT b.customer_segment_name, COUNT(a.customer_segment_code) AS segment_count 
                   FROM appoint_head a
                   LEFT JOIN ms_customer_segment b ON a.customer_segment_code = b.customer_segment_code
                   WHERE a.year_no = ? AND month_no = ? AND is_call = ?
                   GROUP BY b.customer_segment_name";
    $sqlcostsheet = "SELECT 
    sales_channels_group_code,
    is_new,
    qt_no,
    A.so_amount AS amount,
    MONTH(A.qt_date) AS month
FROM cost_sheet_head A
    WHERE 
        A.is_status <> 'C' 
        AND YEAR(A.qt_date) = ? AND MONTH(A.qt_date) = ? AND sales_channels_group_code = ? AND is_new = ?";
    $params = array($year_no, $month_no, $channel, $is_new);
}elseif($year_no <> 0 && $month_no <> 0 && $channel == 'N' && $Sales <> 'N' && $is_new <> 0){
    $sqlrevenue = "SELECT so_no,total_before_vat FROM View_SO_SUM WHERE year_no = ? AND month_no = ? AND staff_id = ? AND is_new = ?";
    $sqlappoint = "SELECT appoint_no,customer_name,province_name FROM appoint_head WHERE year_no = ? AND month_no = ? AND staff_id = ?";
    $sqlsegment = "SELECT b.customer_segment_name, COUNT(a.customer_segment_code) AS segment_count 
                   FROM appoint_head a
                   LEFT JOIN ms_customer_segment b ON a.customer_segment_code = b.customer_segment_code
                   WHERE a.year_no = ? AND month_no = ? AND staff_id = ?
                   GROUP BY b.customer_segment_name";
    $sqlcostsheet = "SELECT 
    sales_channels_group_code,
    is_new,
    qt_no,
    A.so_amount AS amount,
    MONTH(A.qt_date) AS month
FROM cost_sheet_head A
    WHERE 
        A.is_status <> 'C' 
        AND YEAR(A.qt_date) = ? AND MONTH(A.qt_date) = ? AND staff_id = ? AND is_new = ?";
    $params = array($year_no, $month_no, $Sales, $is_new);
}elseif($year_no <> 0 && $month_no == 0 && $channel <> 'N' && $Sales <> 'N' && $is_new <> 0){
    $sqlrevenue = "SELECT so_no,total_before_vat FROM View_SO_SUM WHERE year_no = ? AND ระบบ = ? AND staff_id = ? AND is_new = ?";
    $sqlappoint = "SELECT appoint_no,customer_name,province_name FROM appoint_head WHERE year_no = ? AND is_call = ? AND staff_id = ?";
    $sqlsegment = "SELECT b.customer_segment_name, COUNT(a.customer_segment_code) AS segment_count 
                   FROM appoint_head a
                   LEFT JOIN ms_customer_segment b ON a.customer_segment_code = b.customer_segment_code
                   WHERE a.year_no = ? AND is_call = ? AND staff_id = ?
                   GROUP BY b.customer_segment_name";
    $sqlcostsheet = "SELECT 
    sales_channels_group_code,
    is_new,
    qt_no,
    A.so_amount AS amount,
    MONTH(A.qt_date) AS month
FROM cost_sheet_head A
    WHERE 
        A.is_status <> 'C' 
        AND YEAR(A.qt_date) = ? AND sales_channels_group_code = ? AND staff_id = ? AND is_new = ?";
    $params = array($year_no, $channel, $Sales, $is_new);
}else{
        $sqlrevenue = "SELECT so_no,total_before_vat FROM View_SO_SUM WHERE year_no = ? AND month_no = ? AND ระบบ = ? AND staff_id = ? AND is_new = ?";
    $sqlappoint = "SELECT appoint_no,customer_name,province_name FROM appoint_head WHERE year_no = ? AND month_no = ? AND is_call = ? AND staff_id = ?";
    $sqlsegment = "SELECT b.customer_segment_name, COUNT(a.customer_segment_code) AS segment_count 
                   FROM appoint_head a
                   LEFT JOIN ms_customer_segment b ON a.customer_segment_code = b.customer_segment_code
                   WHERE a.year_no = ? AND month_no = ? AND is_call = ? AND staff_id = ?
                   GROUP BY b.customer_segment_name";
    $sqlcostsheet = "SELECT 
    sales_channels_group_code,
    is_new,
    qt_no,
    A.so_amount AS amount,
    MONTH(A.qt_date) AS month
FROM cost_sheet_head A
    WHERE 
        A.is_status <> 'C' 
        AND YEAR(A.qt_date) = ? AND MONTH(A.qt_date) = ? AND sales_channels_group_code = ? AND staff_id = ? AND is_new = ?";
    $params = array($year_no, $month_no, $channel, $Sales, $is_new);
}

// Execute the first query
$stmt = sqlsrv_query($objCon, $sqlrevenue, $params);
if ($stmt === false) {
    $errors = sqlsrv_errors();
    error_log(print_r($errors, true)); // Log SQL errors for debugging
    http_response_code(500); // Set HTTP status code to indicate internal server error
    echo json_encode(["error" => "Failed to execute first query"]);
    exit;
}

// Initialize an array to hold the first query results
$revenueData = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $revenueData[] = $row;
}
sqlsrv_free_stmt($stmt);

// Execute the second query
$stmt1 = sqlsrv_query($objCon, $sqlappoint, $params);
if ($stmt1 === false) {
    $errors = sqlsrv_errors();
    error_log(print_r($errors, true)); // Log SQL errors for debugging
    http_response_code(500); // Set HTTP status code to indicate internal server error
    echo json_encode(["error" => "Failed to execute second query"]);
    exit;
}

// Initialize an array to hold the second query results
$appointData = [];
while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
    $appointData[] = $row;
}
sqlsrv_free_stmt($stmt1);

$stmt2 = sqlsrv_query($objCon, $sqlsegment, $params);
if ($stmt2 === false) {
    $errors = sqlsrv_errors();
    error_log(print_r($errors, true)); // Log SQL errors for debugging
    http_response_code(500); // Set HTTP status code to indicate internal server error
    echo json_encode(["error" => "Failed to execute segment query"]);
    exit;
}


$segmentData = [];
while ($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
    $segmentData[] = $row;
}
sqlsrv_free_stmt($stmt2);

$stmt3 = sqlsrv_query($objCon, $sqlcostsheet, $params);
if ($stmt3 === false) {
    $errors = sqlsrv_errors();
    error_log(print_r($errors, true)); // Log SQL errors for debugging
    http_response_code(500); // Set HTTP status code to indicate internal server error
    echo json_encode(["error" => "Failed to execute segment query"]);
    exit;
}


$costsheetData = [];
while ($row = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_ASSOC)) {
    $costsheetData[] = $row;
}
sqlsrv_free_stmt($stmt3);
// Close the database connection
sqlsrv_close($objCon);


$data = [
    'revenueData' => $revenueData,
    'appointData' => $appointData,
    'segmentData' => $segmentData,
    'costsheetData' => $costsheetData
];

header('Content-Type: application/json');
echo json_encode($data);

?>
