function fetchYear() {
const year_no = document.getElementById('year').value;
const month_no = document.getElementById('month').value;
let url;

  url = `maintanance.php?year_no=${year_no}&month_no=${month_no}`;
  

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
  updateChart(data.graphpieData);
  updateReport(data);

})
.catch(error => console.error('Error fetching data:', error));

}


function updateTable(data) {
        let totalSum = 0;
        let totalSumbox = 0;
        data.boxData.forEach(box => {
            totalSum += parseFloat(box.total_amount)|| 0;
            totalSumbox += parseFloat(box.vehicle_code)|| 0;
        });

        const revenueElement = document.getElementById('container_value');
        revenueElement.textContent = totalSum.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }); 

        const countElement2 = document.getElementById('container_number');
        countElement2.textContent = totalSumbox.toLocaleString('en-US', {
        });  


        let totalSum1 = 0;
        let totalSum2 = 0;
        data.vehicleData.forEach(vehicle => {
          totalSum1 += parseFloat(vehicle.total_amount)|| 0;
          totalSum2 += parseFloat(vehicle.vehicle_code)|| 0;
        });
        const qtElement = document.getElementById('vehicle_value');
        qtElement.textContent = totalSum1.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }); 


        const countElement1 = document.getElementById('vehicle_number');
        countElement1.textContent = totalSum2.toLocaleString('en-US', {
        }) ; 
/*
                // Calculate and display the ratio (revenue per sales order)
        const winrate = totalSumso || 0;
        const winrateElement = document.getElementById('winrate');
        winrateElement.textContent = winrate.toLocaleString('en-US', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });

        const winrateP = (totalSumso / totalSumqt) * 100 || 0;
        const winratePElement = document.getElementById('winrate_percent');
        winratePElement.textContent = winrateP.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }) + ' %';
                // Calculate and display the ratio (revenue per sales order)
const ratio = totalSum / totalSumso || 0;
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
  });*/
    }
    
    
  
    //*****************************pie segment chart ***************************************************//
    function updateChart(graphpieData) {
      // Prepare chart data with segment_count as the value for the pie chart
      const chartData = graphpieData.map(item => ({
        value: item.Countma, // This will be the displayed value in the pie chart
        name: item.vehicle_code, // Segment name for the pie slices
        total_before_vat: item.total_amount, // Include total_before_vat for the tooltip
      }));
    
      // Initialize chart on the element with ID 'trafficChart'
      const chart = echarts.init(document.querySelector("#trafficChart"));
    
      // Set chart options
      chart.setOption({
        tooltip: {
          trigger: 'item',
          formatter: function (params) {
 // Format total_before_vat with commas and two decimal places
 const formattedValue = params.data.total_before_vat.toLocaleString('en-US', {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
});

// Calculate the percentage of the segment
const percentage = params.percent.toFixed(2);
            return `
              <b>${params.name}</b><br>
              Product qty: ${params.value}<br>
              Ratio: ${percentage} %<br>
              Value: ${formattedValue}<br>
            `;
          }
        },
        legend: {
          top: '5%',
          left: 'center'
        },
        series: [{
          name: 'Maintanance',
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
          data: chartData // Use the prepared chartData
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

    /*document.addEventListener('DOMContentLoaded', (event) => {
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
  });*/

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
function updateReport(data) {
  const box = data.boxData.map(item => item.appoint_no);
  const vehicle = data.graphData.map(item => item.total_amount);
  const dateAP = data.graphData.map(item => item.format_date);
  const month_no = document.getElementById('month').value;
  const monthNames = [
    "January", "February", "March", "April", "May", "June", 
    "July", "August", "September", "October", "November", "December"
  ];
  const target = [
    "160000", "160000", "160000", "160000", "160000", "160000", 
    "160000", "160000", "160000", "160000", "160000", "160000"
  ];
  const formattedValue = target.toLocaleString('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  });
  if(month_no == 0){
    monthName = monthNames;
  }else{  
    monthName = dateAP;
}
  new ApexCharts(document.querySelector("#reportsChart"), {
    series: [/*{
      name: 'Appoints',
      data: appoints,
    }, */{
      name: 'Target',
      data: target,
    }, {
      name: 'Maintanance',
      data: vehicle,
    }],
    chart: {
      height: 350,
      type: 'bar',
      toolbar: {
        show: false
      },
    },
    markers: {
      size: 4
    },
    colors: [/*'#ff771d',*/ '#4154f1', '#2eca6a'],
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
      type: 'category',
      categories: monthName
    },
    tooltip: {
      y: {
        formatter: function(value) {
          return value.toLocaleString(undefined, { style: 'currency', currency: 'THB' });
        },
      },
    }
  }).render();
}
