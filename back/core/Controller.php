<?php
require_once __DIR__ . '/Session.php';

class Controller
{
    public function renderView($view, $data = []) {
        // Ensure clean output buffer
        while (ob_get_level()) {
            ob_end_clean();
        }
        ob_start();
        
        extract($data);
        require_once __DIR__ . "/../views/$view.php";
        
        $content = ob_get_clean();
        echo $content;
    }

    public function redirect($url)
    {
        header("Location: $url");
        exit();
    }
}
?>
