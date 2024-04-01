@extends('common-app.master')
@section('title', 'Client Dashboard')
@section('content')
<style>
    .py-3 {
    padding-top: 1rem !important;
    padding-bottom: 2rem !important;
}
.black{
    color:#000 !important;
    font-weight:bold !important;
}
</style>
<!-- Header Section-->
<section class="bg-white py-3">
  <div class="row d-flex align-items-md-stretch ms-2 pb-2">
    <div class="col-lg-3 col-md-6">
      <label class="p-b-10" for="reportrange">From Date:</label>
      <div class="input-group">     
          <input type="hidden" id="range" value="">
        <input class="form-control" id='reportrange' name="from_date" type="text" placeholder="From Date" >
      </div>
    </div>
  </div>
</section>
<section class="bg-white">
  <div class="row d-flex align-items-md-stretch">
    <div class="col-lg-3 col-md-6" style="background: #d7c5c5 !important;">
      <div class="shadow-0">
        <div class="card-body p-0">
            <div class="<!--bg-light--> p-3 shadow-sm" style='background:#e9e9df !important'>                 
              <a class="btn btn-xs btn-dark py-1 m-1" href="#!" ><i class="fas fa-thumbs-up me-1" ></i><span id="countDelivered"></span> </a><a class="btn btn-xs btn-dark py-1 m-1" href="#!" ><i class="fas fa-heart me-1" > </i>Delivered Shipment</a>
                <small class="text-gray-600"></small>
            </div>
        </div>
      </div>
    </div>       
    <div class="col-lg-3 col-md-6" style="background: #c7b0b0 !important">
      <div class="shadow-0">
        <div class="card-body p-0">
            <div class="bg-light p-3 shadow-sm" style='background:#adad68 !important'>
                <a class="btn btn-xs btn-dark py-1 m-1" href="#!"><i class="fas fa-thumbs-up me-1"></i><span id="countRto"></span></a><a class="btn btn-xs btn-dark py-1 m-1" href="#!"><i class="fas fa-heart me-1" > </i>RTO Shipment</a>
              <small class="text-gray-600"></small>
            </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6" style="background: #9a5c5c !important;">
      <div class="shadow-0">
        <div class="card-body p-0">
            <div class="bg-light p-3 shadow-sm" style='background:#c7c75d !important'>
                <a class="btn btn-xs btn-dark py-1 m-1" href="#!"><i class="fas fa-thumbs-up me-1"></i><span id="countPendingpick"></span></a><a class="btn btn-xs btn-dark py-1 m-1" href="#!"><i class="fas fa-heart me-1"> </i>Pending Pickup Shipment</a>
              <small class="text-gray-600"></small>
            </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6" style="background: #daa4a4 !important;">
      <div class="shadow-0">
        <div class="card-body p-0">
            <div class="bg-light p-3 shadow-sm" style='background:#eaea13 !important'>
                <a class="btn btn-xs btn-dark py-1 m-1" href="#!"><i class="fas fa-thumbs-up me-1"></i><span id="countIntransit"></span></a><a class="btn btn-xs btn-dark py-1 m-1" href="#!"><i class="fas fa-heart me-1" > </i>In Transit Shipment</a>
              <small class="text-gray-600"></small>
            </div>
        </div>
      </div>
    </div>
  </div>
