function fetchYear() {
const year_no = document.getElementById('year').value;
const month_no = document.getElementById('month').value;
const channel = document.getElementById('channel').value;
let url;

  url = `revenue.php?year_no=${year_no}&month_no=${month_no}&channel=${channel}`;

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
        data.revenueData.forEach(revenue => {
            totalSum += parseFloat(revenue.total_before_vat);
        });

        const revenueElement = document.getElementById('revenue');
        revenueElement.textContent = totalSum.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }); 

        // Process appointment data for 2024
        let uniqueAppointNos = new Set();
        data.appointData.forEach(appoint => {
            uniqueAppointNos.add(appoint.appoint_no); // Assuming each record has an appoint_no property
        });

        const countElement = document.getElementById('appoint');
        countElement.textContent = uniqueAppointNos.size; // Display the total count of unique appoint_no
    }
  
    document.addEventListener("DOMContentLoaded", () => {
      new ApexCharts(document.querySelector("#reportsChart"), {
        series: [{
          name: 'Sales',
          data: [31, 40, 28, 51, 42, 92, 56, 60, 0, 0],
        }, {
          name: 'Revenue',
          data: [11, 32, 45, 32, 34, 52, 41, 51, 0, 0]
        }, {
          name: 'Customers',
          data: [15, 11, 32, 18, 9, 24, 11, 25, 0, 0]
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
        colors: ['#4154f1', '#2eca6a', '#ff771d'],
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
          type: 'datetime',
          categories: [
            "2024-09-19T08:00:00.000Z", 
            "2024-09-19T09:00:00.000Z", 
            "2024-09-19T10:00:00.000Z", 
            "2024-09-19T11:00:00.000Z", 
            "2024-09-19T12:00:00.000Z", 
            "2024-09-19T13:00:00.000Z", 
            "2024-09-19T14:00:00.000Z", 
            "2024-09-19T15:00:00.000Z", 
            "2024-09-19T16:00:00.000Z", 
            "2024-09-19T17:00:00.000Z"]
        },
        tooltip: {
          x: {
            format: 'dd/MM/yy HH:mm'
          },
        }
      }).render();
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
      fetch('http://localhost:5000/api/sales')
          .then(response => response.json())
          .then(data => {
              const selectElement = document.getElementById('Sales');
              data.forEach(item => {
                  const option = document.createElement('option');
                  option.value = item.staff_id;
                  option.textContent = item.fname_e || item.nick_name || item.staff_id; // Choose appropriate display name
                  selectElement.appendChild(option);
              });
          })
          .catch(error => console.error('Error fetching data:', error));
  });