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


if ($year_no == 2024 && $month_no == 0 && $channel == 'N') {
    $year_no = $currentYear;
    $sqlrevenue = "SELECT total_before_vat FROM View_SO_SUM WHERE year_no = ?";
    $sqlappoint = "SELECT appoint_no,customer_name,province_name FROM appoint_head WHERE year_no = ?";
    $sqlsegment = "SELECT b.customer_segment_name, COUNT(a.customer_segment_code) AS segment_count 
                   FROM appoint_head a
                   LEFT JOIN ms_customer_segment b ON a.customer_segment_code = b.customer_segment_code
                   WHERE a.year_no = ?
                   GROUP BY b.customer_segment_name";
    $params = array($year_no);
} elseif($year_no <> 0 && $month_no == 0 && $channel <> 'N'){

    $sqlrevenue = "SELECT total_before_vat FROM View_SO_SUM WHERE year_no = ? AND ระบบ = ?";
    $sqlappoint = "SELECT appoint_no,customer_name,province_name FROM appoint_head WHERE year_no = ? AND is_call = ?";
    $sqlsegment = "SELECT b.customer_segment_name, COUNT(a.customer_segment_code) AS segment_count 
    FROM appoint_head a
    LEFT JOIN ms_customer_segment b ON a.customer_segment_code = b.customer_segment_code
    WHERE a.year_no = ? AND is_call = ?
    GROUP BY b.customer_segment_name";
    $params = array($year_no, $channel);
}elseif($year_no <> 0 && $month_no <> 0 && $channel == 'N'){
    $sqlrevenue = "SELECT total_before_vat FROM View_SO_SUM WHERE month_no = ? AND year_no = ?";
    $sqlappoint = "SELECT appoint_no,customer_name,province_name FROM appoint_head WHERE month_no = ? AND year_no = ?";
    $sqlsegment = "SELECT b.customer_segment_name, COUNT(a.customer_segment_code) AS segment_count 
    FROM appoint_head a
    LEFT JOIN ms_customer_segment b ON a.customer_segment_code = b.customer_segment_code
    WHERE month_no = ? AND year_no = ?
    GROUP BY b.customer_segment_name";
    $params = array($month_no, $year_no);

}else{
    $sqlrevenue = "SELECT total_before_vat FROM View_SO_SUM WHERE month_no = ? AND year_no = ?  AND ระบบ = ?";
    $sqlappoint = "SELECT appoint_no,customer_name,province_name FROM appoint_head WHERE month_no = ? AND year_no = ? AND is_call = ?";
    $sqlsegment = "SELECT b.customer_segment_name, COUNT(a.customer_segment_code) AS segment_count 
    FROM appoint_head a
    LEFT JOIN ms_customer_segment b ON a.customer_segment_code = b.customer_segment_code
    WHERE month_no = ? AND year_no = ? AND is_call = ?
    GROUP BY b.customer_segment_name";
    $params = array($month_no, $year_no, $channel);
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

// Initialize an array to hold the segment query results
$segmentData = [];
while ($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
    $segmentData[] = $row;
}
sqlsrv_free_stmt($stmt2);
// Close the database connection
sqlsrv_close($objCon);

// Combine both results into a single response
$data = [
    'revenueData' => $revenueData,
    'appointData' => $appointData,
    'segmentData' => $segmentData
];

header('Content-Type: application/json');
echo json_encode($data);

?>
