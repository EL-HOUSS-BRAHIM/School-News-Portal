<?php include __DIR__ . '/../layouts/dash_header.php'; ?>

<body class="g-sidenav-show bg-gray-100">
    <?php include __DIR__ . '/../layouts/dash_sidenav.php'; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <!-- Navbar -->
        <?php include __DIR__ . '/../layouts/dash_nav.php'; ?>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>Add User</h6>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <form action="/admin/store_user" method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <select class="form-control" id="role" name="role" required>
                                        <option value="writer">Writer</option>
                                        <option value="editor">Editor</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Add User</button>
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
</body>
</html>