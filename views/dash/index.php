<?php include '../views/layouts/dash_header.php'; ?>
<body class="g-sidenav-show  bg-gray-100">
    <?php include '../views/layouts/dash_sidenav.php'; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
      <!-- Navbar -->
      <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
        <div class="container-fluid py-1 px-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
              <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
              <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Dashboard</li>
            </ol>
            <h6 class="font-weight-bolder mb-0">Dashboard</h6>
          </nav>
          <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <div class="ms-md-auto pe-md-3 d-flex align-items-center">
              <div class="input-group">
                <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                <input type="text" class="form-control" placeholder="Type here..." onfocus="focused(this)" onfocusout="defocused(this)">
              </div>
            </div>
            <ul class="navbar-nav  justify-content-end">

              <li class="nav-item d-flex align-items-center">
                <a href="javascript:;" class="nav-link text-body font-weight-bold px-0">
                  <i class="fa fa-user me-sm-1"></i>
                  <span class="d-sm-inline d-none">Sign In</span>
                </a>
              </li>
              <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                  <div class="sidenav-toggler-inner">
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                  </div>
                </a>
              </li>
              <li class="nav-item px-3 d-flex align-items-center">
                <a href="javascript:;" class="nav-link text-body p-0">
                  <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
                </a>
              </li>
              <li class="nav-item dropdown pe-2 d-flex align-items-center">
                <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fa fa-bell cursor-pointer"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                  <li class="mb-2">
                    <a class="dropdown-item border-radius-md" href="javascript:;">
                      <div class="d-flex py-1">
                        <div class="my-auto">
                          <img src="../assets/img/team-2.jpg" class="avatar avatar-sm  me-3 ">
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="text-sm font-weight-normal mb-1">
                            <span class="font-weight-bold">New message</span>
                            from Laur
                          </h6>
                          <p class="text-xs text-secondary mb-0 ">
                            <i class="fa fa-clock me-1"></i>
                            13 minutes ago
                          </p>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li class="mb-2">
                    <a class="dropdown-item border-radius-md" href="javascript:;">
                      <div class="d-flex py-1">
                        <div class="my-auto">
                          <img src="../assets/img/small-logos/logo-spotify.svg" class="avatar avatar-sm bg-gradient-dark  me-3 ">
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="text-sm font-weight-normal mb-1">
                            <span class="font-weight-bold">New album</span> by
                            Travis Scott
                          </h6>
                          <p class="text-xs text-secondary mb-0 ">
                            <i class="fa fa-clock me-1"></i>
                            1 day
                          </p>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item border-radius-md" href="javascript:;">
                      <div class="d-flex py-1">
                        <div class="avatar avatar-sm bg-gradient-secondary  me-3  my-auto">
                          <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>credit-card</title>
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                              <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                <g transform="translate(1716.000000, 291.000000)">
                                  <g transform="translate(453.000000, 454.000000)">
                                    <path class="color-background" d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z" opacity="0.593633743"></path>
                                    <path class="color-background" d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z"></path>
                                  </g>
                                </g>
                              </g>
                            </g>
                          </svg>
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="text-sm font-weight-normal mb-1">
                            Payment successfully completed
                          </h6>
                          <p class="text-xs text-secondary mb-0 ">
                            <i class="fa fa-clock me-1"></i>
                            2 days
                          </p>
                        </div>
                      </div>
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <!-- End Navbar -->
      <!-- Keep the header/sidebar structure but modify the main content -->

      <div class="container-fluid py-4">
        <!-- Statistics Cards -->
        <div class="row">
          <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card">
              <div class="card-body p-3">
                <div class="row">
                  <div class="col-8">
                    <div class="numbers">
                      <p class="text-sm mb-0 text-capitalize font-weight-bold">Total
                        Articles</p>
                      <h5 class="font-weight-bolder mb-0">
                        24
                      </h5>
                    </div>
                  </div>
                  <div class="col-4 text-end">
                    <div class="icon icon-shape bg-gradient-primary shadow text-center">
                      <i class="ni ni-collection text-lg opacity-10" aria-hidden="true"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card">
              <div class="card-body p-3">
                <div class="row">
                  <div class="col-8">
                    <div class="numbers">
                      <p class="text-sm mb-0 text-capitalize font-weight-bold">Total
                        Views</p>
                      <h5 class="font-weight-bolder mb-0">
                        2,300
                      </h5>
                    </div>
                  </div>
                  <div class="col-4 text-end">
                    <div class="icon icon-shape bg-gradient-success shadow text-center">
                      <i class="ni ni-eye text-lg opacity-10" aria-hidden="true"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card">
              <div class="card-body p-3">
                <div class="row">
                  <div class="col-8">
                    <div class="numbers">
                      <p class="text-sm mb-0 text-capitalize font-weight-bold">Total
                        Likes</p>
                      <h5 class="font-weight-bolder mb-0">
                        940
                      </h5>
                    </div>
                  </div>
                  <div class="col-4 text-end">
                    <div class="icon icon-shape bg-gradient-danger shadow text-center">
                      <i class="ni ni-like-2 text-lg opacity-10" aria-hidden="true"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card">
              <div class="card-body p-3">
                <div class="row">
                  <div class="col-8">
                    <div class="numbers">
                      <p class="text-sm mb-0 text-capitalize font-weight-bold">Comments</p>
                      <h5 class="font-weight-bolder mb-0">
                        43
                      </h5>
                    </div>
                  </div>
                  <div class="col-4 text-end">
                    <div class="icon icon-shape bg-gradient-info shadow text-center">
                      <i class="ni ni-chat-round text-lg opacity-10" aria-hidden="true"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Article Management Table -->
        <div class="row">
          <div class="col-12">
            <div class="card mb-4">
              <div class="card-header pb-0">
                <div class="row">
                  <div class="col-6">
                    <h6>My Articles</h6>
                  </div>
                  <div class="col-6 text-end">
                    <button class="btn btn-primary btn-sm">New Article</button>
                  </div>
                </div>
              </div>
              <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                  <table class="table align-items-center mb-0">
                    <thead>
                      <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Article</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Views</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Likes</th>
                        <th class="text-secondary opacity-7">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>
                          <div class="d-flex px-2 py-1">
                            <div class="d-flex flex-column justify-content-center">
                              <h6 class="mb-0 text-sm">Getting Started with Web
                                Development</h6>
                              <p class="text-xs text-secondary mb-0">Published
                                on: 22 Dec 2023</p>
                            </div>
                          </div>
                        </td>
                        <td>
                          <span class="badge badge-sm bg-gradient-success">Published</span>
                        </td>
                        <td class="align-middle text-center text-sm">
                          <span class="text-secondary text-xs font-weight-bold">1,480</span>
                        </td>
                        <td class="align-middle text-center">
                          <span class="text-secondary text-xs font-weight-bold">351</span>
                        </td>
                        <td class="align-middle">
                          <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit article">
                            Edit
                          </a>
                        </td>
                      </tr>
                      <!-- Add more article rows as needed -->
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Performance Chart -->
        <div class="row mt-4">
          <div class="col-lg-7 mb-lg-0 mb-4">
            <div class="card z-index-2">
              <div class="card-header pb-0">
                <h6>Article Performance</h6>
                <p class="text-sm">
                  <i class="fa fa-arrow-up text-success"></i>
                  <span class="font-weight-bold">15% more</span> views this
                  month
                </p>
              </div>
              <div class="card-body p-3">
                <div class="chart">
                  <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
                </div>
              </div>
            </div>
          </div>

          <!-- Recent Activity -->
          <div class="col-lg-5">
            <div class="card">
              <div class="card-header pb-0">
                <h6>Recent Activity</h6>
              </div>
              <div class="card-body p-3">
                <div class="timeline timeline-one-side">
                  <div class="timeline-block mb-3">
                    <span class="timeline-step">
                      <i class="ni ni-bell-55 text-success"></i>
                    </span>
                    <div class="timeline-content">
                      <h6 class="text-dark text-sm font-weight-bold mb-0">New
                        comment on "Getting Started..."</h6>
                      <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">22
                        DEC 7:20 PM</p>
                    </div>
                  </div>
                  <div class="timeline-block mb-3">
                    <span class="timeline-step">
                      <i class="ni ni-like-2 text-primary"></i>
                    </span>
                    <div class="timeline-content">
                      <h6 class="text-dark text-sm font-weight-bold mb-0">Article
                        reached 1000 views</h6>
                      <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">21
                        DEC 11 PM</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

<?php include '../views/layouts/dash_footer.php'; ?>