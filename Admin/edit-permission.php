<?php
include_once('./connectDB.php');
$objCon = connectDB();
/*
$sales = isset($_GET['channel']) ? $_GET['channel'] : NULL;
$uid = "SELECT * FROM xuser WHERE staff_id LIKE '%$sales%'";
$uid = sqlsrv_query($objCon, $uid);
$uid = sqlsrv_fetch_array($uid, SQLSRV_FETCH_ASSOC);
$uid = $uid['usrid'];
*/
$timezone = new DateTimeZone('Asia/Bangkok'); // You can use 'Asia/Bangkok', 'Asia/Jakarta', etc.

// Create a DateTime object with the specified time zone
$date = new DateTime('now', $timezone);
$record_datetime = $date->format('Y-m-d H:i:s'); // For date and time in YYYY-MM-DD HH:MM:SS format

$data = $_POST;

print_r($data);
$id_no_count = count(array_filter(array_keys($data), function($key) {
    return strpos($key, 'id') === 0;
}));
for ($i = 1; $i <= $id_no_count; $i++) {
    $id = $data["id$i"];
    $active = $data["active$i"];
    $level = $data["level$i"];
    $role = $data["Role$i"];

    // SQL query with parameters
    $sql = "UPDATE a_user SET
            level = ?,
            Role = ?,
            active = ?
            WHERE id = ?";
    
    // Parameters for the query
    $params = array($level, $role, $active, $id);
    
    // Execute the query
    $stmt = sqlsrv_query($objCon, $sql, $params);
    
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }else{
       
        echo '<script>alert("แก้ไขสิทธิ์แล้ว");window.location="permission.php";</script>';
     
        }
}
/*
for ($i = 1; $i <= $qt_no_count; $i++) {
    $qt_no = $data["qt_no$i"] ?? null;
    $cs_badge = $data["cs-badge-$i"] ?? null;
    $remark = $data["remark$i"] ?? null;
    $status_badge = $data["status-badge-$i"] ?? null;
    $reason = isset( $data["reason$i"]) &&  $data["reason$i"] !== '' ?  $data["reason$i"] : NULL;
    if ($qt_no) {
        // Fetch existing data for comparison
        $sqlSelect = "SELECT is_prospect, remark, is_tracking, reasoning FROM cost_sheet_head WHERE qt_no = ?";
        $stmtSelect = sqlsrv_query($objCon, $sqlSelect, [$qt_no]);

        if ($stmtSelect === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        $existingData = sqlsrv_fetch_array($stmtSelect, SQLSRV_FETCH_ASSOC);

        // Check if there are differences between existing data and new data
        $dataChanged = false;

        if ($existingData) {
            if ($cs_badge !== null && $cs_badge != $existingData['is_prospect']) {
                $dataChanged = true;
            }
            if ($remark !== null && $remark != $existingData['remark']) {
                $dataChanged = true;
            }
            if ($status_badge !== null && $status_badge != $existingData['is_tracking']) {
                $dataChanged = true;
            }
            if ($reason !== null && $reason != $existingData['reasoning']) {
                $dataChanged = true;
            }
        }

        // Only perform update if data has changed
        if ($dataChanged) {
            $sql = "UPDATE cost_sheet_head SET ";
            $params = [];

            if ($cs_badge !== null) {
                $sql .= "is_prospect = ?, ";
                $params[] = $cs_badge;
            }
            if ($remark !== null) {
                $sql .= "remark = ?, ";
                $params[] = $remark;
            }
            if ($status_badge !== null) {
                $sql .= "is_tracking = ?, ";
                $params[] = $status_badge;
            }
            if ($reason !== null) {
                $sql .= "reasoning = ?, ";
                $params[] = $reason;
            }
         // Update record_datetime as data has changed
         $sql .= "update_date = ?, ";
         $params[] = $record_datetime;
        // Remove the last comma and space
        $sql = rtrim($sql, ', ');
        $sql .= " WHERE qt_no = ?";
        $params[] = $qt_no;

        // Execute the query
        $stmt = sqlsrv_query($objCon, $sql, $params);

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }else{
            
            echo '<script>alert("อัพเดทข้อมูลแล้ว");window.location="tables-data.php";</script>';
         
            }
        }
    }
}
*/
sqlsrv_close($objCon);
?>
