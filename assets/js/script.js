function fetchYear() {
const year_no = document.getElementById('year').value;
const month_no = document.getElementById('month').value;
let url;
if (year_no === 0 && month_no === 0) {
  url = `revenue.php?year_no=${year_no}`; // Signal to fetch all records
} else {
  url = `revenue.php?year_no=${year_no}&month_no=${month_no}`;
}
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
          data: [31, 40, 28, 51, 42, 82, 56],
        }, {
          name: 'Revenue',
          data: [11, 32, 45, 32, 34, 52, 41]
        }, {
          name: 'Customers',
          data: [15, 11, 32, 18, 9, 24, 11]
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
            "2018-09-19T08:27:00.000Z", "2018-09-19T09:30:00.000Z", "2018-09-19T10:30:00.000Z", "2018-09-19T12:30:00.000Z", "2018-09-19T14:30:00.000Z", "2018-09-19T15:30:00.000Z", "2018-09-19T20:00:00.000Z"]
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
    document.addEventListener('DOMContentLoaded', fetchYear);