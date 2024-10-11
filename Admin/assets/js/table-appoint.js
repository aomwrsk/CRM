
function fetchData() {
  const year_no = document.getElementById('year').value;
  const month_no = document.getElementById('month').value;
  /*const channel = document.getElementById('channel').value;*/
  const Sales = document.getElementById('sales').value;
  let url;

  url = `fetch-appoint.php?year_no=${year_no}&month_no=${month_no}&Sales=${Sales}`;
 

    fetch(url)
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.json();
    })
    .then(data => {
      console.log('Data:', data); // Log the data to check the response
      updateTable(data);

    })
    .catch(error => console.error('Error fetching data:', error));
    }

function updateTable(data) {
  const tbody = document.querySelector('#tableAP tbody');
  tbody.innerHTML = '';

  data.tableData.forEach((row, index) => {
    if (!row || !row.appoint_no) {
      console.error(`Row ${index + 1} is invalid:`, row);
      return; // Skip this row if it's invalid
    }
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <th scope='row'>${index+1}</th>
      <td>${row.format_date ? row.format_date : ''}</td>
      <td>${row.customer_name}</td>
      <td>${row.appoint_no}</td>
    `;
    tbody.appendChild(tr);
  });
}
document.addEventListener('DOMContentLoaded', fetchData);

document.addEventListener('DOMContentLoaded', (event) => {
  fetch('staff_id.php')
      .then(response => {
          if (!response.ok) {
              throw new Error(`HTTP error! Status: ${response.status}`);
          }
          return response.json();
      })
      .then(data => {
          const selectElement = document.getElementById('sales');
          data.forEach(item => {
              const option = document.createElement('option');
              option.value = item.staff_id;
              option.textContent = item.fname_e || item.nick_name || item.staff_id; 
              selectElement.appendChild(option);
          });
      })
      .catch(error => console.error('Error fetching data:', error));
});
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

// Optionally, set the current month as the selected option
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

function confirmUpdate() {
  return confirm("Are you sure you want to update the records?");
}
