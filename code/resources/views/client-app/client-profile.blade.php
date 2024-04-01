@extends('common-app/master')
@section('title', 'Client Detail')
@section('content')
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
              <form  class='row g-3 align-items-center'  method="post" action="{{ route('client.profile.update', $data->id) }}">
            @csrf
            <div class="col-lg-3">
                <label class="form-label" for="company">Company *</label>
                <input class="form-control" id="company_id" type="text" name="company_id" value="{{ Auth::user()->company->name }}" required readonly {{  $data ? ($data->company_id ? 'disabled' : '') : '' }}>
                @error('company_id')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
                
              </div>
           
              <div class="col-lg-3">
                <label class="form-label" for="client_code">Client Code *</label>
                <input class="form-control" value="{{ $data->client_code ?? '' }}" type="text" name="client_code" id="client_code" {{  $data ? ($data->client_code ? 'disabled' : '') : '' }}>
                @error('client_code')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
             
              <div class="col-lg-3">
                <label class="form-label" for="name">Client Name *</label>
                <input class="form-control" id="name" name="name" value="{{ $data ? $data->name : '' }}" type="text" required>
                @error('name')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              
              <div class="col-lg-3">
                <label class="form-label" for="email">Email id *</label>
                <input class="form-control" id="email" type="email" name="email" value="{{ $data ? $data->email : '' }}" required>
                @error('email')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-lg-3">
                <label class="form-label" for="phone">Contact No. *</label>
                <input class="form-control" id="phone" type="text" name="phone" required value="{{ $data ? $data->phone : '' }}">
                @error('phone')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-lg-3">
                <label class="form-label" for="country">Country. *</label>
                <input class="form-control" id="country" type="text" name="country" value="India" required value="{{ $data ? $data->country : '' }}" readonly>
                @error('country')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-lg-3">
                <label class="form-label" for="state">State *</label>
                <select class="form-control" id="state" name="state_id" required>
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
                <input class="form-control" id="city" name="city" type="text" required value="{{ $data ? $data->city : '' }}">
                @error('city')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-lg-3">
                <label class="form-label" for="pincode">Pin Code *</label>
                <input class="form-control" id="pincode" name="pincode" type="text" required value="{{ $data ? $data->pincode : '' }}">
                @error('pincode')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-lg-3">
                <label class="form-label" for="billing_address">Billing Address *</label>
                <input class="form-control" id="billing_address" name="billing_address" type="text" required value="{{ $data ? $data->billing_address : '' }}">
                @error('billing_address')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-lg-3">
                <label class="form-label" for="address2">Address2 </label>
                <input class="form-control" id="address2" name="address2" type="text" required value="{{ $data ? $data->address2 : '' }}">
                @error('address2')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-lg-4">
                <div class="pt-2">
                  <button class="btn btn-primary" type="submit">Submit</button>
                  <a href="{{ route('master.index') }}" class="btn btn-danger">Cancel</a>
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