<?php include __DIR__ . '/../layouts/dash_header.php'; ?>

<body class="g-sidenav-show bg-gray-100">
    <?php include __DIR__ . '/../layouts/dash_sidenav.php'; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <?php include __DIR__ . '/../layouts/dash_nav.php'; ?>

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>All Articles</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select class="form-control" id="statusFilter">
                                            <option value="">All Statuses</option>
                                            <?php foreach(Article::getAllStatuses() as $value => $label): ?>
                                                <option value="<?php echo $value; ?>"><?php echo htmlspecialchars($label); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" id="searchInput" placeholder="Search articles...">
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Article</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Author</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Category</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Metrics</th>
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
                                                            <img src="<?php echo htmlspecialchars($article['image'] ?? '../assets/img/default-article.jpg'); ?>" 
                                                                 class="avatar avatar-sm me-3" alt="article image">
                                                        </div>
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm"><?php echo htmlspecialchars($article['title']); ?></h6>
                                                            <p class="text-xs text-secondary mb-0" dir="<?php echo $article['language'] === 'ar' ? 'rtl' : 'ltr'; ?>">
                                                                <?php echo substr(strip_tags(html_entity_decode($article['content'])), 0, 50) . '...'; ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($article['author']); ?></p>
                                                </td>
                                                <td class="align-middle text-center text-sm">
                                                    <span class="badge badge-sm bg-gradient-info">
                                                        <?php echo htmlspecialchars($article['category']); ?>
                                                    </span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <form action="/admin/article/status" method="POST" class="d-inline status-form">
                                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                        <input type="hidden" name="id" value="<?php echo $article['id']; ?>">
                                                        <select name="status" class="form-select form-select-sm d-inline w-auto" 
                                                                onchange="this.form.submit()"
                                                                style="background-color: <?php echo getStatusColor($article['status']); ?>">
                                                            <?php foreach(Article::getAllStatuses() as $value => $label): ?>
                                                                <option value="<?php echo $value; ?>" 
                                                                        <?php echo $value === $article['status'] ? 'selected' : ''; ?>>
                                                                    <?php echo htmlspecialchars($label); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </form>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <span class="text-xs font-weight-bold">
                                                            <i class="fas fa-eye text-primary"></i> <?php echo number_format($article['views'] ?? 0); ?>
                                                        </span>
                                                        <span class="text-xs font-weight-bold">
                                                            <i class="fas fa-heart text-danger"></i> <?php echo number_format($article['likes'] ?? 0); ?>
                                                        </span>
                                                        <span class="text-xs font-weight-bold">
                                                            <i class="fas fa-comment text-success"></i> <?php echo number_format($article['comments'] ?? 0); ?>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span class="text-secondary text-xs font-weight-bold">
                                                        <?php echo $article['created_at'] ? date('M d, Y', strtotime($article['created_at'])) : 'N/A'; ?>
                                                    </span>
                                                </td>
                                                <td class="align-middle">
                                                    <div class="ms-auto">
                                                        <a href="/article/<?php echo urlencode($article['title']); ?>" 
                                                           class="btn btn-link text-primary px-3 mb-0" 
                                                           target="_blank">
                                                            <i class="fas fa-eye text-primary me-2"></i>View
                                                        </a>
                                                        <button type="button" 
                                                                onclick="confirmDelete('<?php echo $article['id']; ?>')"
                                                                class="btn btn-link text-danger px-3 mb-0">
                                                            <i class="far fa-trash-alt me-2"></i>Delete
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
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this article? This action cannot be undone.
                    </div>
                    <div class="modal-footer">
                        <form id="deleteForm" action="/admin/article/delete" method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <input type="hidden" name="id" id="deleteArticleId">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/../layouts/dash_footer.php'; ?>

    <script>
    // Filter functionality
    document.getElementById('statusFilter').addEventListener('change', function() {
        filterTable();
    });

    document.getElementById('searchInput').addEventListener('keyup', function() {
        filterTable();
    });

    function filterTable() {
        const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
        const searchFilter = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const status = row.querySelector('select[name="status"]').value.toLowerCase();
            const title = row.querySelector('h6').textContent.toLowerCase();
            const author = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            
            const matchesStatus = !statusFilter || status === statusFilter;
            const matchesSearch = !searchFilter || 
                                title.includes(searchFilter) || 
                                author.includes(searchFilter);

            row.style.display = matchesStatus && matchesSearch ? '' : 'none';
        });
    }

    // Delete confirmation
    function confirmDelete(articleId) {
        document.getElementById('deleteArticleId').value = articleId;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }

    // Helper function for status colors
    function getStatusColor(status) {
        const colors = {
            'draft': '#ffc107',
            'reviewing': '#17a2b8',
            'private': '#6c757d',
            'published': '#28a745',
            'disqualified': '#dc3545'
        };
        return colors[status] || '#6c757d';
    }
    </script>
</body>
</html>