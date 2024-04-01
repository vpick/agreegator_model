@extends('common-app.master')
@section('title', 'Company Dashboard')
@section('content')
<style>
  .card {
    --bs-card-spacer-y: 1.5rem;
    --bs-card-spacer-x: 1.5rem;
    --bs-card-title-spacer-y: 0.875rem;
    --bs-card-title-color: #566a7f;
    --bs-card-subtitle-color: ;
    --bs-card-border-width: 0;
    --bs-card-border-color: #d9dee3;
    --bs-card-border-radius: 0.5rem;
    --bs-card-box-shadow: 0 2px 6px 0 rgba(67, 89, 113, 0.12);
    --bs-card-inner-border-radius: 0.5rem;
    --bs-card-cap-padding-y: 1.5rem;
    --bs-card-cap-padding-x: 1.5rem;
    --bs-card-cap-bg: transparent;
    --bs-card-cap-color: ;
    --bs-card-height: ;
    --bs-card-color: ;
    --bs-card-bg: #fff;
    --bs-card-img-overlay-padding: 1.5rem;
    --bs-card-group-margin: 0.8125rem;
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    height: var(--bs-card-height);
    color: var(--bs-body-color);
    /* word-wrap: break-word; */
    background-color: var(--bs-card-bg);
    /* background-clip: border-box; */
    /* border: var(--bs-card-border-width) solid var(--bs-card-border-color); */
    border-radius: var(--bs-card-border-radius);
  }
  .w-25
  {
      width: 25%;
  }
  .text-white{
      color:white;
  }
  .theading{
      padding: 23px!important;
  }
  .f-14{
      font-size:14px;
  }
  ::-webkit-scrollbar {
    width: 5px;
    height: auto;
  }

  ::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
    -webkit-border-radius: 10px;
    border-radius: 10px;
  }

  ::-webkit-scrollbar-thumb {
    -webkit-border-radius: 10px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.3);
    -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.5);
  }

  ::-webkit-scrollbar-thumb:window-inactive {
    background: rgba(255, 255, 255, 0.3);
  }
  .cardHeight{
      height: 470px;
  }
  .cardHeight1{
      height: 400px
  }
  .cardBodyStyle{
      overflow : hidden;
      overflow-y: scroll;
  }
  .pb-1 {
      padding-bottom: 0.1rem !important;
  }
  .f-13{
    font-size: 13px;
  }
.dropbtn {
    background-color: white;
    color: #212529;
    padding: 2px 7px 2px 7px;
    font-size: 12px;
    border: 1px solid #ced4da;
}

.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f1f1f1;
  min-width: 60px;
  padding: 5px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

.dropdown-content a {
    color: black;
    padding: 3px 5px;
    text-decoration: none;
    display: block;
    font-size: 12px;
}

.dropdown-content a:hover {background-color: gray;}

.dropdown:hover .dropdown-content {display: block;}

.dropdown:hover .dropbtn {background-color: gray;color:white}
</style>
<section class="py-3">
    <div class="row d-flex align-items-md-stretch ms-2 pb-2">
        <div class="col-lg-3 col-md-6">
            <label class="p-b-10" for="reportrange">From Date: </label>  
            <div class="input-group">     
                <input type="hidden" id="range" value="">
                <input class="form-control" id='reportrange' name="from_date" type="text" placeholder="From Date" >
            </div>
        </div>
        <small>By default 1 months data appear</small>
    </div>
</section>

