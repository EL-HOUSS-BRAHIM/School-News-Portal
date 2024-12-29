<?php include '../views/layouts/dash_header.php'; ?>

<body class="g-sidenav-show bg-gray-100">
    <?php include '../views/layouts/dash_sidenav.php'; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>Review Articles</h6>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Article</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Category</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
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
                                                <td class="align-middle text-center">
                                                    <span class="badge badge-sm bg-gradient-warning">
                                                        <?php echo htmlspecialchars($article['status']); ?>
                                                    </span>
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
                                                    <a href="/admin/publish_article?id=<?php echo $article['id']; ?>" class="btn btn-link text-success px-3 mb-0">
                                                        <i class="fas fa-check text-success me-2"></i>Publish
                                                    </a>
                                                    <a href="/admin/reject_article?id=<?php echo $article['id']; ?>" class="btn btn-link text-danger px-3 mb-0">
                                                        <i class="fas fa-times text-danger me-2"></i>Reject
                                                    </a>
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
    <?php include '../views/layouts/dash_footer.php'; ?>
</body>
</html>