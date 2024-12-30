<?php 

include __DIR__ . '/../layouts/dash_header.php'; ?>
<body class="g-sidenav-show  bg-gray-100">
    <?php include __DIR__ . '/../layouts/dash_sidenav.php'; ?>
    <main
        class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <?php include __DIR__ . '/../layouts/dash_nav.php'; ?>
        <!-- End Navbar -->
            <!-- Statistics Cards -->
            <!-- Statistics Cards -->
<div class="row">
    <div class="col-xl-3 col-sm-6 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Articles</p>
                            <h5 class="font-weight-bolder mb-0">
                                <?php echo htmlspecialchars($totalArticles); ?>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center">
                        <i class="fas fa-newspaper fa-2x text-success opacity-10"></i>
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
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Views</p>
                            <h5 class="font-weight-bolder mb-0">
                                <?php echo number_format($totalViews); ?>
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
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Likes</p>
                            <h5 class="font-weight-bolder mb-0">
                                <?php echo number_format($totalLikes); ?>
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
                                <?php echo number_format($totalComments); ?>
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
                        <a href="/dashboard/article/new" class="btn btn-primary btn-sm">New Article</a>
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
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Performance</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Created</th>
                                <th class="text-secondary opacity-7">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($recentArticles as $article): ?>
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
    <?php echo substr(strip_tags(html_entity_decode($article['content'])), 0, 50) . '...'; ?>
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
</script>


            <!-- Performance Chart -->
            <div class="row mt-4">
    <div class="col-lg-7 mb-lg-0 mb-4">
        <div class="card z-index-2">
            <div class="card-header pb-0">
                <h6>Article Performance</h6>
                <?php
                // Calculate performance change
                $currentViews = end($viewsData)['views'] ?? 0;
                $previousViews = reset($viewsData)['views'] ?? 0;
                $viewsChange = $previousViews > 0 ? 
                    (($currentViews - $previousViews) / $previousViews) * 100 : 0;
                ?>
                <p class="text-sm">
                    <i class="fa fa-arrow-<?php echo $viewsChange >= 0 ? 'up' : 'down'; ?> 
                       text-<?php echo $viewsChange >= 0 ? 'success' : 'danger'; ?>"></i>
                    <span class="font-weight-bold">
                        <?php echo abs(round($viewsChange)); ?>% 
                        <?php echo $viewsChange >= 0 ? 'more' : 'less'; ?>
                    </span>
                    views this month
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
                    <?php if (!empty($recentActivity)): ?>
                        <?php foreach($recentActivity as $activity): ?>
                            <div class="timeline-block mb-3">
                                <span class="timeline-step">
                                    <?php
                                    // Define icon and color based on activity type
                                    $icon = '';
                                    $color = '';
                                    switch($activity['type']) {
                                        case 'comment':
                                            $icon = 'ni-chat-round';
                                            $color = 'info';
                                            break;
                                        case 'view':
                                            $icon = 'ni-eye';
                                            $color = 'success';
                                            break;
                                        case 'like':
                                            $icon = 'ni-like-2';
                                            $color = 'danger';
                                            break;
                                        default:
                                            $icon = 'ni-bell-55';
                                            $color = 'primary';
                                    }
                                    ?>
                                    <i class="ni <?php echo $icon; ?> text-<?php echo $color; ?>"></i>
                                </span>
                                <div class="timeline-content">
                                    <h6 class="text-dark text-sm font-weight-bold mb-0">
                                        <?php
                                        // Format message based on activity type
                                        switch($activity['type']) {
                                            case 'comment':
                                                echo htmlspecialchars($activity['user_name']) . ' commented on "' . 
                                                     htmlspecialchars($activity['article_title']) . '"';
                                                break;
                                            case 'view':
                                                echo 'New view on "' . htmlspecialchars($activity['article_title']) . '"';
                                                break;
                                            default:
                                                echo htmlspecialchars($activity['message']);
                                        }
                                        ?>
                                    </h6>
                                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">
                                        <?php 
                                        $date = new DateTime($activity['created_at']);
                                        echo $date->format('d M H:i'); 
                                        ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center text-secondary py-3">
                            No recent activity
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
var ctx = document.getElementById("chart-line").getContext("2d");
var viewsData = <?php echo json_encode($viewsData); ?>;
var likesData = <?php echo json_encode($likesData); ?>;

new Chart(ctx, {
    type: "line",
    data: {
        labels: viewsData.map(item => {
            // Format date for display
            let date = new Date(item.date);
            return date.toLocaleDateString('default', { month: 'short', day: 'numeric' });
        }),
        datasets: [{
            label: "Views",
            data: viewsData.map(item => item.views),
            borderColor: "#4CAF50",
            tension: 0.4,
            fill: false
        }, {
            label: "Likes",
            data: likesData.map(item => item.likes),
            borderColor: "#f44336",
            tension: 0.4,
            fill: false
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    drawBorder: false,
                    display: true,
                    drawOnChartArea: true,
                    drawTicks: false,
                    borderDash: [5, 5]
                },
                ticks: {
                    display: true,
                    padding: 10,
                    color: '#9ca2b7'
                }
            },
            x: {
                grid: {
                    drawBorder: false,
                    display: true,
                    drawOnChartArea: true,
                    drawTicks: true,
                    borderDash: [5, 5]
                },
                ticks: {
                    display: true,
                    padding: 10,
                    color: '#9ca2b7'
                }
            }
        }
    }
});
</script>

    <?php include __DIR__ . '/../layouts/dash_footer.php'; ?>