<?php
// Start session at the VERY BEGINNING
session_start();

// Include language system - FIXED PATH
require_once __DIR__ . './../../includes/language.php';

// Get translation function
$t = function($key, $default = '') use ($lang) {
    return $lang->t($key, $default);
};

// Get current language for easy access
$currentLang = $lang->getCurrentLang();
$isKhmer = $currentLang === 'km';

// Include database configuration
include './../../includes/config.php';

// Create a new Database instance and get the connection
$database = new Database();
$db = $database->getConnection();

// Check if slug is provided in the URL
if (!isset($_GET['slug'])) {
    echo "<div class='mt-24 mx-auto max-w-c-1390 px-4 md:px-8 2xl:px-0 py-8 text-center text-red-500'>Erasmus program not found. Please provide a valid slug.</div>";
    exit;
}

$slug = $_GET['slug'];

// Prepare and execute the SQL query to fetch program details by slug
$query = "SELECT * FROM erasmus_programs WHERE slug = :slug LIMIT 1";
$stmt = $db->prepare($query);
$stmt->bindParam(':slug', $slug);
$stmt->execute();
$program = $stmt->fetch(PDO::FETCH_ASSOC);

// If program not found, display a message and exit
if (!$program) {
    echo "<div class='mt-24 mx-auto max-w-c-1390 px-4 md:px-8 2xl:px-0 py-8 text-center text-red-500'>Erasmus program not found.</div>";
    exit;
}

// Check if program is active
if (!$program['is_active']) {
    echo "<div class='mt-24 mx-auto max-w-c-1390 px-4 md:px-8 2xl:px-0 py-8 text-center text-yellow-500'>This program is currently inactive.</div>";
    exit;
}

/**
 * Function to resolve image path - FIXED to use correct path to admin/uploads
 */
function getImagePath($path) {
    if (!empty($path)) {
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        } else {
            // Path from frontend/public/Erasmus/ to admin/
            // Database stores path as 'uploads/erasmus/filename.jpg'
            // frontend/public/Erasmus/ -> frontend/public/ -> frontend/ -> root -> admin/
            return '../../../admin/' . ltrim($path, '/');
        }
    }
    return './../../images/erasmus/logo-Erasmus.png';
}

/**
 * Function to format date based on language
 */
function formatDateLocalized($date, $lang) {
    if (!$date) return '';
    
    $timestamp = strtotime($date);
    if ($lang === 'km') {
        $khmerMonths = [
            'មករា', 'កុម្ភៈ', 'មីនា', 'មេសា', 'ឧសភា', 'មិថុនា',
            'កក្កដា', 'សីហា', 'កញ្ញា', 'តុលា', 'វិច្ឆិកា', 'ធ្នូ'
        ];
        $month = $khmerMonths[date('n', $timestamp) - 1];
        $day = date('j', $timestamp);
        $year = date('Y', $timestamp);
        return "$day $month $year";
    } else {
        return date('F j, Y', $timestamp);
    }
}

/**
 * Function to check if application deadline has passed
 */
