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
		<button class="btn btn-outline-dark btn-sm" > <i class="mdi mdi-arrow-up-bold-circle"></i> Import</button>
		<button class="btn btn-outline-dark btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Create New</button>
	    <ul class="dropdown-menu shadow-sm">
			
            <li><a class="dropdown-item" href="{{ route('add_b2b')}}"><i class="mdi mdi-plus"></i> Create B2B Rate Card</a></li>
		</ul>		 
        <button type="button" class="btn btn-outline-dark show_hide_filter btn-sm" onclick="showFilter()"><i class="icon-placeholder mdi mdi-filter"></i> Filters</button>
        <button type="button" class="btn btn-outline-dark show_hide_filter btn-sm" style="display:none;"><i class="icon-placeholder mdi mdi-close"></i> Close</button>
	</div>
	
	<hr />
	</section>
    <!-- Counts Section -->
    <section class="bg-white">
        <div class="container-fluid">
            <div class="row d-flex align-items-md-stretch">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link " id="home-tab" data-toggle="tab" href="{{ route('rate-card.index') }}" role="tab" aria-controls="home" aria-selected="true">B2C Rates</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" id="profile-tab" data-toggle="tab" href="{{ route('b2b_list') }}" role="tab" aria-controls="profile" aria-selected="false">B2B Rates</a>
                    </li>
                </ul>
                
                        <table class="table table1" >
                            <thead>
                                <tr class="bg-light">
                                    <th>#</th>
                                    <th>Origin
                                        <br>
                                        <small>(Pickup location)</small>
                                    </th>
                                    <th class="text-center">Destinations
                                    <br>
                                        <small>(Location incuded)</small>
                                    </th>
                                    <th class="text-center">Region
                                        <br>
                                        <small>(Shipping location)</small>
                                    </th>
                                    <th class="text-center">Courier</th>  
                                    <th>Courier Rates</th>  
                                    <th>LR/Docket Charges</th> 
                                    <th>Fuel Surcharges
                                    <br>
                                        <small>(FSC - % of base freight)</small>
                                    </th> 
                                    <th class="text-center">FOV-Owner Risk</th> 
                                    <th>Min Chargable Weight <br>
                                        <small>(Kg/LR)</small>
                                    </th> 
                                    <th class="text-center">Min Chargable Amount 
                                        <br>
                                        <small>(per LR) (Docket+FOV+Freight).<br>
                                            Fuel additional on freight</small>
                                    </th> 
                                    <th class="text-center">Volumetriuc Weight (Cft)<br>
                                        <small>Formula = cft * ((l*b*h)/27000*7)</small>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rates as $key => $rate)
                                <tr>
                                    <td>
                                    <a title='Edit rate' class="btn btn-sm btn-outline-primary" style="padding-left: 7px;padding-right: 0px;" href="{{ route('edit_b2b', \Crypt::encrypt($rate->id)) }}">
            								<svg class="svg-icon svg-icon-sm svg-icon-heavy me-2 img-icon"><use xlink:href="#survey-1"> </use></svg>
            							</a>
                                    </td>
                                    <td>{{ $rate->origin }}</td>
                                    <td title="{{ $rate->destinations }}">{{  substr($rate->destinations,0,20). "..."}}</td>
                                    <td class="text-center">{{ $rate->region }}</td>
                                    <td >{{ $rate->courier }}</td>
                                    <td class="text-center">{{ $rate->courier_charge }}</td>
                                    <td class="text-center">{{ $rate->docket_charge }}</td>
                                    <td class="text-center">{{ $rate->fuel_surcharge }}</td>
                                   
                                    @php
                                        $fmp = '';
                                        $fmc = '';
                                        $data = json_decode($rate->fov_owner_risk, true);
                                        if (isset($data['fov_charge'][0]['fov_min_charge'])) {
                                            $fovMinCharge = $data['fov_charge'][0]['fov_min_charge'];
                                            $fmc = 'min Rs ' . $fovMinCharge;
                                        } else {
                                            
                                        }
                                        if (isset($data['fov_charge'][0]['fov_percent'])) {
                                            $fovMinPercent = $data['fov_charge'][0]['fov_percent'];
                                            $fmp = $fovMinPercent . '% of inv value and ';
                                        } else {
                                            
                                        }
                                        @endphp
                                        <td class="text-center">
                                            {{ $fmp.$fmc }}
                                        </td>

                                    <td class="text-center">{{ $rate->min_chargable_weight }}</td>
                                    <td class="text-center">{{ $rate->min_chargable_amount }}</td>
                                    <td class="text-center">{{ $rate->volumetric_weight}}</td>  
                                </tr>
                                @endforeach
                            </body>    
                        </table>	
			        {{ $rates->links()}}
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
