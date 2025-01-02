<?php include __DIR__ . '/../layouts/dash_header.php'; ?>
<body class="g-sidenav-show bg-gray-100">
    <?php include __DIR__ . '/../layouts/dash_sidenav.php'; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <?php include __DIR__ . '/../layouts/dash_nav.php'; ?>

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <!-- Add Category Card -->
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>Add New Category</h6>
                        </div>
                        <div class="card-body">
                            <form action="/admin/store_category" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <div class="form-group">
                                    <label for="name">Category Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="image">Category Image</label>
                                    <input type="file" class="form-control" id="image" name="image">
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Add Category</button>
                            </form>
                        </div>
                    </div>

                    <!-- Categories List Card -->
                    <div class="card">
                        <div class="card-header pb-0">
                            <h6>Manage Categories</h6>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Category</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Image</th>
                                            <th class="text-secondary opacity-7">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($categories as $category): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm"><?php echo htmlspecialchars($category['name']); ?></h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php if (!empty($category['image'])): ?>
                                                        <img src="<?php echo htmlspecialchars($category['image']); ?>" alt="Category Image" class="avatar avatar-sm me-3">
                                                        <form action="/admin/delete_category_image" method="POST" class="d-inline">
                                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                            <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                                                            <button type="submit" class="btn btn-link text-danger text-gradient px-3 mb-0">
                                                                <i class="far fa-trash-alt me-2"></i>Delete Image
                                                            </button>
                                                        </form>
                                                    <?php else: ?>
                                                        <form action="/admin/upload_category_image" method="POST" enctype="multipart/form-data" class="d-inline">
                                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                            <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                                                            <input type="file" name="image" class="form-control form-control-sm d-inline-block w-auto">
                                                            <button type="submit" class="btn btn-link text-dark px-3 mb-0">
                                                                <i class="fas fa-upload me-2"></i>Upload
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <form action="/admin/delete_category" method="POST" class="d-inline">
                                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                        <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                                                        <button type="submit" class="btn btn-link text-danger text-gradient px-3 mb-0">
                                                            <i class="far fa-trash-alt me-2"></i>Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include __DIR__ . '/../layouts/dash_footer.php'; ?>
</body>
</html>