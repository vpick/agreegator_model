@extends('common-app/master')
@section('title', 'Order View Card')
@section('content')
<!-- <header class="py-4">
	<div class="container-fluid py-2">
	  <h1 class="h3 fw-normal mb-0">Order details</h1>
	</div>
</header> -->
<style>
    .m-t-50{
        margin-top: 50px;
    }
    .fs-14{
        font-size: 14px;
    }
    .fs-13{
        font-size: 13px;
    color: darkslategray;
    }
</style>
<!-- Forms Section-->
<section class="pb-5"> 
    <div class="container-fluid">
        <div class="row">
	    <!-- Basic Form-->
            <div class="col-lg-12">
                <div class="card">
                    <!--<div class="card-header border-bottom">
                    <h3 class="h4 mb-0">Basic Form</h3>
                    </div>-->
                    @if(session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="row align-items-center gy-3 text-center mb-4">
                                        <div class="col-sm-6 text-sm-start">
                                            <!--<h1 class="h4 mb-0"> Order</h1>-->
                                        </div>
                                        <div class="col-sm-6 text-sm-end">
                                            @if($order->invoice_no !='' )
                                            <a href="{{ route('invoice.print',(Crypt::encrypt($order->invoice_no))) }}" class="btn btn-outline-dark btn-sm" target="_blank">Print Invoice</a>
                                            @endif
                                            @if($order->shipping_label !='')
                                                @if (str_contains($order->shipping_label, 'data:text/html;base64')) 
                                                    <a href="{{ $order->shipping_label }}"  class="btn btn-outline-dark btn-sm" target="_blank" download="{{ $order->awb_no}}.html"><i class="fa fa-print me-2" ></i>Print Label</a>
                                                @else 
                                                    <a href="{{ $order->shipping_label }}"  class="btn btn-outline-dark btn-sm" target="_blank" download="{{ $order->awb_no}}.pdf"><i class="fa fa-print me-2" ></i>Print Label</a>
                                                @endif
                                            @endif
                                             @if($order->docket_print !='')
                                          
                                                <a href="{{ $order->docket_print }}"  class="btn btn-outline-dark btn-sm" target="_blank" download="{{ 'docket_'.$order->awb_no}}.pdf"><i class="fa fa-print me-2" ></i>Docket Print</a>
                                            @endif
                                            @if($order->order_status == 'Booked' && $order->status == '0')
                                                <a href="show-order?ord={{Crypt::encrypt($order->id)}}" class="btn btn-outline-dark btn-sm" >Edit</a>
                                            @else
                                                <button type="button" class="btn btn-outline-dark btn-sm" disabled>Edit</button>
                                            
                                            @endif
                                         @if($order->order_status === 'Booked' && $order->status == '0')
                                            <button type="button" id="{{ $order->id }}" class="btn btn-outline-dark text-danger btn-sm cancel-btn">Cancel Order</button>
                                        @else
                                        <button type="button" class="btn btn-outline-dark text-danger btn-sm" disabled>Cancel Order</button>
                                        @endif  
                                        <button class="btn btn-outline-dark btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Clone Order</button>
                                        <ul class="dropdown-menu shadow-sm">
                                            <li><a class="dropdown-item" href="show-order?ordClone={{Crypt::encrypt($order->order_no)}}"><i class="mdi mdi-plus"></i> Clone Forward Order</a></li>
                                            
                                            <!--<li><a class="dropdown-item" href="#!"><i class="mdi mdi-plus"></i> Clone Reverse QC Order</a></li>-->
                                        </ul>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body p-sm-5">
                                        <div class="row">
                                            <div class="col-12 col-md-3">
                                                <h6 class="text-uppercase text-muted">Order Details</h6>
                                                <p class="text-muted mb-4 text-sm">
                                                    <strong class="text-gray-900">Order No:</strong><span class="ms-2">{{ $order->order_no }}</span><br>
                                                    <strong class="text-gray-900">Order Date:</strong><span class="ms-2">{{ $order->created_at  }}</span><br>
                                                    <strong class="text-gray-900">Payment Type:</strong><span class="ms-2">{{ $order->payment_mode }}</span><br>
                                                    <strong class="text-gray-900">Order Status:</strong><span class="ms-2">{{ $order->order_status }}</span><br>
                                                    <strong class="text-gray-900">AWB No:</strong><span class="ms-2">{{ $order->awb_no }}</span><br>
                                                    <strong class="text-gray-900">Child AWB No:</strong><span class="ms-2">{{ $order->child_awbno }}</span><br>
                                                </p>
                                                <hr>
                                                <h6 class="text-uppercase text-muted">Weight Details</h6>
                                                <p class="text-muted mb-4 text-sm">
                                                    @php
                                                        $total_weight = $order->total_weight/1000;
                                                    @endphp
                                                    <strong class="text-gray-900">Weight:</strong><span class="ms-2">{{ $total_weight }} Kg</span><br>
                                                    <strong class="text-gray-900">Dimension:</strong>
                                                    <span class="ms-2">{{ $order->length.' x '.$order->breadth.' x '.$order->height }} cm</span><br>
                                                    <strong class="text-gray-900">Volumetric Weight:</strong><span class="ms-2"> {{$order->volumetric_weight}} Kg</span><br>
                                                    <strong class="text-gray-900">Charged Weight Slab:</strong><span class="ms-2"> 
                                                        @if($order->volumetric_weight>$total_weight) 
                                                            {{ $order->volumetric_weight }} 
                                                        @else 
                                                            {{ $total_weight }}
                                                        @endif
                                                         Kg
                                                    </span><br>
                                                </p>
                                                <hr>
                                                <p class="text-muted mb-4 text-sm">
                                                    <strong class="text-gray-900">Courier:</strong><span class="ms-2">{{$order->courier_name }} {{$total_weight }} Kg</span><br>
                                                    <strong class="text-gray-900">AWB:</strong><span class="ms-2">{{ $order->awb_no }}</span><br>
                                                    <!-- <strong class="text-gray-900">Essential:</strong><span class="ms-2"> </span><br> -->
                                                </p>
                                            </div>
                                            
                                            <div class="col-12 col-md-3">
                                                <h6 class="text-uppercase text-muted">Warehouse Details</h6>
                                                <p class="text-muted mb-4 text-sm">
                                                    <strong class="text-gray-900"></strong><span class="ms-2">{{ $order->warehouse_code }}</span><br>
                                                    <strong class="text-gray-900"></strong><span class="ms-2">{{ $order->warehouse_phone_number  }}</span><br>
                                                    <strong class="text-gray-900"></strong><span class="ms-2">{{ $order->warehouse_address }}</span><br>
                                                    <strong class="text-gray-900"></strong><span class="ms-2">{{ $order->warehouse_address_2 }}</span><br>
                                                    <strong class="text-gray-900"></strong><span class="ms-2">{{ $order->warehouse_state }}</span><br>
                                                    <strong class="text-gray-900"></strong><span class="ms-2">{{ $order->warehouse_city }}</span><br>
                                                    <strong class="text-gray-900"></strong><span class="ms-2">{{ $order->warehouse_pincode }}</span><br>
                                                    <strong class="text-gray-900">GST No:</strong><span class="ms-2"></span><br>
                                                </p>
                                               
                                            </div>
                                            <div class="col-12 col-md-2">
                                                <h6 class="text-uppercase text-muted">RTO Details</h6>
                                                <p class="text-muted mb-4 text-sm">
                                                    <p class="text-muted mb-4 text-sm">
                                                    <strong class="text-gray-900"></strong><span class="ms-2">{{ $order->warehouse_code }}</span><br>
                                                    <strong class="text-gray-900"></strong><span class="ms-2">{{ $order->warehouse_phone_number  }}</span><br>
                                                    <strong class="text-gray-900"></strong><span class="ms-2">{{ $order->warehouse_address }}</span><br>
                                                    <strong class="text-gray-900"></strong><span class="ms-2">{{ $order->warehouse_address_2 }}</span><br>
                                                    <strong class="text-gray-900"></strong><span class="ms-2">{{ $order->warehouse_state }}</span><br>
                                                    <strong class="text-gray-900"></strong><span class="ms-2">{{ $order->warehouse_city }}</span><br>
                                                    <strong class="text-gray-900"></strong><span class="ms-2">{{ $order->warehouse_pincode }}</span><br>
                                                    <strong class="text-gray-900">GST No:</strong><span class="ms-2"></span><br>
                                                </p>
                                                   
                                                </p>
                                                
                                            </div>
                                        
                                            <div class="col-12 col-md-2">
                                                <h6 class="text-uppercase text-muted">Shipping Details</h6>
                                                <p class="text-muted mb-4 text-sm">
                                                {{ $order->shipping_first_name.' '.$order->shipping_last_name}},<br>
                                                {{ $order->shipping_address_1.' '.$order->shipping_address_2}},<br>
                                                {{ $order->shipping_city.' , '.$order->shipping_state }}, {{$order->shipping_pincode}}, India<br>
                                                    {{$order->shipping_phone_number}}
                                                </p>
                                            </div>
                                            <div class="col-12 col-md-2">
                                                <h6 class="text-uppercase text-muted">Billing Details</h6>
                                                <p class="text-muted mb-4 text-sm">
                                                {{ $order->billing_first_name.' '.$order->billing_last_name}},<br>
                                                {{ $order->billing_address_1.' '.$order->billing_address_2}},<br>
                                                {{ $order->billing_city.' , '.$order->billing_state }}, {{$order->billing_pincode}}, India<br>
                                                    {{$order->billing_phone_number}}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <!-- Table-->
                                                <div class="table-responsive">
                                                    <table class="table mt-4 mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th class="px-0 bg-transparent border-top-0"><span class="h6">Product</span></th>
                                                                <!--<th class="px-0 bg-transparent border-top-0"><span class="h6">SKU Code</span></th>-->
                                                                <th class="px-0 bg-transparent border-top-0 text-end"><span class="h6">Quantity</span></th>
                                                                <th class="px-0 bg-transparent border-top-0 text-end"><span class="h6">Item Price</span></th>
                                                                <th class="px-0 bg-transparent border-top-0 text-end"><span class="h6">Total Value</span></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                             @php $grand_total = 0;
                                                            @endphp
                                                            @foreach($products as $product)
                                                            <tr>
                                                                <td class="px-0">{{$product->product_code}}</td>
                                                                <!--<td class="px-0">{{$product->product_code}}</td>-->
                                                                <td class="px-0 text-end">{{$product->product_quantity}}</td>
                                                                <td class="px-0 text-end">Rs. {{$product->product_price/$product->product_quantity}}</td>
                                                                <td class="px-0 text-end">Rs. {{number_format((float)$product->product_quantity * ($product->product_price/$product->product_quantity), 2, '.', '');
                                                                
                                                                }}</td>
                                                                 @php
                                                                    $grand_total+= ($product->product_price/$product->product_quantity)*$product->product_quantity;
                                                                @endphp
                                                            </tr>
                                                           @endforeach
                                                           
                                                            <tr>
                                                                <td class="px-0 border-bottom-0" colspan="3"><strong>Grand Total</strong></td>
                                                                <td class="px-0 text-end border-bottom-0" colspan="3"><span class="h3 mb-0"> Rs. {{number_format((float)$grand_total, 2, '.', '')}}</span></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>    
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
	    </div>
    </div>
</section>
<script>
    $(document).ready(function () { 
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });
        $(document).on('click', '.cancel-btn', function() {
            var order = $(this).attr('id');
            console.log(order);

            var status = 'Cancelled';
            var orderStatusRoute = "{{ route('order.status') }}";
				swal.fire({
                title: "Warning",
                text: "Do want to change ? ",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Confirm",
                cancelButtonText: "Cancel",
              }).then((result) => {
                if (result.isConfirmed) {
                   
                  $.ajax({
					url: orderStatusRoute, // Ensure the route is correct and defined in your Laravel routes file
					type: "POST",
					data: {
						_token: '{{ csrf_token() }}',
						orderId: order,
						status: status
					},					
					success: function(res) {
                        console.log(res.data);
						if (res.data == true) {
							console.log(res.data);
							Swal.fire({
								title: 'Success!',
								text: "Status Updated!!!",
								timer: 2000,
								icon: 'success'
							}).then(function() {
							    location.reload();
							});
						} else if (res.error) {
							console.log(res.error);
							Swal.fire({
								title: 'Failed!',
								text: "{{ $errors->first() }}",
								timer: 5000,
								icon: 'error'
							});
						}
					},
					error: function(xhr, textStatus, errorThrown) {
						// Handle error
						console.log('Error:', errorThrown);
						Swal.fire({
								title: 'Failed!',
								text: errorThrown,
								timer: 5000,
								icon: 'error'
							});
					}
				});
                }
              });
        });
    })
</script>
@endsection