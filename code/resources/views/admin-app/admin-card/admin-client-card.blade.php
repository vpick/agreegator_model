@extends('admin-app/admin-master')
@section('title', 'client Card')
@section('content')
@php
    if(session()->has('company') && !session()->has('client') && !session()->has('warehouse')){
         echo '<script>window.location.href = "/company_list";</script>';
    }
    else if(session()->has('company') && session()->has('client') && !session()->has('warehouse')){
         echo '<script>window.location.href = "/company_list";</script>';
    }
    else{
         
    }
@endphp
<header class="py-4">
  <div class="container-fluid py-2">
    <h1 class="h3 fw-normal mb-0">Client details</h1>
  </div>
</header>
<!-- Forms Section-->
<section class="pb-5"> 
  <div class="container-fluid">
    <div class="row">
      <!-- Basic Form-->
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
          @if (!empty($data))
              <form  class='row g-3 align-items-center'  method="post" action="{{ route('app-client.update', $data->id) }}">
              @method('PUT')
            @else
              <form class='row g-3 align-items-center'  method="post" action="{{ route('app-client.store') }}">
            @endif
            @csrf
            <div class="col-lg-3">
                <label class="form-label" for="company">Company *</label>
                <select class="form-control" id="company" required name="company_id" {{  $data ? ($data->company_id ? 'disabled' : '') : '' }}>
                  <option value="">Select</option>
                  @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ $data ? ($data->company_id == $company->id ? 'selected' : '') : (old('company_id') == $company->id ? 'selected':'') }}>{{ $company->name }}</option>
                  @endforeach
                </select>
                @error('company_id')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
            
              <div class="col-lg-3">
                <label class="form-label" for="client_code">Client Code *</label>
                <input class="form-control" value="{{ $data ? $data->client_code : old('client_code') }}" id="client_code" name="client_code" type="text" {{ $data ? ($data->client_code ? 'disabled' : '') : '' }}>
                @error('client_code')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              
              <div class="col-lg-3">
                <label class="form-label" for="name">Client Name *</label>
                <input class="form-control" id="name" name="name" value="{{ $data ? $data->name : old('name') }}" type="text" required>
                @error('name')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              
              <div class="col-lg-3">
                <label class="form-label" for="email">Email id *</label>
                <input class="form-control" id="email" type="email" name="email" value="{{ $data ? $data->email : old('email') }}" required>
                @error('email')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-lg-3">
                <label class="form-label" for="phone">Contact No. *</label>
                <input class="form-control" id="phone" type="text" name="phone" required value="{{ $data ? $data->phone : old('phone') }}">
                @error('phone')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-lg-3">
                <label class="form-label" for="country">Country. *</label>
                <input class="form-control" id="country" type="text" name="country" value="India" required value="{{ $data ? $data->country : old('country') }}" readonly>
                @error('country')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-lg-3">
                <label class="form-label" for="state">State *</label>
                <select class="form-control" id="state" name="state_id" required>
                  <option value="">Select state</option>
                  @foreach($states as $state)
                    <option value="{{ $state->id }}" {{ $data ? ($data->state_id == $state->id ? 'selected' : '') : (old('state_id') == $state->id ? 'selected':'')}}>{{ $state->state_name }}</option>
                  @endforeach
                </select>
                @error('state_id')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-lg-3">
                <label class="form-label" for="city">City *</label>
                <input class="form-control" id="city" name="city" type="text" required value="{{ $data ? $data->city : old('city') }}">
                @error('city')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-lg-3">
                <label class="form-label" for="pincode">Pin Code *</label>
                <input class="form-control" id="pincode" name="pincode" type="text" required value="{{ $data ? $data->pincode : old('pincode') }}">
                @error('pincode')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-lg-3">
                <label class="form-label" for="billing_address">Billing Address *</label>
                <input class="form-control" id="billing_address" name="billing_address" type="text" required value="{{ $data ? $data->billing_address : old('billing_address') }}">
                @error('billing_address')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-lg-3">
                <label class="form-label" for="address2">Address2 *</label>
                <input class="form-control" id="address2" name="address2" type="text" required value="{{ $data ? $data->address2 : old('address2') }}">
                @error('address2')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-lg-4">
                <div class="pt-2">
                  <button class="btn btn-primary" type="submit">Submit</button>
                  <a href="{{ route('app-client.index') }}" class="btn btn-danger">Cancel</a>
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