</section>
<hr />
<!-- Statistics Section-->
<section class="bg-white py-3">
  <div class="container-fluid">
    <div class="row align-items-stretch gy-4">
      <div class="col-lg-4">
        <!-- Income-->
        <div class="card text-center h-100 mb-0" style='background: antiquewhite;'>
          <div class="card-body">
            <svg class="svg-icon svg-icon-big svg-icon-light mb-4 text-muted">
              <use xlink:href="#sales-up-1"> </use>
            </svg>
            <p class="text-gray-700 display-6" id="revenue"></p>
            <p class="text-primary h2 fw-bold black">Revenue</p>
            <p class="text-xs text-gray-600 mb-0 black">Value of delivered orders</p>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <!-- Monthly Usage-->
        <div class="card h-100 mb-0" style='background:#fdefde !important'>
          <div class="card-body">
            <h2 class="h3 fw-normal mb-4">Shipment VS Delivered</h2>
            <div class="row align-items-center mb-3 gx-lg-5">
              <div class="col-lg-6">
                <table class="w-100">
                  <tbody>
                    <tr>
                      <td>
                        <div class="position-relative mx-auto" style="max-width: 120px">
                          <canvas class="mx-auto" id="monthlyProgress" width="150" height="150"></canvas>
                          <p class="h3 text-primary fw-normal position-absolute top-50 start-50 translate-middle text-center m-0" id="monthlyPercent"></p>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="col-lg-6 border-start">
              <p class="text-xs fw-light text-gray-500 mb-0 black">Total Shipment</p>
                <p class="fw-bold h2 text-primary" id="total_order"></p>
                <p class="text-xs fw-light text-gray-500 mb-0 black">Delivered Order</p>
                <p class="text-gray-500" id="delivered_order"></p>
              </div>
            </div>
            <p class="text-xs text-muted black">Monthly progress percent of delivered out of total shipment</p>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <!-- User Actibity-->
        <div class="card h-100 mb-0" style='background:#dbd6d0 !important'>
          <div class="card-body">
            <h2 class="h3 fw-normal mb-4">Total COD vs Prepaid</h2>
            <p class="display-6" id="total_shipment">0</p>
            <h3 class="h4 fw-normal"></h3>
            <div class="progress rounded-0 mb-3" id="progress">
              <div class="progress-bar progress-bar bg-primary" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="d-flex justify-content-between">
              <div class="text-start">
                <p class="h5 fw-normal mb-2">COD</p>
                <p class="fw-bold text-xl text-primary mb-0" id="cod">0</p>
              </div>
              <div class="text-end">
                <p class="h5 fw-normal mb-2">Prepaid</p>
                <p class="fw-bold text-xl text-primary mb-0" id="prepaid">0</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<hr />
<section class="bg-white">
  <div class="container-fluid">
    <div class="row d-flex align-items-md-stretch">
      <!-- To Do List-->
      <div class="col-lg-2 col-md-6">
        <div class="card shadow-0">
          <div class="card-body p-3">
            <h2 class="h3 fw-normal">Top destination</h2>
            <!--<p class="text-sm text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>-->
            <div class="form-check" id="cities">  
            </div>
          </div>           
        </div>
      </div>
    
      <!-- Pie Chart-->
      <div class="col-lg-4 col-md-6">
        <div class="card shadow-0">
            <div class="card-body p-3">
                <h2 class="h3 fw-normal">Shipment progress</h2>
                <div id="pieChart"></div>
            </div>
        </div>
      </div>
      <!-- Line Chart -->
      
      <div class="col-lg-6 col-md-12">
          <div id="chart"></div>
      </div>
    </div>
  </div>
