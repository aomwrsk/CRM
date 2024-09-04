document.addEventListener('DOMContentLoaded', () => {
    populateMonthYearSelectors();
    // Attach event listeners for the select elements
    document.getElementById('month').addEventListener('change', fetchData);
    document.getElementById('year').addEventListener('change', fetchData);
});

function showPopup() {
    document.getElementById('popupModal').style.display = 'block';
    fetchData(); // Optional: Fetch data when the modal is shown
}

function closePopup() {
    document.getElementById('popupModal').style.display = 'none';
}

function fetchData() {
    const year_no = document.getElementById('year').value;
    const month_no = document.getElementById('month').value;
    const staff = document.getElementById('staff').value; // Make sure you have this input
    const url = `edit_appoint.php?year_no=${year_no}&month_no=${month_no}&staff=${staff}`;

    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            showTableData(data);
        })
        .catch(error => console.error('Error fetching data:', error));
}

function showTableData(data) {
    const tableBody = document.getElementById('dataTableBody');
    tableBody.innerHTML = ''; // Clear previous data
    const APData = data.ap_data;

    if (APData) {
        APData.forEach(item => {
            const row = document.createElement('tr');

            // Create cells for the data you want to display
            const cell1 = document.createElement('td');
            cell1.textContent = item.appoint_no;
            cell1.style.cursor = 'pointer'; // Change cursor to pointer to indicate clickable
            cell1.addEventListener('click', () => handleCellClick(item.appoint_no));
            const cell2 = document.createElement('td');
            cell2.textContent = item.appoint_date;
            const cell3 = document.createElement('td');
            cell3.textContent = item.SName;
            const cell4 = document.createElement('td');
            cell4.textContent = item.customer_name;
            

            row.appendChild(cell1);
            row.appendChild(cell2);
            row.appendChild(cell3);
            row.appendChild(cell4);

            tableBody.appendChild(row);
        });
    }
}
function handleCellClick(appoint_no) {
    // Example URL - change as needed
    const url = `fetch_ap.php?appoint_no=${appoint_no}`;

    // Perform fetch or AJAX request
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // Handle the response from the PHP script
            console.log('Data received from server:', data);
            // Optionally, update the UI with the new data
        })
        .catch(error => console.error('Error fetching data:', error));
}
function populateMonthYearSelectors() {
    const monthSelect = document.getElementById('month');
    const monthNames = [
        "January", "February", "March", "April", "May", "June", 
        "July", "August", "September", "October", "November", "December"
    ];

    monthNames.forEach((month, index) => {
        const option = document.createElement('option');
        option.value = index + 1; // 1 for January, 2 for February, etc.
        option.text = month;
        monthSelect.appendChild(option);
    });

    // Set the current month as the selected option
    const currentMonth = new Date().getMonth() + 1;
    monthSelect.value = currentMonth;

    const yearSelect = document.getElementById('year');
    const currentYear = new Date().getFullYear();
    const startYear = 2023;

    for (let year = currentYear; year >= startYear; year--) {
        const option = document.createElement('option');
        option.value = year;
        option.text = year;
        yearSelect.appendChild(option);
    }
}
