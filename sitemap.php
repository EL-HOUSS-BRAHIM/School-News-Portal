<?php
header('Content-Type: application/xml');

require_once __DIR__ . '/back/models/Article.php';
require_once __DIR__ . '/back/models/Category.php';
require_once __DIR__ . '/back/config/app.php';

$app = require __DIR__ . '/back/config/app.php';
$baseUrl = $app['constants']['BASE_URL'];

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
              xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">';

// Add static URLs
$staticUrls = [
    '/' => '1.0',
    '/articles' => '0.8',
    '/contact' => '0.6',
    '/categories' => '0.7'
];

foreach ($staticUrls as $url => $priority) {
    echo '<url>';
    echo '<loc>' . htmlspecialchars($baseUrl . $url) . '</loc>';
    echo '<changefreq>daily</changefreq>';
    echo '<priority>' . $priority . '</priority>';
    echo '</url>';
}

// Add articles
$articleModel = new Article();
$articles = $articleModel->getAll();

foreach ($articles as $article) {
    echo '<url>';
    echo '<loc>' . htmlspecialchars($baseUrl . '/article/' . urlencode($article['title'])) . '</loc>';
    echo '<lastmod>' . date('c', strtotime($article['updated_at'] ?? $article['created_at'])) . '</lastmod>';
    echo '<changefreq>weekly</changefreq>';
    echo '<priority>0.6</priority>';
    
    // Add news-specific tags
    echo '<news:news>';
    echo '<news:publication>';
    echo '<news:name>College Dar Bouazza News</news:name>';
    echo '<news:language>fr</news:language>';
    echo '</news:publication>';
    echo '<news:publication_date>' . date('c', strtotime($article['created_at'])) . '</news:publication_date>';
    echo '<news:title>' . htmlspecialchars($article['title']) . '</news:title>';
    echo '</news:news>';
    
    echo '</url>';
}

// Add category pages
$categoryModel = new Category(); 
$categories = $categoryModel->getAll();

foreach ($categories as $category) {
    echo '<url>';
    echo '<loc>' . htmlspecialchars($baseUrl . '/category/' . urlencode($category['id'])) . '</loc>';
    echo '<changefreq>weekly</changefreq>';
    echo '<priority>0.5</priority>';
    echo '</url>';
}

echo '</urlset>';