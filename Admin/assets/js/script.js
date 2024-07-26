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

    }
    
    
  
    document.addEventListener("DOMContentLoaded", async () => {
      try {
        const response = await fetch('reportchart.php');
        const data = await response.json();

        const appoints = data.APData.map(item => item.appoint_no);
        const costsheet = data.QTData.map(item => item.qt_no);
        const saleorder = data.SOData.map(item => item.so_no);
      new ApexCharts(document.querySelector("#reportsChart"), {
        series: [{
          name: 'Appoints',
          data: appoints,
        }, {
          name: 'Revenue',
          data: saleorder,
        }, {
          name: 'Quotation',
          data: costsheet,
        }],
        chart: {
          height: 350,
          type: 'area',
          toolbar: {
            show: false
          },
        },
        markers: {
          size: 4
        },
        colors: ['#ff771d', '#2eca6a','#4154f1'],
        fill: {
          type: "gradient",
          gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.3,
            opacityTo: 0.4,
            stops: [0, 90, 100]
          }
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth',
          width: 2
        },
        xaxis: {
          type: 'date',
          categories: [
            "2024-01", 
            "2024-02", 
            "2024-03", 
            "2024-04", 
            "2024-05", 
            "2024-06", 
            "2024-07", 
            "2024-08", 
            "2024-09", 
            "2024-10",
            "2024-11", 
            "2024-12"]
        },
        tooltip: {
          x: {  
            format: 'dd/MM/yy'
          },
        }
      }).render();
    } catch (error) {
      console.error('Error fetching chart data:', error);
    }
    });

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

    document.addEventListener("DOMContentLoaded", () => {
      new Chart(document.querySelector('#barChart'), {
        type: 'bar',
        data: {
          labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
          datasets: [{
            label: 'Bar Chart',
            data: [65, 59, 80, 81, 56, 55, 40, 55, 59, 80, 81, 56],
            backgroundColor: [
              'rgba(255, 99, 132, 0.2)',
              'rgba(255, 159, 64, 0.2)',
              'rgba(255, 205, 86, 0.2)',
              'rgba(75, 192, 192, 0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(153, 102, 255, 0.2)',
              'rgba(201, 203, 207, 0.2)'
            ],
            borderColor: [
              'rgb(255, 99, 132)',
              'rgb(255, 159, 64)',
              'rgb(255, 205, 86)',
              'rgb(75, 192, 192)',
              'rgb(54, 162, 235)',
              'rgb(153, 102, 255)',
              'rgb(201, 203, 207)'
            ],
            borderWidth: 1
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });
    });
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
  
  