<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title')</title>
     @include('include.header')
        <style>
        .color-white{
            color:white!important;
        }
       
        .disabled-link {
          pointer-events: none!important;
          cursor: not-allowed;
        }

    </style>
  </head>
  <body>
  <!-- Side Navbar -->
    <nav class="side-navbar shrink">
      <div class="side-navbar-inner">
        <!-- Sidebar Header    -->
        <div class="sidebar-header d-flex align-items-center justify-content-center p-3 mb-3">
          <!-- User Info-->
          <div class="sidenav-header-inner text-center">
              <a  href="{{ route('master.index')}}">
              <img class="img-fluid rounded-circle avatar mb-3" src="{{ Auth::user()->company->company_logo }}" alt="{{ Auth::user()->company->name }}">
              </a>
            <h2 class="h5 text-white text-uppercase mb-0">{{ Auth::user()->company->name }}</h2>
            <p class="text-sm mb-0 text-muted">Omneelab</p>
          </div>
          <!-- Small Brand information, appears on minimized sidebar-->
          <a class="brand-small text-center"  href="{{ route('master.index')}}">
            <p class="h1 m-0">MW</p></a>
        </div>
        <!-- Sidebar Navigation Menus-->
        <!--<span class="text-uppercase text-gray-500 text-sm fw-bold letter-spacing-0 mx-lg-2 heading">Main</span>-->
        <ul class="list-unstyled">     
        @if(Auth::user()->user_type == 'isCompany')
            <li class="sidebar-item"><a class="sidebar-link"  href="{{ url('/get-client',(\Crypt::encrypt(Auth::user()->company->id))) }}"> 
              <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2">
                <use xlink:href="#real-estate-1"> </use>
              </svg>Clients </a>
            </li>
        @elseif(Auth::user()->user_type != 'isCompany')
            <li class="sidebar-item"><a class="sidebar-link" href="{{ url('/get-warehouses',(\Crypt::encrypt(Auth::user()->client->id))) }}"> 
              <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2">
                <use xlink:href="#real-estate-1"> </use>
              </svg>Warehouses </a>
            </li>
            
        @endif
        
        
        
        @php
            $style = '';
            if(session()->has('client') && !session()->has('warehouse')){
                
                $style = 'disabled-link';
            }
            else if(session()->has('client') && session()->has('warehouse')){
                
                $style = '';             
            }
            else
            {
                 $style = '';  
            }
        @endphp
            <li class="sidebar-item {{ $style }}"><a class="sidebar-link" href="/billings"> 
              <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2">
                <use xlink:href="#survey-1"> </use>
              </svg>Billing </a>
            </li>
            @if(Auth::user()->user_type != 'isCompany')
                <li class="sidebar-item {{ $style }}"><a class="sidebar-link" href="/ndrlist"> 
                  <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2">
                    <use xlink:href="#sales-up-1"> </use>
                  </svg>NDR </a>
                </li>
            @endif
            <li class="sidebar-item {{ $style }}"><a class="sidebar-link" href="/reports"> 
              <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2">
                <use xlink:href="#portfolio-grid-1"> </use>
              </svg>Reports </a></li>
            <!--<li class="sidebar-item {{ $style }}"><a class="sidebar-link" href="#exampledropdownDropdown" data-bs-toggle="collapse"> -->
            <!--    <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2">-->
            <!--        <use xlink:href="#browser-window-1"> </use>-->
            <!--    </svg>Reports </a>-->
            <!--    <ul class="collapse list-unstyled " id="exampledropdownDropdown">-->
            <!--      <li><a class="sidebar-link" href="{{ route('order.all') }}">Order Report</a></li>-->
                  <!--<li><a class="sidebar-link" href="#">Page</a></li>-->
                  <!--<li><a class="sidebar-link" href="#">Page</a></li>-->
            <!--    </ul>-->
            <!--</li>  -->
          <li class="sidebar-item">
		  <a class="sidebar-link {{ $style }}" @if(Auth::user()->user_type == 'isCompany') href="/company-settings" @elseif(Auth::user()->user_type == 'isClient') href="/client-setting" @else href="/warehouse-setting" @endif> 
              <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2">
                <use xlink:href="#browser-window-1"> </use>
              </svg>Settings 
			</a>
          </li>
          <!--<li class="sidebar-item"><a class="sidebar-link" href="#"> -->
          <!--    <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2">-->
          <!--      <use xlink:href="#disable-1"> </use>-->
          <!--    </svg>Supports </a></li>-->
          <!--<li class="sidebar-item"><a class="sidebar-link" href="#!"> 
              <svg class="svg-icon svg-icon-xs svg-icon-heavy me-2">
                <use xlink:href="#imac-screen-1"> </use>
              </svg>Demo
              <div class="badge bg-warning">6 New</div></a>
	      </li>-->
        </ul>
		<!--<span class="text-uppercase text-gray-500 text-sm fw-bold letter-spacing-0 mx-lg-2 heading">Second menu</span>
        <ul class="list-unstyled py-4">
          <li class="sidebar-item"> <a class="sidebar-link" href="#!"> 
              <svg class="svg-icon svg-icon-xs svg-icon-heavy me-2">
                <use xlink:href="#chart-1"> </use>
              </svg>Demo</a></li>
          <li class="sidebar-item"> <a class="sidebar-link" href="">
              <svg class="svg-icon svg-icon-xs svg-icon-heavy me-2">
                <use xlink:href="#imac-screen-1"> </use>
              </svg>Demo
              <div class="badge bg-info">Special</div></a></li>
          <li class="sidebar-item"> <a class="sidebar-link" href=""> 
              <svg class="svg-icon svg-icon-xs svg-icon-heavy me-2">
                <use xlink:href="#quality-1"> </use>
              </svg>Demo</a></li>
          <li class="sidebar-item"> <a class="sidebar-link" href=""> 
              <svg class="svg-icon svg-icon-xs svg-icon-heavy me-2">
                <use xlink:href="#security-shield-1"> </use>
              </svg>Demo</a></li>
        </ul>-->
      </div>
    </nav>
    <div class="page active">
    <!-- navbar-->
        <header class="header mb-5 pb-3">
            <nav class="nav navbar fixed-top active">
            <div class="container-fluid">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <div class="d-flex align-items-center">
                        <a class="menu-btn d-flex align-items-center justify-content-center p-2 bg-gray-900" id="toggle-btn" href="#">
                            <svg class="svg-icon svg-icon-sm svg-icon-heavy text-white">
                                <use xlink:href="#menu-1"> </use>
                            </svg>
                        </a>
                        <div class="navbar-brand ms-2" >
                            <div class="brand-text d-none d-md-inline-block text-uppercase letter-spacing-0">
                                <span class="text-white fw-normal text-xs"> 
                                    @if(Auth::user()->user_type == 'isCompany')
                                        <span title="company">{{ Auth::user()->company->name.' >' }}</span>
                                        @if(session('client'))<a href="{{ url('/get-warehouses',(\Crypt::encrypt(session('client.id')))) }}" title="client" class="color-white">{{ session('client.name').' >'}}</a>@endif
                                        @if(session('warehouse'))<a href="/orders" title="warehouse" class="color-white">{{ session('warehouse.warehouse_name').' >' }}</a> @endif
                                    @elseif(Auth::user()->user_type == 'isClient')
                                        <span title="client" class="color-white">{{ Auth::user()->client->name.' >' }}</span>
                                        @if(session('warehouse'))
                                           <a href="/orders" title="warehouse" class="color-white">{{ session('warehouse.warehouse_name').' >'}}</a>
                                        @else 
                                            <a href="/orders" span title="warehouse" class="color-white">{{ Auth::user()->warehouse->warehouse_name ?? ''.' >' }}</a>
                                        @endif
                                    @else
                                        <span title="company">{{ Auth::user()->warehouse->warehouse_name ?? '' .' >' }}</span>
                                    @endif
                                </span> 
                                <strong class="text-primary text-sm">@yield('title')</strong>
                            </div>
                        </div>
                    </div>
                    <ul class="nav-menu mb-0 list-unstyled d-flex flex-md-row align-items-md-center">
                        <!-- Notifications dropdown-->
                        <li class="nav-item dropdown"> <a class="nav-link text-white position-relative" id="notifications" rel="nofollow" data-bs-target="#" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-wallet"></i>
                            <span class="badge bg-warning"><i class="fas fa-rupee-sign"></i> 1000000</span></a>
                          <ul class="dropdown-menu dropdown-menu-end mt-sm-3 shadow-sm" aria-labelledby="notifications">
                            <li><a class="dropdown-item py-3" href="#!"> 
                                <div class="d-flex">
                                  <div class="icon icon-sm bg-blue">
                                    <svg class="svg-icon svg-icon-xs svg-icon-heavy">
                                      <use xlink:href="#envelope-1"> </use>
                                    </svg>
                                  </div>
                                  <div class="ms-3"><span class="h6 d-block fw-normal mb-1 text-xs text-gray-600">You have 6 new messages </span><small class="small text-gray-600">4 minutes ago</small></div>
                                </div></a></li>
                            <li><a class="dropdown-item py-3" href="#!"> 
                                <div class="d-flex">
                                  <div class="icon icon-sm bg-green">
                                    <svg class="svg-icon svg-icon-xs svg-icon-heavy">
                                      <use xlink:href="#chats-1"> </use>
                                    </svg>
                                  </div>
                                  <div class="ms-3"><span class="h6 d-block fw-normal mb-1 text-xs text-gray-600">New 2 WhatsApp messages</span><small class="small text-gray-600">4 minutes ago</small></div>
                                </div></a></li>
                            <li><a class="dropdown-item py-3" href="#!"> 
                                <div class="d-flex">
                                  <div class="icon icon-sm bg-orange">
                                    <svg class="svg-icon svg-icon-xs svg-icon-heavy">
                                      <use xlink:href="#checked-window-1"> </use>
                                    </svg>
                                  </div>
                                  <div class="ms-3"><span class="h6 d-block fw-normal mb-1 text-xs text-gray-600">Server Rebooted</span><small class="small text-gray-600">8 minutes ago</small></div>
                                </div></a></li>
                            <li><a class="dropdown-item py-3" href="#!"> 
                                <div class="d-flex">
                                  <div class="icon icon-sm bg-green">
                                    <svg class="svg-icon svg-icon-xs svg-icon-heavy">
                                      <use xlink:href="#chats-1"> </use>
                                    </svg>
                                  </div>
                                  <div class="ms-3"><span class="h6 d-block fw-normal mb-1 text-xs text-gray-600">New 2 WhatsApp messages</span><small class="small text-gray-600">10 minutes ago</small></div>
                                </div></a></li>
                            <li><a class="dropdown-item all-notifications text-center" href="#!"> <strong class="text-xs text-gray-600">view all notifications                                            </strong></a></li>
                          </ul>
                        </li>
                        <!-- Messages dropdown-->
                        <li class="nav-item dropdown"> <a class="nav-link text-white position-relative" id="messages" rel="nofollow" data-bs-target="#" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <svg class="svg-icon svg-icon-xs svg-icon-heavy">
                              <use xlink:href="#envelope-1"> </use>
                            </svg><span class="badge bg-info">10</span></a>
                          <ul class="dropdown-menu dropdown-menu-end mt-sm-3 shadow-sm" aria-labelledby="messages">
                            <li><a class="dropdown-item d-flex py-3" href="#!"> <img class="img-fluid rounded-circle flex-shrink-0 avatar shadow-0" src="{{url('img/avatar-1.jpg') }}" alt="..." width="45">
                                <div class="ms-3"><span class="h6 d-block fw-normal mb-1 text-sm text-gray-600">Jason Doe</span><small class="small text-gray-600"> Sent You Message</small>
                                  <p class="mb-0 small text-gray-600">3 days ago at 7:58 pm - 10.06.2014</p>
                                </div></a></li>
                            <li><a class="dropdown-item d-flex py-3" href="#!"> <img class="img-fluid rounded-circle flex-shrink-0 avatar shadow-0" src="{{url('img/avatar-2.jpg') }}" alt="..." width="45">
                                <div class="ms-3"><span class="h6 d-block fw-normal mb-1 text-sm text-gray-600">Jason Doe</span><small class="small text-gray-600"> Sent You Message</small>
                                  <p class="mb-0 small text-gray-600">3 days ago at 7:58 pm - 10.06.2014</p>
                                </div></a></li>
                            <li><a class="dropdown-item d-flex py-3" href="#!"> <img class="img-fluid rounded-circle flex-shrink-0 avatar shadow-0" src="{{url('img/avatar-3.jpg') }}" alt="..." width="45">
                                <div class="ms-3"><span class="h6 d-block fw-normal mb-1 text-sm text-gray-600">Jason Doe</span><small class="small text-gray-600"> Sent You Message</small>
                                  <p class="mb-0 small text-gray-600">3 days ago at 7:58 pm - 10.06.2014</p>
                                </div></a></li>
                            <li><a class="dropdown-item text-center" href="#!"> <strong class="text-xs text-gray-600">Read all messages</strong></a></li>
                          </ul>
                        </li>
                        <!-- Languages dropdown    -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white text-sm" id="languages" rel="nofollow" data-bs-target="#" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <!--<img src="{{url('img/flags/16/GB.png') }}" alt="English"><span class="d-none d-sm-inline-block ms-2">English</span>-->
                            </a>
                          <ul class="dropdown-menu dropdown-menu-end mt-sm-3 shadow-sm" aria-labelledby="languages">
                            <!--<li><a class="dropdown-item" rel="nofollow" href="#!"> <img class="me-2" src="{{url('img/flags/16/DE.png') }}" alt="English"><span>German</span></a></li>
                            <li><a class="dropdown-item" rel="nofollow" href="#!"> <img class="me-2" src="{{url('img/flags/16/FR.png') }}" alt="English"><span>French </span></a></li>-->
                          </ul>
                        </li>
                        <!-- Log out-->
                        <li class="nav-item dropdown"> 
                            <a class="nav-link text-white position-relative" id="messages" rel="nofollow" data-bs-target="#" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              {{ Auth::user()->username }}
                              <svg class="svg-icon svg-icon-xs svg-icon-heavy">
                                  <use xlink:href="#security-1"> </use>
                              </svg>
                            </a>
                          <ul class="dropdown-menu dropdown-menu-end mt-sm-3 shadow-sm" aria-labelledby="messages">
                            <li>
                              <a class="dropdown-item d-flex py-x" href="{{ route('user.profile') }}"> 
                                <svg class="svg-icon svg-icon-sm svg-icon-heavy mt-1 flex-shrink-0">
                                  <use xlink:href="#user-1"> </use>
                                </svg>
                                <div class="ms-3"><span class="h6 d-block fw-normal mb-1 text-sm text-gray-600">User Profile</span></div>
                              </a>
                            </li>
                            <li>
                              <a class="dropdown-item d-flex py-x" href="{{ route('user.password') }}"> <img src="{{ url('icon/permission.png') }}" alt="permission" style="height: 20px!important">
                                <div class="ms-3"><span class="h6 d-block fw-normal mb-1 text-sm text-gray-600">Change Password</span></div>
                              </a>
                            </li>
                            <li>
                              @if(Auth::user()->user_type == 'isCompany')
                              <a class="dropdown-item d-flex py-x" href="{{ route('company.profile') }}">
                                <svg class="svg-icon svg-icon-xs svg-icon-heavy me-2">
                                  <use xlink:href="#imac-screen-1"> </use>
                                </svg>
                                <div class="ms-3"><span class="h6 d-block fw-normal mb-1 text-sm text-gray-600">                                 
                                    Company Detail                                
                                </span>
                                </div>
                              </a>
                              @endif
                              @if(Auth::user()->user_type == 'isClient')
                              <a class="dropdown-item d-flex py-x" href="{{ route('client.profile')}}">
                                <svg class="svg-icon svg-icon-xs svg-icon-heavy me-2">
                                  <use xlink:href="#imac-screen-1"> </use>
                                </svg>
                                <div class="ms-3"><span class="h6 d-block fw-normal mb-1 text-sm text-gray-600">
                                    Client Detail
                                </span>
                                </div>
                              </a>
                              @endif
                              
                            <li>
                              <a class="dropdown-item d-flex py-x" href="/logout">
                                <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2">
                                  <use xlink:href="#disable-1"> </use>
                                </svg>
                                <div class="ms-3"><span class="h6 d-block fw-normal mb-1 text-sm text-gray-600">Logout</span>
                                </div>
                              </a>
                            </li>
                          </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        </header>
    
    @yield('content')
    <!-- JavaScript files-->
    @include('include.footer')
  </body>
</html>