@extends('common-app/master')
@section('title', 'Warehouse Card')
@section('content')
@php
    if(Auth::user()->user_type == 'isCompany'){
        if(session()->has('client') && !session()->has('warehouse')) 
        {
            echo '<script>window.location.href = "'.url('/get-client', [\Crypt::encrypt(Auth::user()->company->id)]).'";</script>';
        }
        else {
            // Your alternative logic if the condition is not met
        }
    }
    else
    {
    }
@endphp
<header class="py-4">
    <div class="container-fluid py-2">
        <h1 class="h3 fw-normal mb-0">Warehouse details</h1>
    </div>
</header>
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
                <div class="card-body">
                  <form  class='row g-3 align-items-center'  method="post" action="{{ route('warehouse_dsp.send') }}">
                    @csrf
                    <div class="col-lg-3">
                        <select class="form-control" id="partner" name="partner" required>
                          <option value="">Select Logistics Partner</option>
                            @foreach($logistics as $logistic)
                                <option value="{{ $logistic->partner_id }}">{{ $logistic->logistics_name }}</option>
                            @endforeach
                        </select>
                    </div>
                  <div class="col-lg-3">
                    <input type="hidden" value="{{ $warehouse->client_id }}" name="client_id">
                    <label class="form-label" for="warehouse_code">Warehouse Code *</label>
                    <input class="form-control" value="{{ $warehouse->warehouse_code }}" type="text" id="warehouse_code" name="warehouse_code" readonly>
                  </div>
                  
                  <div class="col-lg-3">
                    <label class="form-label" for="warehouse_name">Warehouse Name *</label>
                    <input class="form-control" id="warehouse_name" name="warehouse_name" value="{{ $warehouse->warehouse_name }}" type="text" required>
                  </div>
                  <div class="col-lg-3">
                    <label class="form-label" for="warehouse_phone">Contact No. *</label>
                    <input class="form-control" id="warehouse_phone" type="text"  name="warehouse_phone" value="{{ $warehouse->phone }}" required>
                  </div>
                  <div class="col-lg-3">
                    <label class="form-label" for="warehouse_email">Email Id *</label>
                    <input class="form-control" id="warehouse_email" type="email"  name="warehouse_email" value="{{ $warehouse->support_email }}" required>
                  </div>
                  <div class="col-lg-3">
                    <label class="form-label" for="warehouse_gst">GST No. </label>
                    <input class="form-control" id="warehouse_gst" name="warehouse_gst" type="text" required value="{{ $warehouse->gst_no }}">
                  </div>
                  <div class="col-lg-3">
                    <label class="form-label" for="warehouse_city">City *</label>
                    <input class="form-control" id="warehouse_city" name="warehouse_city" type="text" required value="{{ $warehouse->city }}">
                  </div>
                  <div class="col-lg-3">
                    <label class="form-label" for="warehouse_pincode">Pin Code *</label>
                    <input class="form-control" id="warehouse_pincode" name="warehouse_pincode" type="text" required value="{{ $warehouse->pincode }}">
                  </div>
                  <div class="col-lg-3">
                    <label class="form-label" for="warehouse_address1">Address1 *</label>
                    <input class="form-control" id="warehouse_address1" name="warehouse_address1" type="text" required value="{{ $warehouse->address1 }}">
                  </div>
                  <div class="col-lg-3">
                    <label class="form-label" for="warehouse_address2">Address2 *</label>
                    <input class="form-control" id="warehouse_address2" name="warehouse_address2" type="text" required value="{{ $warehouse->address2 }}">
                  </div>
                  <div class="col-lg-4">
                    <div class="pt-2 mt-3">
                      <button class="btn btn-primary" type="submit">Submit</button>
                      <a href="{{ route('warehouse.index') }}" class="btn btn-danger">Cancel</a>
                    </div> 
                  </div>
                </form>
              </div>
            </div>
          </div>
          </div>
        </div>
      </section>
@endsection
