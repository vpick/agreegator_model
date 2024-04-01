@extends('admin-app/admin-master')
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
</style>

<section class="py-filter">
	<div class="col-md-12 text-right">
		<a href="#" class="btn btn-outline-dark btn-sm"><i class="mdi mdi-arrow-down-bold-circle"></i> Export</a>
		<button type="button" class="btn btn-sm btn-outline-dark refresh_orders_button"><i class="mdi mdi-refresh"></i> Refresh</button>
		<button class="btn btn-outline-dark btn-sm" onClick="imports()"> <i class="mdi mdi-arrow-up-bold-circle"></i> Import</button>
		<button class="btn btn-outline-dark btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Create New</button>
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
    					
    					<div class="col-lg">
    					   <label class="form-label" for="shipment_type">Aggrigator *</label>
                            <select class="form-control" id="shipment_type" name="shipment_type" required >
                                <option value="">Select Mode</option>
                               
                            </select>
    					</div>
    				    <div class="col-lg">
    					   <label class="form-label" for="shipment_type">Courier *</label>
                            <select class="form-control" id="shipment_type" name="shipment_type" required >
                                <option value="">Select Mode</option>
                               
                            </select>
    					</div>
    					<div class="col-lg">
    					   <label class="form-label" for="shipment_type">Shipment Mode *</label>
                            <select class="form-control" id="shipment_type" name="shipment_type" required >
                                <option value="">Select Mode</option>
                                @foreach($shipmentTypes as $shipmentType)
                                    <option value="{{ $shipmentType->shipment_type }}" >{{ $shipmentType->shipment_type }}</option>
                                @endforeach
                            </select>
    					</div>
    					
					</div>
					<div class="row">
    					
    					<div class="col-lg">
    						<button class="btn btn-primary" type="submit" style="margin-top: 24px;">Apply</button>
    						<a href="/orders" class="btn btn-secondary" style="margin-top: 24px;">Clear</a>
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
							@foreach($dataArray['forward']  as $key => $value)																
								<td >{{ '₹ '.$value ?? '0.00'}}</td>									
							@endforeach                                         
							
                        <td rowspan="4" class="pt-35">{{ $rate->cod}}</td>
                        <td rowspan="4" class="pt-35">{{ $rate->cod_percent }}</td>
                    </tr>
                    <tr>
                        <!-- <td>@if($rate->aggregator) 
                                {{ $rate->aggregator.' - '.$rate->courier. ' - '. $rate->shipment_mode.' - '.$rate->min_weight.' Kgs'}} 
                            @else 
                                {{ $rate->courier. ' - '. $rate->shipment_mode.' - '.$rate->min_weight.' Kgs' }} 
                            @endif
                        </td> -->
                          <td>FWD - Additional {{ $rate->additional_weight.' Kg'}}</td>
							@foreach($forward_additionalArray['forward_additional']  as $key => $value)																
								<td >{{ '₹ '.$value ?? '0.00'}}</td>									
							@endforeach                                         
							
                        <!-- <td>{{ $rate->cod}}</td>
                        <td>{{ $rate->cod_percent }}</td> -->
                    </tr>
                    <tr>
                        <!-- <td>@if($rate->aggregator) 
                                {{ $rate->aggregator.' - '.$rate->courier. ' - '. $rate->shipment_mode.' - '.$rate->min_weight.' Kgs'}} 
                            @else 
                                {{ $rate->courier. ' - '. $rate->shipment_mode.' - '.$rate->min_weight.' Kgs' }} 
                            @endif
                        </td> -->
                        <td>RTO</td>
							@foreach($reverseArray['reverse']  as $key => $value)																
								<td >{{ '₹ '.$value ?? '0.00'}}</td>									
							@endforeach                                         
							
                        <!-- <td>{{ $rate->cod}}</td>
                        <td>{{ $rate->cod_percent }}</td> -->
                    </tr>
                    <tr>
                        <!-- <td>@if($rate->aggregator) 
                                {{ $rate->aggregator.' - '.$rate->courier. ' - '. $rate->shipment_mode.' - '.$rate->min_weight.' Kgs'}} 
                            @else 
                                {{ $rate->courier. ' - '. $rate->shipment_mode.' - '.$rate->min_weight.' Kgs' }} 
                            @endif
                        </td> -->
                        <td>DTO</td>
							@foreach($dtoArray['dto']  as $key => $value)																
								<td >{{ '₹ '.$value ?? '0.00'}}</td>									
							@endforeach                                         
							
                        <!-- <td>{{ $rate->cod}}</td>
                        <td>{{ $rate->cod_percent }}</td> -->
                    </tr>
                    @endforeach 
                   
                   
                </table>

				{{ $rates->links() }}
			       
                    
			    
			</div>
        </div>
</section>
<div class="modal fade import_orders_modal" id="exModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
	    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
		<div class="modal-content" id="fulfillment_info">
			<form method="POST" action="/import-order" enctype="multipart/form-data">
					@csrf
					<div class="modal-header">
						<h5 class="modal-title" id="mySmallModalLabel">Bulk Upload Order</h5>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-sm-12 p-b-10">
								Download sample order upload file : <a class="text-info" href="/download-order-sample">Download</a>
							</div>
							<div class="col-sm-12 m-t-10">
								<div class="m-b-10">
									<div class="input-group mb-3">
										<div class="custom-file">
											<input type="file" class="custom-file-input" name="importFile">
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
											<label class="custom-control-label" for="customCheckDup">Check Duplicate order (Only for new order) </label>

										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 text-right">
								<button type="submit" class="btn btn-primary">Upload</button>
								<button class="btn btn-secondary" type="button" data-bs-dismiss="modal" aria-label="Close">Close</button>
							</div>
						</div>
						<div class="row border-top m-t-20 m-b-10">
							<div class="col-sm-12 p-t-10 text-center">
								<b>Bulk Order Update</b>
							</div>
							<div class="col-sm-12 p-t-10">
								For bulk order update export order and import the file after updates.<br />
							</div>
						</div>
						<div class="row">
							<iframe width="490" style="margin: 5px;border-radius: 5px;" height="315" src="#" title="How to Bulk Pincode Upload in OmneeApp?" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
						</div>-->
					</div>
				</form>
		</div>
	</div>
    </div>

<script type="text/javascript">
    function imports()
    {
    	$('#exModal').modal('show');
    }  
</script>
@endsection
