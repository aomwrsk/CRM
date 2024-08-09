function fetchYear() {
const year_no = document.getElementById('year').value;
const month_no = document.getElementById('month').value;
const channel = document.getElementById('channel').value;
const Sales = document.getElementById('Sales').value;
const is_new = document.getElementById('is_new').value;
let url;

  url = `revenue.php?year_no=${year_no}&month_no=${month_no}&channel=${channel}&Sales=${Sales}&is_new=${is_new}`;
  

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
  updateChart(data.segmentData);
  Region(data.regionData)
})
.catch(error => console.error('Error fetching data:', error));

}

function updateTable(data) {
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

        // Calculate and display the ratio (revenue per sales order)
const ratio = totalSum / uniqueso.size;
const ratioElement = document.getElementById('AOV');
ratioElement.textContent = ratio.toLocaleString('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
});

const percentage = (ratio / totalSum) * 100;
const percentageElement = document.getElementById('AOV_percent');
percentageElement.textContent = percentage.toLocaleString('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
}) + ' %';


        let totalSum1 = 0;
        data.costsheetData.forEach(qt => {
          totalSum1 += parseFloat(qt.amount);
        });
        const qtElement = document.getElementById('qt_value');
        qtElement.textContent = totalSum1.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }); 

        // Process appointment data for 2024
        let uniqueAppointNos = new Set();
        data.appointData.forEach(appoint => {
            uniqueAppointNos.add(appoint.appoint_no); // Assuming each record has an appoint_no property
        });

        const countElement = document.getElementById('appoint');
        countElement.textContent = uniqueAppointNos.size; 

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

        const winrateP = (uniqueso.size / uniqueqt.size) * 100;
        const winratePElement = document.getElementById('winrate_percent');
        winratePElement.textContent = winrateP.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }) + ' %';

    }
    
    
  
    //*****************************pie segment chart ***************************************************//
    function updateChart(segmentData) {
      const chartData = segmentData.map(item => ({
        value: item.segment_count,
        name: item.customer_segment_name
      }));
    
      const chart = echarts.init(document.querySelector("#trafficChart"));
      chart.setOption({
        tooltip: {
          trigger: 'item'
        },
        legend: {
          top: '5%',
          left: 'center'
        },
        series: [{
          name: 'Segment',
          type: 'pie',
          radius: ['40%', '70%'],
          avoidLabelOverlap: false,
          label: {
            show: false,
            position: 'center'
          },
          emphasis: {
            label: {
              show: true,
              fontSize: '18',
              fontWeight: 'bold'
            }
          },
          labelLine: {
            show: false
          },
          data: chartData
        }]
      });
    }

    /*function BarChart(RegionData) {
      const regionCategories = ['North', 'Central', 'East', 'North-East', 'West', 'South'];
      const Data = RegionData.map(item => ({
        name: item.segment,
        data: regionCategories.map(region => item[region] || 0) // Ensure data is an array of region counts
    }));
    
      const chart = new ApexCharts(document.querySelector("#columnChart"), {
        chart: {
          type: 'bar',
          height: 350
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'
          },
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories: regionCategories,
        },
        yaxis: {
          title: {
            text: 'Customers'
          }
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function(val) {
              return val + " customers";
            }
          }
        },
        legend: {
          top: '5%',
          left: 'center'
        },
        series: Data
      });
    
      chart.render();
    }*/
    document.addEventListener('DOMContentLoaded', fetchYear);

    document.addEventListener('DOMContentLoaded', (event) => {
      fetch('staff_id.php')
          .then(response => {
              if (!response.ok) {
                  throw new Error(`HTTP error! Status: ${response.status}`);
              }
              return response.json();
          })
          .then(data => {
              const selectElement = document.getElementById('Sales');
              data.forEach(item => {
                  const option = document.createElement('option');
                  option.value = item.staff_id;
                  option.textContent = item.fname_e || item.nick_name || item.staff_id; 
                  selectElement.appendChild(option);
              });
          })
          .catch(error => console.error('Error fetching data:', error));
  });

  function Region(regionData){
    const tableBody = document.getElementById('Rtable');
    tableBody.innerHTML = ''; // Clear any existing rows
    
    regionData.forEach(item => {
        const row = document.createElement('tr');
        
        const segmentCell = document.createElement('td');
        segmentCell.textContent = item.segment;
        row.appendChild(segmentCell);
        
        const northCell = document.createElement('td');
        northCell.textContent = item.North || 0;
        row.appendChild(northCell);
        
        const centralCell = document.createElement('td');
        centralCell.textContent = item.Central || 0;
        row.appendChild(centralCell);
        
        const eastCell = document.createElement('td');
        eastCell.textContent = item.East || 0;
        row.appendChild(eastCell);
        
        const northeastCell = document.createElement('td');
        northeastCell.textContent = item['North-East'] || 0;
        row.appendChild(northeastCell);
        
        const westCell = document.createElement('td');
        westCell.textContent = item.West || 0;
        row.appendChild(westCell);
        
        const southCell = document.createElement('td');
        southCell.textContent = item.South || 0;
        row.appendChild(southCell);
        
        tableBody.appendChild(row);
    });
  }
  // Example data for testing
document.addEventListener('DOMContentLoaded', function() {
  const exampleData = [
      { segment: 'Segment 1', North: 10, Central: 15, East: 20, 'North-East': 25, West: 30, South: 35 },
      { segment: 'Segment 2', North: 5, Central: 10, East: 15, 'North-East': 20, West: 25, South: 30 }
  ];
  Region(exampleData); // Populate the table with example data
});
  