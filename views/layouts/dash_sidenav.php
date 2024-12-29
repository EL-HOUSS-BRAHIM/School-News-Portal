<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-transparent" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="/dashboard">
            <img src="<?php echo $app['constants']['ASSETS_URL']; ?>/img/logo-ct-dark.png" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold">Article Dashboard</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link <?php echo $currentPage == 'dashboard' ? 'active bg-gradient-primary' : ''; ?>" href="/dashboard">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-home <?php echo $currentPage == 'dashboard' ? 'text-white' : 'text-primary'; ?> text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>

            <!-- Articles -->
            <li class="nav-item">
                <a class="nav-link <?php echo $currentPage == 'articles' ? 'active bg-gradient-primary' : ''; ?>" href="/dashboard/articles">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-newspaper <?php echo $currentPage == 'articles' ? 'text-white' : 'text-warning'; ?> text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">My Articles</span>
                </a>
            </li>

            <!-- Admin Pages -->
            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                <!-- Users -->
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'users' ? 'active bg-gradient-primary' : ''; ?>" href="/admin/users">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-users <?php echo $currentPage == 'users' ? 'text-white' : 'text-info'; ?> text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Users</span>
                    </a>
                </li>

                <!-- Review Articles -->
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'review' ? 'active bg-gradient-primary' : ''; ?>" href="/admin/review">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-check <?php echo $currentPage == 'review' ? 'text-white' : 'text-success'; ?> text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Review Articles</span>
                    </a>
                </li>
            <?php endif; ?>
            <!-- New Article -->
            <li class="nav-item">
                <a class="nav-link <?php echo $currentPage == 'new' ? 'active bg-gradient-primary' : ''; ?>" href="/dashboard/article/new">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-plus <?php echo $currentPage == 'new' ? 'text-white' : 'text-success'; ?> text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">New Article</span>
                </a>
            </li>

            <!-- Analytics -->
            <li class="nav-item">
                <a class="nav-link <?php echo $currentPage == 'analytics' ? 'active bg-gradient-primary' : ''; ?>" href="/dashboard/analytics">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-chart-bar <?php echo $currentPage == 'analytics' ? 'text-white' : 'text-danger'; ?> text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Analytics</span>
                </a>
            </li>

            <!-- Profile -->
            <li class="nav-item">
                <a class="nav-link <?php echo $currentPage == 'profile' ? 'active bg-gradient-primary' : ''; ?>" href="/dashboard/profile">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-user <?php echo $currentPage == 'profile' ? 'text-white' : 'text-dark'; ?> text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Profile</span>
                </a>
            </li>

            <!-- Sign Out -->
            <li class="nav-item">
                <a class="nav-link" href="/logout">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-sign-out-alt text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Sign Out</span>
                </a>
            </li>
        </ul>
    </div>
</aside>