<?php
// Start session at the VERY BEGINNING
session_start();

// Include database configuration
include './../../includes/config.php';

// Get language from session or default to English
$language = isset($_SESSION['language']) ? $_SESSION['language'] : 'en';

// Handle language switch
if (isset($_GET['lang'])) {
    $_SESSION['language'] = $_GET['lang'];
    $language = $_GET['lang'];
}

// Create a new Database instance and get the connection
$database = new Database();
$db = $database->getConnection();

// Check if slug is provided in the URL
if (!isset($_GET['slug'])) {
    echo "<div class='mt-24 mx-auto max-w-c-1390 px-4 md:px-8 2xl:px-0 py-8 text-center text-red-500'>Project not found. Please provide a valid slug.</div>";
    exit;
}

$slug = $_GET['slug'];

// Prepare and execute the SQL query to fetch project details by slug
$query = "SELECT * FROM worldbank_programs WHERE slug = :slug LIMIT 1";
$stmt = $db->prepare($query);
$stmt->bindParam(':slug', $slug);
$stmt->execute();
$project = $stmt->fetch(PDO::FETCH_ASSOC);

// If project not found, display a message and exit
if (!$project) {
    echo "<div class='mt-24 mx-auto max-w-c-1390 px-4 md:px-8 2xl:px-0 py-8 text-center text-red-500'>Project not found.</div>";
    exit;
}

// Check if project is active
if (!$project['is_active']) {
    echo "<div class='mt-24 mx-auto max-w-c-1390 px-4 md:px-8 2xl:px-0 py-8 text-center text-yellow-500'>This project is currently inactive.</div>";
    exit;
}

/**
 * Function to resolve image path.
 */
function getImagePath($path) {
    if (!empty($path)) {
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        } else {
            // Path from frontend/public/world-bank/ to admin/uploads/
            // frontend/public/world-bank/ -> frontend/public/ -> frontend/ -> root -> admin/uploads/
            return '../../../adminnuck.nuck.edu.kh/' . ltrim($path, '/');
        }
    }
    return 'https://placehold.co/800x450/cccccc/333333?text=HEIP-2+Project';
}

/**
 * Function to get localized text
 */
