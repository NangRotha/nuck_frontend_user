<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Language {
    private $translations = [];
    private $current_lang = 'en';
    private $available_langs = ['en', 'km'];
    
    public function __construct() {
        // Get current language from URL or session
        if (isset($_GET['lang']) && in_array($_GET['lang'], $this->available_langs)) {
            $this->current_lang = $_GET['lang'];
            $_SESSION['lang'] = $this->current_lang;
        } elseif (isset($_SESSION['lang']) && in_array($_SESSION['lang'], $this->available_langs)) {
            $this->current_lang = $_SESSION['lang'];
        } else {
            // Try to detect browser language
            $browser_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'en', 0, 2);
            $this->current_lang = in_array($browser_lang, $this->available_langs) ? $browser_lang : 'en';
            $_SESSION['lang'] = $this->current_lang;
        }
        
        // Load translations
        $this->loadTranslations();
    }
    
    private function loadTranslations() {
        $lang_file = dirname(__DIR__) . "/languages/{$this->current_lang}.php";
        
        if (file_exists($lang_file)) {
            $this->translations = include($lang_file);
        } else {
            // Fallback to English
            $fallback_file = dirname(__DIR__) . "/languages/en.php";
            if (file_exists($fallback_file)) {
                $this->translations = include($fallback_file);
            } else {
                $this->translations = [];
            }
        }
    }
    
    public function t($key, $default = '') {
        return $this->translations[$key] ?? $default;
    }
    
    public function getCurrentLang() {
        return $this->current_lang;
    }
    
    public function getAlternateLang() {
        return $this->current_lang === 'en' ? 'km' : 'en';
    }
    
    public function getLangUrl($lang) {
        // Get current URL without lang parameter
        $url = $_SERVER['REQUEST_URI'];
        
        // Remove existing lang parameter
        $url = preg_replace('/[?&]lang=(en|km)/', '', $url);
        $url = preg_replace('/\?$/', '', $url);
        
        // Add new lang parameter
        $separator = (strpos($url, '?') !== false) ? '&' : '?';
        return $url . $separator . 'lang=' . $lang;
    }
    
    public function getCurrentUrl() {
        return $this->getLangUrl($this->current_lang);
    }
}

// Initialize language
$lang = new Language();

// Helper function for easy access
function __($key, $default = '') {
    global $lang;
    return $lang ? $lang->t($key, $default) : $default;
}
?>