function isDeadlinePassed($deadline) {
    if (!$deadline) return false;
    return strtotime($deadline) < time();
}
?>
<!DOCTYPE html>
<html lang="<?= $currentLang === 'en' ? 'en' : 'km' ?>" class="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
  <title>NUCK | <?= $t('projects_erasmus') ?> - <?= htmlspecialchars($isKhmer ? ($program['title_km'] ?? $program['title_en']) : ($program['title_en'] ?? 'Erasmus Program')) ?></title>
  <link rel="shortcut icon" href="./../../images/logo_footer/nuck_logo.png" type="image/x-icon">
  
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Kantumruy+Pro:wght@300;400;500;600;700&family=Hanuman:wght@100;300;400;700;900&display=swap" rel="stylesheet">
  
  <!-- AOS Animation Library -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.1/aos.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.1/aos.js"></script>

  <script>
    tailwind.config = {
      darkMode: 'class',
    }
  </script>
  
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    :root {
      --bg-primary: #ffffff;
      --bg-secondary: #f9fafb;
      --text-primary: #111827;
      --text-secondary: #4b5563;
      --text-muted: #6b7280;
      --card-bg: #ffffff;
      --footer-bg: #111827;
      --border-color: #e5e7eb;
      --primary-color: #1e3c72;
      --primary-light: #2a5298;
      --accent-color: #ffd700;
    }

    .dark {
      --bg-primary: #111827;
      --bg-secondary: #1f2937;
      --text-primary: #f3f4f6;
      --text-secondary: #d1d5db;
      --text-muted: #9ca3af;
      --card-bg: #1f2937;
      --footer-bg: #030712;
      --border-color: #374151;
    }

    body {
      font-family: 'Inter', sans-serif;
      overflow-x: hidden;
      padding-top: 70px;
      background-color: var(--bg-primary);
      color: var(--text-primary);
      transition: background-color 0.3s ease, color 0.3s ease;
    }

    @media (min-width: 1024px) {
      body {
        padding-top: 80px;
      }
    }

    .khmer-font, 
    html[lang="km"] body,
    html[lang="km"] p,
    html[lang="km"] span,
    html[lang="km"] h1,
    html[lang="km"] h2,
    html[lang="km"] h3,
    html[lang="km"] h4 {
      font-family: 'Kantumruy Pro', 'Hanuman', sans-serif !important;
    }

    .navbar {
      background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
      position: fixed;
      width: 100%;
      top: 0;
      z-index: 1000;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    }

    .dark .navbar {
      background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    }

    .nav-menu-desktop {
      display: none;
    }

    @media (min-width: 1024px) {
      .nav-menu-desktop {
        display: flex;
        align-items: center;
        gap: 0.25rem;
      }
    }

    .nav-link {
      color: white;
      font-weight: 500;
      padding: 0.5rem 0.75rem;
      border-radius: 0.5rem;
      transition: all 0.3s ease;
      font-size: 0.9rem;
      white-space: nowrap;
      text-decoration: none;
    }

    .nav-link:hover {
      background: rgba(255, 255, 255, 0.15);
    }

    .dropdown-container {
      position: relative;
    }

    .dropdown-menu {
      position: absolute;
      top: 100%;
      left: 0;
      min-width: 240px;
      background: white;
      border-radius: 0.75rem;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
      opacity: 0;
      visibility: hidden;
      transform: translateY(-10px);
      transition: all 0.3s ease;
      z-index: 100;
      margin-top: 0.5rem;
    }

    .dark .dropdown-menu {
      background: #1f2937;
    }

    .dropdown-container:hover .dropdown-menu {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }

    .dropdown-item {
      display: block;
      padding: 0.75rem 1.25rem;
      color: #333;
      transition: all 0.3s ease;
      border-left: 3px solid transparent;
      font-size: 0.9rem;
      text-decoration: none;
    }

    .dark .dropdown-item {
      color: #e5e7eb;
    }

    .dropdown-item:hover {
      background: linear-gradient(90deg, rgba(42, 82, 152, 0.1) 0%, rgba(255, 215, 0, 0.1) 100%);
      border-left-color: var(--accent-color);
      padding-left: 1.75rem;
    }

    .menu-toggle {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      width: 28px;
      height: 20px;
      cursor: pointer;
      z-index: 1001;
    }

    @media (min-width: 1024px) {
      .menu-toggle {
        display: none;
      }
    }

    .menu-toggle span {
      display: block;
      width: 100%;
      height: 2px;
      background: white;
      border-radius: 3px;
      transition: all 0.3s ease;
    }

    .menu-toggle.active span:nth-child(1) {
      transform: translateY(9px) rotate(45deg);
    }

    .menu-toggle.active span:nth-child(2) {
      opacity: 0;
    }

    .menu-toggle.active span:nth-child(3) {
      transform: translateY(-9px) rotate(-45deg);
    }

    .mobile-menu {
      position: fixed;
      top: 0;
      right: -100%;
      width: 85%;
      max-width: 340px;
      height: 100vh;
      background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
      z-index: 1000;
      transition: right 0.3s ease;
      padding: 1.5rem 1rem;
      overflow-y: auto;
      box-shadow: -5px 0 30px rgba(0, 0, 0, 0.2);
    }

    .dark .mobile-menu {
      background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    }

    .mobile-menu.active {
      right: 0;
    }

    .mobile-menu-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      backdrop-filter: blur(5px);
      z-index: 999;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
    }

    .mobile-menu-overlay.active {
      opacity: 1;
      visibility: visible;
    }

    .mobile-nav-link {
      display: block;
      color: white;
      padding: 0.75rem 1rem;
      border-radius: 0.5rem;
      transition: all 0.3s ease;
      text-decoration: none;
    }

    .mobile-nav-link:hover {
      background: rgba(255, 255, 255, 0.1);
    }

    .mobile-dropdown-btn {
      width: 100%;
      text-align: left;
      padding: 0.75rem 1rem;
      color: white;
      background: transparent;
      border: none;
      border-radius: 0.5rem;
      transition: all 0.3s ease;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .mobile-dropdown-btn:hover {
      background: rgba(255, 255, 255, 0.1);
    }

    .mobile-dropdown-btn i {
      transition: transform 0.3s ease;
    }

    .mobile-dropdown-btn.active i {
      transform: rotate(180deg);
    }

    .mobile-dropdown-content {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.3s ease;
      margin-left: 2rem;
      border-left: 2px solid rgba(255, 255, 255, 0.2);
      background: rgba(255, 255, 255, 0.05);
      border-radius: 0 0 0.5rem 0.5rem;
    }

    .mobile-dropdown-content.show {
      max-height: 300px;
    }

    .mobile-dropdown-item {
      display: block;
      color: rgba(255, 255, 255, 0.9);
      padding: 0.5rem 1rem;
      transition: all 0.3s ease;
      font-size: 0.9rem;
      text-decoration: none;
    }

    .mobile-dropdown-item:hover {
      color: white;
      padding-left: 1.5rem;
      background: rgba(255, 255, 255, 0.1);
    }

    .desktop-actions {
      display: none;
    }

    @media (min-width: 1024px) {
      .desktop-actions {
        display: flex;
        align-items: center;
        gap: 0.5rem;
      }
    }

    .language-switcher {
      position: relative;
    }

    .language-btn {
      display: flex;
      align-items: center;
      gap: 0.4rem;
      padding: 0.4rem 0.8rem;
      background: rgba(255, 255, 255, 0.15);
      border-radius: 2rem;
      color: white;
      cursor: pointer;
      transition: all 0.3s ease;
      border: none;
      font-size: 0.85rem;
      white-space: nowrap;
    }

    .language-btn:hover {
      background: rgba(255, 255, 255, 0.25);
    }

    .language-btn img {
      width: 18px;
      height: 18px;
      border-radius: 50%;
      object-fit: cover;
    }

    .language-dropdown {
      position: absolute;
      top: 100%;
      right: 0;
      width: 160px;
      background: white;
      border-radius: 0.75rem;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      margin-top: 0.5rem;
      opacity: 0;
      visibility: hidden;
      transform: translateY(-10px);
      transition: all 0.3s ease;
      z-index: 100;
    }

    .dark .language-dropdown {
      background: #1f2937;
    }

    .language-switcher:hover .language-dropdown {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }

    .language-option {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.6rem 1rem;
      color: #333;
      transition: all 0.3s ease;
      text-decoration: none;
      font-size: 0.9rem;
    }

    .dark .language-option {
      color: #e5e7eb;
    }

    .language-option:hover {
      background: rgba(42, 82, 152, 0.1);
      padding-left: 1.5rem;
    }

    .language-option img {
      width: 18px;
      height: 18px;
      border-radius: 50%;
      object-fit: cover;
    }

    .theme-toggle {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.15);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      cursor: pointer;
      transition: all 0.3s ease;
      border: none;
    }

    .theme-toggle:hover {
      background: rgba(255, 255, 255, 0.25);
      transform: rotate(15deg);
    }

    .register-btn {
      background: linear-gradient(135deg, var(--accent-color) 0%, #ffa500 100%);
      color: var(--primary-color);
      padding: 0.4rem 1.2rem;
      border-radius: 2rem;
      font-weight: 600;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      text-decoration: none;
      font-size: 0.85rem;
      white-space: nowrap;
    }

    .register-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 20px rgba(255, 215, 0, 0.3);
    }

    .mobile-language-section {
      margin-top: 1.5rem;
      padding-top: 1.5rem;
      border-top: 1px solid rgba(255, 255, 255, 0.2);
    }

    .mobile-section-title {
      color: white;
      font-size: 0.9rem;
      font-weight: 600;
      margin-bottom: 0.75rem;
      opacity: 0.9;
    }

    .mobile-language-options {
      display: flex;
      gap: 0.5rem;
      margin-bottom: 1rem;
    }

    .mobile-language-option {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      padding: 0.6rem;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 2rem;
      color: white;
      transition: all 0.3s ease;
      text-decoration: none;
      font-size: 0.9rem;
    }

    .mobile-language-option.active {
      background: rgba(255, 255, 255, 0.25);
      border: 1px solid var(--accent-color);
    }

    .mobile-language-option img {
      width: 20px;
      height: 20px;
      border-radius: 50%;
    }

    .mobile-theme-options {
      display: flex;
      gap: 0.5rem;
    }

    .mobile-theme-btn {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      padding: 0.6rem;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 2rem;
      color: white;
      transition: all 0.3s ease;
      border: none;
      cursor: pointer;
      font-size: 0.9rem;
    }

    .mobile-theme-btn:hover {
      background: rgba(255, 255, 255, 0.2);
    }

    .mobile-theme-btn.active {
      background: rgba(255, 255, 255, 0.25);
      border: 1px solid var(--accent-color);
    }

    footer {
      background-color: var(--footer-bg);
      color: white;
    }

    #scroll-top {
      position: fixed;
      bottom: 20px;
      right: 20px;
      width: 40px;
      height: 40px;
      background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
      color: white;
      border-radius: 50%;
      display: none;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s ease;
      z-index: 99;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
      border: none;
    }

    .dark #scroll-top {
      background: linear-gradient(135deg, #2563eb, #3b82f6);
    }

    #scroll-top.show {
      display: flex;
    }

    #scroll-top:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    }

    .container-custom {
      width: 100%;
      max-width: 1280px;
      margin-left: auto;
      margin-right: auto;
      padding-left: 1rem;
      padding-right: 1rem;
    }

    @media (min-width: 640px) {
      .container-custom {
        padding-left: 1.5rem;
        padding-right: 1.5rem;
      }
    }

    @media (min-width: 1024px) {
      .container-custom {
        padding-left: 2rem;
        padding-right: 2rem;
      }
    }
  </style>
