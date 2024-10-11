var myHeaders = new Headers();

let username = 'ENTE00001';
let token = '38435c4cfd2ed5578ea86978478dc5bb84f62384b4666b169e6a4d43b5bc1206';
let auth = btoa(`${username}:${token}`);

myHeaders.append("Authorization", `Basic ${auth}`);
myHeaders.append("Content-Type", "application/json");

var requestOptions = {
    method: 'GET',
    headers: myHeaders,
    redirect: 'manual'
};

// Fetch data from the API
fetch("https://fleetapi-th.cartrack.com/rest/trips?start_timestamp=2024-10-08 00:00:00&end_timestamp=2024-10-08 23:59:59&page=5", requestOptions)
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.status);
        }
        return response.json();  // Parse response as JSON
    })
    .then(data => {
        console.log('Fetched Data:', data);  // Debug: log the full data

        // Check if data.data is defined and is an array
        if (Array.isArray(data.data)) {
             
            // Extract only the vehicle_id and registration from each trip
            const filteredData = data.data.map(trip => ({
                registration: trip.registration,
                start_timestamp: trip.start_timestamp,
                end_timestamp: trip.end_timestamp,
                trip_duration: trip.trip_duration,
                start_location: trip.start_location,
                end_location: trip.end_location,
                trip_distance: trip.trip_distance,
                start_coordinates_lat: trip.start_coordinates.latitude,
                start_coordinates_long: trip.start_coordinates.longitude,
                end_coordinates_lat: trip.end_coordinates.latitude,
                end_coordinates_long: trip.end_coordinates.longitude
            }));

            console.log('Filtered Data:', filteredData);  // Debug: log the filtered data
            exportToExcel(filteredData);
            // Export the filtered data to Excel
             // Call the function to export data
        } else {
            console.error('The data property is not defined or is not an array.');
        }
    })
    .catch(error => console.log('Error:', error));

// Function to export data to Excel
function exportToExcel(data) {
    // Create a new workbook and a new sheet
    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.json_to_sheet(data);

    // Add the worksheet to the workbook
    XLSX.utils.book_append_sheet(wb, ws, "Trips Data");

    // Generate the Excel file and trigger download
    XLSX.writeFile(wb, 'trips_data1.xlsx');
}

// Function to post data to PHP
function postDataToPHP(data) {
    fetch('fetch-GPS.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',  // Send data as JSON
        },
        body: JSON.stringify(data)  // Send the filtered data as a JSON string
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('PHP post error: ' + response.status);
        }
        return response.text();  // Get response from the PHP script
    })
    .then(result => console.log('PHP Response:', result))
    .catch(error => console.log('Error:', error));
}
