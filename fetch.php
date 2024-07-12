<?php
include_once('./connectDB.php'); // Include your database connection script
$objCon = connectDB(); // Connect to the database

if ($objCon === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Query to fetch data
$sqlappoint = "SELECT ah.appoint_no, ah.customer_name, mp.province_name, ah.record_date, ms.status_name
               FROM appoint_head ah
               LEFT JOIN ms_province mp ON ah.province_code = mp.province_code
			   LEFT JOIN ms_appoint_status ms ON ah.is_status = ms.status_code
               WHERE  ah.month_no = '6' AND ah.year_no = '2024'";
$params = array($year_no);
$stmt = sqlsrv_query($objCon, $sqlappoint, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Fetch data and format as JSON for AJAX response
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    echo "<tr>";
    echo "<td>" . $row['appoint_no'] . "</td>";
    echo "<td>" . $row['customer_name'] . "</td>";
    echo "<td>" . $row['province_name'] . "</td>";
    // Format date as per your requirement
    echo "<td>" . date_format($row['record_date'], 'Y/d/m') . "</td>";
    echo "<td>" . $row['status_name'] . "</td>";
    echo "</tr>";
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($rows);

sqlsrv_free_stmt($stmt);
sqlsrv_close($objCon);
?>
