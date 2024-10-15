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
$MA = isset($_GET['MA']) ? $_GET['MA'] : NULL;
$year_no = isset($_GET['year_no']) ? $_GET['year_no'] : $currentYear;
$month_no = isset($_GET['month_no']) ? $_GET['month_no'] : $currentMonth;
/*$channel = isset($_GET['channel']) ? $_GET['channel'] : NULL;*/

if($year_no <> 0 && $month_no <> 0 && $MA == 'C'){
    $sqlappoint = "WITH MA AS(
					SELECT 
					 CASE WHEN container_type_code != '00' THEN (SELECT B.container_type_name FROM ms_container_type B WHERE A.container_type_code = B.container_type_code)
					 END AS CT,
					 (SELECT size_no FROM ms_container B WHERE A.container_code = B.container_code)AS size_no,
					(SELECT number_no FROM ms_container B WHERE A.container_code = B.container_code)AS number_no,
					CASE WHEN container_type_code != '00' THEN SUM(COALESCE(A.total_before_vat, 0) + COALESCE(brw_summary.stock_amount, 0))END  AS ct_amount
              		  FROM repair_head A
					  LEFT JOIN ms_repair_type msr ON A.repair_type_code = msr.repair_type_code
              		  LEFT JOIN (
              			SELECT A.repair_no, SUM(B.total_amount) AS stock_amount
              			FROM brw_head A
              			LEFT JOIN brw_detail B ON A.brw_no = B.brw_no
              			WHERE A.is_status <> 'C' AND A.stock_code IN ('02', '06')
              			GROUP BY A.repair_no
              		  ) brw_summary ON A.repair_no = brw_summary.repair_no
              		  WHERE A.is_status <> 'C' 
              		  AND YEAR(A.repair_date) = 2024
              		  GROUP BY A.vehicle_code,container_type_code,container_code
				  		)
		SELECT  
*
		FROM MA";
                   $params = array($month_no, $year_no, $MA);
}elseif($year_no <> 0 && $month_no <> 0 && $MA == 'V'){
    $sqlappoint = "WITH MA AS(
					SELECT 
					 CASE WHEN container_type_code = '00' AND vehicle_code IS NOT NULL  THEN vehicle_code
					 END AS TP,
					 CASE WHEN container_type_code = '00' AND vehicle_code IS NOT NULL  THEN SUM(COALESCE(A.total_before_vat, 0) + COALESCE(brw_summary.stock_amount, 0))END  AS 'tp_amount'
              		  FROM repair_head A
					  LEFT JOIN ms_repair_type msr ON A.repair_type_code = msr.repair_type_code
              		  LEFT JOIN (
              			SELECT A.repair_no, SUM(B.total_amount) AS stock_amount
              			FROM brw_head A
              			LEFT JOIN brw_detail B ON A.brw_no = B.brw_no
              			WHERE A.is_status <> 'C' AND A.stock_code IN ('02', '06')
              			GROUP BY A.repair_no
              		  ) brw_summary ON A.repair_no = brw_summary.repair_no
              		  WHERE A.is_status <> 'C' 
              		  AND YEAR(A.repair_date) = 2024
              		  GROUP BY A.vehicle_code,container_type_code,container_code
				  		)
		SELECT  
*
		FROM MA";

                   $params = array($month_no, $year_no, $MA);
}

$stmt = sqlsrv_query($objCon, $sqlappoint, $params);

if ($stmt === false) {
    die(json_encode(["error" => sqlsrv_errors()]));
}

$tableData = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $tableData[] = $row;
}

$data = [
    'tableData' => $tableData
];

sqlsrv_free_stmt($stmt);
sqlsrv_close($objCon);

header('Content-Type: application/json');
echo json_encode($data);
