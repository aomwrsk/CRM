
    document.addEventListener('DOMContentLoaded', (event) => {
      fetch('./form-appoint.php')
          .then(response => {
              if (!response.ok) {
                  throw new Error(`HTTP error! Status: ${response.status}`);
              }
              return response.json();
          })
          .then(data => {
            const selectElements = {
                sales: document.getElementById('inputSales'),
                channel: document.getElementById('inputChannel'),
                social: document.getElementById('inputSocial'),
                contract: document.getElementById('inputContract'),
                nationality: document.getElementById('inputFac-nation'),
                province: document.getElementById('inputProvince'),
                segment: document.getElementById('inputSegment'),
                clType: document.getElementById('inputCL_type'),
                appoint: document.getElementById('inputAppoint'),
                cusStatus: document.getElementById('inputCus_status'),
                isStatus: document.getElementById('inputis_status')
            };

            data.sales_data.forEach(item => createOption(selectElements.sales, item.staff_id, item.fname_e || item.nick_name || item.staff_id));
            data.channel.forEach(item => createOption(selectElements.channel, item.sales_channels_group_code, item.sales_channels_group_name));
            data.search.forEach(item => createOption(selectElements.social, item.sales_channels_search_code, item.sales_channels_search_name));
            data.contact.forEach(item => createOption(selectElements.contract, item.sales_channels_code, item.sales_channels_name));
            data.nationality.forEach(item => createOption(selectElements.nationality, item.nationality_code, item.nationality_name));
            data.province.forEach(item => createOption(selectElements.province, item.province_code, item.province_name));
            data.segment.forEach(item => createOption(selectElements.segment, item.customer_segment_code, item.customer_segment_name));
            data.CL.forEach(item => createOption(selectElements.clType, item.cleaning_type_code, item.cleaning_type_name));
            data.is_appoint.forEach(item => createOption(selectElements.appoint, item.is_appoint_code, item.is_appoint_name));
            data.is_prospect.forEach(item => createOption(selectElements.cusStatus, item.prospect_code, item.prospect_name));
            data.appoint_status.forEach(item => createOption(selectElements.isStatus, item.status_code, item.status_name));

            function createOption(select, value, text) {
                const option = document.createElement('option');
                option.value = value;
                option.textContent = text;
                select.appendChild(option);
            }
              // Get the selected value of the inputSegment dropdown
              const customerSegmentCode = selectElements.segment.value;
              selectElements.clType.disabled = customerSegmentCode !== '04' && customerSegmentCode !== '05';
              selectElements.segment.addEventListener('change', () => {
                  const updatedSegmentCode = selectElements.segment.value;
                  selectElements.clType.disabled = updatedSegmentCode !== '04' && updatedSegmentCode !== '05';
              });
              window.APData = data.ap_data;
          })
          .catch(error => console.error('Error fetching data:', error));

          
  });

  function showPopup() {
    // Show the pop-up modal
    document.getElementById('popupModal').style.display = 'block';
  
    // Fetch and populate data into the table (this is a placeholder, replace with actual data fetching logic)
    const tableBody = document.getElementById('dataTableBody');
    tableBody.innerHTML = ''; // Clear previous data
    const APData = window.APData;
  
  
    if (APData) {
        APData.forEach(item => {
            const row = document.createElement('tr');

            // Create cells for the data you want to display
            const cell1 = document.createElement('td');
            const cell2 = document.createElement('td');
            const cell3 = document.createElement('td');
            const cell4 = document.createElement('td');

            // Assuming each item in salesData has properties `staff_id` and `fname_e`
            cell1.textContent = item.appoint_no; // Replace with appropriate property
            cell2.textContent = item.appoint_date
            cell3.textContent = item.SName;
            cell4.textContent = item.customer_name; // Replace with appropriate property

            row.appendChild(cell1);
            row.appendChild(cell2);
            row.appendChild(cell3);
            row.appendChild(cell4);

            tableBody.appendChild(row);
        });
    }
}
  function closePopup() {
    // Hide the pop-up modal
    document.getElementById('popupModal').style.display = 'none';
  }

