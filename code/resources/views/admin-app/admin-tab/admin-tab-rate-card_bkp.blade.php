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
.table th,td{	
	font-size: 13px;
}

.th-css{
	padding: 20px 0px 20px 0px!important;
}

</style>
<section class="py-filter">
	<div class="col-md-12 text-right">
	    
		<a href="#" class="btn btn-outline-dark btn-sm"><i class="mdi mdi-arrow-down-bold-circle"></i> Export</a>
		<button type="button" class="btn btn-sm btn-outline-dark refresh_orders_button"><i class="mdi mdi-refresh"></i> Refresh</button>
		<button class="btn btn-outline-dark btn-sm" > <i class="mdi mdi-arrow-up-bold-circle"></i> Import</button>
		<button class="btn btn-outline-dark btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Create New</button>
	    <ul class="dropdown-menu shadow-sm">
			<li><a class="dropdown-item" href="{{ route('rate-card.create')}}"><i class="mdi mdi-plus"></i> Create New</a></li>
		</ul>
        <button type="button" class="btn btn-outline-dark show_hide_filter btn-sm" onclick="showFilter()"><i class="icon-placeholder mdi mdi-filter"></i> Filters</button>
        <button type="button" class="btn btn-outline-dark show_hide_filter btn-sm" style="display:none;"><i class="icon-placeholder mdi mdi-close"></i> Close</button>
	</div>
	<!-- <div id='filter' style='display:none'>
	    <div class="card-body">
			<form class="row g-3 align-items-center" method="GET" action="/zone-master">	
			<div class="row">
				<div class="form-group col-lg-2">
					<label>Zonecode</label>
					<input type="text" class="form-control" id='zonecode' name="zonecode" placeholder="Zonecode" value="{{ request()->input('zonecode') ?? '' }}">
					<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;"></span>
				</div>
				<div class="form-group col-lg-2">
					<label>Pincode</label>
					<input type="text" class="form-control" id='pincode' name="pincode" placeholder="Pincode" value="{{ request()->input('pincode') ?? '' }}">
					<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;"></span>
				</div>
				<div class="form-group col-lg-2">
					<label>Courier</label>
					<input type="text" class="form-control" id='courier' name="courier" placeholder="Courier" value="{{ request()->input('courier') ?? '' }}">
					<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;"></span>
				</div>
				
				<div class="form-group col-lg-2">
					<label>Hub Name</label>
					<input type="text" class="form-control" id='hubname' name="hubname" placeholder="Hub Name" value="{{ request()->input('hubname') ?? '' }}">
					<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;"></span>
				</div>
				
				<div class="form-group col-lg-2">
					<label>City</label>
					<input type="text" class="form-control" id='city' name="city" placeholder="City" value="{{ request()->input('city') ?? '' }}">
					<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;"></span>
				</div>
				<div class="form-group col-lg-2">
					<label>State</label>
					<input type="text" class="form-control" id='state' name="state" placeholder="State" value="{{ request()->input('state') ?? '' }}">
					<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;"></span>
				</div>
				<div class="col-lg">
    				<button class="btn btn-primary" type="submit" style="margin-top: 24px;border-radius: 5px">Apply</button>
    				<a href="/zone-master" class="btn btn-secondary" style="margin-top: 24px;border-radius: 5px">Clear</a>
    			</div>
			</div>
			</form>
		</div>
	</div> -->
	<hr />
	</section>
    <!-- Counts Section -->
    <section class="bg-white">
        <div class="container-fluid">
            <div class="row d-flex align-items-md-stretch">
                <div class="tab-content" id="myTabContent">
			        <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
			            <table class="table align-middle mb-0 bg-white">
            			    <thead class="bg-light">
            				    <tr>
            				        <th class="th-css">#</th>
            				        <th class="th-css">Courier </th>
									<th class="th-css">Courier Code</th>
                                    <th class="th-css">Mode</th>
                                    <th class="th-css">Min Weight</th>
									<th class="th-css">Type Name</th>
									@foreach($zones as $zone)
            				        <th style="text-align:center" rowspan="2">
										<p class="p-s">{{ $zone->zone_code }}</p>
										<small class="s-css">{{ $zone->description }}</small><br>
									</th>
									@endforeach
									<th class="th-css">COD Charge</th>
									<th class="th-css">COD %</th> 
									<th class="th-css">FSC (Fuel Charges) %</th> 
									<th class="th-css">Other Charge</th>	
            				    </tr>
							</thead>
			                <tbody>
							@foreach($rates as $key => $rate)
								@php  
									$jsonData = $rate->forward;
									$dataArray = json_decode($jsonData, true);
									$forward_additionalData = $rate->forward_additional;
									$forward_additionalArray = json_decode($forward_additionalData, true);
									$reverseData = $rate->reverse;
									$reverseArray = json_decode($reverseData, true);
								@endphp									
								<tr style='border-radius: 5px 5px 4px 5px;box-shadow: 1px 1px 1px 0px aqua;'>
									<td>
										<div class="d-flex align-items-center">
											<a title='View Partners' href="{{ route('edit-card', ['id' => Crypt::encrypt($rate->id), 'mode' => Crypt::encrypt('forward')]) }}">
											<svg class="svg-icon svg-icon-sm svg-icon-heavy me-2"><use xlink:href="#survey-1"> </use></svg>
											</a>
										</div>	
									</td>
            				        <td>{{ $rate->courier_name }}</td>
									<td>{{ $rate->courier_code }}</td>
            				        <td>{{ $rate->shipment_type }}</td>
									<td>{{ $rate->min_weight.' Kg' }}</td>
									<td>Forward</td>
									@foreach($dataArray['forward']  as $key => $value)												
										<td class="text-center">{{ '₹ '.$value ?? '0.00'}}</td>					
									@endforeach 
            				    </tr>
								<tr style='border-radius: 5px 5px 4px 5px;box-shadow: 1px 1px 1px 0px aqua;'>
									<td>
										<div class="d-flex align-items-center">
											<a title='View Partners' href="{{ route('edit-card', ['id' => Crypt::encrypt($rate->id), 'mode' => Crypt::encrypt('forward_additional')]) }}">
											<svg class="svg-icon svg-icon-sm svg-icon-heavy me-2"><use xlink:href="#survey-1"> </use></svg>
											</a>
										</div>
									</td>
            				        <td>{{ $rate->courier_name }}</td>
									<td>{{ $rate->courier_code }}</td>
            				        <td>{{ $rate->shipment_type }}</td>
									<td>{{ $rate->min_weight.' Kg' }}</td>
									<td>FWD - Additional</td>
									@foreach($forward_additionalArray['forward_additional']  as $key => $value)																
										<td class="text-center">{{ '₹ '.$value ?? '0.00'}}</td>									
									@endforeach 
            				    </tr>
								<tr style='border-radius: 5px 5px 4px 5px;box-shadow: 1px 1px 1px 0px aqua;'>
									<td>
										<div class="d-flex align-items-center">
											<a title='View Partners' href="{{ route('edit-card', ['id' => Crypt::encrypt($rate->id), 'mode' => Crypt::encrypt('reverse')]) }}">
											<svg class="svg-icon svg-icon-sm svg-icon-heavy me-2"><use xlink:href="#survey-1"> </use></svg>
											</a>
										</div>
									</td>
            				        <td>{{ $rate->courier_name }}</td>
									<td>{{ $rate->courier_code }}</td>
            				        <td>{{ $rate->shipment_type }}</td>
									<td>{{ $rate->min_weight.' Kg' }}</td>
									<td>RTO</td>
									@foreach($reverseArray['reverse']  as $key => $value)																
										<td class="text-center">{{ '₹ '.$value ?? '0.00'}}</td>									
									@endforeach 
            				    </tr>
								@endforeach 
							</tbody>
			            </table>
						{{ $rates->links() }}
			        </div>
			    </div>
			</div>
        </div>
    </section>
	
	<div class="modal fade import_orders_modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content" id="fulfillment_info">
            <form method="POST" action="/import-zonecode" enctype="multipart/form-data">
			    @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="mySmallModalLabel">Bulk Upload Partner pincode</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 p-b-10">
                            Download sample pincode upload file : <a class="text-info" href="/download-zone-sample">Download</a>
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
                                        <label class="custom-control-label" for="customCheckDup">Check Duplicate pincode (Only for new pincode) </label>
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
                            <b>Bulk Partner Pincode Update</b>
                        </div>
                        <div class="col-sm-12 p-t-10">
                            For bulk pincode update export pincode and import the file after updates.<br />
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
<script>
function imports()
{
	$('#myModal').modal('show');
}       
</script>
@endsection
