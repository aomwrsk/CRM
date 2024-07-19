function fetchData() {
  const year_no = document.getElementById('year').value;
  const month_no = document.getElementById('month').value;
  const channel = document.getElementById('channel').value;

  let url;

  url = `api.php?year_no=${year_no}&month_no=${month_no}&channel=${channel}`;

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

  data.forEach((row, index) => {
    const tr = document.createElement('tr');

    let badgeClass = 'badge bg-warning';
    switch (row.status) {
      case 'ไม่ได้งาน':
        badgeClass = 'badge bg-danger';
        break;
      case 'ได้งาน':
        badgeClass = 'badge bg-success';
        break;
      case 'ไม่เสนอราคา':
        badgeClass = 'badge bg-secondary';
        break;
    }

    tr.innerHTML = `
      <td>${row.ap}</td>
      <td>${row.name}</td>
      <td>${row.city}</td>
      <td>${row.date}</td>
      <td><span id="status-badge-${index + 1}" class="${badgeClass}" onclick="changeStatus(this)">${row.status}</span></td>
    `;

    tbody.appendChild(tr);
  });
}

function changeStatus(element) {
  switch(element.innerText) {
    case 'ไม่ได้งาน':
      element.innerText = 'อยู่ระหว่างการติดตาม';
      element.classList.replace('bg-danger', 'bg-warning');
      break;
    case 'อยู่ระหว่างการติดตาม':
      element.innerText = 'ไม่เสนอราคา';
      element.classList.replace('bg-warning', 'bg-secondary');
      break;
    case 'ไม่เสนอราคา':
      element.innerText = 'ได้งาน';
      element.classList.replace('bg-secondary', 'bg-success');
      break;
    default:
      element.innerText = 'ไม่ได้งาน';
      element.classList.replace('bg-success', 'bg-danger');
      break;
  }
}

document.addEventListener('DOMContentLoaded', fetchData);
