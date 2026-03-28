<?php
// Include language system
require_once __DIR__ . '/../../../includes/language.php';

// Get translation function
$t = function($key, $default = '') use ($lang) {
    return $lang->t($key, $default);
};
?>
<!DOCTYPE html>
<html lang="<?= $lang->getCurrentLang() === 'en' ? 'en' : 'km' ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
  <title><?= $lang->getCurrentLang() === 'km' ? 'រដ្ឋបាលសាធារណៈ' : 'Public Administration' ?> | Faculty of Social Science - NUCK</title>
  <link rel="shortcut icon" href="./../../../images/logo_footer/nuck_logo.png" type="image/x-icon">
  
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

    /* Khmer Font Styles */
    .khmer-font, 
    html[lang="km"] body,
    html[lang="km"] p,
    html[lang="km"] span,
    html[lang="km"] h1,
    html[lang="km"] h2,
    html[lang="km"] h3,
    html[lang="km"] h4,
    html[lang="km"] .nav-link,
    html[lang="km"] .dropdown-item,
    html[lang="km"] .mobile-nav-link,
    html[lang="km"] .mobile-dropdown-item {
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

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .fade-in {
      animation: fadeIn 1s ease-in-out;
    }

    .career-grid {
      display: grid;
      grid-template-columns: repeat(1, 1fr);
      gap: 1rem;
    }

    @media (min-width: 640px) {
      .career-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    .career-item {
      background-color: var(--bg-secondary);
      padding: 1rem;
      border-radius: 0.5rem;
      transition: all 0.3s ease;
      border-left: 4px solid var(--primary-color);
    }

    .career-item:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
<body class="<?= $lang->getCurrentLang() === 'km' ? 'khmer-font' : '' ?>">
  <!-- Navbar -->
  <nav class="navbar">
    <div class="container-custom">
      <div class="flex justify-between items-center h-[70px] lg:h-20">
        <div class="flex-shrink-0">
          <a href="./../../../?lang=<?= $lang->getCurrentLang() ?>" class="flex items-center">
            <img src="./../../../images/logo/NUCK_Logo_Web.png" alt="NUCK" class="h-9 sm:h-10 lg:h-14 w-auto">
          </a>
        </div>

        <div class="nav-menu-desktop">
          <a href="./../../../?lang=<?= $lang->getCurrentLang() ?>" class="nav-link"><?= $t('nav_home') ?></a>
          
          <div class="dropdown-container">
            <button class="nav-link flex items-center gap-1">
              <?= $t('nav_resources') ?>
              <i class="fas fa-chevron-down text-xs"></i>
            </button>
            <div class="dropdown-menu">
              <a href="./../../../public/partner/?lang=<?= $lang->getCurrentLang() ?>" class="dropdown-item">
                <i class="fas fa-handshake mr-2"></i> <?= $t('nav_our_partners') ?>
              </a>
              <a href="./../../../public/new&events/?lang=<?= $lang->getCurrentLang() ?>" class="dropdown-item">
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
              <a href="./../../../public/Faculty_of_Science_and_Mathematics/?lang=<?= $lang->getCurrentLang() ?>" class="dropdown-item"><?= $t('faculty_science') ?></a>
              <a href="./../../../public/Faculty_of_Arts_Humanitites_and_Languages/?lang=<?= $lang->getCurrentLang() ?>" class="dropdown-item"><?= $t('faculty_arts') ?></a>
              <a href="./../../../public/Faculty_of_Agriculture/?lang=<?= $lang->getCurrentLang() ?>" class="dropdown-item"><?= $t('faculty_agriculture') ?></a>
              <a href="./../../../public/Faculty_of_social_science/?lang=<?= $lang->getCurrentLang() ?>" class="dropdown-item"><?= $t('faculty_social_science') ?></a>
              <a href="./../../../public/Faculty_of_Management/?lang=<?= $lang->getCurrentLang() ?>" class="dropdown-item"><?= $t('faculty_management') ?></a>
            </div>
          </div>

          <div class="dropdown-container">
            <button class="nav-link flex items-center gap-1">
              <?= $t('nav_about') ?>
              <i class="fas fa-chevron-down text-xs"></i>
            </button>
            <div class="dropdown-menu">
              <a href="./../../../public/about/?lang=<?= $lang->getCurrentLang() ?>" class="dropdown-item"><?= $t('about_message_rector') ?></a>
              <a href="./../../../public/vision-and-mission/?lang=<?= $lang->getCurrentLang() ?>" class="dropdown-item"><?= $t('about_vision_mission') ?></a>
              <a href="./../../../public/history_university/?lang=<?= $lang->getCurrentLang() ?>" class="dropdown-item"><?= $t('about_history') ?></a>
            </div>
          </div>

          <div class="dropdown-container">
            <button class="nav-link flex items-center gap-1">
              <?= $t('nav_projects') ?>
              <i class="fas fa-chevron-down text-xs"></i>
            </button>
            <div class="dropdown-menu">
              <a href="./../../../public/world-bank/?lang=<?= $lang->getCurrentLang() ?>" class="dropdown-item"><?= $t('projects_world_bank') ?></a>
              <a href="./../../../public/Erasmus/?lang=<?= $lang->getCurrentLang() ?>" class="dropdown-item"><?= $t('projects_erasmus') ?></a>
            </div>
          </div>
        </div>

        <div class="desktop-actions">
          <div class="language-switcher">
            <button class="language-btn">
              <img src="./../../../images/flage/<?= $lang->getCurrentLang() === 'en' ? 'english.png' : 'cam.png' ?>" alt="<?= strtoupper($lang->getCurrentLang()) ?>">
              <span><?= strtoupper($lang->getCurrentLang()) ?></span>
              <i class="fas fa-chevron-down text-xs"></i>
            </button>
            <div class="language-dropdown">
              <a href="<?= $lang->getLangUrl('en') ?>" class="language-option">
                <img src="./../../../images/flage/english.png" alt="EN">
                English
              </a>
              <a href="<?= $lang->getLangUrl('km') ?>" class="language-option">
                <img src="./../../../images/flage/cam.png" alt="KH">
                ភាសាខ្មែរ
              </a>
            </div>
          </div>

          <button class="theme-toggle" id="theme-toggle-desktop" aria-label="Toggle theme">
            <i class="fas fa-moon"></i>
          </button>

          <a href="./../../../FormRegister?lang=<?= $lang->getCurrentLang() ?>" class="register-btn">
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
      <div class="mobile-nav-item">
        <a href="./../../../?lang=<?= $lang->getCurrentLang() ?>" class="mobile-nav-link">
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
          <a href="./../../../public/partner/?lang=<?= $lang->getCurrentLang() ?>" class="mobile-dropdown-item">
            <i class="fas fa-handshake mr-2"></i> <?= $t('nav_our_partners') ?>
          </a>
          <a href="./../../../public/new&events/?lang=<?= $lang->getCurrentLang() ?>" class="mobile-dropdown-item">
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
          <a href="./../../../public/Faculty_of_Science_and_Mathematics/?lang=<?= $lang->getCurrentLang() ?>" class="mobile-dropdown-item"><?= $t('faculty_science') ?></a>
          <a href="./../../../public/Faculty_of_Arts_Humanitites_and_Languages/?lang=<?= $lang->getCurrentLang() ?>" class="mobile-dropdown-item"><?= $t('faculty_arts') ?></a>
          <a href="./../../../public/Faculty_of_Agriculture/?lang=<?= $lang->getCurrentLang() ?>" class="mobile-dropdown-item"><?= $t('faculty_agriculture') ?></a>
          <a href="./../../../public/Faculty_of_social_science/?lang=<?= $lang->getCurrentLang() ?>" class="mobile-dropdown-item"><?= $t('faculty_social_science') ?></a>
          <a href="./../../../public/Faculty_of_Management/?lang=<?= $lang->getCurrentLang() ?>" class="mobile-dropdown-item"><?= $t('faculty_management') ?></a>
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
          <a href="./../../../public/about/?lang=<?= $lang->getCurrentLang() ?>" class="mobile-dropdown-item"><?= $t('about_message_rector') ?></a>
          <a href="./../../../public/vision-and-mission/?lang=<?= $lang->getCurrentLang() ?>" class="mobile-dropdown-item"><?= $t('about_vision_mission') ?></a>
          <a href="./../../../public/history_university/?lang=<?= $lang->getCurrentLang() ?>" class="mobile-dropdown-item"><?= $t('about_history') ?></a>
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
          <a href="./../../../public/world-bank/?lang=<?= $lang->getCurrentLang() ?>" class="mobile-dropdown-item"><?= $t('projects_world_bank') ?></a>
          <a href="./../../../public/Erasmus/?lang=<?= $lang->getCurrentLang() ?>" class="mobile-dropdown-item"><?= $t('projects_erasmus') ?></a>
        </div>
      </div>
    </div>

    <div class="mobile-language-section">
      <div class="mobile-section-title"><?= $t('language') ?></div>
      <div class="mobile-language-options">
        <a href="<?= $lang->getLangUrl('en') ?>" class="mobile-language-option <?= $lang->getCurrentLang() === 'en' ? 'active' : '' ?>" id="mobile-lang-en">
          <img src="./../../../images/flage/english.png" alt="EN">
          <span>EN</span>
        </a>
        <a href="<?= $lang->getLangUrl('km') ?>" class="mobile-language-option <?= $lang->getCurrentLang() === 'km' ? 'active' : '' ?>" id="mobile-lang-km">
          <img src="./../../../images/flage/cam.png" alt="KH">
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

      <a href="./../../../FormRegister?lang=<?= $lang->getCurrentLang() ?>" class="register-btn w-full justify-center mt-6 py-3">
        <i class="fas fa-user-graduate"></i>
        <?= $t('nav_register') ?> Now
      </a>
    </div>
  </div>

  <!-- Main Content - Public Administration Program -->
  <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="container-custom">
      <!-- Back Link -->
      <div class="mb-6" data-aos="fade-right">
        <a href="./../../../public/Faculty_of_social_science/?lang=<?= $lang->getCurrentLang() ?>" class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
          <i class="fas fa-arrow-left mr-2"></i>
          <?= $lang->getCurrentLang() === 'km' ? 'ត្រឡប់ទៅមហាវិទ្យាល័យវិទ្យាសាស្ត្រសង្គម' : 'Back to Faculty of Social Science' ?>
        </a>
      </div>

      <!-- Title -->
      <div class="flex flex-col items-center mb-8 md:mb-12" data-aos="fade-up">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4 text-center">
          <?= $lang->getCurrentLang() === 'km' ? 'រដ្ឋបាលសាធារណៈ' : 'Public Administration' ?> / 
          <span class="text-blue-600 dark:text-blue-400"><?= $lang->getCurrentLang() === 'km' ? 'Public Administration' : 'រដ្ឋបាលសាធារណៈ' ?></span>
        </h1>
        <div class="w-24 h-1 bg-blue-600 mx-auto"></div>
        <p class="text-lg text-gray-600 dark:text-gray-300 mt-4 text-center max-w-2xl">
          <?= $lang->getCurrentLang() === 'km' ? 'កម្មវិធីបរិញ្ញាបត្រផ្នែករដ្ឋបាលសាធារណៈ' : 'Bachelor of Public Administration Program' ?>
        </p>
      </div>

      <!-- Content Container -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 md:p-8 space-y-8" data-aos="fade-up">
        <!-- Purpose Section -->
        <section>
          <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
            <i class="fas fa-bullseye text-blue-600 mr-3"></i>
            <?= $lang->getCurrentLang() === 'km' ? 'គោលបំណង' : 'Purpose' ?> / 
            <span class="text-gray-500 text-lg ml-2"><?= $lang->getCurrentLang() === 'km' ? 'Purpose' : 'គោលបំណង' ?></span>
          </h2>
          <div class="space-y-3 text-gray-700 dark:text-gray-300 leading-relaxed">
            <p>
              <?= $lang->getCurrentLang() === 'km' ? 'កម្មវិធីបរិញ្ញាបត្រផ្នែករដ្ឋបាលសាធារណៈ ត្រូវបានរៀបចំឡើងដើម្បីផ្តល់ចំណេះដឹង និងជំនាញដល់និស្សិតដើម្បីគ្រប់គ្រងធនធានសាធារណៈដោយប្រសិទ្ធភាព អនុវត្តគោលនយោបាយ និងបម្រើសហគមន៍ដោយសុចរិតភាព និងការទទួលខុសត្រូវ។' : 'The Bachelor of Public Administration program aims to equip students with the knowledge and skills to effectively manage public resources, implement policies, and serve the community with integrity and accountability.' ?>
            </p>
            <p class="text-gray-600 dark:text-gray-400 border-l-4 border-blue-500 pl-4 py-2 bg-gray-50 dark:bg-gray-700 rounded-r-lg">
              <?= $lang->getCurrentLang() === 'km' ? 'កម្មវិធីនេះផ្តោតលើការគ្រប់គ្រងសាធារណៈ ការវិភាគគោលនយោបាយ និងការដឹកនាំក្នុងវិស័យសាធារណៈ។' : 'This program focuses on public management, policy analysis, and leadership in the public sector.' ?>
            </p>
          </div>
        </section>

        <!-- Program Learning Outcomes -->
        <section>
          <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
            <i class="fas fa-graduation-cap text-blue-600 mr-3"></i>
            <?= $lang->getCurrentLang() === 'km' ? 'លទ្ធផលសិក្សានៃកម្មវិធីបណ្ដុះបណ្ដាល' : 'Program Learning Outcomes' ?>
          </h2>
          
          <div class="space-y-6">
            <!-- A. Knowledge -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-5">
              <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-3 flex items-center">
                <span class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm mr-2">A</span>
                <?= $lang->getCurrentLang() === 'km' ? 'ចំណេះដឹង' : 'Knowledge' ?>
              </h3>
              <ul class="space-y-2 text-gray-700 dark:text-gray-300">
                <li class="flex items-start">
                  <i class="fas fa-check-circle text-green-500 mt-1 mr-2 flex-shrink-0"></i>
                  <span><?= $lang->getCurrentLang() === 'km' ? 'យល់ពីគោលការណ៍រដ្ឋបាលសាធារណៈ និងការគ្រប់គ្រង' : 'Understand principles of public administration and governance' ?></span>
                </li>
                <li class="flex items-start">
                  <i class="fas fa-check-circle text-green-500 mt-1 mr-2 flex-shrink-0"></i>
                  <span><?= $lang->getCurrentLang() === 'km' ? 'អនុវត្តគោលនយោបាយសាធារណៈ និងក្របខណ្ឌនៃគ្រប់គ្រង' : 'Apply public policy and management frameworks' ?></span>
                </li>
              </ul>
            </div>

            <!-- B. Cognitive Skills -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-5">
              <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-3 flex items-center">
                <span class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm mr-2">B</span>
                <?= $lang->getCurrentLang() === 'km' ? 'ជំនាញផ្នែកស្មារតី' : 'Cognitive Skills' ?>
              </h3>
              <ul class="space-y-2 text-gray-700 dark:text-gray-300">
                <li class="flex items-start">
                  <i class="fas fa-check-circle text-green-500 mt-1 mr-2 flex-shrink-0"></i>
                  <span><?= $lang->getCurrentLang() === 'km' ? 'វិភាគ និងដោះស្រាយបញ្ហារដ្ឋបាលសាធារណៈ' : 'Analyze and solve public administration challenges' ?></span>
                </li>
                <li class="flex items-start">
                  <i class="fas fa-check-circle text-green-500 mt-1 mr-2 flex-shrink-0"></i>
                  <span><?= $lang->getCurrentLang() === 'km' ? 'អភិវឌ្ឍដំណោះស្រាយច្នៃប្រឌិតសម្រាប់ការផ្តល់សេវាសាធារណៈ' : 'Develop innovative solutions for public service delivery' ?></span>
                </li>
              </ul>
            </div>

            <!-- C. Interpersonal Skills -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-5">
              <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-3 flex items-center">
                <span class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm mr-2">C</span>
                <?= $lang->getCurrentLang() === 'km' ? 'ជំនាញអន្តរកម្ម' : 'Interpersonal Skills' ?>
              </h3>
              <ul class="space-y-2 text-gray-700 dark:text-gray-300">
                <li class="flex items-start">
                  <i class="fas fa-check-circle text-green-500 mt-1 mr-2 flex-shrink-0"></i>
                  <span><?= $lang->getCurrentLang() === 'km' ? 'សហការជាមួយភាគីពាក់ព័ន្ធក្នុងគម្រោងផ្នែកសាធារណៈ' : 'Collaborate with stakeholders in public sector projects' ?></span>
                </li>
                <li class="flex items-start">
                  <i class="fas fa-check-circle text-green-500 mt-1 mr-2 flex-shrink-0"></i>
                  <span><?= $lang->getCurrentLang() === 'km' ? 'ប្រាស្រ័យទាក់ទងគោលនយោបាយសាធារណៈដោយប្រសិទ្ធភាពទៅកាន់ទស្សនិកជនផ្សេងៗ' : 'Communicate public policies effectively to diverse audiences' ?></span>
                </li>
              </ul>
            </div>

            <!-- D. Technical Skills -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-5">
              <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-3 flex items-center">
                <span class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm mr-2">D</span>
                <?= $lang->getCurrentLang() === 'km' ? 'ជំនាញបច្ចេកទេស' : 'Technical Skills' ?>
              </h3>
              <ul class="space-y-2 text-gray-700 dark:text-gray-300">
                <li class="flex items-start">
                  <i class="fas fa-check-circle text-green-500 mt-1 mr-2 flex-shrink-0"></i>
                  <span><?= $lang->getCurrentLang() === 'km' ? 'ប្រើប្រាស់ឧបករណ៍ផ្អែកលើទិន្នន័យសម្រាប់ការសម្រេចចិត្តសាធារណៈ' : 'Use data-driven tools for public decision-making' ?></span>
                </li>
                <li class="flex items-start">
                  <i class="fas fa-check-circle text-green-500 mt-1 mr-2 flex-shrink-0"></i>
                  <span><?= $lang->getCurrentLang() === 'km' ? 'អនុវត្តប្រព័ន្ធគ្រប់គ្រងហិរញ្ញវត្ថុសាធារណៈ' : 'Implement public financial management systems' ?></span>
                </li>
              </ul>
            </div>
          </div>
        </section>

        <!-- Career Opportunities -->
        <section>
          <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
            <i class="fas fa-briefcase text-blue-600 mr-3"></i>
            <?= $lang->getCurrentLang() === 'km' ? 'ឱកាសការងារ' : 'Career Opportunities' ?>
          </h2>
          
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="career-item">
              <div class="flex items-center">
                <i class="fas fa-building text-blue-600 mr-3 text-xl"></i>
                <p class="text-gray-800 dark:text-white font-medium"><?= $lang->getCurrentLang() === 'km' ? 'អ្នករដ្ឋបាលសាធារណៈ' : 'Public Administrator' ?></p>
              </div>
            </div>
            
            <div class="career-item">
              <div class="flex items-center">
                <i class="fas fa-file-alt text-blue-600 mr-3 text-xl"></i>
                <p class="text-gray-800 dark:text-white font-medium"><?= $lang->getCurrentLang() === 'km' ? 'អ្នកវិភាគគោលនយោបាយ' : 'Policy Analyst' ?></p>
              </div>
            </div>
            
            <div class="career-item">
              <div class="flex items-center">
                <i class="fas fa-user-tie text-blue-600 mr-3 text-xl"></i>
                <p class="text-gray-800 dark:text-white font-medium"><?= $lang->getCurrentLang() === 'km' ? 'មន្ត្រីរាជការ' : 'Government Officer' ?></p>
              </div>
            </div>
            
            <div class="career-item">
              <div class="flex items-center">
                <i class="fas fa-coins text-blue-600 mr-3 text-xl"></i>
                <p class="text-gray-800 dark:text-white font-medium"><?= $lang->getCurrentLang() === 'km' ? 'អ្នកគ្រប់គ្រងហិរញ្ញវត្ថុសាធារណៈ' : 'Public Finance Manager' ?></p>
              </div>
            </div>
            
            <div class="career-item">
              <div class="flex items-center">
                <i class="fas fa-heart text-blue-600 mr-3 text-xl"></i>
                <p class="text-gray-800 dark:text-white font-medium"><?= $lang->getCurrentLang() === 'km' ? 'អ្នកគ្រប់គ្រងកម្មវិធីអង្គការក្រៅរដ្ឋាភិបាល' : 'Non-Profit Program Manager' ?></p>
              </div>
            </div>
            
            <div class="career-item">
              <div class="flex items-center">
                <i class="fas fa-microscope text-blue-600 mr-3 text-xl"></i>
                <p class="text-gray-800 dark:text-white font-medium"><?= $lang->getCurrentLang() === 'km' ? 'អ្នកស្រាវជ្រាវផ្នែករដ្ឋបាលសាធារណៈ' : 'Researcher in Public Administration' ?></p>
              </div>
            </div>
          </div>
        </section>

        <!-- Apply Button -->
        <div class="text-center pt-4">
          <a href="./../../../FormRegister?lang=<?= $lang->getCurrentLang() ?>" class="inline-block px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-blue-800 transition shadow-lg hover:shadow-xl">
            <i class="fas fa-edit mr-2"></i>
            <?= $lang->getCurrentLang() === 'km' ? 'ដាក់ពាក្យឥឡូវនេះ' : 'Apply Now' ?>
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="pt-10 sm:pt-12 pb-6">
    <div class="container-custom">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
           <div class="sm:col-span-2">
          <a href="./../../?lang=<?= $lang->getCurrentLang() ?>" class="inline-flex items-center gap-2 mb-4">
            <img src="./../../images/logo_footer/nuck_logo.png" alt="NUCK" class="h-10 w-auto">
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
            <a href="https://t.me/officialstudentassociationofcsuk" target="_blank" class="w-9 h-9 bg-gray-800 rounded-full flex items-center justify-center hover:bg-yellow-500 hover:text-gray-900 transition-all">
              <i class="fab fa-telegram-plane"></i>
            </a>
            <a href="https://youtube.com/@nuck6666" target="_blank" class="w-9 h-9 bg-gray-800 rounded-full flex items-center justify-center hover:bg-yellow-500 hover:text-gray-900 transition-all">
              <i class="fab fa-youtube"></i>
            </a>
            <a href="https://www.instagram.com/national_university_of_cheasim" target="_blank" class="w-9 h-9 bg-gray-800 rounded-full flex items-center justify-center hover:bg-yellow-500 hover:text-gray-900 transition-all">
              <i class="fab fa-instagram"></i>
            </a>
            <a href="https://web.facebook.com/@NationalUniversityofCheasimkamchaymear" target="_blank" class="w-9 h-9 bg-gray-800 rounded-full flex items-center justify-center hover:bg-yellow-500 hover:text-gray-900 transition-all">
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
          <li><a href="./../../../?lang=<?= $lang->getCurrentLang() ?>" class="hover:text-yellow-500 transition"><?= $t('footer_faq') ?></a></li>
          <li><a href="./../../../?lang=<?= $lang->getCurrentLang() ?>" class="hover:text-yellow-500 transition"><?= $t('footer_privacy') ?></a></li>
          <li><a href="./../../../?lang=<?= $lang->getCurrentLang() ?>" class="hover:text-yellow-500 transition"><?= $t('footer_terms') ?></a></li>
        </ul>
      </div>
    </div>
  </footer>

  <button id="scroll-top" aria-label="Scroll to top">
    <i class="fas fa-arrow-up"></i>
  </button>

  <script>
    AOS.init({
      duration: 800,
      once: true
    });

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

    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
      if (!localStorage.getItem('theme')) {
        setTheme(e.matches ? 'dark' : 'light');
      }
    });

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
  </script>
</body>
</html>