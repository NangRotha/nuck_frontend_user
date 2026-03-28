<?php
// Include language system
require_once __DIR__ . '/../../includes/language.php';

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

// Fetch active programs
$query = "SELECT * FROM worldbank_programs WHERE is_active = 1 ORDER BY created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$programs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Function to format date based on language
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
?>
<!DOCTYPE html>
<html lang="<?= $currentLang === 'en' ? 'en' : 'km' ?>" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>NUCK | <?= $t('projects_world_bank') ?> - National University of Cheasim Kamchaymear</title>
    <link rel="shortcut icon" href="./../../images/logo_footer/nuck_logo.png" type="image/x-icon">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Kantumruy+Pro:wght@300;400;500;600;700&family=Hanuman:wght@100;300;400;700;900&family=Noto+Sans+Khmer:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
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
            font-family: 'Inter', 'Kantumruy Pro', 'Noto Sans Khmer', sans-serif;
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
            font-family: 'Kantumruy Pro', 'Noto Sans Khmer', sans-serif !important;
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

        .animate-scale {
            transition: transform 0.3s ease-in-out;
        }
        .animate-scale:hover {
            transform: scale(1.05);
        }
        
        .faq-item {
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .faq-question {
            width: 100%;
            padding: 1.25rem 1.5rem;
            text-align: left;
            background-color: var(--card-bg);
            border: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            color: var(--text-primary);
        }

        .faq-question:hover {
            background-color: var(--bg-secondary);
        }

        .faq-answer {
            padding: 0 1.5rem 1.25rem;
            color: var(--text-secondary);
            display: none;
        }

        .faq-answer.show {
            display: block;
        }

        .faq-icon {
            transition: transform 0.3s ease;
        }

        .faq-icon.rotate {
            transform: rotate(180deg);
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
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
                            <a href="./" class="dropdown-item"><?= $t('projects_world_bank') ?></a>
                            <a href="./../Erasmus/?lang=<?= $currentLang ?>" class="dropdown-item"><?= $t('projects_erasmus') ?></a>
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
                            <a href="?lang=en" class="language-option <?= $currentLang === 'en' ? 'active' : '' ?>">
                                <img src="./../../images/flage/english.png" alt="EN">
                                English
                            </a>
                            <a href="?lang=km" class="language-option <?= $currentLang === 'km' ? 'active' : '' ?>">
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
            <div class="mobile-nav-item">
                <a href="./../../?lang=<?= $currentLang ?>" class="mobile-nav-link">
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
                    <a href="./../../public/partner/?lang=<?= $currentLang ?>" class="mobile-dropdown-item">
                        <i class="fas fa-handshake mr-2"></i> <?= $t('nav_our_partners') ?>
                    </a>
                    <a href="./../../public/new&events/?lang=<?= $currentLang ?>" class="mobile-dropdown-item">
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
                    <a href="./../../public/Faculty_of_Science_and_Mathematics/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('faculty_science') ?></a>
                    <a href="./../../public/Faculty_of_Arts_Humanitites_and_Languages/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('faculty_arts') ?></a>
                    <a href="./../../public/Faculty_of_Agriculture/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('faculty_agriculture') ?></a>
                    <a href="./../../public/Faculty_of_social_science/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('faculty_social_science') ?></a>
                    <a href="./../../public/Faculty_of_Management/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('faculty_management') ?></a>
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
                    <a href="./../../public/about/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('about_message_rector') ?></a>
                    <a href="./../../public/vision-and-mission/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('about_vision_mission') ?></a>
                    <a href="./../../public/history_university/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('about_history') ?></a>
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
                    <a href="./" class="mobile-dropdown-item"><?= $t('projects_world_bank') ?></a>
                    <a href="./../Erasmus/?lang=<?= $currentLang ?>" class="mobile-dropdown-item"><?= $t('projects_erasmus') ?></a>
                </div>
            </div>
        </div>

        <div class="mobile-language-section">
            <div class="mobile-section-title"><?= $t('language') ?></div>
            <div class="mobile-language-options">
                <a href="?lang=en" class="mobile-language-option <?= $currentLang === 'en' ? 'active' : '' ?>">
                    <img src="./../../images/flage/english.png" alt="EN">
                    <span>EN</span>
                </a>
                <a href="?lang=km" class="mobile-language-option <?= $currentLang === 'km' ? 'active' : '' ?>">
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

    <!-- Hero Section -->
    <section class="pt-24 pb-16 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900">
        <div class="container mx-auto px-4 md:px-8 2xl:px-16">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6" data-aos="fade-up">
                    <?= $t('projects_world_bank') ?>
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-300 mb-8" data-aos="fade-up" data-aos-delay="100">
                    <?= $isKhmer ? 
                        'ស្វាគមន៍មកកាន់គម្រោងជំនួយរបស់ធនាគារពិភពលោក នៃសាកលវិទ្យាល័យជាតិជាស៊ីមកំចាយមារ' : 
                        'Welcome to Second Higher Education Improvement Project (HEIP-2) at National University of Cheasim Kamchaymear' ?>
                </p>
                <div class="flex flex-wrap justify-center gap-4" data-aos="fade-up" data-aos-delay="200">
                    <a href="#programs" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-graduation-cap mr-2"></i>
                        <?= $t('view_programs') ?>
                    </a>
                    <a href="#contact" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        <i class="fas fa-envelope mr-2"></i>
                        <?= $t('contact_us') ?>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-16" id="about">
        <div class="container mx-auto px-4 md:px-8 2xl:px-16">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div data-aos="fade-right">
                    <h2 class="text-3xl font-bold mb-6">
                        <?= $t('about_heip2') ?>
                    </h2>
                    <div class="space-y-4 text-gray-700 dark:text-gray-300">
                        <p>
                            <?= $isKhmer ? 
                                'HEIP-2 គឺជាគម្រោងកែលម្អការអប់រំកម្រិតឧត្តមសិក្សាលើកទីពីរ មានគោលបំណងកែលម្អគុណភាព ភាពពាក់ព័ន្ធ និងការស្រាវជ្រាវនៃកម្មវិធីសិក្សា ជាពិសេសក្នុងវិស័យ STEM និងកសិកម្ម ព្រមទាំងពង្រឹងការគ្រប់គ្រងស្ថាប័ននៃគ្រឹះស្ថានឧត្តមសិក្សាគោលដៅ។' : 
                                'HEIP-2 aims to improve the quality, relevance, and research of academic programs, mainly in STEM and agriculture, and to strengthen the institutional governance of target higher education institutions.' ?>
                        </p>
                    </div>
                </div>
                
                <div data-aos="fade-left">
                    <img src="./../../images/world-bank/12-2-optimized.webp" alt="World Bank Project" 
                         class="w-full h-auto rounded-2xl shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Programs Section -->
    <section class="py-16 bg-gray-50 dark:bg-gray-800" id="programs">
        <div class="container mx-auto px-4 md:px-8 2xl:px-16">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4">
                    <?= $t('improvement_projects') ?>
                </h2>
                <p class="text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    <?= $isKhmer ? 
                        'គម្រោង HEIP-2 ដែលកំពុងអនុវត្តនៅសាកលវិទ្យាល័យជាតិជាស៊ីមកំចាយមារ' : 
                        'Current HEIP-2 projects at National University of Cheasim Kamchaymear' ?>
                </p>
            </div>
            
            <?php if (empty($programs)): ?>
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-5xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">
                        <?= $t('no_projects') ?>
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        <?= $isKhmer ? 
                            'គ្មានគម្រោង HEIP-2 ដែលកំពុងអនុវត្តនៅពេលនេះទេ។ សូមត្រលប់មកវិញនៅពេលក្រោយ។' : 
                            'There are no HEIP-2 projects available at the moment. Please check back later.' ?>
                    </p>
                </div>
            <?php else: ?>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($programs as $index => $program): ?>
                    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg overflow-hidden animate-scale" data-aos="zoom-in" data-aos-delay="<?= $index * 50 ?>">
                        <div class="h-48 overflow-hidden">
                            <?php if (!empty($program['image_path'])): ?>
                                <img src="../../../adminnuck.nuck.edu.kh/<?= htmlspecialchars($program['image_path']) ?>" 
                                     alt="<?= htmlspecialchars($isKhmer ? ($program['title_km'] ?? $program['title_en']) : ($program['title_en'] ?? 'World Bank Project')) ?>" 
                                     class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                            <?php else: ?>
                                <div class="w-full h-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                    <i class="fas fa-graduation-cap text-white text-4xl"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-3">
                                <?= htmlspecialchars($isKhmer ? ($program['title_km'] ?? $program['title_en']) : ($program['title_en'] ?? 'World Bank Project')) ?>
                            </h3>
                            
                            <p class="text-gray-700 dark:text-gray-300 mb-4 line-clamp-3">
                                <?= nl2br(htmlspecialchars(substr($isKhmer ? ($program['description_km'] ?? $program['description_en']) : ($program['description_en'] ?? ''), 0, 150))) ?>...
                            </p>
                            
                            <div class="flex justify-between items-center">
                                <a href="worldBank-detail.php?slug=<?= urlencode($program['slug']) ?>&lang=<?= $currentLang ?>"
                                   class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                                    <?= $t('read_more') ?>
                                    <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                                
                                <?php if (isset($program['status']) && $program['status'] === 'active'): ?>
                                <span class="text-xs px-3 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300 rounded-full">
                                    <?= $t('active') ?>
                                </span>
                                <?php else: ?>
                                <span class="text-xs px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300 rounded-full">
                                    <?= $t('planned') ?>
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="py-16">
        <div class="container mx-auto px-4 md:px-8 2xl:px-16">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4">
                    <?= $t('benefits') ?>
                </h2>
                <p class="text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    <?= $isKhmer ? 
                        'អត្ថប្រយោជន៍នៃគម្រោង HEIP-2 សម្រាប់សាកលវិទ្យាល័យ' : 
                        'Benefits of the HEIP-2 Project for the University' ?>
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php 
                $benefits = $isKhmer ? [
                    ['icon' => 'fas fa-flask', 'title' => 'ការស្រាវជ្រាវ', 'desc' => 'បង្កើនសមត្ថភាពស្រាវជ្រាវរបស់សាស្ត្រាចារ្យ និងនិស្សិត'],
                    ['icon' => 'fas fa-chalkboard-teacher', 'title' => 'គុណភាពអប់រំ', 'desc' => 'កែលម្អគុណភាពកម្មវិធីសិក្សា និងការបង្រៀន'],
                    ['icon' => 'fas fa-cogs', 'title' => 'បរិក្ខារ', 'desc' => 'ធ្វើឲ្យប្រសើរឡើងនូវបរិក្ខារពិសោធន៍ និងសម្ភារៈបង្រៀន'],
                    ['icon' => 'fas fa-handshake', 'title' => 'ភាពជាដៃគូ', 'desc' => 'ពង្រឹងភាពជាដៃគូជាមួយស្ថាប័នអប់រំ និងឧស្សាហកម្ម']
                ] : [
                    ['icon' => 'fas fa-flask', 'title' => $t('research'), 'desc' => 'Enhance research capacity for faculty and students'],
                    ['icon' => 'fas fa-chalkboard-teacher', 'title' => $t('education_quality'), 'desc' => 'Improve program quality and teaching methods'],
                    ['icon' => 'fas fa-cogs', 'title' => $t('facilities'), 'desc' => 'Upgrade laboratory facilities and teaching materials'],
                    ['icon' => 'fas fa-handshake', 'title' => $t('partnerships'), 'desc' => 'Strengthen partnerships with educational institutions and industry']
                ];
                
                foreach ($benefits as $benefit): 
                ?>
                <div class="text-center p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-shadow" data-aos="fade-up">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                        <i class="<?= $benefit['icon']; ?> text-blue-600 dark:text-blue-400 text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-semibold mb-2"><?= $benefit['title']; ?></h4>
                    <p class="text-gray-600 dark:text-gray-300"><?= $benefit['desc']; ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-16 bg-gray-50 dark:bg-gray-800" id="contact">
        <div class="container mx-auto px-4 md:px-8 2xl:px-16">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold mb-4">
                        <?= $t('contact_us') ?>
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300">
                        <?= $isKhmer ? 
                            'សម្រាប់ព័ត៌មានបន្ថែមអំពីគម្រោង HEIP-2' : 
                            'For more information about the HEIP-2 Project' ?>
                    </p>
                </div>
                
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="bg-white dark:bg-gray-900 p-8 rounded-xl shadow-lg">
                        <h3 class="text-2xl font-bold mb-6">
                            <?= $t('contact_information') ?>
                        </h3>
                        
                        <div class="space-y-6">
                            <div class="flex items-start">
                                <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center mr-4">
                                    <i class="fas fa-user text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold"><?= $t('project_coordinator') ?></h4>
                                    <p class="text-gray-600 dark:text-gray-300">Mr. BAN Thach</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center mr-4">
                                    <i class="fas fa-envelope text-green-600 dark:text-green-400"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold"><?= $t('email') ?></h4>
                                    <a href="mailto:thachbannuck@gmail.com" class="text-blue-600 dark:text-blue-400 hover:underline">
                                        thachbannuck@gmail.com
                                    </a>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-900 flex items-center justify-center mr-4">
                                    <i class="fas fa-phone text-purple-600 dark:text-purple-400"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold"><?= $t('phone') ?></h4>
                                    <a href="tel:+85578555159" class="text-blue-600 dark:text-blue-400 hover:underline">
                                        +855 78 555 159
                                    </a>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="w-10 h-10 rounded-full bg-yellow-100 dark:bg-yellow-900 flex items-center justify-center mr-4">
                                    <i class="fas fa-map-marker-alt text-yellow-600 dark:text-yellow-400"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold"><?= $t('location') ?></h4>
                                    <p class="text-gray-600 dark:text-gray-300">
                                        <?= $isKhmer ? 
                                            'នាយកដ្ឋានទំនាក់ទំនងអន្តរជាតិ, សាកលវិទ្យាល័យជាតិជាស៊ីមកំចាយមារ' : 
                                            'International Relations Office, National University of Cheasim Kamchaymear' ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-900 p-8 rounded-xl shadow-lg">
                        <h3 class="text-2xl font-bold mb-6">
                            <?= $t('send_message') ?>
                        </h3>
                        
                        <form id="contactForm" class="space-y-4">
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 mb-2">
                                    <?= $t('full_name') ?> *
                                </label>
                                <input type="text" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-800 dark:border-gray-700" required>
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 mb-2">
                                    <?= $t('email') ?> *
                                </label>
                                <input type="email" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-800 dark:border-gray-700" required>
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 mb-2">
                                    <?= $t('message') ?> *
                                </label>
                                <textarea rows="4" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-800 dark:border-gray-700" required></textarea>
                            </div>
                            
                            <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-paper-plane mr-2"></i>
                                <?= $t('send_message') ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-16">
        <div class="container mx-auto px-4 md:px-8 2xl:px-16">
            <div class="max-w-3xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold mb-4">
                        <?= $t('faq') ?>
                    </h2>
                </div>
                
                <div class="space-y-4">
                    <?php 
                    $faqs = $isKhmer ? [
                        ['q' => 'តើអ្នកណាអាចចូលរួមគម្រោង HEIP-2 បាន?', 
                         'a' => 'សាស្ត្រាចារ្យ និងនិស្សិតដែលកំពុងសិក្សានៅ NUCK និងបានបំពេញតម្រូវការមូលដ្ឋានអាចចូលរួមក្នុងគម្រោង HEIP-2 បាន។'],
                        ['q' => 'តើគម្រោង HEIP-2 មានរយៈពេលប៉ុន្មាន?', 
                         'a' => 'គម្រោង HEIP-2 មានរយៈពេល ៥ឆ្នាំ (២០២០-២០២៥) ហើយកម្មវិធីនីមួយៗអាចមានរយៈពេលខុសៗគ្នា។'],
                        ['q' => 'តើមានជំនួយហិរញ្ញប្បទានដែរឬទេ?', 
                         'a' => 'បាទ/ចាស គម្រោង HEIP-2 ផ្តល់ជំនួយហិរញ្ញប្បទានសម្រាប់ការស្រាវជ្រាវ ការបណ្តុះបណ្តាល និងការកែលម្អបរិក្ខារ។']
                    ] : [
                        ['q' => 'Who can participate in HEIP-2 Project?', 
                         'a' => 'Faculty members and students currently enrolled at NUCK who meet the basic requirements can participate in HEIP-2 projects.'],
                        ['q' => 'How long does the HEIP-2 Project last?', 
                         'a' => 'The HEIP-2 Project lasts for 5 years (2020-2025), and individual programs may have varying durations.'],
                        ['q' => 'Is there financial support available?', 
                         'a' => 'Yes, HEIP-2 provides financial support for research, training, and facility improvements.']
                    ];
                    
                    foreach ($faqs as $index => $faq): 
                    ?>
                    <div class="faq-item">
                        <button class="faq-question" onclick="toggleFAQ(<?= $index ?>)">
                            <span><?= $faq['q']; ?></span>
                            <i class="fas fa-chevron-down faq-icon" id="faq-icon-<?= $index ?>"></i>
                        </button>
                        <div class="faq-answer" id="faq-answer-<?= $index ?>">
                            <p><?= $faq['a']; ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

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

        window.toggleFAQ = function(index) {
            const answer = document.getElementById('faq-answer-' + index);
            const icon = document.getElementById('faq-icon-' + index);
            if (answer) {
                answer.classList.toggle('show');
                icon.classList.toggle('rotate');
            }
        };

        document.getElementById('contactForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('<?= $t('message_sent') ?>');
            this.reset();
        });
    </script>
</body>
</html>