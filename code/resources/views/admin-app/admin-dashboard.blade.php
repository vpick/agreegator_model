@extends('admin-app/admin-master')
@section('title', 'System Dashboard')
@section('content')
<!-- Header Section-->
    <!--<header class="py-4">-->
    <!--  <div class="container-fluid py-2">-->
       
    <!--  </div>-->
    <!--</header>-->
      <!-- Statistics Section-->
      <section class="py-5">
        <div class="container-fluid">
          <div class="row align-items-stretch gy-4">
            <div class="col-lg-4">
              <!-- Income-->
              <div class="card text-center h-100 mb-0">
                <div class="card-body">
                  <svg class="svg-icon svg-icon-big svg-icon-light mb-4 text-muted">
                    <use xlink:href="#sales-up-1"> </use>
                  </svg>
                  <p class="text-gray-700 display-6">126,418</p>
                  <p class="text-primary h2 fw-bold">Active Module</p>
                  <p class="text-xs text-gray-600 mb-0">Visibility of all active modules</p>
                </div>
              </div>
            </div>
            
            <div class="col-lg-4">
              <!-- Income-->
              <div class="card text-center h-100 mb-0">
                <div class="card-body">
                  <svg class="svg-icon svg-icon-big svg-icon-light mb-4 text-muted">
                    <use xlink:href="#sales-up-1"> </use>
                  </svg>
                  <p class="text-gray-700 display-6">126,418</p>
                  <p class="text-primary h2 fw-bold">In-active Module</p>
                  <p class="text-xs text-gray-600 mb-0">Visibility of all in-active modules</p>
                </div>
              </div>
            </div>
            
            <div class="col-lg-4">
              <!-- User Actibity-->
              <div class="card h-100 mb-0">
                <div class="card-body">
                  <h2 class="h3 fw-normal mb-4">User Activity</h2>
                  <p class="display-6">210</p>
                  <h3 class="h4 fw-normal">Social Users</h3>
                  <div class="progress rounded-0 mb-3">
                    <div class="progress-bar progress-bar bg-primary" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <div class="d-flex justify-content-between">
                    <div class="text-start">
                      <p class="h5 fw-normal mb-2">Pages Visits</p>
                      <p class="fw-bold text-xl text-primary mb-0">230</p>
                    </div>
                    <div class="text-end">
                      <p class="h5 fw-normal mb-2">New Visits</p>
                      <p class="fw-bold text-xl text-primary mb-0">73.4%</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- Updates Section -->
      <section class="mb-5">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-4 col-md-12">
              <!-- Recent Updates Widget          -->
              <div class="card">
                <div class="card-header border-bottom">
                  <h2 class="h5 fw-normal mb-0"><a class="card-collapse-link text-dark d-block" data-bs-toggle="collapse" href="#updates-box" aria-expanded="true">News Updates</a></h2>
                </div>
                <div class="card-body p-0">
                  <div class="collapse show" id="updates-box" role="tabpanel">
                    <ul class="list-unstyled">
                      <!-- Item-->
                      <div class="p-3 d-flex justify-content-between">
                        <div class="d-flex"><i class="fas fa-rss text-gray-600"></i>
                          <div class="ms-3">
                            <h5 class="fw-normal text-gray-600 mb-1">Lorem ipsum dolor sit amet.</h5>
                            <p class="mb-0 text-xs text-gray-500">Lorem ipsum dolor sit amet, consectetur adipisicing elit sed.</p>
                          </div>
                        </div>
                        <div class="text-end ms-2"><strong class="d-block lh-1 h2 mb-0 text-gray-500 fw-bold">24</strong><small class="d-block lh-1 text-gray-500">Jan</small></div>
                      </div>
                      <!-- Item-->
                      <div class="p-3 d-flex justify-content-between bg-light">
                        <div class="d-flex"><i class="fas fa-rss text-gray-600"></i>
                          <div class="ms-3">
                            <h5 class="fw-normal text-gray-600 mb-1">Lorem ipsum dolor sit amet.</h5>
                            <p class="mb-0 text-xs text-gray-500">Lorem ipsum dolor sit amet, consectetur adipisicing elit sed.</p>
                          </div>
                        </div>
                        <div class="text-end ms-2"><strong class="d-block lh-1 h2 mb-0 text-gray-500 fw-bold">24</strong><small class="d-block lh-1 text-gray-500">Jan</small></div>
                      </div>
                      <!-- Item-->
                      <div class="p-3 d-flex justify-content-between">
                        <div class="d-flex"><i class="fas fa-rss text-gray-600"></i>
                          <div class="ms-3">
                            <h5 class="fw-normal text-gray-600 mb-1">Lorem ipsum dolor sit amet.</h5>
                            <p class="mb-0 text-xs text-gray-500">Lorem ipsum dolor sit amet, consectetur adipisicing elit sed.</p>
                          </div>
                        </div>
                        <div class="text-end ms-2"><strong class="d-block lh-1 h2 mb-0 text-gray-500 fw-bold">24</strong><small class="d-block lh-1 text-gray-500">Jan</small></div>
                      </div>
                      <!-- Item-->
                      <div class="p-3 d-flex justify-content-between bg-light">
                        <div class="d-flex"><i class="fas fa-rss text-gray-600"></i>
                          <div class="ms-3">
                            <h5 class="fw-normal text-gray-600 mb-1">Lorem ipsum dolor sit amet.</h5>
                            <p class="mb-0 text-xs text-gray-500">Lorem ipsum dolor sit amet, consectetur adipisicing elit sed.</p>
                          </div>
                        </div>
                        <div class="text-end ms-2"><strong class="d-block lh-1 h2 mb-0 text-gray-500 fw-bold">24</strong><small class="d-block lh-1 text-gray-500">Jan</small></div>
                      </div>
                      <!-- Item-->
                      <div class="p-3 d-flex justify-content-between">
                        <div class="d-flex"><i class="fas fa-rss text-gray-600"></i>
                          <div class="ms-3">
                            <h5 class="fw-normal text-gray-600 mb-1">Lorem ipsum dolor sit amet.</h5>
                            <p class="mb-0 text-xs text-gray-500">Lorem ipsum dolor sit amet, consectetur adipisicing elit sed.</p>
                          </div>
                        </div>
                        <div class="text-end ms-2"><strong class="d-block lh-1 h2 mb-0 text-gray-500 fw-bold">24</strong><small class="d-block lh-1 text-gray-500">Jan</small></div>
                      </div>
                      <!-- Item-->
                      <div class="p-3 d-flex justify-content-between bg-light">
                        <div class="d-flex"><i class="fas fa-rss text-gray-600"></i>
                          <div class="ms-3">
                            <h5 class="fw-normal text-gray-600 mb-1">Lorem ipsum dolor sit amet.</h5>
                            <p class="mb-0 text-xs text-gray-500">Lorem ipsum dolor sit amet, consectetur adipisicing elit sed.</p>
                          </div>
                        </div>
                        <div class="text-end ms-2"><strong class="d-block lh-1 h2 mb-0 text-gray-500 fw-bold">24</strong><small class="d-block lh-1 text-gray-500">Jan</small></div>
                      </div>
                      <!-- Item-->
                      <div class="p-3 d-flex justify-content-between">
                        <div class="d-flex"><i class="fas fa-rss text-gray-600"></i>
                          <div class="ms-3">
                            <h5 class="fw-normal text-gray-600 mb-1">Lorem ipsum dolor sit amet.</h5>
                            <p class="mb-0 text-xs text-gray-500">Lorem ipsum dolor sit amet, consectetur adipisicing elit sed.</p>
                          </div>
                        </div>
                        <div class="text-end ms-2"><strong class="d-block lh-1 h2 mb-0 text-gray-500 fw-bold">24</strong><small class="d-block lh-1 text-gray-500">Jan</small></div>
                      </div>
                    </ul>
                  </div>
                </div>
              </div>
              <!-- Recent Updates Widget End-->
            </div>
            <div class="col-lg-4 col-md-6">
              <!-- Daily Feed Widget-->
              <div class="card">
                <div class="card-header border-bottom">
                  <h2 class="h5 fw-normal mb-0"><a class="card-collapse-link text-dark d-block" data-bs-toggle="collapse" href="#feeds-box" aria-expanded="true">Your daily Feeds
                      <div class="badge bg-primary position-absolute end-0 me-4">10 messages</div></a></h2>
                </div>
                <div class="card-body-0">
                  <div class="collapse show" id="feeds-box" role="tabpanel">
                    <!-- Item-->
                    <div class="p-3 border-bottom border-gray-200">
                      <div class="d-flex justify-content-between">
                        <div class="d-flex"><a class="flex-shrink-0" href="#!">
						<img class="img-fluid rounded-circle" src="img/avatar-5.jpg" alt="person" width="50">
						</a>
                          <div class="ms-3">
                            <h5 class="fw-normal">Aria Smith</h5>
                            <p class="mb-0 text-xs text-gray-600 lh-1">Posted a new blog</p><small class="text-gray-600 fw-light">Today 5:60 pm - 12.06.2014</small>
                          </div>
                        </div>
                        <div class="text-end"><small class="text-gray-500">5min ago</small></div>
                      </div>
                    </div>
                    <!-- Item-->
                    <div class="p-3 border-bottom border-gray-200">
                      <div class="d-flex justify-content-between">
                        <div class="d-flex"><a class="flex-shrink-0" href="#!"><img class="img-fluid rounded-circle" src="img/avatar-2.jpg" alt="person" width="50"></a>
                          <div class="ms-3">
                            <h5 class="fw-normal">Frank Williams</h5>
                            <p class="mb-0 text-xs text-gray-600 lh-1">Posted a new blog</p><small class="text-gray-600 fw-light">Today 5:60 pm - 12.06.2014</small>
                            <div class="d-flex"><a class="btn btn-xs btn-dark py-1 m-1" href="#!"><i class="fas fa-thumbs-up me-1"></i>Like</a><a class="btn btn-xs btn-dark py-1 m-1" href="#!"><i class="fas fa-heart me-1"> </i>Love  </a></div>
                          </div>
                        </div>
                        <div class="text-end"><small class="text-gray-500">5min ago</small></div>
                      </div>
                    </div>
                    <!-- Item-->
                    <div class="p-3 border-bottom border-gray-200">
                      <div class="d-flex justify-content-between">
                        <div class="d-flex"><a class="flex-shrink-0" href="#!"><img class="img-fluid rounded-circle" src="img/avatar-3.jpg" alt="person" width="50"></a>
                          <div class="ms-3">
                            <h5 class="fw-normal">Ashley Wood</h5>
                            <p class="mb-0 text-xs text-gray-600 lh-1">Posted a new blog</p><small class="text-gray-600 fw-light">Today 5:60 pm - 12.06.2014</small>
                          </div>
                        </div>
                        <div class="text-end"><small class="text-gray-500">5min ago</small></div>
                      </div>
                    </div>
                    <!-- Item-->
                    <div class="p-3 border-bottom border-gray-200">
                      <div class="d-flex justify-content-between">
                        <div class="d-flex"><a class="flex-shrink-0" href="#!"><img class="img-fluid rounded-circle" src="img/avatar-1.jpg" alt="person" width="50"></a>
                          <div class="ms-3">
                            <h5 class="fw-normal">Jason Doe</h5>
                            <p class="mb-0 text-xs text-gray-600 lh-1">Posted a new blog</p><small class="text-gray-600 fw-light">Today 5:60 pm - 12.06.2014</small>
                          </div>
                        </div>
                        <div class="text-end"><small class="text-gray-500">5min ago</small></div>
                      </div>
                      <div class="mt-3 ms-5 ps-3">
                        <div class="bg-light p-3 shadow-sm"><small class="text-gray-600">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s. Over the years.</small></div>
                        <div class="text-end mt-2"><a class="btn btn-xs btn-dark py-1" href="#!"><i class="fas fa-thumbs-up me-1"></i>Like</a></div>
                      </div>
                    </div>
                    <!-- Item-->
                    <div class="p-3">
                      <div class="d-flex justify-content-between">
                        <div class="d-flex"><a class="flex-shrink-0" href="#!"><img class="img-fluid rounded-circle" src="img/avatar-6.jpg" alt="person" width="50"></a>
                          <div class="ms-3">
                            <h5 class="fw-normal">Sam Martinez</h5>
                            <p class="mb-0 text-xs text-gray-600 lh-1">Posted a new blog</p><small class="text-gray-600 fw-light">Today 5:60 pm - 12.06.2014</small>
                          </div>
                        </div>
                        <div class="text-end"><small class="text-gray-500">5min ago</small></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Daily Feed Widget End-->
            </div>
            <div class="col-lg-4 col-md-6">
              <!-- Recent Activities Widget      -->
              <div class="card">
                <div class="card-header border-bottom">
                  <h2 class="h5 fw-normal mb-0"><a class="card-collapse-link text-dark d-block" data-bs-toggle="collapse" href="#activities-box" aria-expanded="true">Recent Activities</a></h2>
                </div>
                <div class="card-body p-0">
                  <div class="collapse show" id="activities-box" role="tabpanel">
                    <div class="row g-0 border-bottom border-gray-200">
                      <div class="col-sm-4 col-3 text-end">
                        <ul class="list-inline mb-0">
                          <li>
                            <div class="d-inline-block p-2 bg-light"><i class="far fa-clock fa-fw"></i></div>
                          </li>
                          <li class="me-2"><span class="small text-gray-500">6:00 am</span></li>
                          <li class="me-2"><span class="small text-info lh-1 d-block">6 hours ago</span></li>
                        </ul>
                      </div>
                      <div class="col-sm-8 col-9 border-start border-gray-200 p-3">
                        <h5 class="fw-normal">Meeting</h5>
                        <p class="small mb-0 text-gray-600">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.         </p>
                      </div>
                    </div>
                    <div class="row g-0 border-bottom border-gray-200">
                      <div class="col-sm-4 col-3 text-end">
                        <ul class="list-inline mb-0">
                          <li>
                            <div class="d-inline-block p-2 bg-light"><i class="far fa-clock fa-fw"></i></div>
                          </li>
                          <li class="me-2"><span class="small text-gray-500">6:00 am</span></li>
                          <li class="me-2"><span class="small text-info lh-1 d-block">6 hours ago</span></li>
                        </ul>
                      </div>
                      <div class="col-sm-8 col-9 border-start border-gray-200 p-3">
                        <h5 class="fw-normal">Meeting</h5>
                        <p class="small mb-0 text-gray-600">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.         </p>
                      </div>
                    </div>
                    <div class="row g-0 border-bottom border-gray-200">
                      <div class="col-sm-4 col-3 text-end">
                        <ul class="list-inline mb-0">
                          <li>
                            <div class="d-inline-block p-2 bg-light"><i class="far fa-clock fa-fw"></i></div>
                          </li>
                          <li class="me-2"><span class="small text-gray-500">6:00 am</span></li>
                          <li class="me-2"><span class="small text-info lh-1 d-block">6 hours ago</span></li>
                        </ul>
                      </div>
                      <div class="col-sm-8 col-9 border-start border-gray-200 p-3">
                        <h5 class="fw-normal">Meeting</h5>
                        <p class="small mb-0 text-gray-600">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.         </p>
                      </div>
                    </div>
                    <div class="row g-0 border-bottom border-gray-200">
                      <div class="col-sm-4 col-3 text-end">
                        <ul class="list-inline mb-0">
                          <li>
                            <div class="d-inline-block p-2 bg-light"><i class="far fa-clock fa-fw"></i></div>
                          </li>
                          <li class="me-2"><span class="small text-gray-500">6:00 am</span></li>
                          <li class="me-2"><span class="small text-info lh-1 d-block">6 hours ago</span></li>
                        </ul>
                      </div>
                      <div class="col-sm-8 col-9 border-start border-gray-200 p-3">
                        <h5 class="fw-normal">Meeting</h5>
                        <p class="small mb-0 text-gray-600">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.         </p>
                      </div>
                    </div>
                    <div class="row g-0">
                      <div class="col-sm-4 col-3 text-end">
                        <ul class="list-inline mb-0">
                          <li>
                            <div class="d-inline-block p-2 bg-light"><i class="far fa-clock fa-fw"></i></div>
                          </li>
                          <li class="me-2"><span class="small text-gray-500">6:00 am</span></li>
                          <li class="me-2"><span class="small text-info lh-1 d-block">6 hours ago</span></li>
                        </ul>
                      </div>
                      <div class="col-sm-8 col-9 border-start border-gray-200 p-3">
                        <h5 class="fw-normal">Meeting</h5>
                        <p class="small mb-0 text-gray-600">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.         </p>
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