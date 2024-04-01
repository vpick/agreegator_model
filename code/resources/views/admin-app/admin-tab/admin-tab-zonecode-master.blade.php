@extends('admin-app/admin-master')
@section('title', 'Zone Master')
@section('content')
<section class="py-filter">
	<div class="col-md-12 text-right">
	    @php
        $currentUrl = url()->current(); // Get the current URL
        $exportUrl = url('export-zonecode') . '?' . http_build_query(request()->all());
        @endphp
		<a href="{{ $exportUrl }}" class="btn btn-outline-dark btn-sm"><i class="mdi mdi-arrow-down-bold-circle"></i> Export</a>
		<button type="button" class="btn btn-sm btn-outline-dark refresh_orders_button"><i class="mdi mdi-refresh"></i> Refresh</button>
		<button class="btn btn-outline-dark btn-sm" onClick="imports()"> <i class="mdi mdi-arrow-up-bold-circle"></i> Import</button>
		<button type="button" class="btn btn-outline-dark show_hide_filter btn-sm" onclick="showFilter()"><i class="icon-placeholder mdi mdi-filter"></i> Filters</button>
        <button type="button" class="btn btn-outline-dark show_hide_filter btn-sm" style="display:none;"><i class="icon-placeholder mdi mdi-close"></i> Close</button>
	</div>
	<div id='filter' style='display:none'>
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
	</div>
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
            				        <th>#</th>
            				        <th>Pincode</th>
            				        <th>Zone code</th>
									
									<th>Courier</th>
									<th>Area Code</th>
									<th>Hub Name</th>
									<th>City</th>
									<th>State</th>
            				        <th>Delivery</th>
									<th>ODA</th>
									<th>COD Delivery</th>
									<th>Prepaid Delivery</th>
									<th>Pickup</th>
									<th>Reverse Pickup</th>
            				    </tr>
							</thead>
			                <tbody>
							@foreach($zonecode as $zone)
								<tr>
            				        <td>#</td>
            				        <td>{{ $zone->pin_code }}</td>
            				        <td>{{ $zone->zone_code }}</td>
            				        
									<td>{{ $zone->courier }}</td>
									<td>{{ $zone->area_code }}</td>
									<td>{{ $zone->hub_name }}</td>
									<td>{{ $zone->city }}</td>
									<td>{{ $zone->state }}</td>
									<td>{{ $zone->delivery }}</td>
									<td>{{ $zone->oda_states }}</td>
									<td>{{ $zone->cod_delivery }}</td>
									<td>{{ $zone->prepaid_delivery }}</td>
									<td>{{ $zone->pickup }}</td>
									<td>{{ $zone->reverse_pickup }}</td>
            				    </tr>
								@endforeach 
							</tbody>
			            </table>
						{{ $zonecode->links() }}
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
