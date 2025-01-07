<?php
class Translate {
    private static $instance = null;
    private static $lang = 'fr';
    private static $translations = [];
    
    public static function init() {
        if (isset($_GET['lang']) && in_array($_GET['lang'], ['fr', 'ar', 'en'])) {
            self::$lang = $_GET['lang'];
            $_SESSION['lang'] = self::$lang;
        } elseif (isset($_SESSION['lang'])) {
            self::$lang = $_SESSION['lang'];
        }
        
        self::loadTranslations();
    }
    
    public static function setLang($lang) {
        if (in_array($lang, ['fr', 'ar', 'en'])) {
            self::$lang = $lang;
            $_SESSION['lang'] = self::$lang;
            self::loadTranslations();
        }
    }
    
    private static function loadTranslations() {
        $langFile = __DIR__ . "/../lang/" . self::$lang . ".php";
        if (file_exists($langFile)) {
            self::$translations = require $langFile;
        }
    }
    
    public static function get($key) {
        return self::$translations[$key] ?? $key;
    }
    
    public static function getCurrentLang() {
        return self::$lang;
    }
}