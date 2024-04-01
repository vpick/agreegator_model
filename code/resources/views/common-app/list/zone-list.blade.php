@extends('common-app/master')
@section('title', 'Zone Master')
@section('content')
<style>
      .input-group-text 
    {
        font-size: .9rem;
        font-weight: 400;
        line-height: 1.7;
        display: flex;
        margin-bottom: 0;
        padding: .375rem .75rem;
        text-align: center;
        white-space: nowrap;
        color: #2e384d;
        border: 1px solid #dce4ec;
        /*border-radius: .25rem;*/
        margin-right: 5px;
        background-color: #fff;
        align-items: center
    }
    .m-b-10{
        margin-bottom:10px;
    }
    .p-t-10 {
        padding-top: 10px;
    }
    
    .border-top {
        border-top: 1px solid #dce4ec!important;
    }
    .ms-choice {
        display: block;
        width: 210px!important;
        height: 36px!important;
        padding: 0;
        overflow: hidden;
        cursor: pointer;
        border: 1px solid #ced4da!important;
        text-align: left;
        white-space: nowrap;
        line-height: 36px!important;
        color: #444;
        text-decoration: none;
        border-radius: 4px;
        background-color: #fff;
    }
    .ms-choice>span.placeholder {
        color: transparent!important;
    }
    .ms-choice>div.icon-caret {
    	display: none!important;
    }
    select {
      width: 100%;
    }
    .ms-choice>span.placeholder {
        color: transparent!important;
        display: none!important;
    }
    .btn-black{
    	color: #fff!important;
      
        background-color: #12263f!important;
        
    }
    .section-css{
    	background:white;
    	padding: 0px;
    	height: 80px;
    }
    .btn-css {
        border-radius: 4px;
        padding: 6px;
        font-size: medium;
        border-color: #12263f!important;
        margin-left:5px !important;
        margin-bottom:5px !important;
        margin-top:5px !important;
    }
    .btn-white{
    	color: #12263f!important;
        
        background-color: #fff!important;
       
    }
</style>
<section class="py-filter">
    <div class="row">
        <div class="col-md-6 text-left"> 
            <!--<span>Zone List</span>-->
            
        </div>
        <div class="col-md-6 text-right">
    		<a href="{{ route('zone.get') }}" class="btn btn-sm btn-outline-dark refresh_orders_button"><i class="mdi mdi-refresh"></i> Refresh</a>		
    		<button class="btn btn-outline-dark btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Create</button>
        	    <ul class="dropdown-menu shadow-sm">
        			<li><a class="dropdown-item" href="{{ route('zone.view') }}"><i class="mdi mdi-plus"></i> Add Zone</a></li>
        	    </ul>
        	   <button type="button" class="btn btn-outline-dark show_hide_filter btn-sm" onclick="showFilter()"><i class="icon-placeholder mdi mdi-filter"></i> Filters</button>
            <button type="button" class="btn btn-outline-dark show_hide_filter btn-sm" style="display:none;"><i class="icon-placeholder mdi mdi-close"></i> Close</button>
    	</div>
    </div>
    <div id='filter' style='display:none'>
        <div class="card-body">
    		<form class="row g-3 align-items-center" method="GET" action="{{ route('zone.get') }}">	
    		<div class="row">
    			<div class="form-group col-lg-2">
    				<label>Zone Code</label>
    				@php
    			        $selectedZone =  request()->input('zone_code');
    			    @endphp
    				<select class="multiple-select" id="zone_code" name="zone_code[]" multiple="multiple" placeholder="zone_code">
    				    @foreach($zone_codes as $zone_code)
    						<option value="{{ $zone_code->zone_code }} " {{ $selectedZone ? ((in_array($zone_code->zone_code, $selectedZone)) ? 'selected' : '') : '' }}>{{ $zone_code->zone_code }}</option>
    					@endforeach
    				</select>
    				<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;"></span>
    			</div>
    			<div class="form-group col-lg-2">
    				<label>Description</label>
    				<input type="text" class="form-control" id='description' name="description" placeholder="Description" value="{{ request()->input('description') ?? '' }}">
    				<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;"></span>
    			</div>
    			
    			<div class="col-lg">
    				<button class="btn btn-primary" type="submit" style="margin-top: 25px;border-radius: 5px">Apply</button>
    				<a href="{{ route('zone.get') }}" class="btn btn-secondary" style="margin-top: 25px;border-radius: 5px">Clear</a>
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
            				        <td>#</td>
                                    <th>Zone Type</th>
                                    <th>DSP</th>
									<th>Zone Code</th>
                                    <th>Description</th>
									<th>State Mapping</th>
            				    </tr>
							</thead>
			                <tbody>
                			 @foreach($zones as $zone)
								<tr>
								    <td>
            							<a title='View User' class="btn btn-sm btn-outline-primary" style="padding-left: 7px;padding-right: 0px;" href="{{ route('zone.fetch', \Crypt::encrypt($zone->id)) }}">
            								<svg class="svg-icon svg-icon-sm svg-icon-heavy me-2 img-icon"><use xlink:href="#survey-1"> </use></svg>
            							</a>														
            						</td>
                                    <td>{{ $zone->zone_type ?? ''}}</td>
                                    <td>{{ $zone->courier->logistics_name ?? '' }}</td>
									<td>{{ $zone->zone_code ?? ''}}</td>
                                    <td>{{ $zone->description ?? '' }}</td>									
									<td title="{{ $zone->zone_mapping }}">{{ substr($zone->zone_mapping,0,30). "..." }}</td>
            				    </tr>
								@endforeach    
			                </tbody>
			            </table>
						{{ $zones->links() }}
			        </div>
			    </div>
			</div>
        </div>
    </section>
@endsection