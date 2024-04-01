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
              <a href="/admin-dashboard">
                <img class="img-fluid rounded-circle avatar mb-3" src="{{ url('img/logistic_app.jpeg') }}" alt="MW-app">
              </a>
            <h2 class="h5 text-white text-uppercase mb-0">MW App</h2>
            <p class="text-sm mb-0 text-muted">Omneelab</p>
          </div>
          <!-- Small Brand information, appears on minimized sidebar-->
          <a class="brand-small text-center" href="/admin-dashboard">
            <p class="h1 m-0">WM</p></a>
        </div>
        <!-- Sidebar Navigation Menus--><span class="text-uppercase text-gray-500 text-sm fw-bold letter-spacing-0 mx-lg-2 heading">Main</span>
        
        <ul class="list-unstyled">      
           
            <li class="sidebar-item"><a class="sidebar-link" href="/company_list"> 
              <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2">
                <use xlink:href="#real-estate-1"> </use>
              </svg>Companies</a></li>
               
            @php
            $style = '';
            if(session()->has('company') && !session()->has('client') && !session()->has('warehouse')){
                //echo "if";
                $style = 'disabled-link';
            }
            else if(session()->has('company') && session()->has('client') && !session()->has('warehouse')){
                //echo "elseif1";
                $style = 'disabled-link';
            }
            else if(session()->has('company') && session()->has('client') && session()->has('warehouse')){
                //echo "elseif2";
                $style = '';             
            }
            else{
                //echo "else";
                 $style = '';  
            }
            @endphp
                <li class="sidebar-item {{ $style }}"><a class="sidebar-link" href="/app-chanels" > 
                  <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2">
                    <use xlink:href="#real-estate-1"> </use>
                  </svg>Our Chanels 
                  </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ $style }}" href="/app-partners" > 
                      <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2">
                        <use xlink:href="#survey-1"> </use>
                      </svg>Our Partner 
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ $style }}" href="{{ route('app-erp.index') }}"> 
                      <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2">
                        <use xlink:href="#survey-1"> </use>
                      </svg>WMS
                    </a>
                </li>
                <li class="sidebar-item">
    		      <a class="sidebar-link {{ $style }}" href="/app-settings"> 
                      <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2">
                        <use xlink:href="#browser-window-1"> </use>
                      </svg>Settings 
    			    </a>
                </li>
                <li class="sidebar-item">
    			  <a class="sidebar-link {{ $style }}" href="/system-master"> 
    				  <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2">
    					<use xlink:href="#browser-window-1"> </use>
    				  </svg>Masters 
    				</a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ $style }}" href="#" > 
                        <svg class="svg-icon svg-icon-sm svg-icon-heavy me-2">
                        <use xlink:href="#disable-1"> </use>
                        </svg>Supports 
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ $style }}" href="#!" > 
                      <svg class="svg-icon svg-icon-xs svg-icon-heavy me-2">
                        <use xlink:href="#imac-screen-1"> </use>
                      </svg>Demo
                      <div class="badge bg-warning">6 New</div>
                    </a>
    	        </li>
            
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
                            <div class="navbar-brand ms-2">
                                <div class="brand-text d-none d-md-inline-block text-uppercase letter-spacing-0">
                                    <span class="text-white fw-normal text-xs">
                                        <a href="{{ url('/company_list') }}" title="system" class="color-white">{{ Auth::user()->role->role }} </a>
                                        @if(session('company'))
                                            <a href="{{ url('/client-list',(\Crypt::encrypt(session('company.id')))) }}" title="company" class="color-white">{{ ' > '.session('company.name').' >'}}</a>
                                      
                                        @endif
                                        @if(session('client'))
                                            <a href="{{ url('/warehouse-list',(\Crypt::encrypt(session('client.id')))) }}" title="client" class="color-white">{{ session('client.name').' >'}}</a>
                                       
                                        @endif
                                        @if(session('warehouse'))
                                            <a href="{{ route('app-warehouse.edit',(\Crypt::encrypt(session('warehouse.id')))) }}" title="warehouse" class="color-white">{{ session('warehouse.warehouse_name').' >' }}</a> 
                                        
                                        @endif
                                        <strong class="text-primary text-sm">@yield('title')</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <ul class="nav-menu mb-0 list-unstyled d-flex flex-md-row align-items-md-center">
                            <!-- Notifications dropdown-->
                            <li class="nav-item dropdown"> <a class="nav-link text-white position-relative" id="notifications" rel="nofollow" data-bs-target="#" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg class="svg-icon svg-icon-xs svg-icon-heavy">
                                  <use xlink:href="#chart-1"> </use>
                                </svg><span class="badge bg-warning">12</span></a>
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
                                <li><a class="dropdown-item all-notifications text-center" href="#!"> <strong class="text-xs text-gray-600">view all notifications</strong></a></li>
                              </ul>
                            </li>
                            <!-- Messages dropdown-->
                            <li class="nav-item dropdown"> <a class="nav-link text-white position-relative" id="messages" rel="nofollow" data-bs-target="#" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg class="svg-icon svg-icon-xs svg-icon-heavy">
                                  <use xlink:href="#envelope-1"> </use>
                                </svg><span class="badge bg-info">10</span></a>
                              <ul class="dropdown-menu dropdown-menu-end mt-sm-3 shadow-sm" aria-labelledby="messages">
                                <li>
                                    <a class="dropdown-item d-flex py-3" href="#!"> <img class="img-fluid rounded-circle flex-shrink-0 avatar shadow-0" src="{{ url('img/avatar-1.jpg') }}" alt="..." width="45">
                                    <div class="ms-3"><span class="h6 d-block fw-normal mb-1 text-sm text-gray-600">Jason Doe</span><small class="small text-gray-600"> Sent You Message</small>
                                      <p class="mb-0 small text-gray-600">3 days ago at 7:58 pm - 10.06.2014</p>
                                    </div>
                                    </a>
                                </li>
                                <li><a class="dropdown-item d-flex py-3" href="#!"> <img class="img-fluid rounded-circle flex-shrink-0 avatar shadow-0" src="{{ url('img/avatar-2.jpg') }}" alt="..." width="45">
                                    <div class="ms-3"><span class="h6 d-block fw-normal mb-1 text-sm text-gray-600">Jason Doe</span><small class="small text-gray-600"> Sent You Message</small>
                                      <p class="mb-0 small text-gray-600">3 days ago at 7:58 pm - 10.06.2014</p>
                                    </div></a></li>
                                <li><a class="dropdown-item d-flex py-3" href="#!"> <img class="img-fluid rounded-circle flex-shrink-0 avatar shadow-0" src="{{ url('img/avatar-3.jpg') }}" alt="..." width="45">
                                    <div class="ms-3"><span class="h6 d-block fw-normal mb-1 text-sm text-gray-600">Jason Doe</span><small class="small text-gray-600"> Sent You Message</small>
                                      <p class="mb-0 small text-gray-600">3 days ago at 7:58 pm - 10.06.2014</p>
                                    </div></a></li>
                                <li><a class="dropdown-item text-center" href="#!"> <strong class="text-xs text-gray-600">Read all messages</strong></a></li>
                              </ul>
                            </li>
                            <!-- Languages dropdown    -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-white text-sm" id="languages" rel="nofollow" data-bs-target="#" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <!--<img src="img/flags/16/GB.png" alt="English"><span class="d-none d-sm-inline-block ms-2">English</span>-->
                                </a>
                              <ul class="dropdown-menu dropdown-menu-end mt-sm-3 shadow-sm" aria-labelledby="languages">
                                <!--<li><a class="dropdown-item" rel="nofollow" href="#!"> <img class="me-2" src="{{ url('img/flags/16/DE.png') }}" alt="English"><span>German</span></a></li>
                                <li><a class="dropdown-item" rel="nofollow" href="#!"> <img class="me-2" src="{{ url('img/flags/16/FR.png') }}" alt="English"><span>French</span></a></li>-->
                              </ul>
                            </li>
                            <!-- Log out-->
                            <li class="nav-item dropdown"> 
                                <a class="nav-link text-white position-relative" id="messages" rel="nofollow" data-bs-target="#" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ Auth::user()->username}}
                                    <svg class="svg-icon svg-icon-xs svg-icon-heavy">
                                      <use xlink:href="#security-1"> </use>
                                    </svg>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end mt-sm-3 shadow-sm" aria-labelledby="messages">
                                    <li>
                                        <a class="dropdown-item d-flex py-x" href="{{ route('app.user.profile') }}">
                                            <svg class="svg-icon svg-icon-sm svg-icon-heavy mt-1 flex-shrink-0">
                                              <use xlink:href="#user-1"> </use>
                                            </svg>
                                        <div class="ms-3"><span class="h6 d-block fw-normal mb-1 text-sm text-gray-600">User Profile</span>
                                         
                                        </div></a>
                                    </li>
                                    <li><a class="dropdown-item d-flex py-x" href="{{ route('app.user.password') }}"><img src="{{ url('icon/permission.png') }}" alt="permission" style="height: 20px!important">
                                        <div class="ms-3"><span class="h6 d-block fw-normal mb-1 text-sm text-gray-600">Reset Password</span>
                                          
                                        </div></a></li>
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
      
     @include('include.footer')
  </body>
</html>