<?php
// Get the raw POST data (the JSON sent from the JavaScript fetch)
$json = file_get_contents('php://input');

// Decode the JSON into a PHP array
$data = json_decode($json, true);  // The 'true' argument converts JSON to associative array

// Check if data was received and decoded properly
if ($data === null) {
    echo 'No valid JSON received.';
    exit;
}

// You can now use $data as a PHP array
// Example: print the data (for debugging)
print_r($data);

// Process the data or insert it into your database, etc.
// ...

// Return a response (optional)
echo "Data received and processed.";
?>
