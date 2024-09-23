<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once('./connectDB.php'); // Include your database connection script
$objCon = connectDB(); // Connect to the database

if ($objCon === false) {
    die(json_encode(["error" => sqlsrv_errors()]));
}

$sqlis = "SELECT qt_no, 
issue_no,
issue_date,
customer_code,
customer_name
FROM issue_head 
ORDER BY issue_no DESC";

$sqlor = "SELECT A.order_no,
shipment_date,
issue_no,
A.customer_code,
customer_name,
MAX(B.plan_no) AS plan_no,
is_status
FROM order_head A
LEFT JOIN order_detail B ON A.order_no = B.order_no
WHERE YEAR(order_date) > '2022'
GROUP BY 
A.order_no,
shipment_date,
issue_no,
A.customer_code,
customer_name,
is_status
ORDER BY order_no DESC";

$stmt = sqlsrv_query($objCon, $sqlis);
$stmt1 = sqlsrv_query($objCon, $sqlor);
if ($stmt === false) {
    die(json_encode(["error" => sqlsrv_errors()]));
}
if ($stmt1 === false) {
    die(json_encode(["error" => sqlsrv_errors()]));
}

$OWdata = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $OWdata[] = $row;
}

$ORdata = [];
while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
    $ORdata[] = $row;
}
$data = [
    'OWdata' => $OWdata,
    'ORdata' => $ORdata
];

sqlsrv_free_stmt($stmt);
sqlsrv_free_stmt($stmt1);
sqlsrv_close($objCon);

header('Content-Type: application/json');
echo json_encode($data);
?>
