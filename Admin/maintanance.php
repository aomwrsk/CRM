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

if ($year_no <> 0 && $month_no == 0) {
    $sqlbox = "SELECT 
                  FORMAT(A.repair_date, 'yyyy-MM') AS format_date, 
                  COUNT(A.total_before_vat)AS vehicle_code, 
                  SUM(COALESCE(pr_summary.pr_amount, 0)) AS pr_amount, 
                  SUM(COALESCE(brw_summary.stock_amount, 0)) AS stock_amount,
                  SUM(COALESCE(pr_summary.pr_amount, 0) + COALESCE(brw_summary.stock_amount, 0)) AS total_amount
                  FROM repair_head A
                  LEFT JOIN (
                  SELECT repair_no, SUM(B.total_amount) AS pr_amount
                  FROM pr_head A
                  LEFT JOIN pr_detail B ON A.pr_no = B.pr_no
                  WHERE A.is_status <> 'C' 
                  GROUP BY A.repair_no
                  ) pr_summary ON A.repair_no = pr_summary.repair_no
                  LEFT JOIN (
                  SELECT A.repair_no, SUM(B.total_amount) AS stock_amount
                  FROM brw_head A
                  LEFT JOIN brw_detail B ON A.brw_no = B.brw_no
              	  WHERE A.is_status <> 'C' AND A.stock_code IN ('02', '06')
                  GROUP BY A.repair_no
                  ) brw_summary ON A.repair_no = brw_summary.repair_no
                  WHERE A.is_status <> 'C' 
                  AND YEAR(A.repair_date) = ? AND vehicle_code IS NULL
                  GROUP BY 
                  FORMAT(A.repair_date, 'yyyy-MM')";
     $sqlvehicle = "	SELECT 
              		FORMAT(A.repair_date, 'yyyy-MM') AS format_date, 
              		COUNT(A.total_before_vat)AS vehicle_code, 
              		SUM(COALESCE(pr_summary.pr_amount, 0)) AS pr_amount, 
              		SUM(COALESCE(brw_summary.stock_amount, 0)) AS stock_amount,
              		SUM(COALESCE(pr_summary.pr_amount, 0) + COALESCE(brw_summary.stock_amount, 0)) AS total_amount
              	  FROM repair_head A
              	  LEFT JOIN (
              		SELECT repair_no, SUM(B.total_amount) AS pr_amount
              		FROM pr_head A
              		LEFT JOIN pr_detail B ON A.pr_no = B.pr_no
              		WHERE A.is_status <> 'C' 
              		GROUP BY A.repair_no
              	  ) pr_summary ON A.repair_no = pr_summary.repair_no
              	  LEFT JOIN (
              		SELECT A.repair_no, SUM(B.total_amount) AS stock_amount
              		FROM brw_head A
              		LEFT JOIN brw_detail B ON A.brw_no = B.brw_no
              		WHERE A.is_status <> 'C' AND A.stock_code IN ('02', '06')
              		GROUP BY A.repair_no
              	  ) brw_summary ON A.repair_no = brw_summary.repair_no
              	  WHERE A.is_status <> 'C' 
              	  AND YEAR(A.repair_date) = ? AND vehicle_code IS NOT NULL
              	  GROUP BY 
              	  FORMAT(A.repair_date, 'yyyy-MM')";  
    $sqlgraph = "	SELECT 
                  FORMAT(A.repair_date, 'yyyy-MM') AS format_date, 
                  COUNT(A.vehicle_code)AS vehicle_code, 
                  SUM(COALESCE(pr_summary.pr_amount, 0)) AS pr_amount, 
                  SUM(COALESCE(brw_summary.stock_amount, 0)) AS stock_amount,
                  SUM(COALESCE(pr_summary.pr_amount, 0) + COALESCE(brw_summary.stock_amount, 0)) AS total_amount
                  FROM repair_head A
                  LEFT JOIN (
                  SELECT repair_no, SUM(B.total_amount) AS pr_amount
                  FROM pr_head A
                  LEFT JOIN pr_detail B ON A.pr_no = B.pr_no
                  WHERE A.is_status <> 'C' 
                  GROUP BY A.repair_no
                  ) pr_summary ON A.repair_no = pr_summary.repair_no
                  LEFT JOIN (
                  SELECT A.repair_no, SUM(B.total_amount) AS stock_amount
                  FROM brw_head A
                  LEFT JOIN brw_detail B ON A.brw_no = B.brw_no
                  WHERE A.is_status <> 'C' AND A.stock_code IN ('02', '06')
                  GROUP BY A.repair_no
                  ) brw_summary ON A.repair_no = brw_summary.repair_no
                  WHERE A.is_status <> 'C' 
                  AND YEAR(A.repair_date) = ? AND repair_type_code <> '002'
                  GROUP BY 
                  FORMAT(A.repair_date, 'yyyy-MM')";   
    $sqlgraphpie = "	SELECT 
                    COALESCE(vehicle_code, 'container') AS vehicle_code,
                    COUNT(A.vehicle_code)AS Countma, 
                    FORMAT(SUM(COALESCE(pr_summary.pr_amount, 0) + COALESCE(brw_summary.stock_amount, 0)),'N2') AS total_amount
                    FROM repair_head A
                    LEFT JOIN (
                    SELECT repair_no, SUM(B.total_amount) AS pr_amount
                    FROM pr_head A
                    LEFT JOIN pr_detail B ON A.pr_no = B.pr_no
                    WHERE A.is_status <> 'C' 
                    GROUP BY A.repair_no
                    ) pr_summary ON A.repair_no = pr_summary.repair_no
                    LEFT JOIN (
                    SELECT A.repair_no, SUM(B.total_amount) AS stock_amount
                    FROM brw_head A
                    LEFT JOIN brw_detail B ON A.brw_no = B.brw_no
                    WHERE A.is_status <> 'C' AND A.stock_code IN ('02', '06')
                    GROUP BY A.repair_no
                    ) brw_summary ON A.repair_no = brw_summary.repair_no
                    WHERE A.is_status <> 'C' 
                    AND YEAR(A.repair_date) = ?
                    GROUP BY 
                    vehicle_code";        
    $params = array($year_no);
}else{
                $sqlbox = "SELECT 
                FORMAT(A.repair_date, 'yyyy-MM') AS format_date, 
                COUNT(A.total_before_vat)AS vehicle_code, 
                SUM(COALESCE(pr_summary.pr_amount, 0)) AS pr_amount, 
                SUM(COALESCE(brw_summary.stock_amount, 0)) AS stock_amount,
                SUM(COALESCE(pr_summary.pr_amount, 0) + COALESCE(brw_summary.stock_amount, 0)) AS total_amount
                FROM repair_head A
                LEFT JOIN (
                SELECT repair_no, SUM(B.total_amount) AS pr_amount
                FROM pr_head A
                LEFT JOIN pr_detail B ON A.pr_no = B.pr_no
                WHERE A.is_status <> 'C' 
                GROUP BY A.repair_no
                ) pr_summary ON A.repair_no = pr_summary.repair_no
                LEFT JOIN (
                SELECT A.repair_no, SUM(B.total_amount) AS stock_amount
                FROM brw_head A
                LEFT JOIN brw_detail B ON A.brw_no = B.brw_no
                WHERE A.is_status <> 'C' AND A.stock_code IN ('02', '06')
                GROUP BY A.repair_no
                ) brw_summary ON A.repair_no = brw_summary.repair_no
                WHERE A.is_status <> 'C' 
                AND YEAR(A.repair_date) = ? AND MONTH(A.repair_date) = ? AND vehicle_code IS NULL
                GROUP BY 
                FORMAT(A.repair_date, 'yyyy-MM')";
$sqlvehicle = "	SELECT 
                FORMAT(A.repair_date, 'yyyy-MM') AS format_date, 
                COUNT(A.total_before_vat)AS vehicle_code, 
                SUM(COALESCE(pr_summary.pr_amount, 0)) AS pr_amount, 
                SUM(COALESCE(brw_summary.stock_amount, 0)) AS stock_amount,
                SUM(COALESCE(pr_summary.pr_amount, 0) + COALESCE(brw_summary.stock_amount, 0)) AS total_amount
                FROM repair_head A
                LEFT JOIN (
                SELECT repair_no, SUM(B.total_amount) AS pr_amount
                FROM pr_head A
                LEFT JOIN pr_detail B ON A.pr_no = B.pr_no
                WHERE A.is_status <> 'C' 
                GROUP BY A.repair_no
                ) pr_summary ON A.repair_no = pr_summary.repair_no
                LEFT JOIN (
                SELECT A.repair_no, SUM(B.total_amount) AS stock_amount
                FROM brw_head A
                LEFT JOIN brw_detail B ON A.brw_no = B.brw_no
                WHERE A.is_status <> 'C' AND A.stock_code IN ('02', '06')
                GROUP BY A.repair_no
                ) brw_summary ON A.repair_no = brw_summary.repair_no
                WHERE A.is_status <> 'C' 
                AND YEAR(A.repair_date) = ? AND MONTH(A.repair_date) = ? AND vehicle_code IS NOT NULL
                GROUP BY 
                FORMAT(A.repair_date, 'yyyy-MM')";  
$sqlgraph = "	SELECT 
                FORMAT(A.repair_date, 'yyyy-MM') AS format_date, 
                COUNT(A.vehicle_code)AS vehicle_code, 
                SUM(COALESCE(pr_summary.pr_amount, 0)) AS pr_amount, 
                SUM(COALESCE(brw_summary.stock_amount, 0)) AS stock_amount,
                SUM(COALESCE(pr_summary.pr_amount, 0) + COALESCE(brw_summary.stock_amount, 0)) AS total_amount
                FROM repair_head A
                LEFT JOIN (
                SELECT repair_no, SUM(B.total_amount) AS pr_amount
                FROM pr_head A
                LEFT JOIN pr_detail B ON A.pr_no = B.pr_no
                WHERE A.is_status <> 'C' 
                GROUP BY A.repair_no
                ) pr_summary ON A.repair_no = pr_summary.repair_no
                LEFT JOIN (
                SELECT A.repair_no, SUM(B.total_amount) AS stock_amount
                FROM brw_head A
                LEFT JOIN brw_detail B ON A.brw_no = B.brw_no
                WHERE A.is_status <> 'C' AND A.stock_code IN ('02', '06')
                GROUP BY A.repair_no
                ) brw_summary ON A.repair_no = brw_summary.repair_no
                WHERE A.is_status <> 'C' 
                AND YEAR(A.repair_date) = ? AND repair_type_code <> '002'
                GROUP BY 
                FORMAT(A.repair_date, 'yyyy-MM')";  
  $sqlgraphpie = "	SELECT 
                COALESCE(vehicle_code, 'container') AS vehicle_code,
                COUNT(A.vehicle_code)AS Countma, 
                FORMAT(SUM(COALESCE(pr_summary.pr_amount, 0) + COALESCE(brw_summary.stock_amount, 0)),'N2') AS total_amount
                FROM repair_head A
                LEFT JOIN (
                SELECT repair_no, SUM(B.total_amount) AS pr_amount
                FROM pr_head A
                LEFT JOIN pr_detail B ON A.pr_no = B.pr_no
                WHERE A.is_status <> 'C' 
                GROUP BY A.repair_no
                ) pr_summary ON A.repair_no = pr_summary.repair_no
                LEFT JOIN (
                SELECT A.repair_no, SUM(B.total_amount) AS stock_amount
                FROM brw_head A
                LEFT JOIN brw_detail B ON A.brw_no = B.brw_no
                WHERE A.is_status <> 'C' AND A.stock_code IN ('02', '06')
                GROUP BY A.repair_no
                ) brw_summary ON A.repair_no = brw_summary.repair_no
                WHERE A.is_status <> 'C' 
                AND YEAR(A.repair_date) = ? AND MONTH(A.repair_date) = ?
                GROUP BY 
                vehicle_code";  
    $params = array($year_no, $month_no);
}

