<?php
// success.php — post-submission confirmation
session_start();

// Include language system
require_once __DIR__ . '/../includes/language.php';

// Get translation function
$t = function($key, $default = '') use ($lang) {
    return $lang->t($key, $default);
};

// Get current language for easy access
$currentLang = $lang->getCurrentLang();
$isKhmer = $currentLang === 'km';

require __DIR__ . '/config.php';

$student_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$student = null;
$db_error = false;

if ($student_id > 0) {
    try {
        $pdo = get_pdo();
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $stmt = $pdo->prepare('SELECT * FROM students WHERE id = ? LIMIT 1');
        $stmt->execute([$student_id]);
        $student = $stmt->fetch();
    } catch (Throwable $e) {
        $db_error = true;
        error_log($e->getMessage());
    }
}

if (!$student || $db_error) {
    // Show error page if no student found or database error
    ?>
    <!DOCTYPE html>
    <html lang="<?= $currentLang === 'en' ? 'en' : 'km' ?>" class="dark">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
      <link rel="icon" href="../images/nuck_logo.png">
      <title>NUCK | <?= $t('error') ?></title>
      
      <!-- Tailwind CSS -->
      <script src="https://cdn.tailwindcss.com"></script>
      
      <!-- Font Awesome -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
      
      <!-- Google Fonts -->
      <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Kantumruy+Pro:wght@300;400;500;600;700&family=Hanuman:wght@100;300;400;700;900&display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Battambang:wght@100;300;400;700;900&display=swap" rel="stylesheet">

      <script>
        tailwind.config = { darkMode: 'class' }
      </script>
      
      <style>
        :root {
          --bg-primary: #ffffff;
          --bg-secondary: #f9fafb;
          --text-primary: #111827;
          --text-secondary: #4b5563;
          --text-muted: #6b7280;
          --card-bg: #ffffff;
          --footer-bg: #111827;
          --border-color: #e5e7eb;
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

        * {
          margin: 0;
          padding: 0;
          box-sizing: border-box;
        }

        body {
          font-family: 'Inter', 'Kantumruy Pro', 'Battambang', sans-serif;
          overflow-x: hidden;
          padding-top: 70px;
          background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
          color: var(--text-primary);
          transition: background-color 0.3s ease, color 0.3s ease;
        }

        @media (min-width: 1024px) {
          body { padding-top: 80px; }
        }

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
          font-family: 'Kantumruy Pro', 'Battambang', sans-serif !important;
        }

        .navbar {
          background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
          position: fixed;
          width: 100%;
          top: 0;
          z-index: 1000;
          box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
          transition: all 0.3s ease;
        }
        .dark .navbar { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); }

        .nav-menu-desktop { display: none; }
        @media (min-width: 1024px) {
          .nav-menu-desktop { display: flex; align-items: center; gap: 0.25rem; }
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
        .nav-link:hover { background: rgba(255, 255, 255, 0.15); }

        .dropdown-container { position: relative; }
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
        .dark .dropdown-menu { background: #1f2937; }
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
        .dark .dropdown-item { color: #e5e7eb; }
        .dropdown-item:hover {
          background: linear-gradient(90deg, rgba(42, 82, 152, 0.1) 0%, rgba(255, 215, 0, 0.1) 100%);
          border-left-color: #ffd700;
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
        @media (min-width: 1024px) { .menu-toggle { display: none; } }
        .menu-toggle span {
          display: block;
          width: 100%;
          height: 2px;
          background: white;
          border-radius: 3px;
          transition: all 0.3s ease;
        }
        .menu-toggle.active span:nth-child(1) { transform: translateY(9px) rotate(45deg); }
        .menu-toggle.active span:nth-child(2) { opacity: 0; }
        .menu-toggle.active span:nth-child(3) { transform: translateY(-9px) rotate(-45deg); }

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
        .dark .mobile-menu { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); }
        .mobile-menu.active { right: 0; }

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
        .mobile-menu-overlay.active { opacity: 1; visibility: visible; }

        .mobile-nav-item { margin-bottom: 0.5rem; }
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
        .mobile-nav-link:hover { background: rgba(255, 255, 255, 0.1); }

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
        .mobile-dropdown-btn:hover { background: rgba(255, 255, 255, 0.1); }
        .mobile-dropdown-btn.active i { transform: rotate(180deg); }

        .mobile-dropdown-content {
          max-height: 0;
          overflow: hidden;
          transition: max-height 0.3s ease;
          margin-left: 2.5rem;
          border-left: 2px solid rgba(255, 255, 255, 0.2);
          background: rgba(255, 255, 255, 0.05);
        }
        .mobile-dropdown-content.show { max-height: 300px; }
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

        .desktop-actions { display: none; }
        @media (min-width: 1024px) {
          .desktop-actions { display: flex; align-items: center; gap: 0.5rem; }
        }

        .language-switcher { position: relative; }
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
        }
        .language-btn:hover { background: rgba(255, 255, 255, 0.25); }
        .language-btn img { width: 18px; height: 18px; border-radius: 50%; object-fit: cover; }
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
        .dark .language-dropdown { background: #1f2937; }
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
        .dark .language-option { color: #e5e7eb; }
        .language-option:hover {
          background: rgba(42, 82, 152, 0.1);
          padding-left: 1.5rem;
        }
        .language-option img { width: 18px; height: 18px; border-radius: 50%; object-fit: cover; }

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
          border: 1px solid #ffd700;
        }
        .mobile-language-option img { width: 20px; height: 20px; border-radius: 50%; }
        .mobile-theme-options { display: flex; gap: 0.5rem; }
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
        .mobile-theme-btn:hover { background: rgba(255, 255, 255, 0.2); }
        .mobile-theme-btn.active {
          background: rgba(255, 255, 255, 0.25);
          border: 1px solid #ffd700;
        }

        footer { background-color: var(--footer-bg); color: white; }

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
        .dark #scroll-top { background: linear-gradient(135deg, #2563eb, #3b82f6); }
        #scroll-top.show { display: flex; }
        #scroll-top:hover {
          transform: translateY(-5px);
          box-shadow: 0 10px 25px rgba(42, 82, 152, 0.4);
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
          .container-custom { padding-left: 1.5rem; padding-right: 1.5rem; }
        }
        @media (min-width: 1024px) {
          .container-custom { padding-left: 2rem; padding-right: 2rem; }
        }

        .checkmark {
          animation: checkmark 0.6s ease-out;
        }
        @keyframes checkmark {
          0% { transform: scale(0); opacity: 0; }
          50% { transform: scale(1.2); }
          100% { transform: scale(1); opacity: 1; }
        }
      </style>
    </head>
    <body class="<?= $isKhmer ? 'khmer-font' : '' ?>">
      <!-- Navbar -->
      <nav class="navbar">
        <div class="container-custom">
          <div class="flex justify-between items-center h-[70px] lg:h-20">
            <div class="flex-shrink-0">
              <a href="../?lang=<?= $currentLang ?>" class="flex items-center">
                <img src="../images/logo/NUCK_Logo_Web.png" alt="NUCK" class="h-9 sm:h-10 lg:h-14 w-auto">
              </a>
            </div>
            <div class="nav-menu-desktop">
              <a href="../?lang=<?= $currentLang ?>" class="nav-link"><?= $t('nav_home') ?></a>
              <div class="dropdown-container">
                <button class="nav-link flex items-center gap-1"><?= $t('nav_resources') ?> <i class="fas fa-chevron-down text-xs"></i></button>
                <div class="dropdown-menu">
                  <a href="../public/partner/?lang=<?= $currentLang ?>" class="dropdown-item"><i class="fas fa-handshake mr-2"></i> <?= $t('nav_our_partners') ?></a>
                  <a href="../public/new&events/?lang=<?= $currentLang ?>" class="dropdown-item"><i class="fas fa-calendar-alt mr-2"></i> <?= $t('nav_news_events') ?></a>
                </div>
              </div>
              <div class="dropdown-container">
                <button class="nav-link flex items-center gap-1"><?= $t('nav_academics') ?> <i class="fas fa-chevron-down text-xs"></i></button>
                <div class="dropdown-menu">
                  <a href="../public/Faculty_of_Science_and_Mathematics/?lang=<?= $currentLang ?>" class="dropdown-item"><?= $t('faculty_science') ?></a>
                  <a href="../public/Faculty_of_Arts_Humanitites_and_Languages/?lang=<?= $currentLang ?>" class="dropdown-item"><?= $t('faculty_arts') ?></a>
                  <a href="../public/Faculty_of_Agriculture/?lang=<?= $currentLang ?>" class="dropdown-item"><?= $t('faculty_agriculture') ?></a>
                  <a href="../public/Faculty_of_social_science/?lang=<?= $currentLang ?>" class="dropdown-item"><?= $t('faculty_social_science') ?></a>
                  <a href="../public/Faculty_of_Management/?lang=<?= $currentLang ?>" class="dropdown-item"><?= $t('faculty_management') ?></a>
                </div>
              </div>
              <div class="dropdown-container">
                <button class="nav-link flex items-center gap-1"><?= $t('nav_about') ?> <i class="fas fa-chevron-down text-xs"></i></button>
                <div class="dropdown-menu">
                  <a href="../public/about/?lang=<?= $currentLang ?>" class="dropdown-item"><?= $t('about_message_rector') ?></a>
                  <a href="../public/vision-and-mission/?lang=<?= $currentLang ?>" class="dropdown-item"><?= $t('about_vision_mission') ?></a>
                  <a href="../public/history_university/?lang=<?= $currentLang ?>" class="dropdown-item"><?= $t('about_history') ?></a>
                </div>
              </div>
              <div class="dropdown-container">
                <button class="nav-link flex items-center gap-1"><?= $t('nav_projects') ?> <i class="fas fa-chevron-down text-xs"></i></button>
                <div class="dropdown-menu">
                  <a href="../public/world-bank/?lang=<?= $currentLang ?>" class="dropdown-item"><?= $t('projects_world_bank') ?></a>
                  <a href="../public/Erasmus/?lang=<?= $currentLang ?>" class="dropdown-item"><?= $t('projects_erasmus') ?></a>
                </div>
              </div>
            </div>
            <div class="desktop-actions">
              <div class="language-switcher">
                <button class="language-btn"><img src="../images/flage/<?= $currentLang === 'en' ? 'english.png' : 'cam.png' ?>" alt="<?= strtoupper($currentLang) ?>"><span><?= strtoupper($currentLang) ?></span><i class="fas fa-chevron-down text-xs"></i></button>
                <div class="language-dropdown">
                  <a href="?lang=en&id=<?= $student_id ?>" class="language-option"><img src="../images/flage/english.png" alt="EN">English</a>
                  <a href="?lang=km&id=<?= $student_id ?>" class="language-option"><img src="../images/flage/cam.png" alt="KH">ភាសាខ្មែរ</a>
                </div>
              </div>
              <button class="theme-toggle" id="theme-toggle-desktop"><i class="fas fa-moon"></i></button>
              <a href="../FormRegister?lang=<?= $currentLang ?>" class="register-btn"><i class="fas fa-user-graduate"></i><span><?= $t('nav_register') ?></span></a>
            </div>
            <div class="menu-toggle" id="menu-toggle"><span></span><span></span><span></span></div>
          </div>
        </div>
      </nav>

      <div class="mobile-menu-overlay" id="mobile-overlay"></div>
      <div class="mobile-menu" id="mobile-menu">
        <div class="flex justify-end mb-4"><button class="text-white text-2xl p-1" id="close-menu"><i class="fas fa-times"></i></button></div>
        <div class="space-y-1">
          <div class="mobile-nav-item"><a href="../?lang=<?= $currentLang ?>" class="mobile-nav-link"><i class="fas fa-home w-6"></i><?= $t('nav_home') ?></a></div>
          <div class="mobile-nav-item">
            <button class="mobile-dropdown-btn" onclick="toggleMobileDropdown('resources')"><span><i class="fas fa-folder-open w-6"></i><?= $t('nav_resources') ?></span><i class="fas fa-chevron-down"></i></button>
            <div class="mobile-dropdown-content" id="resources">
              <a href="../public/partner/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('nav_our_partners') ?></a>
              <a href="../public/new&events/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('nav_news_events') ?></a>
            </div>
          </div>
          <div class="mobile-nav-item">
            <button class="mobile-dropdown-btn" onclick="toggleMobileDropdown('academics')"><span><i class="fas fa-book w-6"></i><?= $t('nav_academics') ?></span><i class="fas fa-chevron-down"></i></button>
            <div class="mobile-dropdown-content" id="academics">
              <a href="../public/Faculty_of_Science_and_Mathematics/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('faculty_science') ?></a>
              <a href="../public/Faculty_of_Arts_Humanitites_and_Languages/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('faculty_arts') ?></a>
              <a href="../public/Faculty_of_Agriculture/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('faculty_agriculture') ?></a>
              <a href="../public/Faculty_of_social_science/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('faculty_social_science') ?></a>
              <a href="../public/Faculty_of_Management/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('faculty_management') ?></a>
            </div>
          </div>
          <div class="mobile-nav-item">
            <button class="mobile-dropdown-btn" onclick="toggleMobileDropdown('about')"><span><i class="fas fa-info-circle w-6"></i><?= $t('nav_about') ?></span><i class="fas fa-chevron-down"></i></button>
            <div class="mobile-dropdown-content" id="about">
              <a href="../public/about/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('about_message_rector') ?></a>
              <a href="../public/vision-and-mission/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('about_vision_mission') ?></a>
              <a href="../public/history_university/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('about_history') ?></a>
            </div>
          </div>
          <div class="mobile-nav-item">
            <button class="mobile-dropdown-btn" onclick="toggleMobileDropdown('projects')"><span><i class="fas fa-project-diagram w-6"></i><?= $t('nav_projects') ?></span><i class="fas fa-chevron-down"></i></button>
            <div class="mobile-dropdown-content" id="projects">
              <a href="../public/world-bank/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('projects_world_bank') ?></a>
              <a href="../public/Erasmus/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('projects_erasmus') ?></a>
            </div>
          </div>
        </div>
        <div class="mobile-language-section">
          <div class="mobile-section-title"><?= $t('language') ?></div>
          <div class="mobile-language-options">
            <a href="?lang=en&id=<?= $student_id ?>" class="mobile-language-option <?= $currentLang === 'en' ? 'active' : '' ?>"><img src="../images/flage/english.png" alt="EN"><span>EN</span></a>
            <a href="?lang=km&id=<?= $student_id ?>" class="mobile-language-option <?= $currentLang === 'km' ? 'active' : '' ?>"><img src="../images/flage/cam.png" alt="KH"><span>KH</span></a>
          </div>
          <div class="mobile-section-title mt-4"><?= $t('theme') ?></div>
          <div class="mobile-theme-options">
            <button class="mobile-theme-btn active" id="mobile-theme-light"><i class="fas fa-sun"></i><span><?= $t('light') ?></span></button>
            <button class="mobile-theme-btn" id="mobile-theme-dark"><i class="fas fa-moon"></i><span><?= $t('dark') ?></span></button>
          </div>
          <a href="../FormRegister?lang=<?= $currentLang ?>" class="register-btn w-full justify-center mt-6 py-3"><i class="fas fa-user-graduate"></i><?= $t('nav_register') ?> Now</a>
        </div>
      </div>

      <div class="container-custom py-6 sm:py-8 lg:py-12">
        <div class="max-w-4xl mx-auto">
          <div class="bg-card rounded-2xl shadow-2xl overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="bg-gradient-to-r from-red-500 to-red-600 text-white text-center py-8 sm:py-12 px-4">
              <div class="mb-4">
                <svg class="w-20 h-20 sm:w-24 sm:h-24 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
              </div>
              <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2"><?= $t('error') ?>!</h1>
              <p class="text-lg sm:text-xl opacity-90"><?= $t('application_not_found') ?></p>
            </div>
            <div class="p-6 sm:p-8 lg:p-10">
              <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-6 rounded-lg mb-8">
                <div class="flex items-start">
                  <svg class="w-6 h-6 text-red-500 mt-1 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                  </svg>
                  <div class="flex-1">
                    <h3 class="text-lg font-semibold text-red-800 dark:text-red-300 mb-2"><?= $t('error_occurred') ?></h3>
                    <p class="text-red-700 dark:text-red-300 mb-2"><?= $t('application_not_found_message') ?></p>
                    <p class="text-red-700 dark:text-red-300 text-sm"><?= $t('application_not_found_message_en') ?></p>
                  </div>
                </div>
              </div>
              <div class="flex justify-center pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="../?lang=<?= $currentLang ?>" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 transition shadow-lg hover:shadow-xl text-center">
                  <?= $t('back_to_home') ?>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <footer class="pt-10 sm:pt-12 pb-6 mt-12">
        <div class="container-custom">
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
            <div class="sm:col-span-2">
              <a href="../?lang=<?= $currentLang ?>" class="inline-flex items-center gap-2 mb-4">
                <img src="../images/logo_footer/nuck_logo.png" alt="NUCK" class="h-10 w-auto">
                <span class="text-lg font-bold text-white <?= $isKhmer ? 'khmer-font' : '' ?>"><?= $isKhmer ? 'សាកលវិទ្យាល័យជាតិជាស៊ីមកំចាយមារ' : 'National University of Cheasim Kamchaymear' ?></span>
              </a>
              <p class="text-gray-400 text-sm leading-relaxed max-w-md <?= $isKhmer ? 'khmer-font' : '' ?>">
                <?= $isKhmer ? 'ផ្លូវជាតិលេខ ៨, ភូមិថ្នល់កែង, ឃុំស្មោងជើង,<br> ស្រុកកំចាយមារ, ខេត្តព្រៃវែង, កម្ពុជា។' : 'National Road 8, Thnal Keng Village, Smoang Cheung Commune,<br> Kamchaymear District, Prey Veng Province, CAMBODIA.' ?>
              </p>
            </div>
            <div>
              <h3 class="text-base sm:text-lg font-bold mb-4 text-white"><?= $t('footer_contacts') ?></h3>
              <ul class="space-y-2 text-sm text-gray-400">
                <li class="flex items-center gap-2"><i class="fas fa-phone-alt w-4"></i><span>097 828 1168</span></li>
                <li class="flex items-center gap-2"><i class="fas fa-envelope w-4"></i><span>info@nuck.edu.kh</span></li>
              </ul>
            </div>
            <div>
              <h3 class="text-base sm:text-lg font-bold mb-4 text-white"><?= $t('footer_follow_us') ?></h3>
              <div class="flex gap-3">
                <a href="https://t.me/officialstudentassociationofcsuk" target="_blank" class="w-9 h-9 bg-gray-800 rounded-full flex items-center justify-center hover:bg-yellow-500 hover:text-gray-900 transition-all text-white"><i class="fab fa-telegram-plane"></i></a>
                <a href="https://youtube.com/@nuck6666" target="_blank" class="w-9 h-9 bg-gray-800 rounded-full flex items-center justify-center hover:bg-yellow-500 hover:text-gray-900 transition-all text-white"><i class="fab fa-youtube"></i></a>
                <a href="https://www.instagram.com/national_university_of_cheasim" target="_blank" class="w-9 h-9 bg-gray-800 rounded-full flex items-center justify-center hover:bg-yellow-500 hover:text-gray-900 transition-all text-white"><i class="fab fa-instagram"></i></a>
                <a href="https://web.facebook.com/@NationalUniversityofCheasimkamchaymear" target="_blank" class="w-9 h-9 bg-gray-800 rounded-full flex items-center justify-center hover:bg-yellow-500 hover:text-gray-900 transition-all text-white"><i class="fab fa-facebook-f"></i></a>
              </div>
            </div>
          </div>
          <div class="pt-6 border-t border-gray-800 flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-xs sm:text-sm text-gray-400 text-center sm:text-left"><?= $t('footer_copyright') ?></p>
            <ul class="flex gap-4 sm:gap-6 text-xs sm:text-sm text-gray-400">
              <li><a href="../?lang=<?= $currentLang ?>" class="hover:text-yellow-500 transition"><?= $t('footer_faq') ?></a></li>
              <li><a href="../?lang=<?= $currentLang ?>" class="hover:text-yellow-500 transition"><?= $t('footer_privacy') ?></a></li>
              <li><a href="../?lang=<?= $currentLang ?>" class="hover:text-yellow-500 transition"><?= $t('footer_terms') ?></a></li>
            </ul>
          </div>
        </div>
      </footer>

      <button id="scroll-top" aria-label="Scroll to top"><i class="fas fa-arrow-up"></i></button>

      <script>
        // Theme System
        const html = document.documentElement;
        function setTheme(theme) {
          if (theme === 'dark') { html.classList.add('dark'); localStorage.setItem('theme', 'dark'); }
          else { html.classList.remove('dark'); localStorage.setItem('theme', 'light'); }
          updateThemeUI(theme === 'dark');
        }
        function updateThemeUI(isDark) {
          const t = document.getElementById('theme-toggle-desktop');
          const l = document.getElementById('mobile-theme-light');
          const d = document.getElementById('mobile-theme-dark');
          if (t) t.innerHTML = isDark ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
          if (l && d) { l.classList.toggle('active', !isDark); d.classList.toggle('active', isDark); }
        }
        function initializeTheme() {
          const saved = localStorage.getItem('theme');
          const system = window.matchMedia('(prefers-color-scheme: dark)').matches;
          setTheme(saved || (system ? 'dark' : 'light'));
        }
        initializeTheme();
        document.getElementById('theme-toggle-desktop')?.addEventListener('click', () => setTheme(html.classList.contains('dark') ? 'light' : 'dark'));
        document.getElementById('mobile-theme-light')?.addEventListener('click', () => { setTheme('light'); setTimeout(closeMobileMenu, 300); });
        document.getElementById('mobile-theme-dark')?.addEventListener('click', () => { setTheme('dark'); setTimeout(closeMobileMenu, 300); });

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
          document.querySelectorAll('.mobile-dropdown-content').forEach(d => { d.classList.remove('show'); d.style.maxHeight = '0'; });
          document.querySelectorAll('.mobile-dropdown-btn').forEach(btn => btn.classList.remove('active'));
        }
        menuToggle?.addEventListener('click', openMobileMenu);
        closeMenuBtn?.addEventListener('click', closeMobileMenu);
        mobileOverlay?.addEventListener('click', closeMobileMenu);
        document.addEventListener('keydown', e => { if (e.key === 'Escape' && mobileMenu?.classList.contains('active')) closeMobileMenu(); });
        window.addEventListener('resize', () => { if (window.innerWidth >= 1024) closeMobileMenu(); });

        document.querySelectorAll('.mobile-dropdown-content').forEach(d => d.style.maxHeight = '0');
        window.toggleMobileDropdown = function(id) {
          const dropdown = document.getElementById(id);
          const btn = event.currentTarget;
          if (!dropdown || !btn) return;
          document.querySelectorAll('.mobile-dropdown-content').forEach(d => { if (d.id !== id) { d.classList.remove('show'); d.style.maxHeight = '0'; d.previousElementSibling?.classList.remove('active'); } });
          const isOpen = dropdown.classList.contains('show');
          dropdown.classList.toggle('show', !isOpen);
          btn.classList.toggle('active', !isOpen);
          dropdown.style.maxHeight = !isOpen ? dropdown.scrollHeight + 'px' : '0';
        };

        const scrollTopBtn = document.getElementById('scroll-top');
        window.addEventListener('scroll', () => scrollTopBtn?.classList.toggle('show', window.scrollY > 500));
        scrollTopBtn?.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
      </script>
    </body>
    </html>
    <?php
    exit;
}
?>

