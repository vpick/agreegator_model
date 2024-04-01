@extends('admin-app/admin-master')
@section('title', 'Order Status')
@section('content')
<!-- <header class="py-4">
	<div class="container-fluid py-2">
	  <h1 class="h3 fw-normal mb-0">Order Status</h1>
	</div>
</header> -->
<section class="py-filter">
	<div class="col-md-12 text-right">
		<!-- <a href="catalogus/all" class="btn btn-outline-dark btn-sm"><i class="mdi mdi-library-books"></i> Manage Catalogue</a> -->
		<!-- <a href="product/all" class="btn btn-outline-dark btn-sm"><i class="mdi mdi-link-variant"></i>Product Catalog</a> -->
		<a href="#" class="btn btn-outline-dark btn-sm"><i class="mdi mdi-arrow-down-bold-circle"></i> Export</a>
		<button type="button" class="btn btn-sm btn-outline-dark refresh_orders_button"><i class="mdi mdi-refresh"></i> Refresh</button>
		<button class="btn btn-outline-dark btn-sm" data-toggle="modal" data-target=".import_orders_modal"> <i class="mdi mdi-arrow-up-bold-circle"></i> Import</button>
		<button class="btn btn-outline-dark btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Create New</button>
	    <ul class="dropdown-menu shadow-sm">
			<li><a class="dropdown-item" href="{{ route('order-status.create')}}"><i class="mdi mdi-plus"></i> Create New</a></li>
		</ul>
		<button type="button" class="btn btn-outline-dark show_hide_filter btn-sm" onclick="showFilter()"><i class="icon-placeholder mdi mdi-filter"></i> Filters</button>
        <button type="button" class="btn btn-outline-dark show_hide_filter btn-sm" style="display:none;"><i class="icon-placeholder mdi mdi-close"></i> Close</button>
	</div>
	<div id='filter' style='display:none'>
	    <div class="card-body">
			<div class="row">
				<div class="form-group col-lg-3">
					<label>From Date:</label>
					<input type="text" required="" class="form-control" id='reportrange' name="order_id" placeholder="Order Id" value="">
					<span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;"></span>
				</div>
				<div class="form-group col-lg-3">
				   <label></label>
				   <button type="submit" name="submit" class="btn btn-primary form-control">Apply</button>
				</div>
			</div>
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
            				        <th>Status</th>
            				        <th>Action</th>
            				    </tr>
            			    </thead>
			                <tbody>
                			    @foreach($orders as $key=>$order)
                    				<tr style='border-radius: 5px 5px 4px 5px;box-shadow: 1px 1px 1px 0px aqua;'>
                    				    <td>
                        					<div class="d-flex align-items-center">
                        					  <!--<img src="https://mdbootstrap.com/img/new/avatars/8.jpg" alt="" style="width: 45px; height: 45px" class="rounded-circle" />-->
                        					  <a title='View Status' href="{{ route('order-status.edit',(\Crypt::encrypt($order->id))) }}" class="">
                        					      <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2"><use xlink:href="#survey-1"> </use></svg>
                        					   </a>   
                        					  
                        					</div>
                    				    </td>
                    				    <td>{{ $order->order_status }} </td>
                    				    <td>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="status({{ $order->id}})">Delete</button>
                                        </td>
                    			    </tr>
                			    @endforeach
			                </tbody>
			            </table>
			        </div>
			    </div>
			</div>
        </div>
    </section>
    <script>
        function status(n) {
            var url = "{{ route('order-status.destroy', ':statusId') }}".replace(':statusId', n);
            if (n) {
              swal.fire({
                title: "Warning",
                text: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Confirm",
                cancelButtonText: "Cancel",
              }).then((result) => {
                if (result.isConfirmed) {
                  window.location.href = url;
                }
              });
            }
        }
</script>
@endsection
