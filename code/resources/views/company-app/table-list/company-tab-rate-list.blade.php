@extends('common-app/master')
@section('title', 'Rate Card')
@section('content')
<style>
	.p-s {
    margin-top: 0;
    margin-bottom: 0px;
	font-size:12px
    }
    .s-css{
        color: dimgray;
        font-weight: 400;
        font-size:10px
    }
    .f-css{
        font-size:10px
    }
    .table1 th,td{	
        font-size: 12px;
        
    }

    .th-css{
        padding: 20px 0px 20px 0px!important;
    }
    .pt-15{
        padding-top: 15px!important;
    }
    .pt-35{
        padding-top: 35px!important;

    }
    .p-b-10
    {
        margin-bottom:10px;
    }
</style>
<section class="py-filter">
	<div class="col-md-12 text-right">
		@php
            $currentUrl = url()->current(); // Get the current URL
            $exportUrl = url('export-rate-contract-b2c') . '?' . http_build_query(request()->all());
        @endphp
        @if(!empty($userP) && $userP->download != '1')
		    <a href="#" onclick="checkPermission()" class="btn btn-outline-dark btn-sm"><i class="mdi mdi-arrow-down-bold-circle"></i> Export to Excel</a>
		@else
		    <a  href="{{ $exportUrl }}" class="btn btn-outline-dark btn-sm"><i class="mdi mdi-arrow-down-bold-circle"></i> Export to Excel</a>
		@endif
		<button type="button" class="btn btn-sm btn-outline-dark refresh_orders_button"><i class="mdi mdi-refresh"></i> Refresh</button>
		<!-- <button class="btn btn-outline-dark btn-sm" onClick="dspimports()"> <i class="mdi mdi-arrow-up-bold-circle"></i>Excel</button> -->
		<button class="btn btn-outline-dark btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"> <i class="mdi mdi-arrow-up-bold-circle"></i> Import</button>
        <ul class="dropdown-menu shadow-sm">
            @if(Auth::user()->user_type == 'isCompany')
            <li><a class="dropdown-item"  onClick="imports('company_dsp')" href="#" id="company_dsp"><i class="mdi mdi-plus"></i>Company to DSP</a></li>
			<li><a class="dropdown-item"  onClick="imports('company_client')" href="#" id="company_client"><i class="mdi mdi-plus"></i>Company to Client</a></li>
            @else
            <li><a class="dropdown-item"  onClick="imports('client_dsp')" href="#" id="client_dsp"><i class="mdi mdi-plus"></i>Client to DSP</a></li>
            @endif
        </ul>
		<button class="btn btn-outline-dark btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" >Create New</button>
	    <ul class="dropdown-menu shadow-sm">
			<li><a class="dropdown-item" href="{{ route('rate-card.create')}}"><i class="mdi mdi-plus"></i> Create B2C Rate Card</a></li>
		</ul>
        <button type="button" class="btn btn-outline-dark show_hide_filter btn-sm" onclick="showFilter()"><i class="icon-placeholder mdi mdi-filter"></i> Filters</button>
        <button type="button" class="btn btn-outline-dark show_hide_filter btn-sm" style="display:none;"><i class="icon-placeholder mdi mdi-close"></i> Close</button>
	</div>
	<div class="col-lg-12" id='filter'>                           
        <div class="card">
            <div class="card-header border-bottom">
              	<h3 class="h4 mb-0">Filters</h3>
            </div>
            <div class="card-body">
                <form class="row g-3 align-items-center" method="GET" action="{{ route('rate-card.index') }}">	
                    <div class="row" style="margin-bottom: 1rem !important;">
                        <div class="col-lg-3">
                            <label class="form-label" for="shipment_contract">Contract Type</label>
                            <select class="form-control" id="shipment_contract" name="shipment_contract" required>
                                <option value="">Select</option>
                                @foreach($contract_type as $type)
                                    <option value="{{ $type }}" {{ $type == request('shipment_contract') ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
    					    <label class="form-label" for="shipment_mode">Shipment Mode </label>
                            <select class="form-control" id="shipment_mode" name="shipment_mode">
                                <option value="">Select Shipment Mode</option>
                                @foreach($shipmentTypes as $shipType)
                                    <option value="{{$shipType->shipment_type}}" {{ $shipType->shipment_type == request('shipment_mode') ? 'selected' : '' }}>{{ $shipType->shipment_type }}</option>
                                @endforeach
                            </select>
    					</div>                        
                        <div class="col-lg-3" id="dspContentDiv">
                            <label class="form-label" for="courier_type" >Logistics Type</label>
                            <select class="form-control" id="courier_type" name="courier_type">
                                <option value="">Logistics Type</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->logistics_type }}" {{ $type->logistics_type == request('courier_type') ? 'selected' : '' }}>{{ $type->logistics_type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3" id="aggrDiv">
                            <label class="form-label" for="aggregator">Aggregator</label>
                            <select class="form-control" id="aggregator" name="aggregator">
                                <option value="">Select Aggregator</option>
                                @foreach($aggregators as $aggregator)
                                    <option value="{{ $aggregator->logistics_name }}" {{ $aggregator->logistics_name == request('aggregator') ? 'selected' : '' }}>{{ $aggregator->logistics_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3" id="courierDiv">
                            <label class="form-label" for="shipment_courier">DSP </label>
                            <select class="form-control" id="shipment_courier" name="shipment_courier">
                                <option value="">Select DSP</option>
                                @foreach($courierList as $courierData)
                                    <option value="{{ $courierData->logistics_name }}" {{ $courierData->logistics_name == request('shipment_courier') ? 'selected' : '' }}>{{ $courierData->logistics_name }}</option>
                                @endforeach
                            </select>
                        </div>
					</div>
					<div class="row">
    					<div class="col-lg">
    						<button class="btn btn-primary" type="submit" style="margin-top: 24px;">Apply</button>
    						<a href="{{ route('rate-card.index') }}" class="btn btn-secondary" style="margin-top: 24px;">Clear</a>
    					</div>	
    					<div class="col-lg"></div>
    					<div class="col-lg"></div>
    					<div class="col-lg"></div>
    				</div>
              	</form>
            </div>
        </div>
    </div>
	<hr />
	</section>
    <!-- Counts Section -->
    <section class="bg-white">
        <div class="container-fluid">
            <div class="row d-flex align-items-md-stretch">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="{{ route('rate-card.index') }}" role="tab" aria-controls="home" aria-selected="true">B2C Rates</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="{{ route('b2b_list') }}" role="tab" aria-controls="profile" aria-selected="false">B2B Rates</a>
                    </li>
                </ul>
                <table class="table table1" >
                    <tr class="bg-light" >
                    <th rowspan="2" class="pt-15">#</th>
                        <th rowspan="2" style="width: 20%;" class="pt-15">Courier</th>
                        <th rowspan="2" class="pt-15">Type</th>
                        @foreach($zones as $zone)
    				        <th class="p-s">
								{{ $zone->zone_code }} ( ₹ )	
							</th>
						@endforeach
                        <th colspan="2">Whichever is Higher</th>   
                    </tr>
                    <tr>
                        @foreach($zones as $zone)
                        <td class="p-s">{{ $zone->description }}</td>
                        @endforeach
                        <td class="p-s">COD ( ₹ )</td>
                        <td class="p-s">COD (%)</td>
                    </tr>
                    @foreach($rates as $key => $rate)
						@php  
							$jsonData = $rate->forward;
							$dataArray = json_decode($jsonData, true);
							$forward_additionalData = $rate->forward_additional;
							$forward_additionalArray = json_decode($forward_additionalData, true);
							$reverseData = $rate->reverse;
							$reverseArray = json_decode($reverseData, true);
                            $dtoData = $rate->dto;
							$dtoArray = json_decode($dtoData, true);
						@endphp
                        
                    <tr>
                        <td rowspan="4" class="pt-35">
                            <div class="d-flex align-items-center">
                                <a title='View Partners' href="{{ route('edit-card', ['id' => Crypt::encrypt($rate->id)]) }}">
                                    <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2"><use xlink:href="#survey-1"></use></svg>
                                </a>
                            </div>  
                        </td>
                        <td rowspan="4" class="pt-35">
                            <span style="font-weight: 600;font-size: 14px;">{{$rate->courier_name }}</span><br>
                            <span>
                                @if($rate->aggregator) 
                                    {{ $rate->aggregator.' - '.$rate->courier. ' - '. $rate->shipment_mode.' - '.$rate->min_weight.' Kgs'}} 
                                @else 
                                    {{ $rate->courier. ' - '. $rate->shipment_mode.' - '.$rate->min_weight.' Kgs' }} 
                                @endif
                            </span>
                        </td>
                        <td>Forward</td>
                            @if(isset($dataArray['forward']))
    							@foreach($dataArray['forward']  as $key => $value)																
    								<td >{{ '₹ '.$value ?? '0.00'}}</td>									
    							@endforeach      
    						@else
    						    <td >{{ '₹ 0.00'}}</td>
    						@endif	
                        <td rowspan="4" class="pt-35">{{ $rate->cod}}</td>
                        <td rowspan="4" class="pt-35">{{ $rate->cod_percent }}</td>
                    </tr>
                    <tr>
                        <td>FWD - Additional {{ $rate->additional_weight.' Kg'}}</td>
                        @if(isset($forward_additionalArray['forward_additional']))
                            @foreach($forward_additionalArray['forward_additional']  as $key => $value)																
                                <td >{{ '₹ '.$value ?? '0.00'}}</td>									
                            @endforeach    
                        @else
                            <td >{{ '₹ 0.00'}}</td>
                        @endif							
                    </tr>
                    <tr>
                        <td>RTO</td>
                        @if(isset($reverseArray['reverse']))
                            @foreach($reverseArray['reverse']  as $key => $value)																
                                <td >{{ '₹ '.$value ?? '0.00'}}</td>									
                            @endforeach    
                        @else
                            <td >{{ '₹ 0.00'}}</td>
                        @endif	
                    </tr>
                    <tr>
                        <td>DTO</td>
                        @if(isset($dtoArray['dto']))
                            @foreach($dtoArray['dto']  as $key => $value)																
                                <td >{{ '₹ '.$value ?? '0.00'}}</td>									
                            @endforeach   
                        @else
                            <td >{{ '₹ 0.00'}}</td>
                        @endif		
                    </tr>
                    @endforeach 
                </table>
				{{ $rates->links() }}
			</div>
        </div>
    </section>
	
	<div class="modal fade import_orders_modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content" id="fulfillment_info">
                <form method="POST" action="/import-ratecontract-b2c" enctype="multipart/form-data">
    			    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="mySmallModalLabel">Bulk Upload Rate Contract</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6 mb-2">
                                <label class="form-label" for="logistics_type">Contract Type *</label>
                                <input type="text" id="contract" class="form-control" name="contract" required>       
                            </div>
                            <div class="col-sm-12 p-b-10">
                                Download sample Rate Contract upload file : <a class="text-info" href="#" id="myForm2">Download</a>
                            </div>
                            <div class="col-sm-12 m-t-10">
                                <div class="m-b-10">
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="importFile" required>
                                            <label class="custom-file-label" for="inputGroupFile02">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 m-t-10">
                                <div class="m-b-10">
                                    <div class="form-group input-group mb-3">
                                        <div class="custom-control custom-checkbox custom-control-inline">
                                            <input type="checkbox" name="check_duplicates" value="1"  class="custom-control-input" id="customCheckDup" />
                                            <label class="custom-control-label" for="customCheckDup">Check Duplicate pincode (Only for new pincode) </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 text-right">
                                <button type="submit" class="btn btn-primary">Upload</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                        <div class="row border-top m-t-20 m-b-10">
                            <div class="col-sm-12 p-t-10 text-center">
                                <b>Bulk Rate Contract Update</b>
                            </div>
                            <div class="col-sm-12 p-t-10">
                                For bulk Rate Contract update export Rate Contract and import the file after updates.<br />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade import_orders_modal" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content" id="fulfillment_info">
                <form method="POST" action="/import-ratecontractdsp-b2c" enctype="multipart/form-data">
    			    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="mySmallModalLabel">Bulk Upload Rate Contract</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6 mb-2">
                                <label class="form-label" for="contract_name">Contract Type *</label>
                                <input type="text" id="contract_name" class="form-control" name="contract" required readonly>  
                                @error('courier')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-6 mb-2">
                                <label class="form-label" for="logistics_type">Logistics Type *</label>
                                <select class="form-control" id="logistics_type" name="logistics_type" required>
                                    <option value="">Select Type</option>
                                    @foreach($types as $type)
                                    <option value="{{ $type->logistics_type }}" >{{ $type->logistics_type }}</option>
                                    @endforeach
                                </select>
                                @error('courier')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-sm-6 p-b-10" id="dspDiv">
                                <label class="form-label" for="dsp_name">Logistic Partner</label>
                                <select class="form-control" id="dsp_name" name="dsp_name"> 
                                    <option value="">Select</option>
                                    @foreach($courierList as $courierData)
                                        <option value="{{ $courierData->logistics_name }}">{{ $courierData->logistics_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-12 p-b-10">
                                Download sample Rate Contract upload file : <a class="text-info" href="#" id="myForm">Download</a>
                            </div>
                            <div class="col-sm-12 m-t-10">
                                <div class="m-b-10">
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="importFile" required>
                                            <label class="custom-file-label" for="inputGroupFile02">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 m-t-10">
                                <div class="m-b-10">
                                    <div class="form-group input-group mb-3">
                                        <div class="custom-control custom-checkbox custom-control-inline">
                                            <input type="checkbox" name="check_duplicates" value="1"  class="custom-control-input" id="customCheckDup" />
                                            <label class="custom-control-label" for="customCheckDup">Check Duplicate rate (Only for new rate) </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 text-right">
                                <button type="submit" class="btn btn-primary">Upload</button>
                                <!--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
                            </div>
                        </div>
                        <div class="row border-top m-t-20 m-b-10">
                            <div class="col-sm-12 p-t-10 text-center">
                                <b>Bulk Rate Contract Update</b>
                            </div>
                            <div class="col-sm-12 p-t-10">
                                For bulk Rate Contract update export Rate Contract and import the file after updates.<br />
                            </div>
                        </div>
                        <!--<div class="row">
                            <iframe width="490" style="margin: 5px;border-radius: 5px;" height="315" src="#" title="How to Bulk Pincode Upload in OmneeApp?" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>-->
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#contract_type').on('change', function () {
                var selectedValue = $(this).val();
                if (selectedValue != '' && selectedValue != 'company_client') 
                {
                    $('#dspDiv').show();
                    $('#dsp').attr('required', true);
                } 
                else 
                {
                    $('#dspDiv').hide();
                    $('#dsp').removeAttr('required');
                }
            });
            $('#logistics_type').on('change', function() {
                var parentID = $(this).val();    
                if(parentID == 'Currior')
                {
                    $('#dsp_name').prop('required', true);            
                }
                else
                {
                    $('#dsp_name').prop('required', false);    
                }
                if (parentID) 
                {             
                    $.ajax({
                        url: '/load/partner-list/' + parentID,
                        type: 'GET',
                        success: function(res) { 
                            console.log(res.data);   
                            $('#dsp_name').empty();    
                            var cont = `<option value="">Select Courier</option>`;
                            $.each(res.data, function(index, val) {                        
                                cont += `<option value="${val['logistics_name']}">${val['logistics_name']}</option>`;
                            });
                            $('#dsp_name').append(cont);    
                            
                        },
                        error: function(res) {
                            console.log(res.error);
                        }
                    });    
                }    
            });
            $('#myForm2').click(function (event) {
                event.preventDefault(); // Prevent the default form submission
                var contract_type = $('#contract').val();
                console.log(contract_type);
                if (contract_type != '' || contract_type == 'company_client') 
                {
                    $.ajax({
                        url: 'download-ratecontract-sample/' + contract_type,
                        method: 'GET',
                        dataType: 'json',
                        success: function (res) {
                            // Handle the success response here
                            if (res.data) {
                                var zones = res.data;
                                var modifiedZones = zones.map(function (zone) {
                                    return zone + '*';
                                });
                                var csvData = 'logistic_type*,aggregator,courier*,courier_name*,shipment_mode*,min_weight*,additional_weight*,cod_charge*,cod_percent*,consignment_type*,' + modifiedZones.join(',');
                                // Process the CSV data or trigger a download
                                downloadCSV(csvData);
                            } 
                            else 
                            {
                                if (typeof Swal === 'function') {
                                    Swal.fire({
                                        title: 'Failed!',
                                        text: res.error,
                                        timer: 5000,
                                        icon: 'error'
                                    });
                                }
                            }
                        },
                        error: function (xhr, status, error) {
                            // Handle the error response here
                            if (typeof Swal === 'function') {
                                Swal.fire({
                                    title: 'Failed!',
                                    text: error,
                                    timer: 5000,
                                    icon: 'error'
                                });
                            }
                        }
                    });
                }
                else
                {
                    console.log('error');
                }
            });
            $('#myForm').click(function (event) {
                event.preventDefault(); // Prevent the default form submission
                var contract_type = $('#contract_name').val(); 
                var dsp = (contract_type == 'company_client') ? 0 : $('#dsp_name').val();
                var logistics_type = $('#logistics_type').val();
                if(contract_type=='' || dsp =='' || logistics_type =='')
                {
                    Swal.fire({
                        title: 'Failed!',
                        text: 'contract_type or dsp or logistics_type cant not be blank',
                        timer: 5000,
                        icon: 'error'
                    });
                    exit();
                }
                if (contract_type != '' || contract_type != 'company_client') 
                {
                    if (dsp !== null || dsp !='') 
                    {
                        $.ajax({
                            url: 'download-ratecontractdsp-sample/' + dsp + '/' + contract_type,
                            method: 'GET',
                            dataType: 'json',
                            success: function (res) {
                                // Handle the success response here
                            
                                if ((res.data !== null && res.data !== undefined && res.data !== '') && 
                                    (res.type !== null && res.type !== undefined && res.type !== '')) {
                                    var zones = res?.data || [];
                                    var modifiedZones = zones.map(function (zone) {
                                        return zone + '*';
                                    });
                                    var type = res?.type?.logistics_type || '';
                                    if (type == 'Aggregator') {
                                        var csvData = 'courier*,courier_name*,shipment_mode*,min_weight*,additional_weight*,cod_charge*,cod_percent*,consignment_type*,' + modifiedZones.join(',');
                                    } else {
                                        var csvData = 'courier_name*,shipment_mode*,min_weight*,additional_weight*,cod_charge*,cod_percent*,consignment_type*,' + modifiedZones.join(',');
                                    }

                                    // Process the CSV data or trigger a download
                                    downloadCSV(csvData);
                                } else {
                                    console.error('Error:', res.error);
                                    Swal.fire({
                                        title: 'Failed!',
                                        text: res.error,
                                        timer: 5000,
                                        icon: 'error'
                                    });
                                }

                            },
                            error: function (xhr, status, error) {
                                // Handle the error response here
                                if (typeof Swal === 'function') {
                                    Swal.fire({
                                        title: 'Failed!',
                                        text: error,
                                        timer: 5000,
                                        icon: 'error'
                                    });
                                }
                            }
                        });
                    }
                    else
                    {
                        Swal.fire({
                            title: 'Failed!',
                            text: "Contract Type and dsp both are required",
                            timer: 5000,
                            icon: 'error'
                        });
                    }
                } 
                else
                {
                    console.log('error1');
                }
                
            });
            var courierType = getParameterByName('courier_type');
            if (courierType != '' && courierType != null) 
            {
                if(courierType == 'Currior')
                {
                    $('#dspContentDiv').show();
                    $('#courierDiv').show();
                    $('#aggrDiv').hide();
                }
                else
                {
                    $('#dspContentDiv').show();
                    $('#courierDiv').hide();
                    $('#aggrDiv').show();
                }
                
            }
            else
            {
                $('#dspContentDiv').hide();
                $('#courierDiv').hide();
                $('#aggrDiv').hide();
            }
            
            
            $('#shipment_contract').on('change', function () {
                $('#shipment_mode').val('');
                $('#courier_type').val('');
                $('#aggregator').val('');
                $('#shipment_courier').val('');
                var selectedValue = $(this).val();
                if (selectedValue != '' && selectedValue != 'company_client') 
                {
                    $('#dspContentDiv').show();
                    $('#courierDiv').hide();
                    $('#aggrDiv').hide();
                     $('#courier_type').attr('required', true);
                    // $('#aggregator').attr('required', true);
                    // $('#shipment_courier').attr('required', true);
                } 
                else 
                {
                    $('#dspContentDiv').hide();
                    $('#courierDiv').hide();
                    $('#aggrDiv').hide();
                     $('#courier_type').removeAttr('required');
                    // $('#aggregator').removeAttr('required');
                    // $('#shipment_courier').removeAttr('required');
                }
            });
            
            $('#courier_type').on('change', function () {
                var selectedValue = $(this).val();
                if (selectedValue != '' && selectedValue != 'Aggrigator') 
                {
                    $('#courierDiv').show();
                    $('#aggrDiv').hide();
                    $('#courier_type').attr('required', true);
                    $('#aggregator').removeAttr('required');
                    $('#shipment_courier').attr('required',true);
                } 
                else 
                {
                    $('#courierDiv').hide();
                    $('#aggrDiv').show();
                    $('#courier_type').attr('required', true);
                    $('#aggregator').attr('required', true);
                    $('#shipment_courier').removeAttr('required');
                }
            });
        });
        function downloadCSV(csvData) 
        {
            // Create a Blob from the CSV data
            var blob = new Blob([csvData], { type: 'text/csv' });

            // Create a download link
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = 'ratecontract-sample.csv';

            // Append the link to the document and trigger a click to start the download
            document.body.appendChild(link);
            link.click();

            // Remove the link from the document
            document.body.removeChild(link);
        }
        function imports(contract_type)
        {
            if(contract_type == 'company_client')
            {
                $('#contract').val(contract_type);
                $('#myModal').modal('show');
            }
            else
            {
                $('#contract_name').val(contract_type);
                $('#myModal1').modal('show');
            }    
        }    
        function dspimports()
        {
            $('#dspModal').modal('show');
        } 
        function getParameterByName(name) 
        {
            var url = window.location.href;
            name = name.replace(/[\[\]]/g, "\\$&");
            var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            
            return decodeURIComponent(results[2].replace(/\+/g, " "));
        }
    </script>
@endsection
