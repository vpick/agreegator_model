@extends('common-app/master')
@section('title', 'User Profile')
@section('content')
<header class="py-4">
  <div class="container-fluid py-2">
    <h1 class="h3 fw-normal mb-0">Update Profile</h1>
  </div>
</header>
<!-- Counts Section -->

<section class="pb-5"> 
    <div class="container-fluid">
        <div class="row">
            
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <form class="row g-3 align-items-center" action="{{ route('profile.update') }}" method="POST">
                                @csrf
                                    <div class="col-lg-4">
                                        <label class="form-label" for="username">User Name</label>
                                        <input type="text" class="form-control" id="username" value="{{ Auth::user()->username }}" disabled>
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="form-label" for="user_code">User Code</label>
                                        <input type="text" class="form-control" id="user_code" value="{{ Auth::user()->user_code }}" disabled>
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="form-label" for="company">Company</label>
                                        <input type="text" class="form-control" id="company" value="{{ Auth::user()->company->name ?? ''}}" disabled>
                                    </div>
                                    @if(Auth::user()->user_type == 'isClient')
                                    <div class="col-lg-4">
                                        <label class="form-label" for="default_Client">Default Client</label>
                                        <input type="text" class="form-control" id="default_Client" value="{{ Auth::user()->client->name ??''}}" disabled>
                                    </div>
                                    
                                    <div class="col-lg-4">
                                        <label class="form-label" for="default_warehouse">Default Warehouse</label>
                                        <input type="text" class="form-control" id="default_warehouse" value="{{ Auth::user()->warehouse->warehouse_name ?? ''}}" disabled>
                                    </div>
                                    @endif
                                    <div class="col-lg-4">
                                        <label class="form-label" for="user_email">User Email</label>
                                        <input type="text" class="form-control" id="user_email" name="email" value="{{ Auth::user()->email ?? ''}}">
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="form-label" for="user_mobile">User Mobile</label>
                                        <input type="text" class="form-control" id="user_mobile" name="mobile" value="{{ Auth::user()->mobile ?? ''}}">
                                    </div>
                                    <div class="col-lg-4"></div>
                                    
                                    <div class="col-lg-3 pt-4">
                                        <button class="btn btn-primary" type="submit">Submit</button>
                                        <a href="{{ route('master.index') }}" class="btn btn-danger">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    
                
            </div>
        </div>         
    </div>
</section>
@endsection

