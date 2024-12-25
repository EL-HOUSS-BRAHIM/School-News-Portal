function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function redirectTo($url) {
    header("Location: $url");
    exit();
}

function setFlashMessage($message) {
    $_SESSION['flash_message'] = $message;
}

function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $message;
    }
    return null;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getCurrentUser() {
    return isLoggedIn() ? $_SESSION['user_id'] : null;
}