</head>
<body class="<?= $isKhmer ? 'khmer-font' : '' ?>">

  <!-- Navbar -->
  <nav class="navbar">
    <div class="container-custom">
      <div class="flex justify-between items-center h-[70px] lg:h-20">
        <div class="flex-shrink-0">
          <a href="./../../?lang=<?= $currentLang ?>" class="flex items-center">
            <img src="./../../images/logo/NUCK_Logo_Web.png" alt="NUCK" class="h-9 sm:h-10 lg:h-14 w-auto">
          </a>
        </div>

        <div class="nav-menu-desktop">
          <a href="./../../?lang=<?= $currentLang ?>" class="nav-link"><?= $t('nav_home') ?></a>
          
          <div class="dropdown-container">
            <button class="nav-link flex items-center gap-1">
              <?= $t('nav_resources') ?>
              <i class="fas fa-chevron-down text-xs"></i>
            </button>
            <div class="dropdown-menu">
              <a href="./../../public/partner/?lang=<?= $currentLang ?>" class="dropdown-item">
                <i class="fas fa-handshake mr-2"></i> <?= $t('nav_our_partners') ?>
              </a>
              <a href="./../../public/new&events/?lang=<?= $currentLang ?>" class="dropdown-item">
                <i class="fas fa-calendar-alt mr-2"></i> <?= $t('nav_news_events') ?>
              </a>
            </div>
          </div>
          
          <div class="dropdown-container">
            <button class="nav-link flex items-center gap-1">
              <?= $t('nav_academics') ?>
              <i class="fas fa-chevron-down text-xs"></i>
            </button>
            <div class="dropdown-menu">
              <a href="./../../public/Faculty_of_Science_and_Mathematics/?lang=<?= $currentLang ?>" class="dropdown-item"><?= $t('faculty_science') ?></a>
              <a href="./../../public/Faculty_of_Arts_Humanitites_and_Languages/?lang=<?= $currentLang ?>" class="dropdown-item"><?= $t('faculty_arts') ?></a>
              <a href="./../../public/Faculty_of_Agriculture/?lang=<?= $currentLang ?>" class="dropdown-item"><?= $t('faculty_agriculture') ?></a>
              <a href="./../../public/Faculty_of_social_science/?lang=<?= $currentLang ?>" class="dropdown-item"><?= $t('faculty_social_science') ?></a>
              <a href="./../../public/Faculty_of_Management/?lang=<?= $currentLang ?>" class="dropdown-item"><?= $t('faculty_management') ?></a>
            </div>
          </div>

          <div class="dropdown-container">
            <button class="nav-link flex items-center gap-1">
              <?= $t('nav_about') ?>
              <i class="fas fa-chevron-down text-xs"></i>
            </button>
            <div class="dropdown-menu">
              <a href="./../../public/about/?lang=<?= $currentLang ?>" class="dropdown-item"><?= $t('about_message_rector') ?></a>
              <a href="./../../public/vision-and-mission/?lang=<?= $currentLang ?>" class="dropdown-item"><?= $t('about_vision_mission') ?></a>
              <a href="./../../public/history_university/?lang=<?= $currentLang ?>" class="dropdown-item"><?= $t('about_history') ?></a>
            </div>
          </div>

          <div class="dropdown-container">
            <button class="nav-link flex items-center gap-1">
              <?= $t('nav_projects') ?>
              <i class="fas fa-chevron-down text-xs"></i>
            </button>
            <div class="dropdown-menu">
              <a href="./../../public/world-bank/?lang=<?= $currentLang ?>" class="dropdown-item"><?= $t('projects_world_bank') ?></a>
              <a href="./../../public/Erasmus/?lang=<?= $currentLang ?>" class="dropdown-item"><?= $t('projects_erasmus') ?></a>
            </div>
          </div>
        </div>

        <div class="desktop-actions">
          <div class="language-switcher">
            <button class="language-btn">
              <img src="./../../images/flage/<?= $currentLang === 'en' ? 'english.png' : 'cam.png' ?>" alt="<?= strtoupper($currentLang) ?>">
              <span><?= strtoupper($currentLang) ?></span>
              <i class="fas fa-chevron-down text-xs"></i>
            </button>
            <div class="language-dropdown">
              <a href="?lang=en&slug=<?= urlencode($slug) ?>" class="language-option">
                <img src="./../../images/flage/english.png" alt="EN">
                English
              </a>
              <a href="?lang=km&slug=<?= urlencode($slug) ?>" class="language-option">
                <img src="./../../images/flage/cam.png" alt="KH">
                ភាសាខ្មែរ
              </a>
            </div>
          </div>

          <button class="theme-toggle" id="theme-toggle-desktop" aria-label="Toggle theme">
            <i class="fas fa-moon"></i>
          </button>

          <a href="./../../FormRegister?lang=<?= $currentLang ?>" class="register-btn">
            <i class="fas fa-user-graduate"></i>
            <span><?= $t('nav_register') ?></span>
          </a>
        </div>

        <div class="menu-toggle" id="menu-toggle">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>
    </div>
  </nav>

  <div class="mobile-menu-overlay" id="mobile-overlay"></div>

  <div class="mobile-menu" id="mobile-menu">
    <div class="flex justify-end mb-4">
      <button class="text-white text-2xl p-1" id="close-menu">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <div class="space-y-1">
      <a href="./../../?lang=<?= $currentLang ?>" class="mobile-nav-link">
        <i class="fas fa-home w-6 mr-3"></i><?= $t('nav_home') ?>
      </a>

      <button class="mobile-dropdown-btn" onclick="toggleMobileDropdown('resources')">
        <span><i class="fas fa-folder-open w-6 mr-3"></i><?= $t('nav_resources') ?></span>
        <i class="fas fa-chevron-down"></i>
      </button>
      <div class="mobile-dropdown-content" id="resources">
        <a href="./../../public/partner/?lang=<?= $currentLang ?>" class="mobile-dropdown-item">
          <i class="fas fa-handshake mr-2 w-4"></i> <?= $t('nav_our_partners') ?>
        </a>
        <a href="./../../public/new&events/?lang=<?= $currentLang ?>" class="mobile-dropdown-item">
          <i class="fas fa-calendar-alt mr-2 w-4"></i> <?= $t('nav_news_events') ?>
        </a>
      </div>

      <button class="mobile-dropdown-btn" onclick="toggleMobileDropdown('academics')">
        <span><i class="fas fa-book w-6 mr-3"></i><?= $t('nav_academics') ?></span>
        <i class="fas fa-chevron-down"></i>
      </button>
      <div class="mobile-dropdown-content" id="academics">
        <a href="./../../public/Faculty_of_Science_and_Mathematics/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('faculty_science') ?></a>
        <a href="./../../public/Faculty_of_Arts_Humanitites_and_Languages/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('faculty_arts') ?></a>
        <a href="./../../public/Faculty_of_Agriculture/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('faculty_agriculture') ?></a>
        <a href="./../../public/Faculty_of_social_science/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('faculty_social_science') ?></a>
        <a href="./../../public/Faculty_of_Management/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('faculty_management') ?></a>
      </div>

      <button class="mobile-dropdown-btn" onclick="toggleMobileDropdown('about')">
        <span><i class="fas fa-info-circle w-6 mr-3"></i><?= $t('nav_about') ?></span>
        <i class="fas fa-chevron-down"></i>
      </button>
      <div class="mobile-dropdown-content" id="about">
        <a href="./../../public/about/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('about_message_rector') ?></a>
        <a href="./../../public/vision-and-mission/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('about_vision_mission') ?></a>
        <a href="./../../public/history_university/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('about_history') ?></a>
      </div>

      <button class="mobile-dropdown-btn" onclick="toggleMobileDropdown('projects')">
        <span><i class="fas fa-project-diagram w-6 mr-3"></i><?= $t('nav_projects') ?></span>
        <i class="fas fa-chevron-down"></i>
      </button>
      <div class="mobile-dropdown-content" id="projects">
        <a href="./../../public/world-bank/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('projects_world_bank') ?></a>
        <a href="./../../public/Erasmus/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('projects_erasmus') ?></a>
      </div>

      <div class="mobile-language-section">
        <div class="mobile-section-title"><?= $t('language') ?></div>
        <div class="mobile-language-options">
          <a href="?lang=en&slug=<?= urlencode($slug) ?>" class="mobile-language-option <?= $currentLang === 'en' ? 'active' : '' ?>">
            <img src="./../../images/flage/english.png" alt="EN">
            <span>EN</span>
          </a>
          <a href="?lang=km&slug=<?= urlencode($slug) ?>" class="mobile-language-option <?= $currentLang === 'km' ? 'active' : '' ?>">
            <img src="./../../images/flage/cam.png" alt="KH">
            <span>KH</span>
          </a>
        </div>

        <div class="mobile-section-title mt-4"><?= $t('theme') ?></div>
        <div class="mobile-theme-options">
          <button class="mobile-theme-btn active" id="mobile-theme-light">
            <i class="fas fa-sun"></i>
            <span><?= $t('light') ?></span>
          </button>
          <button class="mobile-theme-btn" id="mobile-theme-dark">
            <i class="fas fa-moon"></i>
            <span><?= $t('dark') ?></span>
          </button>
        </div>

        <a href="./../../FormRegister?lang=<?= $currentLang ?>" class="register-btn w-full justify-center mt-6 py-3">
          <i class="fas fa-user-graduate"></i>
          <?= $t('nav_register') ?> Now
        </a>
      </div>
    </div>
  </div>

  <!-- Breadcrumb Navigation -->
  <div class="container-custom py-6">
    <a href="./index.php?lang=<?= $currentLang ?>" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition">
      <i class="fas fa-arrow-left mr-2"></i>
      <?= $t('back_to_programs') ?>
    </a>
  </div>
  
  <!-- Main Content -->
  <div class="container-custom py-4">
    <div class="max-w-6xl mx-auto">
      <!-- Program Header -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-8" data-aos="fade-up">
        <div class="relative h-64 md:h-96 overflow-hidden">
          <img src="<?= htmlspecialchars(getImagePath($program['image_path'] ?? '')) ?>" 
               alt="<?= htmlspecialchars($isKhmer ? ($program['title_km'] ?? $program['title_en']) : ($program['title_en'] ?? 'Erasmus Program')) ?>" 
               class="w-full h-full object-cover">
          <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
          
          <div class="absolute top-4 right-4">
            <?php if (isDeadlinePassed($program['application_deadline'])): ?>
              <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                <i class="fas fa-times-circle mr-1"></i>
                <?= $t('closed') ?>
              </span>
            <?php else: ?>
              <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                <i class="fas fa-check-circle mr-1"></i>
                <?= $t('open') ?>
              </span>
            <?php endif; ?>
          </div>
          
          <div class="absolute bottom-0 left-0 right-0 p-6 md:p-8">
            <div class="flex items-center mb-3 flex-wrap gap-2">
              <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                <i class="fas fa-map-marker-alt mr-1"></i>
                <span><?= htmlspecialchars($program['country'] ?? 'Vietnam') ?></span>
              </span>
              <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                <i class="fas fa-university mr-1"></i>
                <span><?= htmlspecialchars($program['university'] ?? 'Partner University') ?></span>
              </span>
            </div>
            <h1 class="text-2xl md:text-4xl font-bold text-white mb-2">
              <?= htmlspecialchars($isKhmer ? ($program['title_km'] ?? $program['title_en']) : ($program['title_en'] ?? 'Erasmus Program')) ?>
            </h1>
            <p class="text-gray-200 text-sm md:text-base">
              <i class="far fa-calendar-alt mr-2"></i>
              <?= htmlspecialchars($isKhmer ? ($program['duration_km'] ?? $program['duration_en']) : ($program['duration_en'] ?? 'Duration')) ?>
            </p>
          </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-6 border-b dark:border-gray-700">
          <div class="flex items-center p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
            <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900 flex items-center justify-center mr-4">
              <i class="fas fa-clock text-red-600 dark:text-red-400 text-xl"></i>
            </div>
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-300"><?= $t('application_deadline') ?></p>
              <p class="text-lg font-semibold text-red-700 dark:text-red-300">
                <?= formatDateLocalized($program['application_deadline'], $currentLang) ?>
              </p>
            </div>
          </div>
          
          <div class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
            <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center mr-4">
              <i class="fas fa-calendar-alt text-blue-600 dark:text-blue-400 text-xl"></i>
            </div>
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-300"><?= $isKhmer ? 'កាលបរិច្ឆេទចាប់ផ្ដើម' : 'Program Start Date' ?></p>
              <p class="text-lg font-semibold text-blue-700 dark:text-blue-300">
                <?= formatDateLocalized($program['start_date'], $currentLang) ?>
              </p>
            </div>
          </div>
          
          <div class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
            <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center mr-4">
              <i class="fas fa-hourglass-half text-green-600 dark:text-green-400 text-xl"></i>
            </div>
            <div>
              <p class="text-sm text-gray-600 dark:text-gray-300"><?= $isKhmer ? 'រយៈពេលកម្មវិធី' : 'Program Duration' ?></p>
              <p class="text-lg font-semibold text-green-700 dark:text-green-300">
                <?= htmlspecialchars($isKhmer ? ($program['duration_km'] ?? $program['duration_en']) : ($program['duration_en'] ?? 'Duration')) ?>
              </p>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Program Details -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6" data-aos="fade-up">
            <h2 class="text-2xl font-bold mb-4 flex items-center">
              <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mr-3"></i>
              <?= $t('about_program') ?>
            </h2>
            <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 leading-relaxed">
              <?= nl2br(htmlspecialchars($isKhmer ? ($program['description_km'] ?? $program['description_en']) : ($program['description_en'] ?? ''))) ?>
            </div>
          </div>
          
          <?php if (!empty($program['requirements_en']) || !empty($program['requirements_km'])): ?>
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6" data-aos="fade-up">
            <h2 class="text-2xl font-bold mb-4 flex items-center">
              <i class="fas fa-list-check text-green-600 dark:text-green-400 mr-3"></i>
              <?= $t('requirements') ?>
            </h2>
            <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">
              <?= nl2br(htmlspecialchars($isKhmer ? ($program['requirements_km'] ?? $program['requirements_en']) : ($program['requirements_en'] ?? ''))) ?>
            </div>
          </div>
          <?php endif; ?>
        </div>
        
        <div class="space-y-6">
          <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl shadow-lg p-6 text-white" data-aos="zoom-in">
            <h3 class="text-xl font-bold mb-4"><?= $t('start_application') ?></h3>
            
            <?php if (isDeadlinePassed($program['application_deadline'])): ?>
              <div class="mb-4 p-3 bg-red-500/20 rounded-lg border border-red-400">
                <p class="font-medium">
                  <i class="fas fa-exclamation-triangle mr-2"></i>
                  <?= $t('applications_closed') ?>
                </p>
              </div>
            <?php else: ?>
              <div class="mb-6">
                <p class="text-sm mb-2"><?= $t('deadline_in') ?>:</p>
                <div id="countdown" class="flex justify-center space-x-2 mb-4">
                  <div class="text-center">
                    <div class="bg-white/20 rounded-lg p-2">
                      <span id="days" class="text-2xl font-bold">00</span>
                    </div>
                    <span class="text-xs mt-1"><?= $t('days') ?></span>
                  </div>
                  <div class="text-center">
                    <div class="bg-white/20 rounded-lg p-2">
                      <span id="hours" class="text-2xl font-bold">00</span>
                    </div>
                    <span class="text-xs mt-1"><?= $t('hours') ?></span>
                  </div>
                  <div class="text-center">
                    <div class="bg-white/20 rounded-lg p-2">
                      <span id="minutes" class="text-2xl font-bold">00</span>
                    </div>
                    <span class="text-xs mt-1"><?= $t('minutes') ?></span>
                  </div>
                  <div class="text-center">
                    <div class="bg-white/20 rounded-lg p-2">
                      <span id="seconds" class="text-2xl font-bold">00</span>
                    </div>
                    <span class="text-xs mt-1"><?= $t('seconds') ?></span>
                  </div>
                </div>
              </div>
              
              <a href="mailto:erasmus@nuck.edu.kh?subject=Application for <?= urlencode($program['title_en'] ?? 'Erasmus Program') ?>" 
                 class="block w-full text-center bg-white text-blue-600 hover:bg-gray-100 font-semibold py-3 rounded-lg transition-colors mb-3">
                <i class="fas fa-paper-plane mr-2"></i>
                <?= $t('apply_now') ?>
              </a>
            <?php endif; ?>
            
            <a href="mailto:erasmus@nuck.edu.kh?subject=Inquiry about <?= urlencode($program['title_en'] ?? 'Erasmus Program') ?>" 
               class="block w-full text-center bg-transparent border border-white hover:bg-white/20 font-semibold py-3 rounded-lg transition-colors">
              <i class="fas fa-envelope mr-2"></i>
              <?= $t('request_info') ?>
            </a>
          </div>
          
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6" data-aos="fade-left">
            <h3 class="text-xl font-bold mb-4 flex items-center">
              <i class="fas fa-headset text-green-600 dark:text-green-400 mr-3"></i>
              <?= $t('contact_information') ?>
            </h3>
            
            <div class="space-y-4">
              <div class="flex items-start">
                <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center mr-4">
                  <i class="fas fa-envelope text-green-600 dark:text-green-400"></i>
                </div>
                <div>
                  <h4 class="font-semibold"><?= $t('email') ?></h4>
                  <a href="mailto:erasmus@nuck.edu.kh" class="text-blue-600 dark:text-blue-400 hover:underline">
                    erasmus@nuck.edu.kh
                  </a>
                </div>
              </div>
              
              <div class="flex items-start">
                <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center mr-4">
                  <i class="fas fa-phone text-blue-600 dark:text-blue-400"></i>
                </div>
                <div>
                  <h4 class="font-semibold"><?= $t('phone') ?></h4>
                  <a href="tel:+85578555159" class="text-blue-600 dark:text-blue-400 hover:underline">
                    +855 78 555 159
                  </a>
                </div>
              </div>
              
              <div class="flex items-start">
                <div class="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-900 flex items-center justify-center mr-4">
                  <i class="fas fa-map-marker-alt text-purple-600 dark:text-purple-400"></i>
                </div>
                <div>
                  <h4 class="font-semibold"><?= $t('location') ?></h4>
                  <p class="text-gray-600 dark:text-gray-300 text-sm">
                    <?= $isKhmer ? 
                        'នាយកដ្ឋានទំនាក់ទំនងអន្តរជាតិ, សាកលវិទ្យាល័យជាតិជាស៊ីមកំចាយមារ' : 
                        'International Relations Office, National University of Cheasim Kamchaymear' ?>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Footer -->
  <footer class="pt-10 sm:pt-12 pb-6">
    <div class="container-custom">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
        <div class="sm:col-span-2">
          <a href="./../../?lang=<?= $currentLang ?>" class="inline-flex items-center gap-2 mb-4">
            <img src="./../../images/logo_footer/nuck_logo.png" alt="NUCK" class="h-10 w-auto">
            <span class="text-lg font-bold text-white <?= $isKhmer ? 'khmer-font' : '' ?>"><?= $isKhmer ? 'សាកលវិទ្យាល័យជាតិជាស៊ីមកំចាយមារ' : 'National University of Cheasim Kamchaymear' ?></span>
          </a>
          <p class="text-gray-400 text-sm leading-relaxed max-w-md <?= $isKhmer ? 'khmer-font' : '' ?>">
            <?= $isKhmer ? 'ផ្លូវជាតិលេខ ៨, ភូមិថ្នល់កែង, ឃុំស្មោងជើង,<br> ស្រុកកំចាយមារ, ខេត្តព្រៃវែង, កម្ពុជា។' : 'National Road 8, Thnal Keng Village, Smoang Cheung Commune,<br> Kamchaymear District, Prey Veng Province, CAMBODIA.' ?>
          </p>
        </div>

        <div>
          <h3 class="text-base sm:text-lg font-bold mb-4 text-white"><?= $t('footer_contacts') ?></h3>
          <ul class="space-y-2 text-sm text-gray-400">
            <li class="flex items-center gap-2">
              <i class="fas fa-phone-alt w-4"></i>
              <a href="tel:0978281168" class="hover:text-yellow-500 transition">097 828 1168</a>
            </li>
            <li class="flex items-center gap-2">
              <i class="fas fa-envelope w-4"></i>
              <a href="mailto:info@nuck.edu.kh" class="hover:text-yellow-500 transition">info@nuck.edu.kh</a>
            </li>
          </ul>
        </div>

        <div>
          <h3 class="text-base sm:text-lg font-bold mb-4 text-white"><?= $t('footer_follow_us') ?></h3>
          <div class="flex gap-3">
            <a href="https://t.me/officialstudentassociationofcsuk" target="_blank" class="w-9 h-9 bg-gray-800 rounded-full flex items-center justify-center hover:bg-yellow-500 hover:text-gray-900 transition-all text-white">
              <i class="fab fa-telegram-plane"></i>
            </a>
            <a href="https://youtube.com/@nuck6666" target="_blank" class="w-9 h-9 bg-gray-800 rounded-full flex items-center justify-center hover:bg-yellow-500 hover:text-gray-900 transition-all text-white">
              <i class="fab fa-youtube"></i>
            </a>
            <a href="https://www.instagram.com/national_university_of_cheasim" target="_blank" class="w-9 h-9 bg-gray-800 rounded-full flex items-center justify-center hover:bg-yellow-500 hover:text-gray-900 transition-all text-white">
              <i class="fab fa-instagram"></i>
            </a>
            <a href="https://web.facebook.com/@NationalUniversityofCheasimkamchaymear" target="_blank" class="w-9 h-9 bg-gray-800 rounded-full flex items-center justify-center hover:bg-yellow-500 hover:text-gray-900 transition-all text-white">
              <i class="fab fa-facebook-f"></i>
            </a>
          </div>
        </div>
      </div>

      <div class="pt-6 border-t border-gray-800 flex flex-col sm:flex-row justify-between items-center gap-4">
        <p class="text-xs sm:text-sm text-gray-400 text-center sm:text-left">
          <?= $t('footer_copyright') ?>
        </p>
        <ul class="flex gap-4 sm:gap-6 text-xs sm:text-sm text-gray-400">
          <li><a href="./../../?lang=<?= $currentLang ?>" class="hover:text-yellow-500 transition"><?= $t('footer_faq') ?></a></li>
          <li><a href="./../../?lang=<?= $currentLang ?>" class="hover:text-yellow-500 transition"><?= $t('footer_privacy') ?></a></li>
          <li><a href="./../../?lang=<?= $currentLang ?>" class="hover:text-yellow-500 transition"><?= $t('footer_terms') ?></a></li>
        </ul>
      </div>
    </div>
  </footer>

  <button id="scroll-top" aria-label="Scroll to top">
    <i class="fas fa-arrow-up"></i>
  </button>

  <script>
    AOS.init({ duration: 800, once: true });

    // Theme System
    const html = document.documentElement;

    function setTheme(theme) {
      if (theme === 'dark') {
        html.classList.add('dark');
        localStorage.setItem('theme', 'dark');
      } else {
        html.classList.remove('dark');
        localStorage.setItem('theme', 'light');
      }
      updateThemeUI(theme === 'dark');
    }

    function updateThemeUI(isDark) {
      const themeToggleDesktop = document.getElementById('theme-toggle-desktop');
      const mobileThemeLight = document.getElementById('mobile-theme-light');
      const mobileThemeDark = document.getElementById('mobile-theme-dark');

      if (themeToggleDesktop) {
        themeToggleDesktop.innerHTML = isDark ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
      }
      if (mobileThemeLight && mobileThemeDark) {
        if (isDark) {
          mobileThemeLight.classList.remove('active');
          mobileThemeDark.classList.add('active');
        } else {
          mobileThemeLight.classList.add('active');
          mobileThemeDark.classList.remove('active');
        }
      }
    }

    function initializeTheme() {
      const savedTheme = localStorage.getItem('theme');
      const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
      setTheme(savedTheme || (systemPrefersDark ? 'dark' : 'light'));
    }

    initializeTheme();

    document.getElementById('theme-toggle-desktop')?.addEventListener('click', () => {
      setTheme(html.classList.contains('dark') ? 'light' : 'dark');
    });

    document.getElementById('mobile-theme-light')?.addEventListener('click', () => {
      setTheme('light');
      setTimeout(closeMobileMenu, 300);
    });

    document.getElementById('mobile-theme-dark')?.addEventListener('click', () => {
      setTheme('dark');
      setTimeout(closeMobileMenu, 300);
    });

    // Mobile Menu
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileOverlay = document.getElementById('mobile-overlay');
    const closeMenuBtn = document.getElementById('close-menu');

    function openMobileMenu() {
      mobileMenu.classList.add('active');
      mobileOverlay.classList.add('active');
      menuToggle.classList.add('active');
      document.body.style.overflow = 'hidden';
    }

    function closeMobileMenu() {
      mobileMenu.classList.remove('active');
      mobileOverlay.classList.remove('active');
      menuToggle.classList.remove('active');
      document.body.style.overflow = '';
      document.querySelectorAll('.mobile-dropdown-content').forEach(d => {
        d.classList.remove('show');
        d.style.maxHeight = '0';
      });
      document.querySelectorAll('.mobile-dropdown-btn').forEach(btn => {
        btn.classList.remove('active');
      });
    }

    menuToggle?.addEventListener('click', openMobileMenu);
    closeMenuBtn?.addEventListener('click', closeMobileMenu);
    mobileOverlay?.addEventListener('click', closeMobileMenu);

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && mobileMenu?.classList.contains('active')) closeMobileMenu();
    });

    window.addEventListener('resize', () => {
      if (window.innerWidth >= 1024) closeMobileMenu();
    });

    document.querySelectorAll('.mobile-dropdown-content').forEach(d => {
      d.style.maxHeight = '0';
    });

    window.toggleMobileDropdown = function(id) {
      const dropdown = document.getElementById(id);
      const btn = event.currentTarget;
      if (!dropdown || !btn) return;

      document.querySelectorAll('.mobile-dropdown-content').forEach(d => {
        if (d.id !== id) {
          d.classList.remove('show');
          d.style.maxHeight = '0';
          d.previousElementSibling?.classList.remove('active');
        }
      });

      const isOpen = dropdown.classList.contains('show');
      dropdown.classList.toggle('show', !isOpen);
      btn.classList.toggle('active', !isOpen);
      dropdown.style.maxHeight = !isOpen ? dropdown.scrollHeight + 'px' : '0';
    };

    const scrollTopBtn = document.getElementById('scroll-top');
    if (scrollTopBtn) {
      window.addEventListener('scroll', () => {
        if (window.scrollY > 500) {
          scrollTopBtn.classList.add('show');
        } else {
          scrollTopBtn.classList.remove('show');
        }
      });
      scrollTopBtn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    }

    // Countdown Timer
    <?php if (!isDeadlinePassed($program['application_deadline']) && $program['application_deadline']): ?>
    const deadline = new Date("<?= date('Y-m-d', strtotime($program['application_deadline'])) ?>T23:59:59").getTime();
    
    function updateCountdown() {
      const now = new Date().getTime();
      const timeLeft = deadline - now;
      
      if (timeLeft < 0) {
        document.getElementById('countdown').innerHTML = '<div class="text-center w-full"><span class="text-lg font-bold"><?= $t('deadline_passed') ?></span></div>';
        return;
      }
      
      const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
      const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
      const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
      
      document.getElementById('days').textContent = days.toString().padStart(2, '0');
      document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
      document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
      document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
    }
    
    updateCountdown();
    setInterval(updateCountdown, 1000);
    <?php endif; ?>
  </script>
</body>
</html>
