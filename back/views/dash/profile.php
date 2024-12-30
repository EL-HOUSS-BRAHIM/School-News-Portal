<?php include __DIR__ . '/../layouts/dash_header.php'; ?>

<body class="g-sidenav-show bg-gray-100">
    <?php include __DIR__ . '/../layouts/dash_sidenav.php'; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <!-- Navbar -->
        <?php include __DIR__ . '/../layouts/dash_nav.php'; ?>
        <!-- End Navbar -->
         

        <div class="container-fluid py-4">
            <div class="row">
                <!-- Profile Info Card -->
                <div class="col-12 col-xl-4">
                    <div class="card h-100">
                        <div class="card-header pb-0 p-3">
                            <div class="row">
                                <div class="col-md-8 d-flex align-items-center">
                                    <h6 class="mb-0">Profile Information</h6>
                                </div>
                                <div class="col-md-4 text-end">
                                    <a href="javascript:;" id="editProfile">
                                        <i class="fas fa-user-edit text-secondary text-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Profile"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="text-center">
                                <img src="<?php echo htmlspecialchars($userData['avatar']); ?>" alt="profile_image" class="avatar avatar-xl rounded-circle me-2">
                                <form id="avatarForm" class="mt-3" style="display: none;">
                                    <input type="file" name="avatar" class="form-control" accept="image/*">
                                    <button type="submit" class="btn btn-primary btn-sm mt-2">Update Avatar</button>
                                </form>
                            </div>
                            <hr class="horizontal gray-light my-4">
                            <ul class="list-group">
                            
<li class="list-group-item border-0 ps-0 pt-0 text-sm">
    <strong class="text-dark">Username:</strong> &nbsp; 
    <?php echo htmlspecialchars($userData['username'] ?? 'Unknown'); ?>
</li>
<li class="list-group-item border-0 ps-0 text-sm">
    <strong class="text-dark">Email:</strong> &nbsp; 
    <?php echo htmlspecialchars($userData['email'] ?? 'No email'); ?>
</li>
<li class="list-group-item border-0 ps-0 text-sm">
    <strong class="text-dark">Role:</strong> &nbsp; 
    <?php echo htmlspecialchars($userData['role'] ?? 'user'); ?>
</li>
<li class="list-group-item border-0 ps-0 pb-0 text-sm">
    <strong class="text-dark">Joined:</strong> &nbsp; 
    <?php echo date('M d, Y', strtotime($userData['joined_date'] ?? 'now')); ?>
</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Statistics Card -->
                <div class="col-12 col-xl-4">
                    <div class="card h-100">
                        <div class="card-header pb-0 p-3">
                            <h6 class="mb-0">Platform Statistics</h6>
                        </div>
                        <div class="card-body p-3">
                            <ul class="list-group">
                                <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                                    <div class="icon icon-shape icon-sm me-3 bg-gradient-primary shadow text-center">
                                        <i class="ni ni-collection text-white opacity-10"></i>
                                    </div>
                                    <div class="d-flex align-items-start flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">Total Articles</h6>
                                        <p class="mb-0 text-xs"><?php echo number_format($stats['total_articles']); ?> published articles</p>
                                    </div>
                                </li>
                                <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                                    <div class="icon icon-shape icon-sm me-3 bg-gradient-success shadow text-center">
                                        <i class="ni ni-eye text-white opacity-10"></i>
                                    </div>
                                    <div class="d-flex align-items-start flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">Total Views</h6>
                                        <p class="mb-0 text-xs"><?php echo number_format($stats['total_views']); ?> article views</p>
                                    </div>
                                </li>
                                <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                                    <div class="icon icon-shape icon-sm me-3 bg-gradient-danger shadow text-center">
                                        <i class="ni ni-like-2 text-white opacity-10"></i>
                                    </div>
                                    <div class="d-flex align-items-start flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">Total Likes</h6>
                                        <p class="mb-0 text-xs"><?php echo number_format($stats['total_likes']); ?> article likes</p>
                                    </div>
                                </li>
                                <li class="list-group-item border-0 d-flex align-items-center px-0">
                                    <div class="icon icon-shape icon-sm me-3 bg-gradient-info shadow text-center">
                                        <i class="ni ni-chart-bar-32 text-white opacity-10"></i>
                                    </div>
                                    <div class="d-flex align-items-start flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">Avg. Engagement</h6>
                                        <p class="mb-0 text-xs"><?php echo number_format($stats['avg_engagement'], 2); ?>% engagement rate</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Password Change Card -->
                <div class="col-12 col-xl-4">
                    <div class="card h-100">
                        <div class="card-header pb-0 p-3">
                            <h6 class="mb-0">Change Password</h6>
                        </div>
                        <div class="card-body p-3">
                            <form id="passwordForm">
                                <div class="form-group">
                                    <label class="form-control-label">Current Password</label>
                                    <input type="password" class="form-control" name="current_password" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">New Password</label>
                                    <input type="password" class="form-control" name="new_password" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Confirm New Password</label>
                                    <input type="password" class="form-control" name="confirm_password" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm mt-3">Update Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer Start -->
    <footer class="footer mt-auto py-4 px-sm-3 px-md-5" style="background: #111111;">
        <p class="m-0 text-center" style="color: white;">
            © <?php echo date('Y'); ?> 
            <a href="#" style="color: orange;">
                <?php echo htmlspecialchars($app['app_name'] ?? 'School News Portal'); ?>
            </a>. 
            Tous droits réservés.
            <br>
            Développé par <a href="https://github.com/EL-HOUSS-BRAHIM/" target="_blank" style="color: orange;">Brahim Elhouss</a>.
        </p>
    </footer>
    <!-- Footer End -->
    </main>

    <?php include __DIR__ . '/../layouts/dash_footer.php'; ?>

    <script>
        // Avatar upload handling
        document.getElementById('editProfile').addEventListener('click', function() {
            document.getElementById('avatarForm').style.display = 'block';
        });

        // Password update handling
        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Add password update AJAX logic here
        });
    </script>
</body>
</html>