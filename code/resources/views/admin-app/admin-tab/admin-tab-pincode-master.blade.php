@extends('admin-app/admin-master')
@section('title', 'Pincode Master')
@section('content')
<section class="py-filter">
	<div class="col-md-12 text-right">
	    @php
        $currentUrl = url()->current(); // Get the current URL
        $exportUrl = url('export-pincode') . '?' . http_build_query(request()->all());
        @endphp
		<a href="{{ $exportUrl }}" class="btn btn-outline-dark btn-sm"><i class="mdi mdi-arrow-down-bold-circle"></i> Export</a>
		<button type="button" class="btn btn-sm btn-outline-dark refresh_orders_button"><i class="mdi mdi-refresh"></i> Refresh</button>
		<button class="btn btn-outline-dark btn-sm" onClick="imports()"> <i class="mdi mdi-arrow-up-bold-circle"></i> Import</button>
		<button type="button" class="btn btn-outline-dark show_hide_filter btn-sm" onclick="showFilter()"><i class="icon-placeholder mdi mdi-filter"></i> Filters</button>
        <button type="button" class="btn btn-outline-dark show_hide_filter btn-sm" style="display:none;"><i class="icon-placeholder mdi mdi-close"></i> Close</button>
	</div>
	<div id='filter' style='display:none'>
	    <div class="card-body">
			<form class="row g-3 align-items-center" method="GET" action="/pincode-master">	
			<div class="row">
				<div class="form-group col-lg-2">
					<label>Pincode</label>
					<input type="text" class="form-control" id='pincode' name="pincode" placeholder="Pincode" value="{{ request()->input('pincode') ?? '' }}">
					<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;"></span>
				</div>
				<div class="form-group col-lg-2">
					<label>District</label>
					<input type="text" class="form-control" id='district' name="district" placeholder="District" value="{{ request()->input('district') ?? '' }}">
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
					<button class="btn btn-primary" type="submit" style="margin-top: 25px;border-radius: 5px">Apply</button>
					<a href="/pincode-master" class="btn btn-secondary" style="margin-top: 25px;border-radius: 5px">Clear</a>
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
            				        <th>District</th>
									<th>City</th>
									<th>State</th>
            				    </tr>
							</thead>
			                <tbody>
                			 @foreach($pincode as $postalcode)
								<tr>
            				        <td>#</td>
            				        <td>{{ $postalcode->pincode }}</td>
            				        <td>{{ $postalcode->district }}</td>
									<td>{{ $postalcode->city }}</td>
									<td>{{ $postalcode->state }}</td>
            				    </tr>
								@endforeach    
			                </tbody>
			            </table>
						{{ $pincode->links() }}
			        </div>
			    </div>
			</div>
        </div>
    </section>
	
	<div class="modal fade import_orders_modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content" id="fulfillment_info">
            <form method="POST" action="/import-pincode" enctype="multipart/form-data">
			    @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="mySmallModalLabel">Bulk Upload Pincode</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 p-b-10">
                            Download sample pincode upload file : <a class="text-info" href="/download-pincode-sample">Download</a>
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
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                    <div class="row border-top m-t-20 m-b-10">
                        <div class="col-sm-12 p-t-10 text-center">
                            <b>Bulk Pincode Update</b>
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
