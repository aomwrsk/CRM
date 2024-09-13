<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once('./connectDB.php'); // Include your database connection script
$objCon = connectDB(); // Connect to the database

if ($objCon === false) {
    die(print_r(sqlsrv_errors(), true));
}

$issue = isset($_GET['issue_no']) ? $_GET['issue_no'] : NULL;
$set_type = isset($_GET['set']) ? $_GET['set'] : NULL;
$qt_no = isset($_GET['qt_no']) ? $_GET['qt_no'] : NULL;

$sqlqt = "SELECT 
    qt_no,
    expiration_qt_date,
    GETDATE() AS present_date,
    DATEDIFF(DAY, GETDATE(), expiration_qt_date) AS days_left
FROM 
    cost_sheet_head
WHERE 
    qt_no = ?";
    
$param = array($qt_no);
$stmtqt = sqlsrv_query($objCon, $sqlqt, $param);

if ($stmtqt === false) {
    $errors = sqlsrv_errors();
    error_log(print_r($errors, true)); // Log SQL errors for debugging
    http_response_code(500);
    echo json_encode(["error" => "Failed to execute the query"]);
    exit;
}

// Initialize variables for the result
$rowdate = null;
$rowexpdate = null;

while ($row = sqlsrv_fetch_array($stmtqt, SQLSRV_FETCH_ASSOC)) {
    $rowdate = $row['days_left'];
    $rowexpdate = $row['expiration_qt_date']; // Assuming you need this value too
}

sqlsrv_free_stmt($stmtqt);

if (is_null($issue) || is_null($set_type)) {
    http_response_code(400); // Bad Request
    echo json_encode(["error" => "Missing required parameters"]);
    exit;
}
switch ($set_type) {
    case 1:
        $vehicle_transport_type = "A.vehicle_transport_code";
        $vehicle_transport_group = "A.vehicle_group_code";
        $container = "A.container_type_code";
        break;
    case 2:
        $vehicle_transport_type = "A.vehicle_transport_code1";
        $vehicle_transport_group = "A.vehicle_group_code1";
        $container = "A.container_type_code1";
        break;
    case 3:
        $vehicle_transport_type = "A.vehicle_transport_code2";
        $vehicle_transport_group = "A.vehicle_group_code2";
        $container = "A.container_type_code2";
        break;
    default:
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "Invalid set_type"]);
        exit;
}

    $sql = "SELECT 
    (SELECT Z.fname
             FROM dbo.hr_staff AS Z
             WHERE B.staff_id = Z.staff_id) AS name,
			 (SELECT Z.lname
             FROM dbo.hr_staff AS Z
             WHERE B.staff_id = Z.staff_id) AS lname,
	B.staff_id,
    A.qt_no,
	A.department_code,
    A.issue_no,
    A.issue_date,
    A.customer_code,
    A.customer_name,
    CASE 
        WHEN A.customer_account_no = 0 THEN
            (SELECT Z.address1 
             FROM dbo.ms_customer AS Z
             WHERE A.customer_code = Z.customer_code)
        ELSE
            (SELECT Z.address1
             FROM dbo.ms_customer_account AS Z
             WHERE A.customer_code = Z.customer_code AND A.customer_account_no = Z.seq)
    END AS address1,
    CASE 
        WHEN A.customer_account_no = 0 THEN
            (SELECT B.tambon_name
             FROM dbo.ms_customer AS Z
			 LEFT JOIN ms_tambon B ON Z.tambon_code = B.tambon_code
             WHERE A.customer_code = Z.customer_code)
        ELSE
            (SELECT B.tambon_name
             FROM dbo.ms_customer_account AS Z
			 LEFT JOIN ms_tambon B ON Z.tambon_code = B.tambon_code
             WHERE A.customer_code = Z.customer_code AND A.customer_account_no = Z.seq)
    END AS tambon_name,
	CASE 
        WHEN A.customer_account_no = 0 THEN
            (SELECT B.amphur_name 
             FROM dbo.ms_customer AS Z
			 LEFT JOIN ms_amphur B ON Z.amphur_code = B.amphur_code
             WHERE A.customer_code = Z.customer_code)
        ELSE
            (SELECT B.amphur_name 
             FROM dbo.ms_customer_account AS Z
			 LEFT JOIN ms_amphur B ON Z.amphur_code = B.amphur_code
             WHERE A.customer_code = Z.customer_code AND A.customer_account_no = Z.seq)
    END AS amphur_name,
		CASE 
        WHEN A.customer_account_no = 0 THEN
            (SELECT B.province_name 
             FROM dbo.ms_customer AS Z
			 LEFT JOIN ms_province B ON Z.province_code = B.province_code
             WHERE A.customer_code = Z.customer_code)
        ELSE
            (SELECT B.province_name  
             FROM dbo.ms_customer_account AS Z
			 LEFT JOIN ms_province B ON Z.province_code = B.province_code
             WHERE A.customer_code = Z.customer_code AND A.customer_account_no = Z.seq)
    END AS province_name,
    		CASE 
        WHEN A.customer_account_no = 0 THEN
            (SELECT Z.zip_code 
             FROM dbo.ms_customer AS Z
             WHERE A.customer_code = Z.customer_code)
        ELSE
            (SELECT Z.zip_code 
             FROM dbo.ms_customer_account AS Z
             WHERE A.customer_code = Z.customer_code AND A.customer_account_no = Z.seq)
    END AS zip_code,
	B.contact_name,
     Z.size,
    Z.vehicle_transport_name,
	Z.transport_group,
	Z.capacity,
	A.vehicle_transport_code,
	Y.vehicle_group_name,
	A.vehicle_group_code,
	x.container_type_name,
	A.container_type_code,
		A.vehicle_transport_code1,
	A.vehicle_group_code1,
	A.container_type_code1,
		A.vehicle_transport_code2,
	A.vehicle_group_code2,
	A.container_type_code2
