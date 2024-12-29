<?php include '../views/layouts/dash_header.php'; ?>

<body class="g-sidenav-show bg-gray-100">
    <?php include '../views/layouts/dash_sidenav.php'; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Articles</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">Manage Articles</h6>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <!-- Search -->
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                        <div class="input-group">
                            <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" placeholder="Type here..." onfocus="focused(this)" onfocusout="defocused(this)">
                        </div>
                    </div>
                    
                    <!-- User Navigation -->
                    <ul class="navbar-nav justify-content-end">
                        <li class="nav-item d-flex align-items-center">
                            <a href="/dashboard/profile" class="nav-link text-body font-weight-bold px-0">
                                <i class="fa fa-user me-sm-1"></i>
                                <span class="d-sm-inline d-none"><?php echo htmlspecialchars($userData['username']); ?></span>
                            </a>
                        </li>
                        
                        <!-- Mobile Nav Toggle -->
                        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                                <div class="sidenav-toggler-inner">
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                </div>
                            </a>
                        </li>
                        
                        <!-- Settings -->
                        <li class="nav-item px-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body p-0">
                                <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
                            </a>
                        </li>
                        
                        <!-- Notifications -->
                        <li class="nav-item dropdown pe-2 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-bell cursor-pointer"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                                <li class="mb-2">
                                    <a class="dropdown-item border-radius-md" href="#">
                                        <div class="d-flex py-1">
                                            <div class="my-auto">
                                                <img src="../assets/img/team-2.jpg" class="avatar avatar-sm me-3">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="text-sm font-weight-normal mb-1">
                                                    <span class="font-weight-bold">New Comment</span>
                                                    Someone commented on your article
                                                </h6>
                                                <p class="text-xs text-secondary mb-0">
                                                    <i class="fa fa-clock me-1"></i>
                                                    13 minutes ago
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

        <div class="container-fluid py-4">
            <!-- Filters and Search -->
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <h6 class="mb-0">Article Management</h6>
                                </div>
                                <div class="col-6 text-end">
                                    <a href="/dashboard/article/new" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i> New Article
                                    </a>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <select class="form-select" aria-label="Filter by status" name="status_filter">
                                        <option value="">All Status</option>
                                        <?php foreach(Article::getAllStatuses() as $value => $label): ?>
                                            <option value="<?php echo $value; ?>" <?php echo isset($_GET['status']) && $_GET['status'] === $value ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($label); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" aria-label="Filter by category">
                                        <option selected>All Categories</option>
                                        <?php if (isset($categories) && is_array($categories)): ?>
                                            <?php foreach($categories as $category): ?>
                                                <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" class="form-control" placeholder="Search articles...">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Article</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Category</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Performance</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Created</th>
                                            <th class="text-secondary opacity-7">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($articles as $article): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div>
                                                            <img src="<?php echo $article['image'] ?? '../assets/img/default-article.jpg'; ?>" 
                                                                 class="avatar avatar-sm me-3" alt="article image">
                                                        </div>
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm"><?php echo htmlspecialchars($article['title']); ?></h6>
                                                            <p class="text-xs text-secondary mb-0">
                                                                <?php echo substr(htmlspecialchars($article['content']), 0, 50) . '...'; ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-sm bg-gradient-info">
                                                        <?php echo htmlspecialchars($article['category']); ?>
                                                    </span>
                                                </td>
                                                <td class="align-middle text-center text-sm">
                                                    <form action="/dashboard/article/status" method="POST" class="d-inline">
                                                        <input type="hidden" name="id" value="<?php echo $article['id']; ?>">
                                                        <select name="status" class="form-select form-select-sm d-inline w-auto" onchange="showSaveButton(this)">
    <?php foreach(Article::getAvailableStatuses($_SESSION['user_role']) as $value => $label): ?>
        <option value="<?php echo $value; ?>" 
                <?php echo $value == $article['status'] ? 'selected' : ''; ?>
                <?php echo !isset(Article::getAvailableStatuses($_SESSION['user_role'])[$value]) ? 'disabled' : ''; ?>>
            <?php echo htmlspecialchars($label); ?>
        </option>
    <?php endforeach; ?>
</select>
                                                        <div class="d-flex justify-content-center mt-2">
                                                            <button type="submit" class="btn btn-sm btn-primary save-button" style="display: none;">Save</button>
                                                        </div>
                                                    </form>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <div class="d-flex align-items-center justify-content-center">
                                                        <span class="me-2 text-xs font-weight-bold">
                                                            <i class="fas fa-eye text-primary"></i> <?php echo number_format($article['views']); ?>
                                                        </span>
                                                        <span class="text-xs font-weight-bold">
                                                            <i class="fas fa-heart text-danger"></i> <?php echo number_format($article['likes']); ?>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span class="text-secondary text-xs font-weight-bold">
                                                        <?php 
                                                        if (!empty($article['created_at'])) {
                                                            echo date('M d, Y', strtotime($article['created_at']));
                                                        } else {
                                                            echo 'N/A';
                                                        }
                                                        ?>
                                                    </span>
                                                </td>
                                                <td class="align-middle">
                                                    <div class="ms-auto">
                                                        <a href="/dashboard/article/edit?id=<?php echo $article['id']; ?>" 
                                                           class="btn btn-link text-dark px-3 mb-0">
                                                            <i class="fas fa-pencil-alt text-dark me-2"></i>Edit
                                                        </a>
                                                        <a href="/article?id=<?php echo $article['id']; ?>" 
                                                           class="btn btn-link text-primary px-3 mb-0" target="_blank">
                                                            <i class="fas fa-eye text-primary me-2"></i>View
                                                        </a>
                                                        <button onclick="deleteArticle(<?php echo $article['id']; ?>)" 
                                                                class="btn btn-link text-danger px-3 mb-0">
                                                            <i class="fas fa-trash text-danger me-2"></i>Delete
                                                        </button>
                                                    </div>
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

            <!-- Pagination -->
            <div class="row">
                <div class="col-12">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-end">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">
                                    <i class="fa fa-angle-left"></i>
                                    <span class="sr-only">Previous</span>
                                </a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">
                                    <i class="fa fa-angle-right"></i>
                                    <span class="sr-only">Next</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </main>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this article?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    function deleteArticle(id) {
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        const confirmBtn = document.getElementById('confirmDelete');
        
        confirmBtn.onclick = function() {
            window.location.href = `/dashboard/article/delete?id=${id}`;
        }
        
        modal.show();
    }

    function showSaveButton(selectElement) {
        const form = selectElement.closest('form');
        const saveButton = form.querySelector('.save-button');
        const originalValue = selectElement.getAttribute('data-original-value');

        if (selectElement.value !== originalValue) {
            saveButton.style.display = 'inline-block';
        } else {
            saveButton.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const selects = document.querySelectorAll('select[name="status"]');
        selects.forEach(select => {
            select.setAttribute('data-original-value', select.value);
        });
    });
    </script>

    <?php include '../views/layouts/dash_footer.php'; ?>
</body>
</html>