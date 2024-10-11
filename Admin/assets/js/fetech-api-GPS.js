// Specify the API endpoint for user data
const apiUrl = 'https://fleetapi-th.cartrack.com/rest/trips?start_timestamp=2024-10-09 00:00:00&end_timestamp=2024-10-09 23:59:59';

// Make a GET request using the Fetch API
fetch(apiUrl)
  .then(response => {
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    return response.json();
  })
  .then(response => {
    // Process the retrieved user data
    console.log('User Data:', response);
  })
  .catch(error => {
    console.error('Error:', error);
  });