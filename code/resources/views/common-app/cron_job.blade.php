@extends('common-app/master')
@section('title', 'Cron Job')
@section('content')
<section class="bg-white">
    <div class="container-fluid">
        <div class="row d-flex align-items-md-stretch">
		    <div class="col-lg-12 col-md-12">
			  <div class="row d-flex align-items-md-stretch">
              <div class="card">
				<div class="card-header border-bottom">
                  <h2 class="h5 fw-normal mb-0">
                      <a class="card-collapse-link text-dark d-block" data-bs-toggle="collapse" href="#activities-box-ord" aria-expanded="true"><strong>Scheduler Details</strong></a>
                  </h2>
                </div>
                <div class="card-body p-0">
                  <div class="collapse show" id="activities-box-ord" role="tabpanel">
                    <div class="row g-0 border-bottom border-gray-200">
                        <div class="col-sm-12 col-12">
                            <hr />
                            <ul>
                                <li>
                                    <p>Shipment booking scheduler is running now if within a 2 hours awb not getting from logistics partners side scheduler automatically cancelled the order..!</p>
                              </li>
                            </ul>
                            <hr />
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
<pre>{{ $logContents }}</pre>
@endsection