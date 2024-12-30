<?php
class ApiSecurityMiddleware {
    public function handle() {
        // CSRF Protection
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $excluded_routes = ['/login', '/register'];
            
            // Add debug logging
            error_log("Request URI: " . $_SERVER['REQUEST_URI']);
            error_log("Session CSRF: " . ($_SESSION['csrf_token'] ?? 'not set'));
            error_log("POST CSRF: " . ($_POST['csrf_token'] ?? 'not set'));
            
            if (!in_array($_SERVER['REQUEST_URI'], $excluded_routes)) {
                if (!isset($_SESSION['csrf_token'])) {
                    error_log("No CSRF token in session");
                    die('CSRF token missing in session');
                }
                
                if (!isset($_POST['csrf_token'])) {
                    error_log("No CSRF token in POST data");
                    die('CSRF token missing in request');
                }
                
                if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
                    error_log("CSRF token mismatch");
                    die('CSRF token validation failed');
                }
            }
        }

        // Rate Limiting
        $this->checkRateLimit();

        // Add security headers
        header('X-Frame-Options: DENY');
        header('X-Content-Type-Options: nosniff');
        header('X-XSS-Protection: 1; mode=block');
        }

    private function checkRateLimit() {
        $ip = $_SERVER['REMOTE_ADDR'];
        $limit = 100; // requests
        $interval = 60; // seconds
        
        $key = "rate_limit:$ip";
        // Implement rate limiting logic here
    }
}