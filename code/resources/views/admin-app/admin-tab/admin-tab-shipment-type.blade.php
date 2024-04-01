@extends('admin-app/admin-master')
@section('title', 'Shipment Type Master')
@section('content')
<section class="py-filter">
	<div class="col-md-12 text-right">
	    @php
        $currentUrl = url()->current(); // Get the current URL
        $exportUrl = url('export-shipment-type') . '?' . http_build_query(request()->all());
        @endphp
		<a href="{{ $exportUrl }}" class="btn btn-outline-dark btn-sm"><i class="mdi mdi-arrow-down-bold-circle"></i> Export</a>
		<button type="button" class="btn btn-sm btn-outline-dark refresh_orders_button"><i class="mdi mdi-refresh"></i> Refresh</button>
		<button class="btn btn-outline-dark btn-sm" onClick="imports()"> <i class="mdi mdi-arrow-up-bold-circle"></i> Import</button>
		<button class="btn btn-outline-dark btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Create</button>
    	    <ul class="dropdown-menu shadow-sm">
    			<li><a class="dropdown-item" href="/add-shipmentType"><i class="mdi mdi-plus"></i> Add Shipment Mode</a></li>
    			
    	    </ul>
		<button type="button" class="btn btn-outline-dark show_hide_filter btn-sm" onclick="showFilter()"><i class="icon-placeholder mdi mdi-filter"></i> Filters</button>
        <button type="button" class="btn btn-outline-dark show_hide_filter btn-sm" style="display:none;"><i class="icon-placeholder mdi mdi-close"></i> Close</button>
	</div>
	<div id="filter" style="display:none">
	    <div class="card-body">
			<form class="row g-3 align-items-center" action="/shipmentType" method="GET">	
    			<div class="row">
    				<div class="form-group col-lg-2">
    					<label>Shipment Type</label>
    					<input type="text" class="form-control" id="shipment_type" name="shipment_type" placeholder="Shipment Type" value="{{ request()->input('shipment_type') ?? ''}}">
    					<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;"></span>
    				</div>
    				
    				<div class="col-lg">
    					<button class="btn btn-primary" type="submit" style="margin-top: 25px;border-radius: 5px">Apply</button>
    					<a href="/shipmentType" class="btn btn-secondary" style="margin-top: 25px;border-radius: 5px">Clear</a>
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
            				        <th>Action</th>
            				        <th>Shipment Mode</th>
            				    </tr>
							</thead>
			                <tbody>
                			 @foreach($shipmentTypes as $shipmentType)
								<tr>
            				        <td >
            							<a title='View User' class="btn btn-sm btn-outline-primary" style="padding-left: 7px;padding-right: 0px;" href="{{ route('shipmentType.edit', \Crypt::encrypt($shipmentType->id)) }}">
            								<svg class="svg-icon svg-icon-sm svg-icon-heavy me-2 img-icon"><use xlink:href="#survey-1"> </use></svg>
            							</a>														
            						</td>
            				        <td>{{ $shipmentType->shipment_type }}</td>
            				    </tr>
								@endforeach    
			                </tbody>
			            </table>
						{{ $shipmentTypes->links() }}
			        </div>
			    </div>
			</div>
        </div>
    </section>
	
	<div class="modal fade import_orders_modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content" id="fulfillment_info">
                <form method="POST" action="/import-shipment-type" enctype="multipart/form-data">
    			    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="mySmallModalLabel">Bulk Upload Pincode</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12 p-b-10">
                                Download sample pincode upload file : <a class="text-info" href="/download-shipmentType-sample">Download</a>
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