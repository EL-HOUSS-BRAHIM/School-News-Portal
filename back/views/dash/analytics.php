<?php include __DIR__ . '/../layouts/dash_header.php'; ?>

<body class="g-sidenav-show bg-gray-100">
    <?php include __DIR__ . '/../layouts/dash_sidenav.php'; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <!-- Navbar -->
        <?php include __DIR__ . '/../layouts/dash_nav.php'; ?>
        <!-- End Navbar -->

        <div class="container-fluid py-4">
            <!-- Overall Metrics -->
            <div class="row">
                <div class="col-xl-3 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Views</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            <?php echo number_format($metrics['total_views']); ?>
                                            <span class="text-<?php echo $metrics['views_trend'] >= 0 ? 'success' : 'danger'; ?> text-sm font-weight-bolder">
                                                <?php echo $metrics['views_trend']; ?>%
                                            </span>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-primary shadow text-center">
                                        <i class="ni ni-chart-bar-32 text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional metrics cards -->
                <div class="col-xl-3 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Engagement Rate</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            <?php echo number_format($metrics['engagement_rate'], 2); ?>%
                                            <span class="text-<?php echo $metrics['engagement_trend'] >= 0 ? 'success' : 'danger'; ?> text-sm font-weight-bolder">
                                                <?php echo $metrics['engagement_trend']; ?>%
                                            </span>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-success shadow text-center">
                                        <i class="ni ni-active-40 text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Chart -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card z-index-2">
                        <div class="card-header pb-0">
                            <h6>Performance Overview</h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="chart">
                                <canvas id="performance-chart" class="chart-canvas" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Articles Table -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header pb-0 p-3">
                            <h6 class="mb-0">Top Performing Articles</h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Article</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Views</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Likes</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Comments</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Engagement</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($metrics['top_articles'] as $article): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div>
                                                        <img src="<?php echo $article['image'] ?? '../assets/img/default-article.jpg'; ?>" class="avatar avatar-sm me-3">
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm"><?php echo htmlspecialchars($article['title']); ?></h6>
                                                        <p class="text-xs text-secondary mb-0"><?php echo date('M d, Y', strtotime($article['created_at'])); ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?php echo number_format($article['views']); ?></p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="text-xs font-weight-bold"><?php echo number_format($article['likes']); ?></span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-xs font-weight-bold"><?php echo number_format($article['comments']); ?></span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-xs font-weight-bold"><?php echo number_format($article['engagement'], 2); ?>%</span>
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

    <!-- Chart.js initialization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('performance-chart').getContext('2d');
            var performanceData = <?php echo json_encode($metrics['performance_data']); ?>;
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: performanceData.labels,
                    datasets: [{
                        label: 'Views',
                        data: performanceData.views,
                        borderColor: '#4CAF50',
                        tension: 0.4,
                        fill: false
                    }, {
                        label: 'Engagement',
                        data: performanceData.engagement,
                        borderColor: '#f44336',
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
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>

<?php include __DIR__ . '/../layouts/dash_footer.php'; ?>