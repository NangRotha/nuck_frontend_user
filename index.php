<?php
// Initialize language system at the very top
require_once __DIR__ . '/includes/language.php';
$lang = new Language();
$t = [$lang, 't']; // Helper function reference

// Include database configuration
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/image_helper.php';

$database = new Database();
$db = $database->getConnection();

// Fetch blogs
$query = "SELECT * FROM blogs ORDER BY publish_date DESC LIMIT 6";
$stmt = $db->prepare($query);
$stmt->execute();
$blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="<?= $lang->getCurrentLang() === 'en' ? 'en' : 'km' ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
  <title>NUCK | National University of Cheasim Kamchaymear</title>
  <link rel="shortcut icon" href="./images/logo_footer/nuck_logo.png" type="image/x-icon">
  
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Kantumruy+Pro:wght@300;400;500;600;700&family=Hanuman:wght@100;300;400;700;900&display=swap" rel="stylesheet">

  <script>
    // Configure Tailwind for dark mode
    tailwind.config = {
      darkMode: 'class',
    }
  </script>
  
  <style>
    /* Your existing styles remain exactly the same */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    /* === THEME CSS VARIABLES === */
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

    .khmer-font {
      font-family: 'Kantumruy Pro', sans-serif;
    }
    
    .hanuman-font {
      font-family: 'Hanuman', serif;
    }

    /* === TEXT COLOR UTILITIES === */
    .text-primary {
      color: var(--text-primary) !important;
    }
    .text-secondary {
      color: var(--text-secondary) !important;
    }
    .text-muted {
      color: var(--text-muted) !important;
    }
    .bg-page {
      background-color: var(--bg-primary) !important;
    }
    .bg-section {
      background-color: var(--bg-secondary) !important;
    }
    .bg-card {
      background-color: var(--card-bg) !important;
    }

    /* Navbar Styles */
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

    /* Desktop Navigation */
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
      position: relative;
      font-size: 0.9rem;
      white-space: nowrap;
    }

    .nav-link:hover {
      background: rgba(255, 255, 255, 0.15);
    }

    /* Dropdown Styles */
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
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
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
      border-left-color: #ffd700;
      padding-left: 1.75rem;
    }

    /* Mobile Menu */
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
      background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
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

    .mobile-nav-item {
      margin-bottom: 0.5rem;
    }

    .mobile-nav-link {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      color: white;
      font-size: 1rem;
      font-weight: 500;
      padding: 0.7rem 0.75rem;
      border-radius: 0.5rem;
      transition: all 0.3s ease;
      text-decoration: none;
      width: 100%;
      text-align: left;
      background: transparent;
      border: none;
      cursor: pointer;
    }

    .mobile-nav-link:hover {
      background: rgba(255, 255, 255, 0.1);
    }

    .mobile-dropdown-btn {
      display: flex;
      align-items: center;
      justify-content: space-between;
      width: 100%;
      color: white;
      font-size: 1rem;
      font-weight: 500;
      padding: 0.7rem 0.75rem;
      border-radius: 0.5rem;
      transition: all 0.3s ease;
      cursor: pointer;
      background: transparent;
      border: none;
      text-align: left;
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
      margin-left: 2.5rem;
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
      padding: 0.6rem 1rem;
      transition: all 0.3s ease;
      font-size: 0.9rem;
      text-decoration: none;
    }

    .mobile-dropdown-item:hover {
      color: white;
      padding-left: 1.5rem;
      background: rgba(255, 255, 255, 0.05);
    }

    /* Desktop Actions */
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

    /* Language Switcher Desktop */
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

    /* Theme Toggle */
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

    /* Register Button */
    .register-btn {
      background: linear-gradient(135deg, #ffd700 0%, #ffa500 100%);
      color: #1e3c72;
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

    /* Mobile Language and Theme */
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
      border: 1px solid #ffd700;
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
      border: 1px solid #ffd700;
    }

    /* Slider Styles */
    .slider-container {
      position: relative;
      overflow: hidden;
      height: 300px;
      width: 100%;
    }

    @media (min-width: 640px) {
      .slider-container {
        height: 400px;
      }
    }

    @media (min-width: 1024px) {
      .slider-container {
        height: 500px;
      }
    }
    
    .slide {
      position: absolute;
      width: 100%;
      height: 100%;
      opacity: 0;
      transition: opacity 1s ease-in-out;
    }
    
    .slide.active {
      opacity: 1;
    }
    
    .slide-content {
      position: relative;
      height: 100%;
      background-size: cover;
      background-position: center;
    }
    
    .slide-overlay {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.5);
    }

    .slider-btn {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      width: 36px;
      height: 36px;
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(5px);
      border: none;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      cursor: pointer;
      transition: all 0.3s ease;
      z-index: 10;
      font-size: 1rem;
    }

    @media (min-width: 768px) {
      .slider-btn {
        width: 45px;
        height: 45px;
        font-size: 1.2rem;
      }
    }

    .slider-btn:hover {
      background: rgba(255, 255, 255, 0.3);
    }

    .slider-btn.prev {
      left: 10px;
    }

    .slider-btn.next {
      right: 10px;
    }

    @media (min-width: 768px) {
      .slider-btn.prev {
        left: 20px;
      }

      .slider-btn.next {
        right: 20px;
      }
    }

    .slider-dots {
      position: absolute;
      bottom: 15px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      gap: 6px;
      z-index: 10;
    }

    @media (min-width: 768px) {
      .slider-dots {
        bottom: 20px;
        gap: 8px;
      }
    }

    .slider-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.5);
      cursor: pointer;
      transition: all 0.3s ease;
    }

    @media (min-width: 768px) {
      .slider-dot {
        width: 10px;
        height: 10px;
      }
    }

    .slider-dot.active {
      background: white;
      transform: scale(1.2);
    }

    /* Gradient Button */
    .gradient-btn {
      background: linear-gradient(to right, #0a2e6f, #1a4a9e);
      color: white;
      transition: all 0.3s ease;
    }

    .dark .gradient-btn {
      background: linear-gradient(to right, #2563eb, #3b82f6);
    }
    
    .gradient-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(10, 46, 111, 0.3);
    }

    /* Blog Card */
    .blog-card {
      transition: all 0.3s ease;
      background-color: var(--card-bg);
    }
    
    .blog-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    /* Faculty Card */
    .faculty-card {
      position: relative;
      border-radius: 1rem;
      overflow: hidden;
      transition: all 0.3s ease;
      height: 200px;
    }

    @media (min-width: 640px) {
      .faculty-card {
        height: 220px;
      }
    }

    @media (min-width: 1024px) {
      .faculty-card {
        height: 240px;
      }
    }

    .faculty-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .faculty-card img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s ease;
    }

    .faculty-card:hover img {
      transform: scale(1.1);
    }

    .faculty-card .faculty-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);
      opacity: 0;
      transition: opacity 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem;
      text-align: center;
    }
    
    .faculty-card:hover .faculty-overlay {
      opacity: 1;
    }

    /* Partner Logo */
    .partner-logo {
      filter: grayscale(100%);
      opacity: 0.7;
      transition: all 0.3s ease;
      max-height: 50px;
      object-fit: contain;
    }

    @media (min-width: 768px) {
      .partner-logo {
        max-height: 60px;
      }
    }

    .partner-logo:hover {
      filter: grayscale(0);
      opacity: 1;
      transform: scale(1.1);
    }

    /* Contact Items */
    .contact-item {
      display: flex;
      gap: 1rem;
      margin-bottom: 1.5rem;
    }

    .contact-icon {
      flex-shrink: 0;
      width: 44px;
      height: 44px;
      background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 1.1rem;
      transition: background 0.3s ease;
    }

    .dark .contact-icon {
      background: linear-gradient(135deg, #2563eb, #3b82f6);
    }

    /* Scroll to Top */
    #scroll-top {
      position: fixed;
      bottom: 20px;
      right: 20px;
      width: 40px;
      height: 40px;
      background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
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
      box-shadow: 0 10px 25px rgba(42, 82, 152, 0.4);
    }

    /* Section backgrounds using CSS variables */
    .section-primary {
      background-color: var(--bg-primary);
    }
    .section-secondary {
      background-color: var(--bg-secondary);
    }

    /* Heading text - always correct color */
    .heading-text {
      color: var(--text-primary);
    }
    .body-text {
      color: var(--text-secondary);
    }
    .muted-text {
      color: var(--text-muted);
    }

    /* Utility Classes */
    .line-clamp-3 {
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .line-clamp-2 {
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
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

    /* Blog category badge */
    .badge-blue {
      background-color: #dbeafe;
      color: #1e40af;
    }
    .dark .badge-blue {
      background-color: #1e3a8a;
      color: #bfdbfe;
    }

    /* Divider bar */
    .divider-bar {
      width: 5rem;
      height: 4px;
      background: linear-gradient(to right, #2563eb, #fbbf24);
      margin: 0 auto;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar">
    <div class="container-custom">
      <div class="flex justify-between items-center h-[70px] lg:h-20">
        <!-- Logo -->
        <div class="flex-shrink-0">
          <a href="./?lang=<?= $lang->getCurrentLang() ?>" class="flex items-center">
            <img src="./images/logo/NUCK_Logo_Web.png" alt="NUCK" class="h-9 sm:h-10 lg:h-14 w-auto">
          </a>
        </div>

        <!-- Desktop Navigation -->
        <div class="nav-menu-desktop">
          <a href="./?lang=<?= $lang->getCurrentLang() ?>" class="nav-link"><?= $t('nav_home') ?></a>
          
          <div class="dropdown-container">
            <button class="nav-link flex items-center gap-1">
              <?= $t('nav_resources') ?>
              <i class="fas fa-chevron-down text-xs"></i>
            </button>
            <div class="dropdown-menu">
              <a href="./public/partner/?lang=<?= $lang->getCurrentLang() ?>" class="dropdown-item">
                <i class="fas fa-handshake mr-2"></i> <?= $t('nav_our_partners') ?>
              </a>
              <a href="./public/new&events/?lang=<?= $lang->getCurrentLang() ?>" class="dropdown-item">
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
              <a href="./public/Faculty_of_Science_and_Mathematics/?lang=<?= $lang->getCurrentLang() ?>" class="dropdown-item"><?= $t('faculty_science') ?></a>
              <a href="./public/Faculty_of_Arts_Humanitites_and_Languages/?lang=<?= $lang->getCurrentLang() ?>" class="dropdown-item"><?= $t('faculty_arts') ?></a>
              <a href="./public/Faculty_of_Agriculture/?lang=<?= $lang->getCurrentLang() ?>" class="dropdown-item"><?= $t('faculty_agriculture') ?></a>
              <a href="./public/Faculty_of_social_science/?lang=<?= $lang->getCurrentLang() ?>" class="dropdown-item"><?= $t('faculty_social_science') ?></a>
              <a href="./public/Faculty_of_Management/?lang=<?= $lang->getCurrentLang() ?>" class="dropdown-item"><?= $t('faculty_management') ?></a>
            </div>
          </div>

          <div class="dropdown-container">
            <button class="nav-link flex items-center gap-1">
              <?= $t('nav_about') ?>
              <i class="fas fa-chevron-down text-xs"></i>
            </button>
            <div class="dropdown-menu">
              <a href="./public/about/?lang=<?= $lang->getCurrentLang() ?>" class="dropdown-item"><?= $t('about_message_rector') ?></a>
              <a href="./public/vision-and-mission/?lang=<?= $lang->getCurrentLang() ?>" class="dropdown-item"><?= $t('about_vision_mission') ?></a>
              <a href="./public/history_university/?lang=<?= $lang->getCurrentLang() ?>" class="dropdown-item"><?= $t('about_history') ?></a>
            </div>
          </div>

          <div class="dropdown-container">
            <button class="nav-link flex items-center gap-1">
              <?= $t('nav_projects') ?>
              <i class="fas fa-chevron-down text-xs"></i>
            </button>
            <div class="dropdown-menu">
              <a href="./public/world-bank/?lang=<?= $lang->getCurrentLang() ?>" class="dropdown-item"><?= $t('projects_world_bank') ?></a>
              <a href="./public/Erasmus/?lang=<?= $lang->getCurrentLang() ?>" class="dropdown-item"><?= $t('projects_erasmus') ?></a>
            </div>
          </div>
        </div>

        <!-- Desktop Actions -->
        <div class="desktop-actions">
          <div class="language-switcher">
            <button class="language-btn" id="desktop-language-btn">
              <img src="./images/flage/<?= $lang->getCurrentLang() === 'en' ? 'english.png' : 'cam.png' ?>" alt="<?= strtoupper($lang->getCurrentLang()) ?>">
              <span><?= strtoupper($lang->getCurrentLang()) ?></span>
              <i class="fas fa-chevron-down text-xs"></i>
            </button>
            <div class="language-dropdown" id="desktop-language-dropdown">
              <a href="<?= $lang->getLangUrl('en') ?>" class="language-option">
                <img src="./images/flage/english.png" alt="EN">
                English
              </a>
              <a href="<?= $lang->getLangUrl('km') ?>" class="language-option">
                <img src="./images/flage/cam.png" alt="KH">
                ភាសាខ្មែរ
              </a>
            </div>
          </div>

          <button class="theme-toggle" id="theme-toggle-desktop" aria-label="Toggle theme">
            <i class="fas fa-moon"></i>
          </button>

          <a href="./FormRegister?lang=<?= $lang->getCurrentLang() ?>" class="register-btn">
            <i class="fas fa-user-graduate"></i>
            <span><?= $t('nav_register') ?></span>
          </a>
        </div>

        <!-- Mobile Menu Toggle -->
        <div class="menu-toggle" id="menu-toggle">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>
    </div>
  </nav>

  <!-- Mobile Menu Overlay -->
  <div class="mobile-menu-overlay" id="mobile-overlay"></div>

  <!-- Mobile Menu -->
  <div class="mobile-menu" id="mobile-menu">
    <div class="flex justify-end mb-4">
      <button class="text-white text-2xl p-1" id="close-menu">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <div class="space-y-1">
      <div class="mobile-nav-item">
        <a href="./?lang=<?= $lang->getCurrentLang() ?>" class="mobile-nav-link">
          <i class="fas fa-home w-6"></i>
          <?= $t('nav_home') ?>
        </a>
      </div>

      <div class="mobile-nav-item">
        <button class="mobile-dropdown-btn" onclick="toggleMobileDropdown('resources')">
          <span>
            <i class="fas fa-folder-open w-6"></i>
            <?= $t('nav_resources') ?>
          </span>
          <i class="fas fa-chevron-down"></i>
        </button>
        <div class="mobile-dropdown-content" id="resources">
          <a href="./public/partner/?lang=<?= $lang->getCurrentLang() ?>" class="mobile-dropdown-item">
            <i class="fas fa-handshake mr-2"></i> <?= $t('nav_our_partners') ?>
          </a>
          <a href="./public/new&events/?lang=<?= $lang->getCurrentLang() ?>" class="mobile-dropdown-item">
            <i class="fas fa-calendar-alt mr-2"></i> <?= $t('nav_news_events') ?>
          </a>
        </div>
      </div>

      <div class="mobile-nav-item">
        <button class="mobile-dropdown-btn" onclick="toggleMobileDropdown('academics')">
          <span>
            <i class="fas fa-book w-6"></i>
            <?= $t('nav_academics') ?>
          </span>
          <i class="fas fa-chevron-down"></i>
        </button>
        <div class="mobile-dropdown-content" id="academics">
          <a href="./public/Faculty_of_Science_and_Mathematics/?lang=<?= $lang->getCurrentLang() ?>" class="mobile-dropdown-item"><?= $t('faculty_science') ?></a>
          <a href="./public/Faculty_of_Arts_Humanitites_and_Languages/?lang=<?= $lang->getCurrentLang() ?>" class="mobile-dropdown-item"><?= $t('faculty_arts') ?></a>
          <a href="./public/Faculty_of_Agriculture/?lang=<?= $lang->getCurrentLang() ?>" class="mobile-dropdown-item"><?= $t('faculty_agriculture') ?></a>
          <a href="./public/Faculty_of_social_science/?lang=<?= $lang->getCurrentLang() ?>" class="mobile-dropdown-item"><?= $t('faculty_social_science') ?></a>
          <a href="./public/Faculty_of_Management/?lang=<?= $lang->getCurrentLang() ?>" class="mobile-dropdown-item"><?= $t('faculty_management') ?></a>
        </div>
      </div>

      <div class="mobile-nav-item">
        <button class="mobile-dropdown-btn" onclick="toggleMobileDropdown('about')">
          <span>
            <i class="fas fa-info-circle w-6"></i>
            <?= $t('nav_about') ?>
          </span>
          <i class="fas fa-chevron-down"></i>
        </button>
        <div class="mobile-dropdown-content" id="about">
          <a href="./public/about/?lang=<?= $lang->getCurrentLang() ?>" class="mobile-dropdown-item"><?= $t('about_message_rector') ?></a>
          <a href="./public/vision-and-mission/?lang=<?= $lang->getCurrentLang() ?>" class="mobile-dropdown-item"><?= $t('about_vision_mission') ?></a>
          <a href="./public/history_university/?lang=<?= $lang->getCurrentLang() ?>" class="mobile-dropdown-item"><?= $t('about_history') ?></a>
        </div>
      </div>

      <div class="mobile-nav-item">
        <button class="mobile-dropdown-btn" onclick="toggleMobileDropdown('projects')">
          <span>
            <i class="fas fa-project-diagram w-6"></i>
            <?= $t('nav_projects') ?>
          </span>
          <i class="fas fa-chevron-down"></i>
        </button>
        <div class="mobile-dropdown-content" id="projects">
          <a href="./public/world-bank/?lang=<?= $lang->getCurrentLang() ?>" class="mobile-dropdown-item"><?= $t('projects_world_bank') ?></a>
          <a href="./public/Erasmus/?lang=<?= $lang->getCurrentLang() ?>" class="mobile-dropdown-item"><?= $t('projects_erasmus') ?></a>
        </div>
      </div>
    </div>

    <div class="mobile-language-section">
      <div class="mobile-section-title"><?= $t('language') ?></div>
      <div class="mobile-language-options">
        <a href="<?= $lang->getLangUrl('en') ?>" class="mobile-language-option <?= $lang->getCurrentLang() === 'en' ? 'active' : '' ?>" id="mobile-lang-en">
          <img src="./images/flage/english.png" alt="EN">
          <span>EN</span>
        </a>
        <a href="<?= $lang->getLangUrl('km') ?>" class="mobile-language-option <?= $lang->getCurrentLang() === 'km' ? 'active' : '' ?>" id="mobile-lang-km">
          <img src="./images/flage/cam.png" alt="KH">
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

      <a href="./FormRegister?lang=<?= $lang->getCurrentLang() ?>" class="register-btn w-full justify-center mt-6 py-3">
        <i class="fas fa-user-graduate"></i>
        <?= $t('nav_register') ?> Now
      </a>
    </div>
  </div>

  <!-- Image Slider -->
  <div class="slider-container">
    <div class="slide active">
      <div class="slide-content" style="background-image: url('./images/slide_images/slide1.jpg')">
        <div class="slide-overlay flex items-center justify-center">
          <div class="text-center text-white px-4 max-w-4xl">
            <h1 class="hanuman-font text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-bold mb-2 sm:mb-4"><?= $t('slider_welcome_title') ?></h1>
            <p class="khmer-font text-sm sm:text-base md:text-lg lg:text-xl mb-4 sm:mb-6"><?= $t('slider_welcome_subtitle') ?></p>
          </div>
        </div>
      </div>
    </div>

    <div class="slide">
      <div class="slide-content" style="background-image: url('./images/slide_images/slide2.jpg')">
        <div class="slide-overlay flex items-center justify-center">
          <div class="text-center text-white px-4 max-w-4xl">
            <h1 class="hanuman-font text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-bold mb-2 sm:mb-4"><?= $t('slider_library_title') ?></h1>
            <p class="khmer-font text-sm sm:text-base md:text-lg lg:text-xl mb-4 sm:mb-6"><?= $t('slider_library_subtitle') ?></p>
          </div>
        </div>
      </div>
    </div>

    <div class="slide">
      <div class="slide-content" style="background-image: url('./images/slide_images/slide3.jpg')">
        <div class="slide-overlay flex items-center justify-center">
          <div class="text-center text-white px-4 max-w-4xl">
            <h1 class="hanuman-font text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-bold mb-2 sm:mb-4"><?= $t('slider_lab_title') ?></h1>
            <p class="khmer-font text-sm sm:text-base md:text-lg lg:text-xl mb-4 sm:mb-6"><?= $t('slider_lab_subtitle') ?></p>
          </div>
        </div>
      </div>
    </div>

    <button class="slider-btn prev" id="prev-slide">
      <i class="fas fa-chevron-left"></i>
    </button>
    <button class="slider-btn next" id="next-slide">
      <i class="fas fa-chevron-right"></i>
    </button>

    <div class="slider-dots">
      <div class="slider-dot active" onclick="goToSlide(0)"></div>
      <div class="slider-dot" onclick="goToSlide(1)"></div>
      <div class="slider-dot" onclick="goToSlide(2)"></div>
    </div>
  </div>

  <!-- About Section -->
  <section class="py-10 sm:py-12 md:py-16 lg:py-20 section-primary">
    <div class="container-custom">
      <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-8 lg:gap-12">
        <div class="order-2 lg:order-1">
          <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold heading-text mb-4">
            <?= $t('about_title') ?>
          </h2>
          <p class="mt-4 body-text text-sm sm:text-base leading-relaxed">
            <?= $t('about_text_1') ?>
          </p>
          <p class="mt-4 body-text text-sm sm:text-base leading-relaxed">
            <?= $t('about_text_2') ?>
          </p>
          <div class="mt-6">
            <a href="./public/about/?lang=<?= $lang->getCurrentLang() ?>" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
              <?= $t('about_learn_more') ?>
              <i class="fas fa-arrow-right ml-2"></i>
            </a>
          </div>
        </div>
        <div class="order-1 lg:order-2">
          <img src="./images/about/RECTOR.png" alt="Rector" class="rounded-lg shadow-xl w-full h-auto" />
        </div>
      </div>
    </div>
  </section>

  <!-- Registration Card -->
  <section class="py-10 sm:py-12 md:py-16 bg-gradient-to-r from-blue-900 to-blue-700">
    <div class="container-custom max-w-4xl">
      <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 sm:p-8 md:p-12 text-center">
        <div class="inline-block bg-blue-100/20 p-4 rounded-full mb-6">
          <i class="fas fa-user-graduate text-4xl sm:text-5xl text-yellow-400"></i>
        </div>
        
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-2"><?= $t('register_title') ?></h2>
        <p class="text-lg sm:text-xl text-white/90 mb-4 khmer-font"><?= $t('register_subtitle') ?></p>
        
        <p class="text-white/80 mb-8 max-w-2xl mx-auto text-sm sm:text-base">
          <?= $t('register_description') ?>
        </p>
        
        <a href="./FormRegister?lang=<?= $lang->getCurrentLang() ?>" class="inline-block gradient-btn text-white font-bold text-base sm:text-lg py-3 sm:py-4 px-8 sm:px-12 rounded-lg transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl">
          <span class="block"><?= $t('register_button') ?></span>
          <span class="khmer-font text-xs sm:text-sm mt-1 opacity-90"><?= $t('register_button_sub') ?></span>
        </a>
        
        <p class="text-white/60 text-xs sm:text-sm mt-4"><?= $t('register_note') ?></p>
      </div>
    </div>
  </section>

  <!-- News & Events Section -->
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Hanuman&display=swap');
    .Hanuman-text {
        font-family: 'Hanuman', sans-serif;
    }
    
    .blog-card {
        transition: all 0.3s ease;
    }
    
    .blog-card:hover {
        transform: translateY(-5px);
    }
  </style>

  <section class="py-16 px-4">
    <div class="max-w-7xl mx-auto">
      <section class="py-10 sm:py-12 md:py-16 px-4 section-primary">
        <div class="container-custom">
          <div class="text-center mb-8 md:mb-12">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold heading-text mb-2">
              <?= $t('news_title') ?>
            </h2>
            <div class="divider-bar mt-2"></div>
          </div>

          <div class="Hanuman-text grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (!empty($blogs)): ?>
              <?php foreach ($blogs as $blog): ?>
                <?php
                  $imageURL = getImagePath($blog['image_path']);
                  $content = strip_tags($blog['content']);
                  $excerpt = mb_substr($content, 0, 150, 'UTF-8');
                ?>
                <article class="blog-card bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-md hover:shadow-xl">
                  <img 
                    src="<?= htmlspecialchars($imageURL) ?>" 
                    alt="<?= htmlspecialchars($blog['title']) ?>" 
                    class="w-full h-48 object-cover"
                    onerror="this.src='/images/placeholder.png';"
                  >
                  <div class="p-6">
                    <div class="flex items-center text-sm mb-3">
                      <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-100 px-3 py-1 rounded-full">
                        <?= htmlspecialchars($blog['category'] ?? 'Uncategorized') ?>
                      </span>
                      <span class="mx-2 text-gray-500">|</span>
                      <span class="text-gray-500 dark:text-gray-400">
                        <?= $blog['publish_date'] ? date('d M Y', strtotime($blog['publish_date'])) : 'Date not set' ?>
                      </span>
                    </div>
                    <h3 class="text-xl font-bold mb-2 dark:text-white"><?= htmlspecialchars($blog['title']) ?></h3>
                    <p class="text-gray-600 dark:text-gray-300 line-clamp-3">
                      <?= htmlspecialchars($excerpt) ?>...
                    </p>
                    <div class="mt-4">
                      <a href="./blog-detail.php?slug=<?= urlencode($blog['slug']) ?>&lang=<?= $lang->getCurrentLang() ?>" 
                        class="text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300 font-medium transition-colors">
                        <?= $t('news_read_more') ?> →
                      </a>
                    </div>
                  </div>
                </article>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="col-span-3 text-center text-gray-500 dark:text-gray-400 py-12">
                No news articles found.
              </div>
            <?php endif; ?>
          </div>

          <div class="flex justify-center mt-12">
            <a href="./public/new&events/?lang=<?= $lang->getCurrentLang() ?>" 
              class="Hanuman-text bg-university-blue hover:bg-university-gold 
                      dark:bg-university-gold dark:hover:bg-university-blue 
                      text-black dark:text-white px-8 py-3 rounded-lg 
                      transition-colors duration-300 font-medium 
                      focus:ring-2 focus:ring-university-blue 
                      focus:ring-offset-2 dark:focus:ring-university-gold">
              <span class="ml-2"><?= $t('news_view_all') ?></span>
            </a>
          </div>
        </div>
      </section>
    </div>
  </section>

  <!-- Academics Section -->
  <section class="py-10 sm:py-12 md:py-16 section-secondary">
    <div class="container-custom">
      <div class="text-center mb-8 md:mb-12">
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold heading-text mb-2">
          <?= $t('nav_academics') ?>
        </h2>
        <div class="divider-bar mt-2"></div>
      </div>

      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4 md:gap-6">
        <a href="./public/Faculty_of_Agriculture/?lang=<?= $lang->getCurrentLang() ?>" class="faculty-card group">
          <img src="./images/ademices/social.png" alt="Agriculture">
          <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
          <div class="absolute bottom-0 left-0 right-0 p-3 sm:p-4 text-white">
            <h3 class="text-xs sm:text-sm font-bold mb-1 line-clamp-2"><?= $lang->getCurrentLang() === 'en' ? 'Faculty of Agriculture' : 'មហាវិទ្យាល័យកសិកម្ម' ?></h3>
            <p class="text-[10px] sm:text-xs opacity-90"><?= $t('faculty_agriculture') ?></p>
          </div>
          <div class="faculty-overlay">
            <div>
              <h3 class="text-xs sm:text-sm font-bold text-white mb-1"><?= $lang->getCurrentLang() === 'en' ? 'Faculty of Agriculture' : 'មហាវិទ្យាល័យកសិកម្ម' ?></h3>
              <p class="text-white text-[10px] sm:text-xs mb-2"><?= $t('faculty_agriculture') ?></p>
              <span class="inline-block bg-white text-blue-900 px-2 sm:px-3 py-1 rounded-lg text-[10px] sm:text-xs font-medium">
                <?= $t('view') ?> <i class="fas fa-arrow-right ml-1"></i>
              </span>
            </div>
          </div>
        </a>

        <a href="./public/Faculty_of_Arts_Humanitites_and_Languages/?lang=<?= $lang->getCurrentLang() ?>" class="faculty-card group">
          <img src="./images/ademices/art.jpg" alt="Arts">
          <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
          <div class="absolute bottom-0 left-0 right-0 p-3 sm:p-4 text-white">
            <h3 class="text-xs sm:text-sm font-bold mb-1 line-clamp-2"><?= $lang->getCurrentLang() === 'en' ? 'Faculty of Arts' : 'មហាវិទ្យាល័យសិល្បៈ' ?></h3>
            <p class="text-[10px] sm:text-xs opacity-90"><?= $t('faculty_arts') ?></p>
          </div>
          <div class="faculty-overlay">
            <div>
              <h3 class="text-xs sm:text-sm font-bold text-white mb-1"><?= $lang->getCurrentLang() === 'en' ? 'Faculty of Arts' : 'មហាវិទ្យាល័យសិល្បៈ' ?></h3>
              <p class="text-white text-[10px] sm:text-xs mb-2"><?= $t('faculty_arts') ?></p>
              <span class="inline-block bg-white text-blue-900 px-2 sm:px-3 py-1 rounded-lg text-[10px] sm:text-xs font-medium">
                <?= $t('view') ?> <i class="fas fa-arrow-right ml-1"></i>
              </span>
            </div>
          </div>
        </a>

        <a href="./public/Faculty_of_Management/?lang=<?= $lang->getCurrentLang() ?>" class="faculty-card group">
          <img src="./images/ademices/management.jpg" alt="Management">
          <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
          <div class="absolute bottom-0 left-0 right-0 p-3 sm:p-4 text-white">
            <h3 class="text-xs sm:text-sm font-bold mb-1 line-clamp-2"><?= $lang->getCurrentLang() === 'en' ? 'Faculty of Management' : 'មហាវិទ្យាល័យគ្រប់គ្រង' ?></h3>
            <p class="text-[10px] sm:text-xs opacity-90"><?= $t('faculty_management') ?></p>
          </div>
          <div class="faculty-overlay">
            <div>
              <h3 class="text-xs sm:text-sm font-bold text-white mb-1"><?= $lang->getCurrentLang() === 'en' ? 'Faculty of Management' : 'មហាវិទ្យាល័យគ្រប់គ្រង' ?></h3>
              <p class="text-white text-[10px] sm:text-xs mb-2"><?= $t('faculty_management') ?></p>
              <span class="inline-block bg-white text-blue-900 px-2 sm:px-3 py-1 rounded-lg text-[10px] sm:text-xs font-medium">
                <?= $t('view') ?> <i class="fas fa-arrow-right ml-1"></i>
              </span>
            </div>
          </div>
        </a>

        <a href="./public/Faculty_of_Science_and_Mathematics/?lang=<?= $lang->getCurrentLang() ?>" class="faculty-card group">
          <img src="./images/ademices/it.png" alt="Science">
          <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
          <div class="absolute bottom-0 left-0 right-0 p-3 sm:p-4 text-white">
            <h3 class="text-xs sm:text-sm font-bold mb-1 line-clamp-2"><?= $lang->getCurrentLang() === 'en' ? 'Faculty of Science' : 'មហាវិទ្យាល័យវិទ្យាសាស្រ្ត' ?></h3>
            <p class="text-[10px] sm:text-xs opacity-90"><?= $t('faculty_science') ?></p>
          </div>
          <div class="faculty-overlay">
            <div>
              <h3 class="text-xs sm:text-sm font-bold text-white mb-1"><?= $lang->getCurrentLang() === 'en' ? 'Faculty of Science' : 'មហាវិទ្យាល័យវិទ្យាសាស្រ្ត' ?></h3>
              <p class="text-white text-[10px] sm:text-xs mb-2"><?= $t('faculty_science') ?></p>
              <span class="inline-block bg-white text-blue-900 px-2 sm:px-3 py-1 rounded-lg text-[10px] sm:text-xs font-medium">
                <?= $t('view') ?> <i class="fas fa-arrow-right ml-1"></i>
              </span>
            </div>
          </div>
        </a>

        <a href="./public/Faculty_of_social_science/?lang=<?= $lang->getCurrentLang() ?>" class="faculty-card group">
          <img src="./images/ademices/Development.jpg" alt="Social Science">
          <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
          <div class="absolute bottom-0 left-0 right-0 p-3 sm:p-4 text-white">
            <h3 class="text-xs sm:text-sm font-bold mb-1 line-clamp-2"><?= $lang->getCurrentLang() === 'en' ? 'Faculty of Social Science' : 'មហាវិទ្យាល័យសង្គម' ?></h3>
            <p class="text-[10px] sm:text-xs opacity-90"><?= $t('faculty_social_science') ?></p>
          </div>
          <div class="faculty-overlay">
            <div>
              <h3 class="text-xs sm:text-sm font-bold text-white mb-1"><?= $lang->getCurrentLang() === 'en' ? 'Faculty of Social Science' : 'មហាវិទ្យាល័យសង្គម' ?></h3>
              <p class="text-white text-[10px] sm:text-xs mb-2"><?= $t('faculty_social_science') ?></p>
              <span class="inline-block bg-white text-blue-900 px-2 sm:px-3 py-1 rounded-lg text-[10px] sm:text-xs font-medium">
                <?= $t('view') ?> <i class="fas fa-arrow-right ml-1"></i>
              </span>
            </div>
          </div>
        </a>
      </div>
    </div>
  </section>

  <!-- Partners Section -->
  <section class="py-10 sm:py-12 md:py-16">
    <div class="container-custom">
      <div class="text-center mb-8 md:mb-12">
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold heading-text mb-2">
          <?= $t('partners_title') ?>
        </h2>
        <div class="divider-bar mt-2"></div>
      </div>

      <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-7 gap-4 sm:gap-6 items-center">
        <div class="flex justify-center">
          <img src="./images/parthner/rupp-logo.svg" alt="RUPP" class="partner-logo">
        </div>
        <div class="flex justify-center">
          <img src="./images/parthner/partners_ErasmusPlus.png" alt="Erasmus+" class="partner-logo">
        </div>
        <div class="flex justify-center">
          <img src="./images/parthner/brand1.svg" alt="Partner" class="partner-logo">
        </div>
        <div class="flex justify-center">
          <img src="./images/parthner/usaid.svg" alt="USAID" class="partner-logo">
        </div>
        <div class="flex justify-center">
          <img src="./images/parthner/partners_NutriSEA 1.svg" alt="NutriSEA" class="partner-logo">
        </div>
        <div class="flex justify-center">
          <img src="./images/parthner/UBB-logo-small.png" alt="UBB" class="partner-logo">
        </div>
        <div class="flex justify-center">
          <img src="./images/parthner/partners_Unicam.jpg" alt="Unicam" class="partner-logo">
        </div>
      </div>
    </div>
  </section>

  <!-- Contact Section -->
  <section class="py-10 sm:py-12 md:py-16 section-secondary" id="contact">
    <div class="container-custom">
      <div class="text-center mb-8 md:mb-12">
        <p class="text-blue-600 dark:text-blue-400 font-semibold uppercase tracking-wide text-sm mb-2"><?= $t('contact_title') ?></p>
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold heading-text mb-2">
          <?= $t('contact_title') ?>
        </h2>
        <div class="divider-bar mt-2"></div>
        <p class="mt-4 body-text max-w-2xl mx-auto text-sm sm:text-base">
          <?= $t('contact_subtitle') ?>
        </p>
      </div>

      <div class="grid lg:grid-cols-2 gap-8 lg:gap-12">
        <div>
          <div class="contact-item">
            <div class="contact-icon">
              <i class="fas fa-map-marker-alt"></i>
            </div>
            <div>
              <h3 class="font-bold heading-text mb-1 text-base sm:text-lg"><?= $t('contact_address') ?></h3>
              <p class="body-text text-sm sm:text-base">
                National Road 8, Thnal Keng Village, Smoang Cheung Commune,<br>
                Kamchaymear District, Prey Veng Province, CAMBODIA.
              </p>
            </div>
          </div>

          <div class="contact-item">
            <div class="contact-icon">
              <i class="fas fa-phone-alt"></i>
            </div>
            <div>
              <h3 class="font-bold heading-text mb-1 text-base sm:text-lg"><?= $t('contact_phone') ?></h3>
              <p class="body-text text-sm sm:text-base">Phone: 012 358 894 / 015 358 294</p>
              <p class="body-text text-sm sm:text-base">Email: info@nuck.edu.kh</p>
            </div>
          </div>

          <div class="contact-item">
            <div class="contact-icon">
              <i class="fas fa-clock"></i>
            </div>
            <div>
              <h3 class="font-bold heading-text mb-1 text-base sm:text-lg"><?= $t('contact_hours') ?></h3>
              <p class="body-text text-sm sm:text-base"><?= $t('contact_hours_week') ?></p>
              <p class="body-text text-sm sm:text-base"><?= $t('contact_hours_weekend') ?></p>
            </div>
          </div>
        </div>

        <div class="h-64 sm:h-72 md:h-80 lg:h-96 rounded-xl overflow-hidden shadow-lg">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3908.712915616002!2d105.6570435!3d11.5724263!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x310b86ece26c69dd%3A0x1d6d525716c4ba97!2z4Z6f4Z624Z6A4Z6b4Z6c4Z634Z6R4Z-S4Z6Z4Z624Z6b4Z-Q4Z6Z4Z6H4Z624Z6P4Z634Z6H4Z624Z6f4Z-K4Z644Z6Y4Z6A4Z-G4Z6F4Z624Z6Z4Z6Y4Z624Z6a!5e0!3m2!1skm!2skh!4v1736206853453!5m2!1skm!2skh"
            width="100%"
            height="100%"
            style="border:0;"
            allowfullscreen
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            title="University Location"
          ></iframe>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="pt-10 sm:pt-12 pb-6" style="background-color: var(--footer-bg); color: white;">
    <div class="container-custom">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
           <div class="sm:col-span-2">
          <a href="./../../?lang=<?= $lang->getCurrentLang() ?>" class="inline-flex items-center gap-2 mb-4">
            <img src="./images/logo_footer/nuck_logo.png" alt="NUCK" class="h-10 w-auto">
            <span class="text-lg font-bold text-white <?= $lang->getCurrentLang() === 'km' ? 'khmer-font' : '' ?>"><?= $lang->getCurrentLang() === 'km' ? 'សាកលវិទ្យាល័យជាតិជាស៊ីមកំចាយមារ' : 'National University of Cheasim Kamchaymear' ?></span>
          </a>
          <p class="text-gray-400 text-sm leading-relaxed max-w-md <?= $lang->getCurrentLang() === 'km' ? 'khmer-font' : '' ?>">
            <?= $lang->getCurrentLang() === 'km' ? 'ផ្លូវជាតិលេខ ៨, ភូមិថ្នល់កែង, ឃុំស្មោងជើង,<br> ស្រុកកំចាយមារ, ខេត្តព្រៃវែង, កម្ពុជា។' : 'National Road 8, Thnal Keng Village, Smoang Cheung Commune,<br> Kamchaymear District, Prey Veng Province, CAMBODIA.' ?>
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
          <li><a href="./?lang=<?= $lang->getCurrentLang() ?>" class="hover:text-yellow-500 transition"><?= $t('footer_faq') ?></a></li>
          <li><a href="./?lang=<?= $lang->getCurrentLang() ?>" class="hover:text-yellow-500 transition"><?= $t('footer_privacy') ?></a></li>
          <li><a href="./?lang=<?= $lang->getCurrentLang() ?>" class="hover:text-yellow-500 transition"><?= $t('footer_terms') ?></a></li>
        </ul>
      </div>
    </div>
  </footer>

  <!-- Scroll to Top Button -->
  <button id="scroll-top" aria-label="Scroll to top">
    <i class="fas fa-arrow-up"></i>
  </button>

  <script>
    // =============================================
    // THEME SYSTEM - CSS Variables based (reliable)
    // =============================================
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
      const theme = savedTheme || (systemPrefersDark ? 'dark' : 'light');
      setTheme(theme);
    }

    // Initialize on page load
    initializeTheme();

    // Desktop toggle
    document.getElementById('theme-toggle-desktop')?.addEventListener('click', () => {
      const isDark = html.classList.contains('dark');
      setTheme(isDark ? 'light' : 'dark');
    });

    // Mobile toggles
    document.getElementById('mobile-theme-light')?.addEventListener('click', () => {
      setTheme('light');
      setTimeout(closeMobileMenu, 300);
    });

    document.getElementById('mobile-theme-dark')?.addEventListener('click', () => {
      setTheme('dark');
      setTimeout(closeMobileMenu, 300);
    });

    // System theme change listener
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
      if (!localStorage.getItem('theme')) {
        setTheme(e.matches ? 'dark' : 'light');
      }
    });

    // =============================================
    // MOBILE MENU
    // =============================================
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
    mobileMenu?.addEventListener('click', (e) => e.stopPropagation());

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && mobileMenu?.classList.contains('active')) closeMobileMenu();
    });

    window.addEventListener('resize', () => {
      if (window.innerWidth >= 1024) closeMobileMenu();
    });

    // =============================================
    // MOBILE DROPDOWNS
    // =============================================
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

    // =============================================
    // IMAGE SLIDER
    // =============================================
    let currentSlide = 0;
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.slider-dot');

    function showSlide(index) {
      if (!slides.length) return;
      if (index >= slides.length) currentSlide = 0;
      else if (index < 0) currentSlide = slides.length - 1;
      else currentSlide = index;

      slides.forEach((s, i) => s.classList.toggle('active', i === currentSlide));
      dots.forEach((d, i) => d.classList.toggle('active', i === currentSlide));
    }

    window.goToSlide = (index) => showSlide(index);

    document.getElementById('prev-slide')?.addEventListener('click', () => showSlide(currentSlide - 1));
    document.getElementById('next-slide')?.addEventListener('click', () => showSlide(currentSlide + 1));

    let slideInterval = setInterval(() => showSlide(currentSlide + 1), 5000);
    const sliderContainer = document.querySelector('.slider-container');
    sliderContainer?.addEventListener('mouseenter', () => clearInterval(slideInterval));
    sliderContainer?.addEventListener('mouseleave', () => {
      slideInterval = setInterval(() => showSlide(currentSlide + 1), 5000);
    });

    // =============================================
    // SCROLL TO TOP
    // =============================================
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
  </script>
</body>
</html>