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
$staff = isset($_GET['staff']) ? $_GET['staff'] : NULL;

$sqlaptable = "SELECT appoint_no, appoint_date,B.fname AS SName, customer_name FROM appoint_head A
                JOIN hr_staff B ON A.staff_id = B.staff_id
                WHERE year_no = $year_no AND month_no = $month_no AND A.staff_id = $staff";
$stmtap = sqlsrv_query($objCon, $sqlaptable);
$ap_data = array();
while ($row = sqlsrv_fetch_array($stmtap, SQLSRV_FETCH_ASSOC)) {
    $ap_data[] = $row;
}


$data = [
    'ap_data' => $ap_data
];

sqlsrv_close($objCon);
header('Content-Type: application/json');
echo json_encode($data);
?>