@extends('common-app/master')
@section('title', 'User Profile')
@section('content')
<header class="py-4">
  <div class="container-fluid py-2">
    <h1 class="h3 fw-normal mb-0"></h1>
  </div>
</header>

<section class="pb-5"> 
    <div class="container-fluid">
        <div class="row"> 
            <div class="col-lg-4"></div>
                <div class="col-lg-4">                    
                    <div class="card">                        
                        <div class="card-body">
                            <div class="col-lg-12 p-4 text-center">
                                <h4> UPDATE PASSWORD</h4>
                            </div>
                            <form class=" g-3 align-items-center" action="{{ route('password.update') }}" method="POST">
                                @csrf
                                <div class="row  p-2">
                                    <div class="col-lg-12">
                                        <label class="form-label" for="new_password">New Password</label>
                                        <input type="password" class="form-control" id="new_password" name="password" required>
                                        @error('password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row  p-2">
                                    <div class="col-lg-12">
                                        <label class="form-label" for="confirm_password">Confirm Password</label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                        @error('confirm_password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row p-2">
                                    <div class="col-lg-6 pt-4">
                                        <button class="btn btn-primary" type="submit">Submit</button>
                                        <a href="{{ route('master.index') }}" class="btn btn-danger">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <div class="col-lg-4"></div>
        </div>         
    </div>
</section>
@endsection

