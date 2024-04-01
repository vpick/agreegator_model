<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>LogisticsApp</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <!-- Google fonts - Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <!-- Choices CSS-->
    <link rel="stylesheet" href="{{url('vendor/choices.js/public/assets/styles/choices.min.css') }}">
    <!-- Custom Scrollbar-->
    <link rel="stylesheet" href="{{url('vendor/overlayscrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="{{url('css/style.default.css') }}" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="{{url('css/custom.css')}}">
    <!-- Favicon-->
    <link rel="shortcut icon" href="{{url('img/favicon.ico') }}">
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
	<link href="{{url('node_modules/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">

    <!-- Add SweetAlert2 scripts -->
    <script src="{{url('node_modules/sweetalert2/dist/sweetalert2.min.js') }}"></script>
  </head>
  <body>
     <!-- Header Section-->
	 
    <div class="login-page d-flex align-items-center bg-black-100">
      <div class="container mb-3">
        <div class="row">
          <div class="col-md-12" >
            @if(\Request::old('success'))
            <div class="alert alert-success" > {{\Request::old('success')}} </div>
            @elseif(\Request::old('error'))
            <div class="alert alert-danger" > {{\Request::old('error')}} </div>
            @endif
          </div>
          <div class="col-md-6 mx-auto">
            <div class="card">
              <div class="card-body p-5">
                <header class="text-center mb-5">
                    <img class="img-fluid rounded-circle avatar mb-3" src="{{ url('img/logistic_app.jpeg') }}" alt="logistic-app">
                  <h1 class="text-xxl text-black-400 text-uppercase">MW <strong class="text-primary">App</strong></h1>
                  <!--<p class="text-gray-500 fw-light">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.</p>-->
                </header>
                <form method="POST" action="{{ route('login') }}">
                  @csrf
                  <div class="row">
                    <div class="col-lg-7 mx-auto">
                      <div class="input-material-group mb-3">
                        <input class="input-material" id="username" type="text" name="username" autocomplete="off" required>
                        <label class="label-material" for="username">Username</label>
                        @error('username')
                          <span class="text-danger">{{ $message }}</span>
                        @enderror
                      </div>
                      <div class="input-material-group mb-4">
                        <input class="input-material" id="password" type="password" name="password" required >
                        <label class="label-material" for="password">Password</label>
                        @error('password')
                          <span class="text-danger">{{ $message }}</span>
                        @enderror
                      </div>
                    </div>
                    <div class="col-12 text-center">       
                      <button class="btn btn-primary mb-3" type="submit">Login</button><br><a class="text-xs text-paleBlue" href="#!">Forgot Password?  </a>
					            <!--<br><span class="text-xs mb-0 text-gray-500">Do not have an account?  </span><a class="text-xs text-paleBlue ms-1" href="register.html"> Signup</a>-->
                      <!-- This should be submit button but I replaced it with <a> for demo purpose-->
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="text-center position-absolute bottom-0 start-0 w-100 z-index-20">
        <p class="text-gray-500">Powered by <a class="external" href="#">Omneelab</a>
          <!-- Please do not remove the backlink to us unless you support further theme's development at https://bootstrapious.com/donate. It is part of the license conditions. Thank you for understanding :)                      -->
        </p>
      </div>
    </div>
    <!-- JavaScript files-->
    <script src="{{url('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{url('vendor/chart.js/Chart.min.js') }}"></script>
    <script src="{{url('vendor/just-validate/js/just-validate.min.js') }}"></script>
    <script src="{{url('vendor/choices.js/public/assets/scripts/choices.min.js') }}"></script>
    <script src="{{url('vendor/overlayscrollbars/js/OverlayScrollbars.min.js') }}"></script>
    <!-- Main File-->
    <script src="{{url('js/front.js') }}"></script>
    <script>
      // ------------------------------------------------------- //
      //   Inject SVG Sprite - 
      //   see more here 
      //   https://css-tricks.com/ajaxing-svg-sprite/
      // ------------------------------------------------------ //
      function injectSvgSprite(path) {
      
          var ajax = new XMLHttpRequest();
          ajax.open("GET", path, true);
          ajax.send();
          ajax.onload = function(e) {
          var div = document.createElement("div");
          div.className = 'd-none';
          div.innerHTML = ajax.responseText;
          document.body.insertBefore(div, document.body.childNodes[0]);
          }
      }
      // this is set to BootstrapTemple website as you cannot 
      // inject local SVG sprite (using only 'icons/orion-svg-sprite.svg' path)
      // while using file:// protocol
      // pls don't forget to change to your domain :)
      injectSvgSprite('https://bootstraptemple.com/files/icons/orion-svg-sprite.svg'); 
      
      
    </script>
    @if(session('status'))
    		<script type="text/javascript">
    			Swal.fire({
    				title: 'Success!',
    				text: "{{ session('status') }}",
    				timer: 2000,
    				icon: 'success'
    			});
    		</script>
    	@endif
    	@if(session('error'))
            <script type="text/javascript">
    			Swal.fire({
    				title: 'Failed!',
    				text: "{{ session('error') }}",
    				timer: 5000,
    				icon: 'error'
    			});
    		</script>
        @endif
    <script> 
          if({!! json_encode(Auth::check()) !!}) {
              var user = {!! json_encode(Auth::user()) !!};
              if (user) {
                  if (user.user_type === 'isSystem') {
                      window.location.href = '{{ route("master-app.index") }}';
                  } else {
                      window.location.href = '{{ route("master.index") }}';
                  }
              } else {
                  window.location.href = '{{ route("login") }}';
                  window.stop();
              }
          } else {
              window.location.href = '{{ url("login") }}';
              window.stop();
          }
        </script>
    <!-- FontAwesome CSS - loading as last, so it doesn't block rendering-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  </body>
</html>