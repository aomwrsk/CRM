<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once('./connectDB.php'); // Include your database connection script
$objCon = connectDB();
// Get the raw POST data (JSON)
$input = file_get_contents("php://input");
$data = json_decode($input, true); // Decode JSON data into an associative array
$timezone = new DateTimeZone('Asia/Bangkok'); // You can use 'Asia/Bangkok', 'Asia/Jakarta', etc.

// Create a DateTime object with the specified time zone
$date = new DateTime('now', $timezone);
$record_datetime = $date->format('Y-m-d H:i:s');
if (is_array($data)) {
    foreach ($data as $trip) {
        // Prepare the SQL INSERT statement
        $sql = "INSERT INTO transport_gps_distance (recors_date, vehicle_id, start_timestamp, end_timestamp, trip_duration, start_location, end_location, trip_distance, start_geofence_name, end_geofence_name, start_coordinates_lat, start_coordinates_long, end_coordinates_lat, end_coordinates_long) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        // Prepare the parameters for SQL Server
        $params = array(
            $record_datetime,
            $trip['registration'],
            $trip['start_timestamp'],
            $trip['end_timestamp'],
            $trip['trip_duration'],
            $trip['start_location'],
            $trip['end_location'],
            $trip['trip_distance'],
            $trip['start_geofence_name'],
            $trip['end_geofence_name'],
            $trip['start_coordinates_lat'],
            $trip['start_coordinates_long'],
            $trip['end_coordinates_lat'],
            $trip['end_coordinates_long']
        );
        
        // Execute the SQL query
        $stmt = sqlsrv_query($objCon, $sql, $params);
        
        // Check if the query was successful
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }
    
    // Return a success message
    echo "Data inserted successfully";
} else {
    echo "Invalid data format";
}

// Close the SQL Server connection
sqlsrv_close($objCon);
?>
