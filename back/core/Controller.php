<?php

class Controller
{
    public function renderView($view, $data = [])
    {
        extract($data);
        require_once __DIR__ . "/../views/$view.php";
    }

    public function redirect($url)
    {
        header("Location: $url");
        exit();
    }
}
?>