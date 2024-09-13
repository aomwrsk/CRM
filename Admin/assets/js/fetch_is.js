function fetchData() {

const issue = document.getElementById('Is_No').value;
let url;

  url = `fetch_is.php?issue_no=${issue}`;
fetch(url)
.then(response => {
  if (!response.ok) {
    throw new Error('Network response was not ok');
  }
  return response.json();
})
.then(data => {
  console.log('Data:', data); // Log the data to check the response
  updateData(data);

})
.catch(error => console.error('Error fetching data:', error));

}
function updateData(data) {
        let totalSum = 0;
        let uniqueso = new Set();
        data.revenueData.forEach(revenue => {
            totalSum += parseFloat(revenue.total_before_vat);
            uniqueso.add(revenue.so_no); 
        });

        const revenueElement = document.getElementById('revenue');
        revenueElement.textContent = totalSum.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }); 

        
        const countElement2 = document.getElementById('so_number');
        countElement2.textContent = uniqueso.size; 




        let totalSum1 = 0;
        data.costsheetData.forEach(qt => {
          totalSum1 += parseFloat(qt.amount);
        });
        const qtElement = document.getElementById('qt_value');
        qtElement.textContent = totalSum1.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }); 

       
        let uniqueap = new Set();
        data.appointData.forEach(ap => {
          uniqueap.add(ap.appoint_no); 
        });
        const countElement = document.getElementById('appoint');
        countElement.textContent = uniqueap.size;
        

        let uniqueqt = new Set();
        data.costsheetData.forEach(qt => {
          uniqueqt.add(qt.qt_no); 
        });

        const countElement1 = document.getElementById('qt_number');
        countElement1.textContent = uniqueqt.size; 

                // Calculate and display the ratio (revenue per sales order)
        const winrate = uniqueso.size;
        const winrateElement = document.getElementById('winrate');
        winrateElement.textContent = winrate.toLocaleString('en-US', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });

        const winrateP = (uniqueso.size / uniqueqt.size) * 100 || 0;
        const winratePElement = document.getElementById('winrate_percent');
        winratePElement.textContent = winrateP.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }) + ' %';
                // Calculate and display the ratio (revenue per sales order)
const ratio = totalSum / uniqueso.size || 0;
const ratioElement = document.getElementById('AOV');
ratioElement.textContent = ratio.toLocaleString('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
});

const percentage = (ratio / totalSum) * 100 || 0;
const percentageElement = document.getElementById('AOV_percent');
percentageElement.textContent = percentage.toLocaleString('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
}) + ' %';
const tbody = document.querySelector('#region tbody');
  tbody.innerHTML = '';

  data.regionData.forEach((row, index) => {
    const tr = document.createElement('tr');

    tr.innerHTML = `
      <td>${row.segment}</td>
      <td>${row.North}</td>
      <td>${row.Central}</td>
       <td>${row.East}</td>
      <td>${row.North_East}</td>
      <td>${row.West}</td>
     <td>${row.South}</td>
    `;

    tbody.appendChild(tr);
  });
    }
    
    
  
  
    document.addEventListener('DOMContentLoaded', fetchData);