// Execute the first query
$stmt = sqlsrv_query($objCon, $sqlbox, $params);
if ($stmt === false) {
    $errors = sqlsrv_errors();
    error_log(print_r($errors, true)); // Log SQL errors for debugging
    http_response_code(500); // Set HTTP status code to indicate internal server error
    echo json_encode(["error" => "Failed to execute first query"]);
    exit;
}

$stmt1 = sqlsrv_query($objCon, $sqlvehicle, $params);
if ($stmt1 === false) {
    $errors = sqlsrv_errors();
    error_log(print_r($errors, true)); // Log SQL errors for debugging
    http_response_code(500); // Set HTTP status code to indicate internal server error
    echo json_encode(["error" => "Failed to execute first query"]);
    exit;
}

$stmt2 = sqlsrv_query($objCon, $sqlgraph, $params);
if ($stmt2 === false) {
    $errors = sqlsrv_errors();
    error_log(print_r($errors, true)); // Log SQL errors for debugging
    http_response_code(500); // Set HTTP status code to indicate internal server error
    echo json_encode(["error" => "Failed to execute first query"]);
    exit;
}

$stmt3 = sqlsrv_query($objCon, $sqlgraphpie, $params);
if ($stmt3 === false) {
    $errors = sqlsrv_errors();
    error_log(print_r($errors, true)); // Log SQL errors for debugging
    http_response_code(500); // Set HTTP status code to indicate internal server error
    echo json_encode(["error" => "Failed to execute first query"]);
    exit;
}

// Initialize an array to hold the first query results
$boxData = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $boxData[] = $row;
}
sqlsrv_free_stmt($stmt);

$vehicleData = [];
while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
    $vehicleData[] = $row;
}
sqlsrv_free_stmt($stmt1);

$graphData = [];
while ($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
    $graphData[] = $row;
}
sqlsrv_free_stmt($stmt2);

$graphpieData = [];
while ($row = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_ASSOC)) {
    $graphpieData[] = $row;
}
sqlsrv_free_stmt($stmt3);


// Close the database connection
sqlsrv_close($objCon);

$data = [
    'boxData' => $boxData,
    'vehicleData' => $vehicleData,
    'graphData' => $graphData,
    'graphpieData' => $graphpieData
];

header('Content-Type: application/json');
echo json_encode($data);

?>
