function fetchset() {

  // Find the selected radio button
  const selectedRadio = document.querySelector('input[name="issue"]:checked');
  const set_type = document.getElementById('Trans_set').value;
  if (selectedRadio) {
    // Get data attributes from the selected row
    const owNo = selectedRadio.getAttribute('data-ow-no');
    const qtNo = selectedRadio.getAttribute('data-qt-no');
    // Set the value of the input field with the selected OW No.
    document.getElementById('Is_No').value = owNo;
    let url;

    url = `fetch_is.php?issue_no=${owNo}&qt_no=${qtNo}&set=${set_type}`;
  fetch(url)
  .then(response => {
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    return response.json();
  })
  .then(data => {
    console.log('Selected OW No:', owNo);
    console.log('Selected QT No:', qtNo);
    const daysLeft = data.days_left;
    const expirationDate = data.expiration_qt_date;
    const dateObj = new Date(expirationDate);

// Extract the year, month, and day
const formattedDate = dateObj.getFullYear() + '-' + 
                      String(dateObj.getMonth() + 1).padStart(2, '0') + '-' + 
                      String(dateObj.getDate()).padStart(2, '0');
    if (daysLeft <= 30 && daysLeft > 0) {
      alert(`ใบเสนอราคา จะหมดอายุวันที่ ${formattedDate} จำนวนวันคงเหลือ ${daysLeft} วัน`);
    }else if (daysLeft < 0) {
      alert(`ใบเสนอราคาหมดอายุวันที่ ${formattedDate} ไม่สามารถเปิด Order ได้`);
      window.location.href = "forms-order.php"; // Corrected the redirection syntax
    }
    
    updateData(data);
  })
  } else {
    alert('กรุณาเลือกแถวก่อนยืนยัน');
  }
  const modalElement = document.getElementById('ExtralargeModal');
  const modal = bootstrap.Modal.getInstance(modalElement); // Bootstrap 5 API to get the modal instance
  modal.hide(); // Close the modal
}
function updateData(data) {
  // Ensure the data format is correct before updating the inputs
  if (data.issue && data.issue.length > 0) {
    const issue = data.issue[0]; // Assuming you need only the first issue
    document.getElementById('inputIs_No').value = issue.issue_no; // Update the input field with issue number
    document.getElementById('inputQt_No').value = issue.qt_no;
    document.getElementById('department').value = issue.department_code;
    document.getElementById('Customer_contact').value = issue.contact_name;
    document.getElementById('Customer_name').value = issue.customer_name;
    document.getElementById('Comp_Address').value = issue.address1 + ' ' + issue.tambon_name + ' ' + issue.amphur_name + ' ' + issue.province_name + ' ' + issue.zip_code;
    document.getElementById('Staff_name').value = issue.name + ' ' + issue.lname;
    document.getElementById('inputTrans_type').value = issue.size + '(' + issue.vehicle_transport_name + ')/' + issue.transport_group + ' ' + issue.capacity + 'ตัน/เที่ยว';
    document.getElementById('Trans_group').value = issue.vehicle_group_name;
    document.getElementById('Container_support').value = issue.container_type_name;
  } else {
    console.error('No issue data found');
  }
  const tbody = document.querySelector('#tablewaste tbody');
  tbody.innerHTML = ''; // Clear the table body before adding new rows

  data.waste.forEach((row, index) => {
  
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <th scope='row'>${index + 1}</th>
      <td><input type="checkbox" class="form-check-input"  name="selectWaste" id="selectWaste${index + 1}"style="margin-left: 20%;"></td>
      <td>${row.waste_code}</td>
      <td>${row.waste_name}</td>
      <td>${row.eliminate_code}</td>
      <td>${row.cost_qty === 0 || row.cost_qty === null ? '' : row.cost_qty}</td>
      <td>${row.unit_name}</td>
      <td>${row.cost_amount}</td>
      <td>${row.customer}</td>
      <td>${row.mf_code}</td>
      <td>
  <input type="checkbox" class="form-check-input" id="is_factory${index + 1}" name="is_factory" 
         ${row.is_factory === 'Y' ? 'checked' : ''} disabled>
  <input type="hidden" name="is_factory_hidden" value="${row.is_factory === 'Y' ? 'Y' : 'N'}">
</td>
     <td>${row.request_sk_no ? row.request_sk_no : ''}</td>
    `;
    tbody.appendChild(tr);
  });

  const tbody1 = document.querySelector('#tableorder_remark tbody');
  tbody1.innerHTML = ''; // Clear the table body before adding new rows

  data.order_remark.forEach((row, index) => {
  
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <th scope='row'>${index + 1}</th>
      <td><input type="text" class="form-control" name="order_remark" id="order_remark${index + 1}" value="${row.remark ? row.remark : ''}"></td>
     <td></td>
    `;
    tbody1.appendChild(tr);
  });
}
document.getElementById('confirmBtn').addEventListener('click', fetchset);