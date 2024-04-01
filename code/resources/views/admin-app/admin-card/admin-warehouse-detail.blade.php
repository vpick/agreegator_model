@extends('admin-app.admin-master')
@section('title', 'Warehouse Card')
@section('content')
<style>
    .mt-15{
        margin-top: 1.5rem !important;

    }
</style>
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
                        <div class="row">
                            <div class="col-lg-3">
                                <label class="form-label" for="company_id">Company *</label>
                                <select class="form-control" id="company_id" name="company_id" required disabled>
                                  <option value="">Select company</option>
                                  @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ $data ? ($data->company_id == $company->id ? 'selected' : '') : ''}}>{{ $company->name }}</option>
                                  @endforeach
                                </select>
                                @error('company_id')
                                  <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label" for="client_id">Client *</label>
                                <select class="form-control" id="client_id" name="client_id" required disabled>
                                  <option value="">Select client</option>
                                  @if(!empty($data))
                                    @foreach($clients as $client)
                                      <option value="{{ $client->id }}" {{ $data ? ($data->client_id == $client->id ? 'selected' : '') : ''}}>{{ $client->name }}</option>
                                    @endforeach
                                  @endif
                                </select>
                                @error('client_id')
                                  <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label" for="warehouse_code">Warehouse Code *</label>
                                <input class="form-control" value="{{ $data->warehouse_code ?? '' }}" id="warehouse_code" type="text" name="warehouse_code" disabled>
                                @error('warehouse_code')
                                  <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label" for="warehouse_name">Warehouse Name *</label>
                                <input class="form-control" id="warehouse_name" name="warehouse_name" value="{{ $data ? $data->warehouse_name : old('warehouse_name') }}" type="text" required disabled>
                                @error('warehouse_name')
                                  <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label" for="contact_name">Contact Name *</label>
                                <input class="form-control" id="contact_name" name="contact_name" value="{{ $data ? $data->contact_name : old('contact_name') }}" type="text" required disabled>
                                @error('contact_name')
                                  <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label" for="phone">Contact no. *</label>
                                <input class="form-control" id="phone" type="text"  name="phone" required value="{{ $data ? $data->phone :'' }}" disabled>
                                @error('phone')
                                  <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label" for="gst_no">Gst no </label>
                                <input class="form-control" id="gst_no" name="gst_no" type="text" required readonly value="{{ $data ? $data->gst_no : '' }}" disabled>
                                @error('gst_no')
                                  <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label" for="support_email">Support Email id *</label>
                                <input class="form-control" id="support_email" type="email" name="support_email" disabled value="{{ $data ? $data->support_email : '' }}" required>
                                @error('support_email')
                                  <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label" for="support_phone">Support Phone No. *</label>
                                <input class="form-control" id="support_phone" type="text" name="support_phone" required disabled value="{{ $data ? $data->support_phone : '' }}">
                                @error('support_phone')
                                  <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                          
                            <div class="col-lg-3">
                                <label class="form-label" for="state">State *</label>
                                <select class="form-control" id="state" name="state_id" required disabled>
                                  <option value="">Select state</option>
                                  @foreach($states as $state)
                                    <option value="{{ $state->id }}" {{ $data ? ($data->state_id == $state->id ? 'selected' : '') : ''}}>{{ $state->state_name }}</option>
                                  @endforeach
                                </select>
                                @error('state_id')
                                  <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label" for="city">City *</label>
                                <input class="form-control" id="city" name="city" type="text" required value="{{ $data ? $data->city : '' }}" disabled>
                                @error('city')
                                  <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label" for="pincode">Pin Code *</label>
                                <input class="form-control" id="pincode" name="pincode" type="text" required value="{{ $data ? $data->pincode : '' }}" disabled>
                                @error('pincode')
                                  <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label" for="address1">Address1 *</label>
                                <input class="form-control" id="address1" name="address1" type="text" required value="{{ $data ? $data->address1 : '' }}" disabled>
                                @error('address1')
                                  <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label" for="address2">Address2 *</label>
                                <input class="form-control" id="address2" name="address2" type="text" readonly value="{{ $data ? $data->address2 : '' }}" disabled>
                                @error('address2')
                                  <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-4">
                                <div class="pt-2 mt-15">
                                  <a href="{{ url()->previous() }}" class="btn btn-danger">Cancel</a>
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