<!DOCTYPE html>
<html lang="<?= $currentLang === 'en' ? 'en' : 'km' ?>" class="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
  <link rel="icon" href="../images/nuck_logo.png">
  <title>NUCK | <?= $t('success') ?></title>
  
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Kantumruy+Pro:wght@300;400;500;600;700&family=Hanuman:wght@100;300;400;700;900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Battambang:wght@100;300;400;700;900&display=swap" rel="stylesheet">

  <script>
    tailwind.config = { darkMode: 'class' }
  </script>
  
  <style>
    :root {
      --bg-primary: #ffffff;
      --bg-secondary: #f9fafb;
      --text-primary: #111827;
      --text-secondary: #4b5563;
      --text-muted: #6b7280;
      --card-bg: #ffffff;
      --footer-bg: #111827;
      --border-color: #e5e7eb;
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

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', 'Kantumruy Pro', 'Battambang', sans-serif;
      overflow-x: hidden;
      padding-top: 70px;
      background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
      color: var(--text-primary);
      transition: background-color 0.3s ease, color 0.3s ease;
    }

    @media (min-width: 1024px) {
      body { padding-top: 80px; }
    }

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
      font-family: 'Kantumruy Pro', 'Battambang', sans-serif !important;
    }

    .navbar {
      background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
      position: fixed;
      width: 100%;
      top: 0;
      z-index: 1000;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    }
    .dark .navbar { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); }

    .nav-menu-desktop { display: none; }
    @media (min-width: 1024px) {
      .nav-menu-desktop { display: flex; align-items: center; gap: 0.25rem; }
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
    .nav-link:hover { background: rgba(255, 255, 255, 0.15); }

    .dropdown-container { position: relative; }
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
    .dark .dropdown-menu { background: #1f2937; }
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
    .dark .dropdown-item { color: #e5e7eb; }
    .dropdown-item:hover {
      background: linear-gradient(90deg, rgba(42, 82, 152, 0.1) 0%, rgba(255, 215, 0, 0.1) 100%);
      border-left-color: #ffd700;
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
    @media (min-width: 1024px) { .menu-toggle { display: none; } }
    .menu-toggle span {
      display: block;
      width: 100%;
      height: 2px;
      background: white;
      border-radius: 3px;
      transition: all 0.3s ease;
    }
    .menu-toggle.active span:nth-child(1) { transform: translateY(9px) rotate(45deg); }
    .menu-toggle.active span:nth-child(2) { opacity: 0; }
    .menu-toggle.active span:nth-child(3) { transform: translateY(-9px) rotate(-45deg); }

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
    .dark .mobile-menu { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); }
    .mobile-menu.active { right: 0; }

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
    .mobile-menu-overlay.active { opacity: 1; visibility: visible; }

    .mobile-nav-item { margin-bottom: 0.5rem; }
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
    .mobile-nav-link:hover { background: rgba(255, 255, 255, 0.1); }

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
    .mobile-dropdown-btn:hover { background: rgba(255, 255, 255, 0.1); }
    .mobile-dropdown-btn.active i { transform: rotate(180deg); }

    .mobile-dropdown-content {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.3s ease;
      margin-left: 2.5rem;
      border-left: 2px solid rgba(255, 255, 255, 0.2);
      background: rgba(255, 255, 255, 0.05);
    }
    .mobile-dropdown-content.show { max-height: 300px; }
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

    .desktop-actions { display: none; }
    @media (min-width: 1024px) {
      .desktop-actions { display: flex; align-items: center; gap: 0.5rem; }
    }

    .language-switcher { position: relative; }
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
    }
    .language-btn:hover { background: rgba(255, 255, 255, 0.25); }
    .language-btn img { width: 18px; height: 18px; border-radius: 50%; object-fit: cover; }
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
    .dark .language-dropdown { background: #1f2937; }
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
    .dark .language-option { color: #e5e7eb; }
    .language-option:hover {
      background: rgba(42, 82, 152, 0.1);
      padding-left: 1.5rem;
    }
    .language-option img { width: 18px; height: 18px; border-radius: 50%; object-fit: cover; }

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
      border: 1px solid #ffd700;
    }
    .mobile-language-option img { width: 20px; height: 20px; border-radius: 50%; }
    .mobile-theme-options { display: flex; gap: 0.5rem; }
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
    .mobile-theme-btn:hover { background: rgba(255, 255, 255, 0.2); }
    .mobile-theme-btn.active {
      background: rgba(255, 255, 255, 0.25);
      border: 1px solid #ffd700;
    }

    footer { background-color: var(--footer-bg); color: white; }

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
    .dark #scroll-top { background: linear-gradient(135deg, #2563eb, #3b82f6); }
    #scroll-top.show { display: flex; }
    #scroll-top:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(42, 82, 152, 0.4);
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
      .container-custom { padding-left: 1.5rem; padding-right: 1.5rem; }
    }
    @media (min-width: 1024px) {
      .container-custom { padding-left: 2rem; padding-right: 2rem; }
    }

    .checkmark {
      animation: checkmark 0.6s ease-out;
    }
    @keyframes checkmark {
      0% { transform: scale(0); opacity: 0; }
      50% { transform: scale(1.2); }
      100% { transform: scale(1); opacity: 1; }
    }
  </style>
</head>
<body class="<?= $isKhmer ? 'khmer-font' : '' ?>">

  <!-- Navbar -->
  <nav class="navbar">
    <div class="container-custom">
      <div class="flex justify-between items-center h-[70px] lg:h-20">
        <div class="flex-shrink-0">
          <a href="../?lang=<?= $currentLang ?>" class="flex items-center">
            <img src="../images/logo/NUCK_Logo_Web.png" alt="NUCK" class="h-9 sm:h-10 lg:h-14 w-auto">
          </a>
        </div>

        <div class="nav-menu-desktop">
          <a href="../?lang=<?= $currentLang ?>" class="nav-link"><?= $t('nav_home') ?></a>
          
          <div class="dropdown-container">
            <button class="nav-link flex items-center gap-1">
              <?= $t('nav_resources') ?>
              <i class="fas fa-chevron-down text-xs"></i>
            </button>
            <div class="dropdown-menu">
              <a href="../public/partner/?lang=<?= $currentLang ?>" class="dropdown-item">
                <i class="fas fa-handshake mr-2"></i> <?= $t('nav_our_partners') ?>
              </a>
              <a href="../public/new&events/?lang=<?= $currentLang ?>" class="dropdown-item">
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
              <a href="../public/Faculty_of_Science_and_Mathematics/?lang=<?= $currentLang ?>" class="dropdown-item"><?= $t('faculty_science') ?></a>
              <a href="../public/Faculty_of_Arts_Humanitites_and_Languages/?lang=<?= $currentLang ?>" class="dropdown-item"><?= $t('faculty_arts') ?></a>
              <a href="../public/Faculty_of_Agriculture/?lang=<?= $currentLang ?>" class="dropdown-item"><?= $t('faculty_agriculture') ?></a>
              <a href="../public/Faculty_of_social_science/?lang=<?= $currentLang ?>" class="dropdown-item"><?= $t('faculty_social_science') ?></a>
              <a href="../public/Faculty_of_Management/?lang=<?= $currentLang ?>" class="dropdown-item"><?= $t('faculty_management') ?></a>
            </div>
          </div>

          <div class="dropdown-container">
            <button class="nav-link flex items-center gap-1">
              <?= $t('nav_about') ?>
              <i class="fas fa-chevron-down text-xs"></i>
            </button>
            <div class="dropdown-menu">
              <a href="../public/about/?lang=<?= $currentLang ?>" class="dropdown-item"><?= $t('about_message_rector') ?></a>
              <a href="../public/vision-and-mission/?lang=<?= $currentLang ?>" class="dropdown-item"><?= $t('about_vision_mission') ?></a>
              <a href="../public/history_university/?lang=<?= $currentLang ?>" class="dropdown-item"><?= $t('about_history') ?></a>
            </div>
          </div>

          <div class="dropdown-container">
            <button class="nav-link flex items-center gap-1">
              <?= $t('nav_projects') ?>
              <i class="fas fa-chevron-down text-xs"></i>
            </button>
            <div class="dropdown-menu">
              <a href="../public/world-bank/?lang=<?= $currentLang ?>" class="dropdown-item"><?= $t('projects_world_bank') ?></a>
              <a href="../public/Erasmus/?lang=<?= $currentLang ?>" class="dropdown-item"><?= $t('projects_erasmus') ?></a>
            </div>
          </div>
        </div>

        <div class="desktop-actions">
          <div class="language-switcher">
            <button class="language-btn">
              <img src="../images/flage/<?= $currentLang === 'en' ? 'english.png' : 'cam.png' ?>" alt="<?= strtoupper($currentLang) ?>">
              <span><?= strtoupper($currentLang) ?></span>
              <i class="fas fa-chevron-down text-xs"></i>
            </button>
            <div class="language-dropdown">
              <a href="?lang=en&id=<?= $student_id ?>" class="language-option">
                <img src="../images/flage/english.png" alt="EN">
                English
              </a>
              <a href="?lang=km&id=<?= $student_id ?>" class="language-option">
                <img src="../images/flage/cam.png" alt="KH">
                ភាសាខ្មែរ
              </a>
            </div>
          </div>

          <button class="theme-toggle" id="theme-toggle-desktop" aria-label="Toggle theme">
            <i class="fas fa-moon"></i>
          </button>

          <a href="../FormRegister?lang=<?= $currentLang ?>" class="register-btn">
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
        <a href="../?lang=<?= $currentLang ?>" class="mobile-nav-link">
          <i class="fas fa-home w-6"></i>
          <?= $t('nav_home') ?>
        </a>
      </div>

      <div class="mobile-nav-item">
        <button class="mobile-dropdown-btn" onclick="toggleMobileDropdown('resources')">
          <span><i class="fas fa-folder-open w-6"></i> <?= $t('nav_resources') ?></span>
          <i class="fas fa-chevron-down"></i>
        </button>
        <div class="mobile-dropdown-content" id="resources">
          <a href="../public/partner/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('nav_our_partners') ?></a>
          <a href="../public/new&events/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('nav_news_events') ?></a>
        </div>
      </div>

      <div class="mobile-nav-item">
        <button class="mobile-dropdown-btn" onclick="toggleMobileDropdown('academics')">
          <span><i class="fas fa-book w-6"></i> <?= $t('nav_academics') ?></span>
          <i class="fas fa-chevron-down"></i>
        </button>
        <div class="mobile-dropdown-content" id="academics">
          <a href="../public/Faculty_of_Science_and_Mathematics/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('faculty_science') ?></a>
          <a href="../public/Faculty_of_Arts_Humanitites_and_Languages/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('faculty_arts') ?></a>
          <a href="../public/Faculty_of_Agriculture/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('faculty_agriculture') ?></a>
          <a href="../public/Faculty_of_social_science/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('faculty_social_science') ?></a>
          <a href="../public/Faculty_of_Management/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('faculty_management') ?></a>
        </div>
      </div>

      <div class="mobile-nav-item">
        <button class="mobile-dropdown-btn" onclick="toggleMobileDropdown('about')">
          <span><i class="fas fa-info-circle w-6"></i> <?= $t('nav_about') ?></span>
          <i class="fas fa-chevron-down"></i>
        </button>
        <div class="mobile-dropdown-content" id="about">
          <a href="../public/about/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('about_message_rector') ?></a>
          <a href="../public/vision-and-mission/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('about_vision_mission') ?></a>
          <a href="../public/history_university/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('about_history') ?></a>
        </div>
      </div>

      <div class="mobile-nav-item">
        <button class="mobile-dropdown-btn" onclick="toggleMobileDropdown('projects')">
          <span><i class="fas fa-project-diagram w-6"></i> <?= $t('nav_projects') ?></span>
          <i class="fas fa-chevron-down"></i>
        </button>
        <div class="mobile-dropdown-content" id="projects">
          <a href="../public/world-bank/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('projects_world_bank') ?></a>
          <a href="../public/Erasmus/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('projects_erasmus') ?></a>
        </div>
      </div>
    </div>

    <div class="mobile-language-section">
      <div class="mobile-section-title"><?= $t('language') ?></div>
      <div class="mobile-language-options">
        <a href="?lang=en&id=<?= $student_id ?>" class="mobile-language-option <?= $currentLang === 'en' ? 'active' : '' ?>">
          <img src="../images/flage/english.png" alt="EN">
          <span>EN</span>
        </a>
        <a href="?lang=km&id=<?= $student_id ?>" class="mobile-language-option <?= $currentLang === 'km' ? 'active' : '' ?>">
          <img src="../images/flage/cam.png" alt="KH">
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

      <a href="../FormRegister?lang=<?= $currentLang ?>" class="register-btn w-full justify-center mt-6 py-3">
        <i class="fas fa-user-graduate"></i>
        <?= $t('nav_register') ?> Now
      </a>
    </div>
  </div>

  <div class="container-custom py-6 sm:py-8 lg:py-12">
    <div class="max-w-4xl mx-auto">
      <div class="bg-card rounded-2xl shadow-2xl overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white text-center py-8 sm:py-12 px-4">
          <div class="flex items-center justify-center mb-4">
            <img src="../images/logo_footer/nuck_logo.png" alt="NUCK Logo" class="h-20 w-20 rounded-full bg-white p-2 shadow-lg">
          </div>
          <div class="checkmark mb-4">
            <svg class="w-20 h-20 sm:w-24 sm:h-24 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2"><?= $t('success_title') ?></h1>
          <p class="text-lg sm:text-xl opacity-90"><?= $t('success_subtitle') ?></p>
        </div>
        
        <div class="p-6 sm:p-8 lg:p-10">
          <!-- Student Information Summary -->
          <div class="mb-8">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-gray-200 mb-6 text-center">
              <?= $t('application_summary') ?>
            </h2>
            <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6 space-y-4">
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <p class="text-sm text-gray-600 dark:text-gray-400"><?= $t('application_id') ?></p>
                  <p class="font-semibold text-gray-900 dark:text-white">#<?= str_pad($student['id'], 6, '0', STR_PAD_LEFT) ?></p>
                </div>
                <div>
                  <p class="text-sm text-gray-600 dark:text-gray-400"><?= $t('name_khmer') ?></p>
                  <p class="font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($student['name_khmer']) ?></p>
                </div>
                <div>
                  <p class="text-sm text-gray-600 dark:text-gray-400"><?= $t('program') ?></p>
                  <p class="font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($student['program']) ?></p>
                </div>
                <div>
                  <p class="text-sm text-gray-600 dark:text-gray-400"><?= $t('submission_date') ?></p>
                  <p class="font-semibold text-gray-900 dark:text-white"><?= !empty($student['created_at']) ? date('d/m/Y H:i', strtotime($student['created_at'])) : 'N/A' ?></p>
                </div>
              </div>
            </div>
          </div>

          <!-- Success Message -->
          <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 p-6 rounded-xl mb-8">
            <div class="flex items-start">
              <svg class="w-6 h-6 text-green-500 mt-1 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
              </svg>
              <div class="flex-1">
                <h3 class="text-lg font-semibold text-green-800 dark:text-green-200 mb-2"><?= $t('application_received') ?></h3>
                <p class="text-green-700 dark:text-green-300 mb-2"><?= $t('application_received_message') ?></p>
                <p class="text-green-700 dark:text-green-300 text-sm"><?= $t('application_received_message_en') ?></p>
              </div>
            </div>
          </div>

          <!-- Payment Status -->
          <?php
          $payment_status = $student['payment_status'] ?? null;
          $has_proof = !empty($student['payment_proof_path']);
          
          if ($payment_status === 'pending' && $has_proof):
          ?>
          <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 p-6 rounded-xl mb-8">
            <div class="flex items-start">
              <svg class="w-6 h-6 text-yellow-500 mt-1 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
              </svg>
              <div class="flex-1">
                <h3 class="text-lg font-semibold text-yellow-800 dark:text-yellow-200 mb-2"><?= $t('payment_pending_verification') ?></h3>
                <p class="text-yellow-700 dark:text-yellow-300 mb-2"><?= $t('payment_pending_message') ?></p>
                <p class="text-yellow-700 dark:text-yellow-300 text-sm"><?= $t('payment_pending_message_en') ?></p>
              </div>
            </div>
          </div>
          <?php elseif (!$has_proof): ?>
          <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 p-6 rounded-xl mb-8">
            <div class="flex items-start">
              <svg class="w-6 h-6 text-yellow-500 mt-1 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
              </svg>
              <div class="flex-1">
                <h3 class="text-lg font-semibold text-yellow-800 dark:text-yellow-200 mb-2"><?= $t('payment_required') ?></h3>
                <p class="text-yellow-700 dark:text-yellow-300 mb-2"><?= $t('payment_required_message') ?></p>
                <p class="text-yellow-700 dark:text-yellow-300 text-sm mb-4"><?= $t('payment_required_message_en') ?></p>
                <a href="payment.php?id=<?= $student['id'] ?>&lang=<?= $currentLang ?>" class="inline-block px-6 py-2 bg-yellow-600 text-white rounded-lg font-semibold hover:bg-yellow-700 transition">
                  <?= $t('pay_now') ?>
                </a>
              </div>
            </div>
          </div>
          <?php endif; ?>
          
          <!-- Next Steps -->
          <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">📋 <?= $t('next_steps') ?></h3>
            <ul class="space-y-3">
              <li class="flex items-start">
                <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300 rounded-full flex items-center justify-center text-sm font-bold mr-3">1</span>
                <span class="text-gray-700 dark:text-gray-300"><?= $t('save_id_number') ?></span>
              </li>
              <li class="flex items-start">
                <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300 rounded-full flex items-center justify-center text-sm font-bold mr-3">2</span>
                <span class="text-gray-700 dark:text-gray-300"><?= $t('check_email') ?></span>
              </li>
              <li class="flex items-start">
                <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300 rounded-full flex items-center justify-center text-sm font-bold mr-3">3</span>
                <span class="text-gray-700 dark:text-gray-300"><?= $t('wait_contact') ?></span>
              </li>
            </ul>
          </div>
          
          <!-- Action Buttons -->
          <div class="flex flex-col sm:flex-row gap-4 justify-center pt-6 border-t border-gray-200 dark:border-gray-700">
            <a href="../?lang=<?= $currentLang ?>" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl font-semibold hover:from-blue-700 hover:to-blue-800 transition shadow-lg hover:shadow-xl text-center">
              <?= $t('back_to_home') ?>
            </a>
            <button onclick="window.print()" class="px-8 py-3 border-2 border-blue-600 text-blue-600 dark:text-blue-400 rounded-xl font-semibold hover:bg-blue-50 dark:hover:bg-blue-900 transition text-center">
              <?= $t('print') ?>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="pt-10 sm:pt-12 pb-6 mt-12">
    <div class="container-custom">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
        <div class="sm:col-span-2">
          <a href="../?lang=<?= $currentLang ?>" class="inline-flex items-center gap-2 mb-4">
            <img src="../images/logo_footer/nuck_logo.png" alt="NUCK" class="h-10 w-auto">
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
              <span>097 828 1168</span>
            </li>
            <li class="flex items-center gap-2">
              <i class="fas fa-envelope w-4"></i>
              <span>info@nuck.edu.kh</span>
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
          <li><a href="../?lang=<?= $currentLang ?>" class="hover:text-yellow-500 transition"><?= $t('footer_faq') ?></a></li>
          <li><a href="../?lang=<?= $currentLang ?>" class="hover:text-yellow-500 transition"><?= $t('footer_privacy') ?></a></li>
          <li><a href="../?lang=<?= $currentLang ?>" class="hover:text-yellow-500 transition"><?= $t('footer_terms') ?></a></li>
        </ul>
      </div>
    </div>
  </footer>

  <button id="scroll-top" aria-label="Scroll to top">
    <i class="fas fa-arrow-up"></i>
  </button>

  <script>
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
        mobileThemeLight.classList.toggle('active', !isDark);
        mobileThemeDark.classList.toggle('active', isDark);
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

    // Mobile Dropdowns
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

    // Scroll to Top
    const scrollTopBtn = document.getElementById('scroll-top');
    window.addEventListener('scroll', () => scrollTopBtn?.classList.toggle('show', window.scrollY > 500));
    scrollTopBtn?.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
  </script>
</body>
</html>