FROM 
    issue_head A
LEFT JOIN cost_sheet_head B ON A.qt_no = B.qt_no
LEFT JOIN 
    dbo.ms_vehicle_transport_type Z ON $vehicle_transport_type = Z.vehicle_transport_code
LEFT JOIN 
    dbo.ms_vehicle_group y ON $vehicle_transport_group = y.vehicle_group_code
	LEFT JOIN 
    dbo.ms_container_type x ON $container = x.container_type_code
WHERE issue_no = ?";
    $sql1 = "	SELECT waste_code,
	waste_name,
	eliminate_code,
	cost_qty,
	Z.unit_name,
	A.unit_code,
	cost_amount,
	 CASE 
			WHEN A.customer_account_no = 0 THEN
				(SELECT Z.customer_name 
				 FROM dbo.ms_customer AS Z
				 WHERE A.customer_code = Z.customer_code)
			ELSE
				(SELECT Z.customer_name
				 FROM dbo.ms_customer_account AS Z
				 WHERE A.customer_code = Z.customer_code AND A.customer_account_no = Z.seq)
		END AS customer,
		mf_code,
		is_factory,
		request_sk_no
	FROM issue_detail A 
	LEFT JOIN 
    dbo.ms_unit Z ON A.unit_code = Z.unit_code WHERE issue_no = ?";
    $sql2 = "select B.remark from issue_head A 
LEFT JOIN cost_sheet_order_remark B ON A.qt_no = B.qt_no
where issue_no = ?";
    $params = array($issue);
// Execute the first query
$stmt = sqlsrv_query($objCon, $sql, $params);
if ($stmt === false) {
    $errors = sqlsrv_errors();
    error_log(print_r($errors, true)); // Log SQL errors for debugging
    http_response_code(500); // Set HTTP status code to indicate internal server error
    echo json_encode(["error" => "Failed to execute first query"]);
    exit;
}
$stmt1 = sqlsrv_query($objCon, $sql1, $params);
if ($stmt1 === false) {
    $errors = sqlsrv_errors();
    error_log(print_r($errors, true)); // Log SQL errors for debugging
    http_response_code(500); // Set HTTP status code to indicate internal server error
    echo json_encode(["error" => "Failed to execute first query"]);
    exit;
}

$stmt2 = sqlsrv_query($objCon, $sql2, $params);
if ($stmt2 === false) {
    $errors = sqlsrv_errors();
    error_log(print_r($errors, true)); // Log SQL errors for debugging
    http_response_code(500); // Set HTTP status code to indicate internal server error
    echo json_encode(["error" => "Failed to execute first query"]);
    exit;
}
// Initialize an array to hold the first query results
$issue = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $issue[] = $row;
}
sqlsrv_free_stmt($stmt);

$waste = [];
while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
    $waste[] = $row;
}
sqlsrv_free_stmt($stmt1);

$order_remark = [];
while ($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
    $order_remark[] = $row;
}
sqlsrv_free_stmt($stmt2);

// Execute the second query


// Close the database connection
sqlsrv_close($objCon);

$data = [
    'issue' => $issue,
    'waste' => $waste,
    'order_remark' => $order_remark,
    'days_left' => $rowdate,
    'expiration_qt_date' => $rowexpdate
];

header('Content-Type: application/json');
echo json_encode($data);

