@extends('common-app/master')
@section('title', 'Shipment Tracking')
@section('content')
<!--<section class="py-5"></section>-->
<!-- Header Section-->
<style>
    .f-s{
font-size: 13px;
}
</style>
<section class="bg-white">
    <div class="container-fluid">
        <div class="row d-flex align-items-md-stretch">
		    <div class="col-lg-6 col-md-6">
			  <div class="row d-flex align-items-md-stretch">
              <div class="card">
				<div class="card-header border-bottom">
                  <h2 class="h5 fw-normal mb-0"><a class="card-collapse-link text-dark d-block" data-bs-toggle="collapse" href="#activities-box-ord" aria-expanded="true"><strong>Order Details</strong></a></h2>
                </div>
                <div class="card-body p-0">
                  <div class="collapse show" id="activities-box-ord" role="tabpanel">
                    <div class="row g-0 border-bottom border-gray-200">
                        <div class="col-sm-12 col-12">
                          <table class="table align-middle mb-0 bg-white" style="border: transparent;">
                              <tbody>
                                  <tr>
                                      <td>Order ID</td>
                                      <td>{{ $orderDetail->order_no }}</td>
                                  </tr>
                                  <tr>
                                      <td>AWB No.</td>
                                      <td>{{ $orderDetail->awb_no }}</td>
                                  </tr>
                                  <tr>
                                      <td>Order Status</td>
                                      <td>{{ $orderDetail->order_status }}</td>
                                  </tr>
                                  <tr>
                                      <td>Remark</td>
                                      <td>{{ $orderDetail->remarks }}</td>
                                  </tr>
                                  <tr>
                                      <td>Order Value</td>
                                      <td>₹{{ $orderDetail->total_amount }}</td>
                                  </tr>
                              </tbody>
                          </table>
                     </div>
                    </div>
                  </div>
                </div>
				<div class="card-header border-bottom">
                  <h2 class="h5 fw-normal mb-0"><a class="card-collapse-link text-dark d-block" data-bs-toggle="collapse" href="#activities-box-prodct" aria-expanded="true"><strong>Product Details</strong></a></h2>
                </div>
                <div class="card-body p-0">
                  <div class="collapse show" id="activities-box-prodct" role="tabpanel">
                    <div class="row g-0 border-bottom border-gray-200">
                      <div class="col-sm-12 col-12">
                        <table class="table align-middle mb-0 bg-white">
                		  <thead class="bg-light">
                				<tr>
                				  <th>Product Code</th>
                				   <th>Product Description</th>
                				  <th>HSN Code</th>
                				  <th>Qty</th>
                				  <th>Price</th>
                				</tr>
                			  </thead>
                			  <tbody>
                			  @foreach($products as $product)
                				<tr style='border-radius: 5px 5px 4px 5px;box-shadow: 1px 1px 1px 0px aqua;'>
                				  <td>{{ $product->product_code }}</td>
                				  <td>{{ $product->product_description }}</td>
                				  <td>{{ $product->product_hsn_code }}</td>
                				  <td>{{ $product->product_quantity }}</td>
                				  <td>{{ $product->product_price }}</td>
                				</tr>
                			  @endforeach
                		  </tbody>
                		</table>
                    </div>
                  </div>
                </div>
				<div class="card-header border-bottom">
                  <h2 class="h5 fw-normal mb-0"><a class="card-collapse-link text-dark d-block" data-bs-toggle="collapse" href="#activities-box-cust" aria-expanded="true"><strong>Customer Details</strong></a></h2>
                </div>
                <div class="card-body p-0">
                  <div class="collapse show" id="activities-box-cust" role="tabpanel">
                    <div class="row g-0 border-bottom border-gray-200">
                      <div class="col-sm-12 col-12">
                          <table class="table align-middle mb-0 bg-white" style="border: transparent;">
                              <tbody>
                                  <tr>
                                      <td>Name</td>
                                      <td>{{ $orderDetail->shipping_company_name }}</td>
                                  </tr>
                                  <tr>
                                      <td>Phone No.</td>
                                      <td>{{ $orderDetail->shipping_phone_number}}</td>
                                  </tr>
                                  <tr>
                                      <td>Email Id</td>
                                      <td>{{ $orderDetail->shipping_email}}</td>
                                  </tr>
                                  <tr>
                                      <td>Address</td>
                                      <td>{{ $orderDetail->shipping_address_1 }}</td>
                                  </tr>
                                  <tr>
                                      <td>Pincode</td>
                                      <td>{{ $orderDetail->shipping_pincode }}</td>
                                  </tr>
                                  <tr>
                                      <td>City</td>
                                      <td>{{ $orderDetail->shipping_city }}</td>
                                  </tr>
                                  <tr>
                                      <td>State</td>
                                      <td>{{ $orderDetail->shipping_state }}</td>
                                  </tr>
                              </tbody>
                          </table>
                     </div>
                    </div>
                  </div>
                </div>
				<div class="card-header border-bottom">
                  <h2 class="h5 fw-normal mb-0"><a class="card-collapse-link text-dark d-block" data-bs-toggle="collapse" href="#activities-box-ship" aria-expanded="true"><strong>Shipment Details</strong></a></h2>
                </div>
                <div class="card-body p-0">
                  <div class="collapse show" id="activities-box-ship" role="tabpanel">
                    <div class="row g-0 border-bottom border-gray-200">
                      <div class="col-sm-12 col-12">
                          <table class="table align-middle mb-0 bg-white" style="border: transparent;">
                              <tbody>
                                  <tr>
                                      <td>Courier Name</td>
                                      <td>{{ $orderDetail->courier_name }}</td>
                                  </tr>
                                  <tr>
                                      <td>Total Weight</td>
                                      <td>{{ ($orderDetail->total_weight)/1000 .' Kg'}}</td>
                                  </tr>
                                  <tr>
                                      <td>Volumetric Weight</td>
                                      <td>{{ $orderDetail->volumetric_weight.' Kg'}}</td>
                                  </tr>
                                  <tr>
                                      <td>Size</td>
                                      <td>{{ $orderDetail->length.'X'.$orderDetail->breadth.'X'.$orderDetail->height.' '.$orderDetail->dimension_unit }}</td>
                                  </tr>
                                  <tr>
                                      <td>Shipping Charges</td>
                                      <td>₹ {{ $orderDetail->shipping_charges}}</td>
                                  </tr>
                                  <tr>
                                      <td>Order Amount</td>
                                      <td>₹ {{ $orderDetail->total_amount }}</td>
                                  </tr>
                                  <tr>
                                      <td>Final Amount</td>
                                      <td>₹ {{ $orderDetail->invoice_amount }}</td>
                                  </tr>
                              </tbody>
                          </table>
                     </div>
                    </div>
                  </div>
                </div>
		     </div>
              <!--<div class="card shadow-0">
                <div class="card-body p-0">
                  <h2 class="h3 fw-normal">Order Details</h2>
                    <div class="form-check">
                      <label class="form-check-label text-sm" for="list1">Order ID : NS-226890</label>
					  <label class="form-check-label text-sm" for="list1">AWB No. : 14345621222477</label>
					  <label class="form-check-label text-sm" for="list1">Order Date : 27 Apr, 2023 . 07:18 PM</label>
					  <label class="form-check-label text-sm" for="list1">Order Value : 1860</label>
					  <label class="form-check-label text-sm" for="list1">Shipping Price : 230</label>
					  <label class="form-check-label text-sm" for="list1">Drop City : Kottayam</label>
                    </div>
                </div>
              </div>-->
            </div>
			</div>
			</div>
			<!--<div class="col-lg-2 col-md-2">
              <div class="card shadow-0">
                <div class="card-body p-0">
                  <h2 class="h3 fw-normal">Customer Details</h2>
                    <div class="form-check">
                      <label class="form-check-label text-sm" for="list1">Name : Krish Tiwary</label>
					  <label class="form-check-label text-sm" for="list1">Phone No. : 9873700947</label>
					  <label class="form-check-label text-sm" for="list1">Email : Krish.tiwary@omneelab.com</label>
					  <label class="form-check-label text-sm" for="list1">Pincode : 686631</label>
					  <label class="form-check-label text-sm" for="list1">State : Kerala</label>
					  <label class="form-check-label text-sm" for="list1">Delivery Address : Devi Ayurvedic Clinic Pthen Purackal Building Pala Road Ettumanoor Near Federal Bank Kottayam Dist. Kerala</label>
                    </div>
                </div>
              </div>
            </div>
			<div class="col-lg-2 col-md-2">
              <div class="card shadow-0">
                <div class="card-body p-0">
                  <h2 class="h3 fw-normal">Product Details</h2>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="list1">
                      <label class="form-check-label text-sm" for="list1">Similique sunt in culpa qui officia</label>
                    </div>
                </div>
              </div>
            </div>-->
            <div class="col-lg-6 col-md-6">
              <!-- Recent Activities Widget      -->
              <div class="card">
                <div class="card-header border-bottom">
                  <h2 class="h5 fw-normal mb-0"><a class="card-collapse-link text-dark d-block" data-bs-toggle="collapse" href="#activities-box" aria-expanded="true"><strong>Order History</strong></a></h2>
                </div>
                
                <div class="card-body p-0">
                  <div class="collapse show" id="activities-box" role="tabpanel">
                @if(isset($dataArray['history']))
                   @foreach ($dataArray['history'] as $event)
                    <div class="row g-0 border-bottom border-gray-200">
                      <div class="col-sm-4 col-3 text-end">
                        <ul class="list-inline mb-0">
                          <li>
                            <div class="d-inline-block p-2 bg-light"><i class="far fa-clock fa-fw"></i></div>
                          </li>
                          <li class="me-2"><span class="small text-info-500">{{ $event['event_time'] ?? '' }}</span></li>
                          <!--<li class="me-2"><span class="small text-info lh-1 d-block">6 hours ago</span></li>-->
                        </ul>
                      </div>
                      <div class="col-sm-8 col-9 border-start border-gray-200 p-3">
                        <h5 class="fw-normal">{{ $event['status_code'] ?? '' }} ({{ $event['message'] ?? '' }})</h5>
                        <p class="small mb-0 text-gray-600">{{ $event['location'] ?? ''}} </p>
                        
                      </div>
                    </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
</section>
@endsection
