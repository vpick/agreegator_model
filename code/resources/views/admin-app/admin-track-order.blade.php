@extends('admin-app/admin-master')
@section('title', 'Shipment Tracking')
@section('content')
<!--<section class="py-5"></section>-->
<!-- Header Section-->
<section class="bg-white">
    <div class="container-fluid">
        <div class="row d-flex align-items-md-stretch">
		    <div class="col-lg-6 col-md-6">
			<div class="row d-flex align-items-md-stretch">
              <div class="card">
                
				<div class="card-header border-bottom">
                  <h2 class="h5 fw-normal mb-0"><a class="card-collapse-link text-dark d-block" data-bs-toggle="collapse" href="#activities-box-ord" aria-expanded="true">Order Details</a></h2>
                </div>
                <div class="card-body p-0">
                  <div class="collapse show" id="activities-box-ord" role="tabpanel">
                    <div class="row g-0 border-bottom border-gray-200">
                      <div class="col-sm-4 col-3 text-end">
                        <ul class="list-inline mb-0">
                          <li>
                            <div class="d-inline-block p-2 bg-light"><i class="far fa-clock fa-fw"></i></div>
                          </li>
                          <li class="me-2"><span class="small text-gray-500">6:00 am</span></li>
                          <li class="me-2"><span class="small text-info lh-1 d-block">6 hours ago</span></li>
                        </ul>
                      </div>
                      <div class="col-sm-8 col-9 border-start border-gray-200 p-3">
                        <h5 class="fw-normal">Delivered</h5>
                        <p class="small mb-0 text-gray-600">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.         </p>
                      </div>
                    </div>
                  </div>
                </div>
				<div class="card-header border-bottom">
                  <h2 class="h5 fw-normal mb-0"><a class="card-collapse-link text-dark d-block" data-bs-toggle="collapse" href="#activities-box-prodct" aria-expanded="true">Product Details</a></h2>
                </div>
                <div class="card-body p-0">
                  <div class="collapse show" id="activities-box-prodct" role="tabpanel">
                    <div class="row g-0 border-bottom border-gray-200">
                      <div class="col-sm-4 col-3 text-end">
                        <ul class="list-inline mb-0">
                          <li>
                            <div class="d-inline-block p-2 bg-light"><i class="far fa-clock fa-fw"></i></div>
                          </li>
                          <li class="me-2"><span class="small text-gray-500">6:00 am</span></li>
                          <li class="me-2"><span class="small text-info lh-1 d-block">6 hours ago</span></li>
                        </ul>
                      </div>
                      <div class="col-sm-8 col-9 border-start border-gray-200 p-3">
                        <h5 class="fw-normal">Delivered</h5>
                        <p class="small mb-0 text-gray-600">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.         </p>
                      </div>
                    </div>
                  </div>
                </div>
				
				<div class="card-header border-bottom">
                  <h2 class="h5 fw-normal mb-0"><a class="card-collapse-link text-dark d-block" data-bs-toggle="collapse" href="#activities-box-cust" aria-expanded="true">Customer Details</a></h2>
                </div>
                <div class="card-body p-0">
                  <div class="collapse show" id="activities-box-cust" role="tabpanel">
                    <div class="row g-0 border-bottom border-gray-200">
                      <div class="col-sm-4 col-3 text-end">
                        <ul class="list-inline mb-0">
                          <li>
                            <div class="d-inline-block p-2 bg-light"><i class="far fa-clock fa-fw"></i></div>
                          </li>
                          <li class="me-2"><span class="small text-gray-500">6:00 am</span></li>
                          <li class="me-2"><span class="small text-info lh-1 d-block">6 hours ago</span></li>
                        </ul>
                      </div>
                      <div class="col-sm-8 col-9 border-start border-gray-200 p-3">
                        <h5 class="fw-normal">Delivered</h5>
                        <p class="small mb-0 text-gray-600">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.         </p>
                      </div>
                    </div>
                  </div>
                </div>
				
				<div class="card-header border-bottom">
                  <h2 class="h5 fw-normal mb-0"><a class="card-collapse-link text-dark d-block" data-bs-toggle="collapse" href="#activities-box-ship" aria-expanded="true">Shipment Details</a></h2>
                </div>
                <div class="card-body p-0">
                  <div class="collapse show" id="activities-box-ship" role="tabpanel">
                    <div class="row g-0 border-bottom border-gray-200">
                      <div class="col-sm-4 col-3 text-end">
                        <ul class="list-inline mb-0">
                          <li>
                            <div class="d-inline-block p-2 bg-light"><i class="far fa-clock fa-fw"></i></div>
                          </li>
                          <li class="me-2"><span class="small text-gray-500">6:00 am</span></li>
                          <li class="me-2"><span class="small text-info lh-1 d-block">6 hours ago</span></li>
                        </ul>
                      </div>
                      <div class="col-sm-8 col-9 border-start border-gray-200 p-3">
                        <h5 class="fw-normal">Delivered</h5>
                        <p class="small mb-0 text-gray-600">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.         </p>
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
                  <h2 class="h5 fw-normal mb-0"><a class="card-collapse-link text-dark d-block" data-bs-toggle="collapse" href="#activities-box" aria-expanded="true">Order History</a></h2>
                </div>
                <div class="card-body p-0">
                  <div class="collapse show" id="activities-box" role="tabpanel">
                    <div class="row g-0 border-bottom border-gray-200">
                      <div class="col-sm-4 col-3 text-end">
                        <ul class="list-inline mb-0">
                          <li>
                            <div class="d-inline-block p-2 bg-light"><i class="far fa-clock fa-fw"></i></div>
                          </li>
                          <li class="me-2"><span class="small text-gray-500">6:00 am</span></li>
                          <li class="me-2"><span class="small text-info lh-1 d-block">6 hours ago</span></li>
                        </ul>
                      </div>
                      <div class="col-sm-8 col-9 border-start border-gray-200 p-3">
                        <h5 class="fw-normal">Delivered</h5>
                        <p class="small mb-0 text-gray-600">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.         </p>
                      </div>
                    </div>
                    <div class="row g-0 border-bottom border-gray-200">
                      <div class="col-sm-4 col-3 text-end">
                        <ul class="list-inline mb-0">
                          <li>
                            <div class="d-inline-block p-2 bg-light"><i class="far fa-clock fa-fw"></i></div>
                          </li>
                          <li class="me-2"><span class="small text-gray-500">6:00 am</span></li>
                          <li class="me-2"><span class="small text-info lh-1 d-block">6 hours ago</span></li>
                        </ul>
                      </div>
                      <div class="col-sm-8 col-9 border-start border-gray-200 p-3">
                        <h5 class="fw-normal">Intransit</h5>
                        <p class="small mb-0 text-gray-600">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.         </p>
                      </div>
                    </div>
                    <div class="row g-0 border-bottom border-gray-200">
                      <div class="col-sm-4 col-3 text-end">
                        <ul class="list-inline mb-0">
                          <li>
                            <div class="d-inline-block p-2 bg-light"><i class="far fa-clock fa-fw"></i></div>
                          </li>
                          <li class="me-2"><span class="small text-gray-500">6:00 am</span></li>
                          <li class="me-2"><span class="small text-info lh-1 d-block">6 hours ago</span></li>
                        </ul>
                      </div>
                      <div class="col-sm-8 col-9 border-start border-gray-200 p-3">
                        <h5 class="fw-normal">Picked Up</h5>
                        <p class="small mb-0 text-gray-600">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.         </p>
                      </div>
                    </div>
                    <div class="row g-0 border-bottom border-gray-200">
                      <div class="col-sm-4 col-3 text-end">
                        <ul class="list-inline mb-0">
                          <li>
                            <div class="d-inline-block p-2 bg-light"><i class="far fa-clock fa-fw"></i></div>
                          </li>
                          <li class="me-2"><span class="small text-gray-500">6:00 am</span></li>
                          <li class="me-2"><span class="small text-info lh-1 d-block">6 hours ago</span></li>
                        </ul>
                      </div>
                      <div class="col-sm-8 col-9 border-start border-gray-200 p-3">
                        <h5 class="fw-normal">Out for Pickup</h5>
                        <p class="small mb-0 text-gray-600">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.         </p>
                      </div>
                    </div>
                    <div class="row g-0">
                      <div class="col-sm-4 col-3 text-end">
                        <ul class="list-inline mb-0">
                          <li>
                            <div class="d-inline-block p-2 bg-light"><i class="far fa-clock fa-fw"></i></div>
                          </li>
                          <li class="me-2"><span class="small text-gray-500">6:00 am</span></li>
                          <li class="me-2"><span class="small text-info lh-1 d-block">6 hours ago</span></li>
                        </ul>
                      </div>
                      <div class="col-sm-8 col-9 border-start border-gray-200 p-3">
                        <h5 class="fw-normal">Pickup Shedule</h5>
                        <p class="small mb-0 text-gray-600">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.         </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
</section>
@endsection
