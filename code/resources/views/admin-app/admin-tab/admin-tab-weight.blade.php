@extends('admin-app/admin-master')
@section('title', 'Weight Master')
@section('content')
<section class="py-filter">
	<div class="col-md-12 text-right">
	   
		
		<a href="{{ route('weight-range.index') }}" class="btn btn-sm btn-outline-dark refresh_orders_button"><i class="mdi mdi-refresh"></i> Refresh</a>
		
		<button class="btn btn-outline-dark btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Create</button>
    	    <ul class="dropdown-menu shadow-sm">
    			<li><a class="dropdown-item" href="{{ route('weight-range.create') }}"><i class="mdi mdi-plus"></i> Add Weight Range</a></li>
    			
    	    </ul>
        <button type="button" class="btn btn-outline-dark show_hide_filter btn-sm" style="display:none;"><i class="icon-placeholder mdi mdi-close"></i> Close</button>
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
            				        <th>Weight Range</th>
									<th>Description</th>
            				    </tr>
							</thead>
			                <tbody>
                			 @foreach($weights as $weight)
								<tr>
            				        <td >
            							<a title='View User' class="btn btn-sm btn-outline-primary" style="padding-left: 7px;padding-right: 0px;" href="{{ route('weight-range.edit', \Crypt::encrypt($weight->id)) }}">
            								<svg class="svg-icon svg-icon-sm svg-icon-heavy me-2 img-icon"><use xlink:href="#survey-1"> </use></svg>
            							</a>														
            						</td>
            				        <td>{{ $weight->weight_range.'   Kg' }}</td>
									<td>{{ $weight->description }}</td>
            				    </tr>
								@endforeach    
			                </tbody>
			            </table>
						{{ $weights->links() }}
			        </div>
			    </div>
			</div>
        </div>
    </section>
@endsection