</section>

  <script>   
      var donutData = [];
      var monthlyData = [];
      var monthName = [];
      var monthTotalCount = [];
      var monthDeliveredCount = [];
      $(document).ready(function () {     
        $('#reportrange').on('change', function () {
            var  dateRange= $(this).val();
            $.ajax({
              url: 'clientDashboard',
              type: "GET",
              data: {from_date:dateRange},
              success: function(data) {
                var data = data.data;
                console.log(data);
                $('#countDelivered').text(data.delivered);
                $('#countRto').text(data.rto);
                $('#countPendingpick').text(data.pending_pick);
                $('#countIntransit').text(data.transit);
                $('#revenue').text('₹ '+(data.revenue).toFixed(2));
                var ship = data.ship;
                var total_order = data.order;
                var cities = data.cities;  
                var record_months = data.monthlyReport; 
                var cod = data.cod;
                var prepaid = data.prepaid;   
                $('#total_shipment').text(data.order);     
                $('#cod').text(cod);        
                $('#prepaid').text(prepaid); 
                
                var cod_per = (cod/total_order)*100;
                var prepaid_per = (prepaid/total_order)*100;
                //console.log(cod_per,prepaid_per);
                html='<div class="progress-bar progress-bar bg-primary" role="progressbar" style="width: ' + prepaid_per + '%" aria-valuenow="'+prepaid+'" aria-valuemin="0" aria-valuemax="100"></div>';
                $('#progress').append(html); 
                $('#cities').empty();
                if((cities.length)>0){
                    cities.forEach(cityElement => {
                      var cityName = cityElement.city;
                      var cityCount = cityElement.count;
                      var html ='<div class="d-flex"><a class="btn btn-xs btn-dark py-1 m-1" href="#!"><i class="fas fa-thumbs-up me-1"></i>'+cityCount+'</a><a class="btn btn-xs btn-dark py-1 m-1" href="#!"><i class="fas fa-heart me-1"></i>'+cityName+'</a></div> '
                      $('#cities').append(html);
                  });                   
                }
                else{                   
                  $('#cities').empty();
                } 
                monthName = [];
                monthTotalCount = [];
                monthDeliveredCount = [];
                record_months.forEach(recordElement => {
                    monthName.push(recordElement.month_name);
                    monthTotalCount.push(recordElement.total_order);
                    monthDeliveredCount.push(recordElement.total_delivered)
                });
                 // Extract and parse values
                var delivered = parseInt($('#countDelivered').text());
                var rto = parseInt($('#countRto').text());
                var pending_pick = parseInt($('#countPendingpick').text());
                // Update donutData array
                
                donutData = [total_order, rto, delivered]; 
                monthlyData = [total_order,delivered]   
                onDataUpdate();                   
              },
              error: function (error) {
                  console.log("AJAX error:", error);
              }
            });                  
          });
      });
      function onDataUpdate() {
      // Now 'donutData' has been updated, and you can use it here
        console.log("Updated 'donutData':", donutData);
        console.log("Updated 'monthlyData':", monthlyData);
        console.log("Updated 'monthName':", monthName);
        console.log("Updated 'monthCount':", monthTotalCount);
        console.log("Updated 'monthCount':", monthDeliveredCount);
        // Main Template Color
        var brandPrimary = "#33b35a";

        // ------------------------------------------------------- //
        // Line Chart
        // ------------------------------------------------------ //
        var LINECHART = document.getElementById("lineCahrt");
        var myLineChart = new Chart(LINECHART, {
            type: "line",
            options: {
                legend: {
                    display: false,
                },
            },
            data: {
                labels: monthName,
                datasets: [
                    {
                        label: "Total shipment",
                        fill: true,
                        lineTension: 0.3,
                        backgroundColor: "rgba(77, 193, 75, 0.4)",
                        borderColor: brandPrimary,
                        borderCapStyle: "butt",
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: "miter",
                        borderWidth: 1,
                        pointBorderColor: brandPrimary,
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 1,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: brandPrimary,
                        pointHoverBorderColor: "rgba(220,220,220,1)",
                        pointHoverBorderWidth: 2,
                        pointRadius: 1,
                        pointHitRadius: 0,
                        data: monthTotalCount,
                        spanGaps: false,
                    },
                    {
                        label: "Total delivered",
                        fill: true,
                        lineTension: 0.3,
                        backgroundColor: "rgba(75,192,192,0.4)",
                        borderColor: "rgba(75,192,192,1)",
                        borderCapStyle: "butt",
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: "miter",
                        borderWidth: 1,
                        pointBorderColor: "rgba(75,192,192,1)",
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 1,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "rgba(75,192,192,1)",
                        pointHoverBorderColor: "rgba(220,220,220,1)",
                        pointHoverBorderWidth: 2,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        data: monthDeliveredCount,
                        spanGaps: false,
                    },
                ],
            },
        });
            // ------------------------------------------------------- //
        // Pie Chart
        // ------------------------------------------------------ //
        var PIECHART = document.getElementById("pieChart");
        var myPieChart = new Chart(PIECHART, {
            type: "doughnut",
            data: {
                labels: ["Shipment", "RTO", "Delivered"],
                datasets: [
                    {
                        data: donutData, // Use the updated 'donutData' array here
                        borderWidth: [1, 1, 1],
                        backgroundColor: [brandPrimary, "rgba(75,192,192,1)", "#FFCE56"],
                        hoverBackgroundColor: [brandPrimary, "rgba(75,192,192,1)", "#FFCE56"],
                    },
                ],
            },
        });
        // ------------------------------------------------------- //
        // Progress Chart
        // ------------------------------------------------------ //
        $('#total_order').text(monthlyData[0]);
        $('#delivered_order').text(monthlyData[1]);
        
        var monthlyPercent;

        if (monthlyData[0] !== 0) {
            monthlyPercent = Math.round((monthlyData[1] / monthlyData[0]) * 100);
        } else {
            monthlyPercent = 0; // or a specific value depending on your use case
        }
       
        $('#monthlyPercent').text(parseInt(monthlyPercent)+'%');
        
        var MONTHLYPROGRESS = document.getElementById("monthlyProgress");
        var myPieChart = new Chart(MONTHLYPROGRESS, {
            type: "doughnut",
            options: {
                cutoutPercentage: 100-monthlyPercent,
                responsive: true,
                maintainAspectRatio: true,
                legend: {
                    display: false,
                },
            },
            data: {
                labels: ["Shipment", "Delivered"],
                datasets: [
                    {
                        data: monthlyData,
                        borderWidth: [1, 1],
                        backgroundColor: [brandPrimary, "#ffffff"],
                        hoverBackgroundColor: [brandPrimary, "#ffffff"],
                    },
                ],
            },
        });
        $('#total_order').text(monthlyData[0]);
        $('#delivered_order').text(monthlyData[1]);
        
}
</script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    var options = {
          series: [{
          name: 'Website Blog',
          type: 'column',
          data: [440, 505, 414, 671, 227, 413, 201, 352, 752, 320, 257, 160]
        }, {
          name: 'Social Media',
          type: 'line',
          data: [23, 42, 35, 27, 43, 22, 17, 31, 22, 22, 12, 16]
        }],
          chart: {
          height: 350,
          type: 'line',
        },
        stroke: {
          width: [0, 4]
        },
        title: {
          text: 'Traffic Sources'
        },
        dataLabels: {
          enabled: true,
          enabledOnSeries: [1]
        },
        labels: ['01 Jan 2001', '02 Jan 2001', '03 Jan 2001', '04 Jan 2001', '05 Jan 2001', '06 Jan 2001', '07 Jan 2001', '08 Jan 2001', '09 Jan 2001', '10 Jan 2001', '11 Jan 2001', '12 Jan 2001'],
        xaxis: {
          type: 'datetime'
        },
        yaxis: [{
          title: {
            text: 'Website Blog',
          },
        
        }, {
          opposite: true,
          title: {
            text: 'Social Media'
          }
        }]
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
      
</script>
<script>
     var options = {
          series: [44, 55, 41, 17, 15],
          chart: {
          width: 380,
          type: 'donut',
        },
        plotOptions: {
          pie: {
            startAngle: -90,
            endAngle: 270
          }
        },
        dataLabels: {
          enabled: false
        },
        fill: {
          type: 'gradient',
        },
        legend: {
          formatter: function(val, opts) {
            return val + " - " + opts.w.globals.series[opts.seriesIndex]
          }
        },
        title: {
          text: 'Gradient Donut with custom Start-angle'
        },
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
            legend: {
              position: 'bottom'
            }
          }
        }]
        };

        var chart = new ApexCharts(document.querySelector("#pieChart"), options);
        chart.render();
</script>
@endsection     