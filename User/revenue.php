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
/*$channel = isset($_GET['channel']) ? $_GET['channel'] : NULL;*/
/*$Sales = isset($_GET['Sales']) ? $_GET['Sales'] : NULL;*/
$is_new = isset($_GET['is_new']) ? $_GET['is_new'] : NULL;
$staff = isset($_GET['staff']) ? $_GET['staff'] : NULL;


if ($year_no <> 0 && $month_no == 0  && $is_new == 0) {
    $sqlrevenue = "SELECT so_no,total_before_vat FROM View_SO_SUM WHERE year_no = ? AND staff_id = ?";
    $sqlappoint = "SELECT appoint_no,customer_name,province_code FROM appoint_head WHERE year_no = ? AND staff_id = ?";
    $sqlsegment = "SELECT b.customer_segment_name, COUNT(a.customer_segment_code) AS segment_count 
                   FROM View_SO_SUM a
                   LEFT JOIN ms_customer_segment b ON a.customer_segment_code = b.customer_segment_code
                   WHERE a.year_no = ? AND a.staff_id = ?
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
    $sqlregion = "SELECT 
  C.customer_segment_name AS segment,
  COUNT(CASE WHEN B.zone_code = '01' THEN A.province_code END) AS 'North',
  COUNT(CASE WHEN B.zone_code = '02' THEN A.province_code END) AS 'Central',
  COUNT(CASE WHEN B.zone_code = '03' THEN A.province_code END) AS 'East',
  COUNT(CASE WHEN B.zone_code = '04' THEN A.province_code END) AS 'North_East',
  COUNT(CASE WHEN B.zone_code = '05' THEN A.province_code END) AS 'West',
  COUNT(CASE WHEN B.zone_code = '06' THEN A.province_code END) AS 'South'
FROM 
  View_SO_SUM A
LEFT JOIN 
  ms_province B ON A.province_code = B.province_code
LEFT JOIN 
  ms_customer_segment C ON A.customer_segment_code = C.customer_segment_code
WHERE 
  A.year_no = ? AND staff_id = ?
GROUP BY
  C.customer_segment_name
";
    $params = array($year_no, $staff);
}elseif($year_no <> 0 && $month_no <> 0 && $is_new == 0){
    $sqlrevenue = "SELECT so_no,total_before_vat FROM View_SO_SUM WHERE year_no = ? AND month_no = ? AND staff_id = ?";
    $sqlappoint = "SELECT appoint_no,customer_name,province_name FROM appoint_head WHERE year_no = ? AND month_no = ? AND staff_id = ?";
    $sqlsegment = "SELECT b.customer_segment_name, COUNT(a.customer_segment_code) AS segment_count 
                   FROM View_SO_SUM a
                   LEFT JOIN ms_customer_segment b ON a.customer_segment_code = b.customer_segment_code
                   WHERE a.year_no = ? AND month_no = ? AND staff_id = ?
                   GROUP BY b.customer_segment_name";
    $sqlcostsheet = "SELECT 
    sales_channels_group_code,
    is_new,
    qt_no,
    so_amount AS amount,
    MONTH(qt_date) AS month
FROM cost_sheet_head 
    WHERE 
        is_status <> 'C' 
        AND YEAR(qt_date) = ? AND MONTH(qt_date) = ? AND staff_id = ?";
         $sqlregion = "SELECT 
         C.customer_segment_name AS segment,
         COUNT(CASE WHEN B.zone_code = '01' THEN A.province_code END) AS 'North',
         COUNT(CASE WHEN B.zone_code = '02' THEN A.province_code END) AS 'Central',
         COUNT(CASE WHEN B.zone_code = '03' THEN A.province_code END) AS 'East',
         COUNT(CASE WHEN B.zone_code = '04' THEN A.province_code END) AS 'North_East',
         COUNT(CASE WHEN B.zone_code = '05' THEN A.province_code END) AS 'West',
         COUNT(CASE WHEN B.zone_code = '06' THEN A.province_code END) AS 'South'
       FROM 
         View_SO_SUM A
       LEFT JOIN 
         ms_province B ON A.province_code = B.province_code
       LEFT JOIN 
         ms_customer_segment C ON A.customer_segment_code = C.customer_segment_code
       WHERE A.year_no = ? AND A.month_no = ? AND A.staff_id = ?
       GROUP BY
         C.customer_segment_name
       ";
    $params = array($year_no, $month_no,$staff);
}elseif($year_no <> 0 && $month_no == 0 && $is_new <> 0){

  if ($is_new == 'Y') {
    $is_new_array = ['01', '04'];
} else if ($is_new == 'N') {
    $is_new_array = ['02', '03'];
}

$is_new_list = "'" . implode("','", $is_new_array) . "'";

    $sqlrevenue = "SELECT so_no,total_before_vat FROM View_SO_SUM WHERE year_no = ? AND status IN ($is_new_list) AND staff_id = ?";
    $sqlappoint = "SELECT appoint_no,customer_name,province_name FROM appoint_head WHERE year_no = ? AND staff_id = ?";
    $sqlsegment = "SELECT b.customer_segment_name, COUNT(a.customer_segment_code) AS segment_count 
                   FROM View_SO_SUM a
                   LEFT JOIN ms_customer_segment b ON a.customer_segment_code = b.customer_segment_code
                   WHERE a.year_no = ? AND staff_id = ? AND status IN ($is_new_list)
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
        AND YEAR(A.qt_date) = ?  AND staff_id = ?";
        $sqlregion = "SELECT 
        C.customer_segment_name AS segment,
        COUNT(CASE WHEN B.zone_code = '01' THEN A.province_code END) AS 'North',
        COUNT(CASE WHEN B.zone_code = '02' THEN A.province_code END) AS 'Central',
        COUNT(CASE WHEN B.zone_code = '03' THEN A.province_code END) AS 'East',
        COUNT(CASE WHEN B.zone_code = '04' THEN A.province_code END) AS 'North_East',
        COUNT(CASE WHEN B.zone_code = '05' THEN A.province_code END) AS 'West',
        COUNT(CASE WHEN B.zone_code = '06' THEN A.province_code END) AS 'South'
      FROM 
        View_SO_SUM A
      LEFT JOIN 
        ms_province B ON A.province_code = B.province_code
      LEFT JOIN 
        ms_customer_segment C ON A.customer_segment_code = C.customer_segment_code
       WHERE a.year_no = ? AND staff_id = ? AND status IN ($is_new_list)
      GROUP BY
        C.customer_segment_name
      ";
    $params = array($year_no, $staff);
}else{
  if ($is_new == 'Y') {
    $is_new_array = ['01', '04'];
} else if ($is_new == 'N') {
    $is_new_array = ['02', '03'];
}

$is_new_list = "'" . implode("','", $is_new_array) . "'";

        $sqlrevenue = "SELECT so_no,total_before_vat FROM View_SO_SUM WHERE year_no = ? AND month_no = ?  AND status IN ($is_new_list) AND staff_id = ?";
    $sqlappoint = "SELECT appoint_no,customer_name,province_name FROM appoint_head WHERE year_no = ? AND month_no = ? AND staff_id = ?";
    $sqlsegment = "SELECT b.customer_segment_name, COUNT(a.customer_segment_code) AS segment_count 
                   FROM View_SO_SUM a
                   LEFT JOIN ms_customer_segment b ON a.customer_segment_code = b.customer_segment_code
                   WHERE a.year_no = ? AND month_no = ? AND staff_id = ? AND status IN ($is_new_list)
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
        AND YEAR(A.qt_date) = ? AND MONTH(A.qt_date) = ?   AND staff_id = ?";
         $sqlregion = "SELECT 
         C.customer_segment_name AS segment,
         COUNT(CASE WHEN B.zone_code = '01' THEN A.province_code END) AS 'North',
         COUNT(CASE WHEN B.zone_code = '02' THEN A.province_code END) AS 'Central',
         COUNT(CASE WHEN B.zone_code = '03' THEN A.province_code END) AS 'East',
         COUNT(CASE WHEN B.zone_code = '04' THEN A.province_code END) AS 'North_East',
         COUNT(CASE WHEN B.zone_code = '05' THEN A.province_code END) AS 'West',
         COUNT(CASE WHEN B.zone_code = '06' THEN A.province_code END) AS 'South'
       FROM 
         View_SO_SUM A
       LEFT JOIN 
         ms_province B ON A.province_code = B.province_code
       LEFT JOIN 
         ms_customer_segment C ON A.customer_segment_code = C.customer_segment_code
       WHERE a.year_no = ? AND month_no = ? AND staff_id = ? AND status IN ($is_new_list)
       GROUP BY
         C.customer_segment_name
       ";
    $params = array($year_no, $month_no, $staff);
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

$stmt4 = sqlsrv_query($objCon, $sqlregion, $params);
if ($stmt4 === false) {
    // Log SQL errors if the query fails
    $errors = sqlsrv_errors();
    error_log(print_r($errors, true)); // Log errors for debugging purposes
    http_response_code(500); // Set HTTP status code to 500 (Internal Server Error)
    echo json_encode(["error" => "Failed to execute segment query"]); // Return error message as JSON
    exit;
}


$regionData = [];
while ($row = sqlsrv_fetch_array($stmt4, SQLSRV_FETCH_ASSOC)) {
    $regionData[] = $row;
}
sqlsrv_free_stmt($stmt4);

// Close the database connection
sqlsrv_close($objCon);

$data = [
    'revenueData' => $revenueData,
    'appointData' => $appointData,
    'segmentData' => $segmentData,
    'costsheetData' => $costsheetData,
    'regionData' => $regionData
];

header('Content-Type: application/json');
echo json_encode($data);

?>
