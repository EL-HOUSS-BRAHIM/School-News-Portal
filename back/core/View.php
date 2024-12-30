<?php

class View
{
    public static function render($view, $data = [])
    {
        extract($data);
        require __DIR__ . "/../views/$view.php";
    }
}
?>