function getLocalizedText($en, $km, $lang) {
    return $lang === 'km' ? $km : $en;
}
?>
<!DOCTYPE html>
<html lang="<?php echo $language; ?>" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title><?php echo $language === 'km' ? 'ព័ត៌មានលម្អិតគម្រោង HEIP-2 | NUCK' : 'HEIP-2 Project Details | NUCK'; ?></title>
    <link rel="shortcut icon" href="./../../images/logo_footer/nuck_logo.png" type="image/x-icon">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Kantumruy+Pro:wght@300;400;500;600;700&family=Hanuman:wght@100;300;400;700;900&family=Noto+Sans+Khmer:wght@100;200;300;400;500;600;700;800;900&family=Battambang:wght@300;400;700&display=swap" rel="stylesheet">
    
    <!-- AOS Animation Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.1/aos.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.1/aos.js"></script>

    <script>
        // Configure Tailwind for dark mode
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
            font-family: 'Inter', 'Kantumruy Pro', 'Noto Sans Khmer', 'Battambang', sans-serif;
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
            font-family: 'Kantumruy Pro', 'Noto Sans Khmer', 'Battambang', sans-serif;
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
            font-size: 0.9rem;
            white-space: nowrap;
            text-decoration: none;
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
            border-left-color: var(--accent-color);
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

        /* Project Detail Styles */
        .project-header {
            position: relative;
            height: 400px;
            width: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        @media (min-width: 768px) {
            .project-header {
                height: 500px;
            }
        }

        .project-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.4) 100%);
        }

        .project-title-wrapper {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 0 1rem;
        }

        .project-title {
            animation: fadeInUp 1s ease;
            color: white;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .divider-bar-white {
            width: 5rem;
            height: 4px;
            background: linear-gradient(to right, white, var(--accent-color));
            margin: 1rem auto;
        }

        .detail-card {
            background-color: var(--card-bg);
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            margin-bottom: 2rem;
            padding: 2rem;
        }

        .detail-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }

        .section-title i {
            color: var(--primary-color);
            margin-right: 0.75rem;
        }

        /* Scroll to Top */
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
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container-custom">
            <div class="flex justify-between items-center h-[70px] lg:h-20">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="./../../" class="flex items-center">
                        <img src="./../../images/logo/NUCK_Logo_Web.png" alt="NUCK" class="h-9 sm:h-10 lg:h-14 w-auto">
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="nav-menu-desktop">
                    <a href="./../../" class="nav-link">HOME</a>
                    
                    <div class="dropdown-container">
                        <button class="nav-link flex items-center gap-1">
                            RESOURCES
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a href="./../../public/partner/" class="dropdown-item">
                                <i class="fas fa-handshake mr-2"></i> Our Partners
                            </a>
                            <a href="./../../public/new&events/" class="dropdown-item">
                                <i class="fas fa-calendar-alt mr-2"></i> News & Events
                            </a>
                        </div>
                    </div>
                    
                    <div class="dropdown-container">
                        <button class="nav-link flex items-center gap-1">
                            ACADEMICS
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a href="./../../public/Faculty_of_Science_and_Mathematics" class="dropdown-item">Science & Mathematics</a>
                            <a href="./../../public/Faculty_of_Arts_Humanitites_and_Languages" class="dropdown-item">Arts, Humanities & Languages</a>
                            <a href="./../../public/Faculty_of_Agriculture" class="dropdown-item">Agriculture</a>
                            <a href="./../../public/Faculty_of_social_science" class="dropdown-item">Social Science</a>
                            <a href="./../../public/Faculty_of_Management" class="dropdown-item">Management</a>
                        </div>
                    </div>

                    <div class="dropdown-container">
                        <button class="nav-link flex items-center gap-1">
                            ABOUT
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a href="./../../public/about/" class="dropdown-item">Message from Rector</a>
                            <a href="./../../public/vision-and-mission/" class="dropdown-item">Vision & Mission</a>
                            <a href="./../../public/history_university/" class="dropdown-item">University History</a>
                        </div>
                    </div>

                    <div class="dropdown-container">
                        <button class="nav-link flex items-center gap-1">
                            PROJECTS
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a href="./index.php" class="dropdown-item">World Banks</a>
                            <a href="./../Erasmus/index.php" class="dropdown-item">Erasmus+</a>
                        </div>
                    </div>
                </div>

                <!-- Desktop Actions -->
                <div class="desktop-actions">
                    <div class="language-switcher">
                        <button class="language-btn" id="desktop-language-btn">
                            <img src="./../../images/flage/english.png" alt="EN">
                            <span><?php echo $language === 'km' ? 'ខ្មែរ' : 'EN'; ?></span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div class="language-dropdown" id="desktop-language-dropdown">
                            <a href="?slug=<?php echo urlencode($slug); ?>&lang=en" class="language-option <?php echo $language === 'en' ? 'active' : ''; ?>">
                                <img src="./../../images/flage/english.png" alt="EN">
                                English
                            </a>
                            <a href="?slug=<?php echo urlencode($slug); ?>&lang=km" class="language-option <?php echo $language === 'km' ? 'active' : ''; ?>">
                                <img src="./../../images/flage/cam.png" alt="KH">
                                ភាសាខ្មែរ
                            </a>
                        </div>
                    </div>

                    <button class="theme-toggle" id="theme-toggle-desktop" aria-label="Toggle theme">
                        <i class="fas fa-moon"></i>
                    </button>

                    <a href="./../../FormRegister" class="register-btn">
                        <i class="fas fa-user-graduate"></i>
                        <span>Register</span>
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
                <a href="./../../" class="mobile-nav-link">
                    <i class="fas fa-home w-6"></i>
                    HOME
                </a>
            </div>

            <div class="mobile-nav-item">
                <button class="mobile-dropdown-btn" onclick="toggleMobileDropdown('resources')">
                    <span>
                        <i class="fas fa-folder-open w-6"></i>
                        RESOURCES
                    </span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="mobile-dropdown-content" id="resources">
                    <a href="./../../public/partner/" class="mobile-dropdown-item">
                        <i class="fas fa-handshake mr-2"></i> Our Partners
                    </a>
                    <a href="./../../public/new&events/" class="mobile-dropdown-item">
                        <i class="fas fa-calendar-alt mr-2"></i> News & Events
                    </a>
                </div>
            </div>

            <div class="mobile-nav-item">
                <button class="mobile-dropdown-btn" onclick="toggleMobileDropdown('academics')">
                    <span>
                        <i class="fas fa-book w-6"></i>
                        ACADEMICS
                    </span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="mobile-dropdown-content" id="academics">
                    <a href="./../../public/Faculty_of_Science_and_Mathematics" class="mobile-dropdown-item">Science & Mathematics</a>
                    <a href="./../../public/Faculty_of_Arts_Humanitites_and_Languages" class="mobile-dropdown-item">Arts, Humanities & Languages</a>
                    <a href="./../../public/Faculty_of_Agriculture" class="mobile-dropdown-item">Agriculture</a>
                    <a href="./../../public/Faculty_of_social_science" class="mobile-dropdown-item">Social Science</a>
                    <a href="./../../public/Faculty_of_Management" class="mobile-dropdown-item">Management</a>
                </div>
            </div>

            <div class="mobile-nav-item">
                <button class="mobile-dropdown-btn" onclick="toggleMobileDropdown('about')">
                    <span>
                        <i class="fas fa-info-circle w-6"></i>
                        ABOUT
                    </span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="mobile-dropdown-content" id="about">
                    <a href="./../../public/about/" class="mobile-dropdown-item">Message from Rector</a>
                    <a href="./../../public/vision-and-mission/" class="mobile-dropdown-item">Vision & Mission</a>
                    <a href="./../../public/history_university/" class="mobile-dropdown-item">University History</a>
                </div>
            </div>

            <div class="mobile-nav-item">
                <button class="mobile-dropdown-btn" onclick="toggleMobileDropdown('projects')">
                    <span>
                        <i class="fas fa-project-diagram w-6"></i>
                        PROJECTS
                    </span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="mobile-dropdown-content" id="projects">
                    <a href="./index.php" class="mobile-dropdown-item">World Banks</a>
                    <a href="./../Erasmus/index.php" class="mobile-dropdown-item">Erasmus+</a>
                </div>
            </div>
        </div>

        <div class="mobile-language-section">
            <div class="mobile-section-title">Language</div>
            <div class="mobile-language-options">
                <a href="?slug=<?php echo urlencode($slug); ?>&lang=en" class="mobile-language-option <?php echo $language === 'en' ? 'active' : ''; ?>" id="mobile-lang-en">
                    <img src="./../../images/flage/english.png" alt="EN">
                    <span>EN</span>
                </a>
                <a href="?slug=<?php echo urlencode($slug); ?>&lang=km" class="mobile-language-option <?php echo $language === 'km' ? 'active' : ''; ?>" id="mobile-lang-km">
                    <img src="./../../images/flage/cam.png" alt="KH">
                    <span>KH</span>
                </a>
            </div>

            <div class="mobile-section-title mt-4">Theme</div>
            <div class="mobile-theme-options">
                <button class="mobile-theme-btn active" id="mobile-theme-light">
                    <i class="fas fa-sun"></i>
                    <span>Light</span>
                </button>
                <button class="mobile-theme-btn" id="mobile-theme-dark">
                    <i class="fas fa-moon"></i>
                    <span>Dark</span>
                </button>
            </div>

            <a href="./../../FormRegister" class="register-btn w-full justify-center mt-6 py-3">
                <i class="fas fa-user-graduate"></i>
                Register Now
            </a>
        </div>
    </div>

    <!-- Project Header -->
    <div class="project-header" style="background-image: url('<?php echo getImagePath($project['image_path'] ?? ''); ?>');">
        <div class="project-overlay"></div>
        <div class="project-title-wrapper">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4 project-title">
                <?php echo htmlspecialchars(getLocalizedText($project['title_en'], $project['title_km'], $language)); ?>
            </h1>
            <div class="divider-bar-white"></div>
            <p class="text-lg text-white/80 mt-4 max-w-2xl khmer-font">
                <?php echo $language === 'km' ? 'គម្រោងកែលម្អការអប់រំកម្រិតឧត្តមសិក្សាលើកទីពីរ' : 'Second Higher Education Improvement Project'; ?>
            </p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container-custom py-12">
        <div class="max-w-4xl mx-auto">
            <!-- Back Button -->
            <div class="mb-6" data-aos="fade-right">
                <a href="./index.php?lang=<?php echo $language; ?>" class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <?php echo $language === 'km' ? 'ត្រឡប់ទៅគម្រោង HEIP-2' : 'Back to HEIP-2 Projects'; ?>
                </a>
            </div>

            <!-- Project Details Card -->
            <div class="detail-card" data-aos="fade-up">
                <!-- Status Badge -->
                <div class="flex justify-end mb-4">
                    <?php if (isset($project['status']) && $project['status'] === 'active'): ?>
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            <i class="fas fa-check-circle mr-2"></i>
                            <?php echo $language === 'km' ? 'កំពុងអនុវត្ត' : 'Active'; ?>
                        </span>
                    <?php else: ?>
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            <i class="fas fa-clock mr-2"></i>
                            <?php echo $language === 'km' ? 'គ្រោង' : 'Planned'; ?>
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Project Title -->
                <h1 class="text-3xl md:text-4xl font-bold text-primary mb-6 text-center">
                    <?php echo htmlspecialchars(getLocalizedText($project['title_en'], $project['title_km'], $language)); ?>
                </h1>

                <!-- Project Description -->
                <div class="space-y-6 text-secondary leading-relaxed">
                    <p>
                        <?php echo nl2br(htmlspecialchars(getLocalizedText($project['description_en'], $project['description_km'], $language))); ?>
                    </p>
                </div>

                <!-- Project Objectives -->
                <div class="mt-8">
                    <h2 class="section-title">
                        <i class="fas fa-bullseye text-blue-600"></i>
                        <?php echo $language === 'km' ? 'គោលបំណងគម្រោង' : 'Project Objectives'; ?>
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                            <span class="text-secondary"><?php echo $language === 'km' ? 'កែលម្អគុណភាពកម្មវិធីសិក្សា' : 'Improve quality of academic programs'; ?></span>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                            <span class="text-secondary"><?php echo $language === 'km' ? 'ពង្រឹងសមត្ថភាពស្រាវជ្រាវ' : 'Strengthen research capacity'; ?></span>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                            <span class="text-secondary"><?php echo $language === 'km' ? 'អភិវឌ្ឍកម្មវិធី STEM' : 'Develop STEM programs'; ?></span>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                            <span class="text-secondary"><?php echo $language === 'km' ? 'ពង្រឹងការគ្រប់គ្រងស្ថាប័ន' : 'Strengthen institutional governance'; ?></span>
                        </div>
                    </div>
                </div>


                <!-- Apply Button -->
                <div class="text-center pt-8">
                    <a href="./../../FormRegister" class="inline-block px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-blue-800 transition shadow-lg hover:shadow-xl">
                        <i class="fas fa-edit mr-2"></i>
                        <?php echo $language === 'km' ? 'ដាក់ពាក្យឥឡូវនេះ' : 'Apply Now'; ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="pt-10 sm:pt-12 pb-6" style="background-color: var(--footer-bg); color: white;">
        <div class="container-custom">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
                <div class="sm:col-span-2">
                    <a href="./../../" class="inline-flex items-center gap-2 mb-4">
                        <img src="./../../images/logo_footer/nuck_logo.png" alt="NUCK" class="h-10 w-auto">
                        <span class="text-lg font-bold text-white">National University of Cheasim Kamchaymear</span>
                    </a>
                    <p class="text-gray-400 text-sm leading-relaxed max-w-md">
                        National Road 8, Thnal Keng Village, Smoang Cheung Commune,<br>
                        Kamchaymear District, Prey Veng Province, CAMBODIA.
                    </p>
                </div>

                <div>
                    <h3 class="text-base sm:text-lg font-bold mb-4 text-white">Contacts</h3>
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
                    <h3 class="text-base sm:text-lg font-bold mb-4 text-white">Follow Us</h3>
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
                    © Copyright 2025 National University of CheaSim Kamchaymear. All rights reserved.
                </p>
                <ul class="flex gap-4 sm:gap-6 text-xs sm:text-sm text-gray-400">
                    <li><a href="./../../" class="hover:text-yellow-500 transition">F.A.Q</a></li>
                    <li><a href="./../../" class="hover:text-yellow-500 transition">Privacy Policy</a></li>
                    <li><a href="./../../" class="hover:text-yellow-500 transition">Terms & Conditions</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button id="scroll-top" aria-label="Scroll to top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true
        });

        // =============================================
        // THEME SYSTEM
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

        initializeTheme();

        document.getElementById('theme-toggle-desktop')?.addEventListener('click', () => {
            const isDark = html.classList.contains('dark');
            setTheme(isDark ? 'light' : 'dark');
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

        // =============================================
        // MOBILE LANGUAGE ACTIVE STATE
        // =============================================
        const currentPath = window.location.pathname;
        const mobileLangEn = document.getElementById('mobile-lang-en');
        const mobileLangKm = document.getElementById('mobile-lang-km');

        if (currentPath.includes('/km/')) {
            mobileLangEn?.classList.remove('active');
            mobileLangKm?.classList.add('active');
        } else {
            mobileLangEn?.classList.add('active');
            mobileLangKm?.classList.remove('active');
        }
    </script>
</body>
</html>