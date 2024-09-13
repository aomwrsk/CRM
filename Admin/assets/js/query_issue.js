let dataTable; // Declare this outside the function for scope
let dataTable1;
function fetchData() {
  fetch('query_issue.php')
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.json();
    })
    .then(data => {
      console.log('Data:', data); // Log the data to check the response
      updateTable(data);

      // Destroy previous DataTable instance if it exists
      if (dataTable) {
        dataTable.destroy();
      }else if(dataTable1) {
        dataTable1.destroy();
      }

      // Reinitialize the DataTable after the data has been updated
      dataTable = new simpleDatatables.DataTable("#tableOW");
      dataTable1 = new simpleDatatables.DataTable("#tableOR");
    })
    .catch(error => console.error('Error fetching data:', error));
}

function updateTable(data) {
  const tbody = document.querySelector('#tableOW tbody');
  tbody.innerHTML = ''; // Clear the table body before adding new rows

  const tbody1 = document.querySelector('#tableOR tbody');
  tbody1.innerHTML = ''; // Clear the table body before adding new rows

  data.OWdata.forEach((row, index) => {
    if (!row || !row.qt_no) {
      console.error(`Row ${index + 1} is invalid:`, row);
      return; // Skip this row if it's invalid
    }
    const dateTime = row.issue_date;
    const dateObj = new Date(dateTime);
    const formattedDate = dateObj.toISOString().split('T')[0];
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${row.issue_no}</td>
      <td>${formattedDate}</td>
      <td>${row.qt_no}</td>
      <td>${row.customer_code}</td>
      <td>${row.customer_name ? row.customer_name : ''}</td>
      <td><input type="radio" class="form-check-input" id="gridRadios${index + 1}" name="issue" data-qt-no="${row.qt_no}" data-ow-no="${row.issue_no}"></td>
    `;
    tbody.appendChild(tr);
  });
  data.ORdata.forEach((row, index) => {
    if (!row || !row.issue_no) {
      console.error(`Row ${index + 1} is invalid:`, row);
      return; // Skip this row if it's invalid
    }
    const dateTime = row.shipment_date;
    const dateObj = new Date(dateTime);
    const formattedDate = dateObj.toISOString().split('T')[0];
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${row.order_no}</td>
      <td>${formattedDate}</td>
      <td>${row.issue_no}</td>
      <td>${row.customer_code}</td>
      <td>${row.customer_name ? row.customer_name : ''}</td>
      <td>${row.plan_no ? row.plan_no : ''}</td>
      <td>${row.is_status}</td>
      <td><input type="radio" class="form-check-input" id="Orders${index + 1}" name="order" data-or-no="${row.or_no}" data-ow-no="${row.issue_no}"></td>
    `;
    tbody1.appendChild(tr);
  });
}

document.addEventListener('DOMContentLoaded', fetchData);