<section class="" style='padding-left:10px; padding-right:10px;'>
    <div class="row d-flex align-items-md-stretch">
        <div class="col-sm-6 col-lg-3">
            <div class="card card-border-shadow-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-primary"><i class='fas fa-users'></i></span>
                        </div>
                        <h4 class="ms-1 mb-0" id="totalClientCount">0</h4>
                    </div>
                    <p class="mb-1">Total Clients</p>
                    <small class="text-muted">Active & Inactive Clients</small>
                    <div style="float:right">
                        <div class="dropdown">
                            <button class="dropbtn">Active:  <span id="activeClientCount">0</span></button>
                            <div class="dropdown-content" id="activeClients">
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="dropbtn">Inactive: <span id="inactiveClientCount">0</span></button>
                            <div class="dropdown-content" id="inactiveClients">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-border-shadow-primary">
                <div class="card-body" style="height: 156px;">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-primary"><i class='fas fa-truck'></i></span>
                        </div>
                        <h4 class="ms-1 mb-0" id="averageShipment">0</h4>
                    </div>
                    <p class="mb-1"> Avarage no of shipments</p>
                    <small class="text-muted">By default 1 month data appear</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-border-shadow-primary">
                <div class="card-body" style="height: 156px;">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-primary"><i class='fas fa-truck'></i></span>
                        </div>
                        <h4 class="ms-1 mb-0" id="averageWeight">0 Kg</h4>
                    </div>
                    <p class="mb-1">Average weight of shipments</p>
                    <small class="text-muted">By default 1 month data appear</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-border-shadow-primary">
                <div class="card-body" style="height: 156px;">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-primary"><i class='fas fa-truck'></i></span>
                        </div>
                        <h4 class="ms-1 mb-0" id="gmv">₹ 0</h4>
                    </div>
                    <p class="mb-1">(GMV) Gross Merchandise Value </p>
                    <!-- <small class="text-muted" >By default 6 month data appear</small> -->
                    <!-- <p class="mb-0">
                        <span class="fw-medium me-1" id="totalPendingpickProgress">0%</span>
                        <small class="text-muted">than last time period</small>
                    </p> -->
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card cardHeight">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Top 10 Clients</h5>
                        <!-- <small class="text-muted">62 deliveries in progress</small> -->
                    </div>
                </div>
                <div class="card-body cardBodyStyle">
                    <table class="table align-middle mb-0 bg-white">
                        <thead class="bg-light">
                            <tr class="f-13">
                                <th>Client</th>
                                <th>Total Shipment</th>
                                <th>Total Wt.(Kg)</th>
                                <th>GMV (₹)</th>
                            </tr>
                        </thead>
                        <tbody id="topClients">
                        
                        </tbody>
			        </table>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card cardHeight">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Top 10 Cities</h5>
                        <!-- <small class="text-muted">62 deliveries in progress</small> -->
                    </div>
                </div>
                <div class="card-body cardBodyStyle">
                    <table class="table align-middle mb-0 bg-white">
                        <thead class="bg-light">
                            <tr class="f-13">
                                <th>City</th>
                                <th>Total Shipment</th>
                                <th>Total Wt.(Kg)</th>
                                <th>GMV (₹)</th>
                            </tr>
                        </thead>
                        <tbody id="topCities">
                        
                        </tbody>
			        </table>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card cardHeight">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Top 10 DSP</h5>
                        <!-- <small class="text-muted">62 deliveries in progress</small> -->
                    </div>
                </div>
                <div class="card-body cardBodyStyle">
                    <table class="table align-middle mb-0 bg-white">
                        <thead class="bg-light">
                            <tr class="f-13">
                                <th>DSP</th>
                                <th>Total Shipment</th>
                                <th>Total Wt.(Kg)</th>
                                <th>GMV (₹)</th>
                            </tr>
                        </thead>
                        <tbody id="topDsps">
                       
                        </tbody>
			        </table>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card cardHeight">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Total Pincode Service without ODA</h5>
                        <!-- <small class="text-muted">62 deliveries in progress</small> -->
                    </div>
                </div>
                <div class="card-body cardBodyStyle">
                    
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function () {
        $('[data-bs-toggle="tooltip"]').tooltip();
        $('#reportrange').on('change', function () {
	        var dateRange = $(this).val();	            
            var shipmentTotalOrder = 0;
            var monthNoCounts = 0;
            var averageShipment = 0;
            var shipmentTotalWeight = 0;
            var averageWeight = 0;
            $.ajax({
                url: "{{ url('companyDashboard') }}",
                type: "GET",
                data: {from_date:dateRange},
                success: function(res) 
                {
                    console.log(res);
                    if (res.client_data && res.client_data.length > 0) 
                    {
                        var clientData = res.client_data[0];
                        if (clientData.total_client) 
                        {
                            $('#totalClientCount').text(clientData.total_client);
                        }
                        if (clientData.inactive_client) 
                        {
                            $('#inactiveClientCount').text(clientData.inactive_client);
                        }
                        if (clientData.active_client) 
                        {
                            $('#activeClientCount').text(clientData.active_client);
                        }
                    }
                    if (res.active_clients && res.active_clients.length > 0) 
                    {
                        var activeClients = res.active_clients;
                        var activeClientsContent = '';
                        $('#activeClients').empty();
                        $.each(activeClients, function (index, val) {
                            activeClientsContent += `<a href="${val}">${val}</a>`;
                        });
                        $('#activeClients').append(activeClientsContent);
                    }  
                    if (res.inactive_clients && res.inactive_clients.length > 0) 
                    {
                        var inactiveClients = res.inactive_clients;
                        var inactiveClientsContent = '';
                        $('#activeClients').empty();
                        $.each(inactiveClients, function (index, val) {
                            inactiveClientsContent += `<a href="${val}">${val}</a>`;
                        });
                        $('#inactiveClients').append(inactiveClientsContent);
                    }  
                    if (res.averageShipment && res.averageShipment.length > 0) 
                    {
                        var averageShipment = Math.round(res.averageShipment[0]?.total_order || 0);
                        var averageWeight = parseFloat(res.averageShipment[0]?.total_weight || 0);
                        var gmv = parseFloat(res.gmvData[0]?.gmv || 0).toFixed(2);
                        $('#averageShipment').text(averageShipment);
                        $('#averageWeight').text((averageWeight / 1000).toFixed(2) + ' Kg');
                        $('#gmv').text('₹ ' + Math.round(gmv));
                    }  
                    $('#topClients').empty();
                    if (res.topClients && res.topClients.length > 0) 
                    {
                        var topClients = res.topClients;
                        var topClientsContent = '';
                        $.each(topClients, function (index, val) {
                            const total_shipment = val['total_shipment'];
                            const total_weight = parseFloat(val['total_weight']).toFixed(2);
                            const gmv = parseFloat(val['gmv']).toFixed(2)
                            topClientsContent += `<tr style='border-radius: 5px 5px 4px 5px;box-shadow: 1px 1px 1px 0px aqua;'>
                                <td><p class="fw-bold mb-1 f-13">${val['client_code']}</p></td>
                                <td><p class="fw-normal mb-1 f-13">${total_shipment}</p></td>
                                <td><p class="fw-normal mb-1 f-13">${total_weight}</p></td>
                                <td><p class="fw-normal mb-1 f-13">${gmv}</p></td>
                            </tr>`;
                        });
                        $('#topClients').append(topClientsContent);
                    }
                    $('#topCities').empty();
                    if (res.topCities && res.topCities.length > 0) 
                    {
                        var topCities = res.topCities;
                        var topCitiesContent = '';
                        $.each(topCities, function (index, val) {
                            const total_shipment = val['total_shipment'];
                            const total_weight = parseFloat(val['total_weight']).toFixed(2);
                            const gmv = parseFloat(val['gmv']).toFixed(2)
                            topCitiesContent += `<tr style='border-radius: 5px 5px 4px 5px;box-shadow: 1px 1px 1px 0px aqua;'>
                                <td><p class="fw-bold mb-1 f-13">${val['shipping_city']}</p></td>
                                <td><p class="fw-normal mb-1 f-13">${total_shipment}</p></td>
                                <td><p class="fw-normal mb-1 f-13">${total_weight}</p></td>
                                <td><p class="fw-normal mb-1 f-13">${gmv}</p></td>
                            </tr>`;
                        });
                        $('#topCities').append(topCitiesContent);
                    }
                    $('#topDsps').empty();
                    if (res.topDsps && res.topDsps.length > 0) 
                    {
                        var topDsps = res.topDsps;
                        var topDspsContent = '';
                        $.each(topDsps, function (index, val) {
                            const requestPartnerParts = val['request_partner'].split('App');
                            const total_shipment = val['total_shipment'];
                            const total_weight = parseFloat(val['total_weight']).toFixed(2);
                            const gmv = parseFloat(val['gmv']).toFixed(2);
                            topDspsContent += `<tr style='border-radius: 5px 5px 4px 5px;box-shadow: 1px 1px 1px 0px aqua;'>
                                <td><p class="fw-bold mb-1 f-13">${requestPartnerParts[0]}</p></td>
                                <td><p class="fw-normal mb-1 f-13">${total_shipment}</p></td>
                                <td><p class="fw-normal mb-1 f-13">${total_weight}</p></td>
                                <td><p class="fw-normal mb-1 f-13">${gmv}</p></td>
                            </tr>`;
                        });
                        $('#topDsps').append(topDspsContent);
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    // Handle error
                    console.log('Error:', errorThrown);
                }
            });
        })
    });
</script>

@endsection