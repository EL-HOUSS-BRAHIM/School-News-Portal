<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Helpers.php';

class UploadController extends Controller 
{
    public function uploadImage()
    {
        try {
            // Check for CSRF token in either header or POST data
            $headers = getallheaders();
            $csrfToken = null;
            
            // Check headers first (case-insensitive)
            foreach ($headers as $key => $value) {
                if (strtolower($key) === 'x-csrf-token') {
                    $csrfToken = $value;
                    break;
                }
            }
            
            // If not in headers, check POST data
            if (!$csrfToken && isset($_POST['csrf_token'])) {
                $csrfToken = $_POST['csrf_token'];
            }

            // Validate CSRF token
            if (!$csrfToken || !isset($_SESSION['csrf_token']) || 
                !hash_equals($_SESSION['csrf_token'], $csrfToken)) {
                throw new Exception('Invalid CSRF token');
            }

            if (!isset($_FILES['upload']) || $_FILES['upload']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('No file uploaded or upload error occurred');
            }

            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $fileType = mime_content_type($_FILES['upload']['tmp_name']);
            if (!in_array($fileType, $allowedTypes)) {
                throw new Exception('Invalid file type. Only images are allowed.');
            }

            // Upload to Cloudinary
            $imageUrl = uploadToCloudinary($_FILES['upload']['tmp_name']);
            
            // Return response in CKEditor expected format
            header('Content-Type: application/json');
            echo json_encode([
                'uploaded' => true,
                'url' => $imageUrl
            ]);

        } catch (Exception $e) {
            error_log("Image upload error: " . $e->getMessage());
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode([
                'uploaded' => false,
                'error' => [
                    'message' => $e->getMessage()
                ]
            ]);
        }
    }
}