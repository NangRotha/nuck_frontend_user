<?php
// index.php — Registration form UI with navbar and footer
// Include language system
require_once __DIR__ . '/../includes/language.php';

// Get translation function
$t = function($key, $default = '') use ($lang) {
    return $lang->t($key, $default);
};

// Get current language for easy access
$currentLang = $lang->getCurrentLang();
$isKhmer = $currentLang === 'km';

session_start();
?>
<!DOCTYPE html>
<html lang="<?= $currentLang === 'en' ? 'en' : 'km' ?>" class="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
  <link rel="icon" href="images/nuck_logo.png">
  <title>NUCK | <?= $t('registration') ?> - National University of Cheasim Kamchaymear</title>
  
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Kantumruy+Pro:wght@300;400;500;600;700&family=Hanuman:wght@100;300;400;700;900&display=swap" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Battambang:wght@100;300;400;700;900&family=Kantumruy+Pro:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">

  <script>
    // Configure Tailwind for dark mode
    tailwind.config = {
      darkMode: 'class',
    }
  </script>
  
  <style>
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
      font-family: 'Inter', 'Kantumruy Pro', sans-serif;
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
    html[lang="km"] h4,
    html[lang="km"] .nav-link,
    html[lang="km"] .dropdown-item,
    html[lang="km"] .mobile-nav-link,
    html[lang="km"] .mobile-dropdown-item,
    html[lang="km"] .form-label {
      font-family: 'Kantumruy Pro', 'Battambang', sans-serif !important;
    }

    /* Navbar Styles */
    .navbar {
      background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
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

    .section-title {
      background: linear-gradient(to right, #eff6ff, #f9fafb);
      border-left: 4px solid #2563eb;
    }
    
    .dark .section-title {
      background: linear-gradient(to right, #1e293b, #111827);
      border-left: 4px solid #3b82f6;
      color: #f3f4f6;
    }

    .photo-container {
      position: relative;
      width: 100%;
      height: 100%;
    }
    
    #photoPlaceholder {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      background: #f9fafb;
      color: #9ca3af;
      text-align: center;
      padding: 1rem;
    }
    
    .dark #photoPlaceholder {
      background: #1f2937;
      color: #6b7280;
    }
    
    #photoPlaceholder.hidden {
      display: none;
    }
    
    #photoPreview {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: none;
    }
    
    #photoPreview.show {
      display: block;
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

    /* Footer Styles */
    footer {
      background-color: var(--footer-bg);
      color: white;
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

    /* Form Styles */
    .form-input {
      width: 100%;
      padding: 0.65rem 1rem;
      border: 1px solid var(--border-color);
      border-radius: 0.5rem;
      background-color: var(--bg-primary);
      color: var(--text-primary);
      transition: all 0.3s ease;
    }

    .form-input:focus {
      outline: none;
      ring: 2px solid #3b82f6;
      border-color: transparent;
    }

    .form-label {
      display: block;
      font-size: 0.875rem;
      font-weight: 500;
      margin-bottom: 0.5rem;
      color: var(--text-secondary);
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

    .error-tooltip {
      color: #ef4444;
      font-size: 0.75rem;
      margin-top: 0.25rem;
    }

    .border-red-500 {
      border-color: #ef4444 !important;
    }
  </style>
  
  <script>
    function previewPhoto(event) {
      const file = event.target.files[0];
      const preview = document.getElementById('photoPreview');
      const placeholder = document.getElementById('photoPlaceholder');
      
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          preview.src = e.target.result;
          preview.classList.add('show');
          if (placeholder) {
            placeholder.classList.add('hidden');
          }
        };
        reader.readAsDataURL(file);
      }
    }

    // Validate Khmer characters only
    function validateKhmer(input) {
      const khmerPattern = /^[\u1780-\u17FF\s]+$/;
      const value = input.value;
      
      const cleanValue = value.replace(/[^\u1780-\u17FF\s]/g, '');
      if (value !== cleanValue) {
        input.value = cleanValue;
      }
      
      if (value && !khmerPattern.test(value)) {
        input.setCustomValidity('សូមបញ្ចូលតែអក្សរខ្មែរប៉ុណ្ណោះ / Please enter Khmer characters only');
        input.classList.add('border-red-500');
        
        if (!input.nextElementSibling || !input.nextElementSibling.classList.contains('error-tooltip')) {
          const tooltip = document.createElement('div');
          tooltip.className = 'error-tooltip';
          tooltip.textContent = '⚠ សូមបញ្ចូលតែអក្សរខ្មែរ / Khmer characters only';
          input.parentNode.insertBefore(tooltip, input.nextSibling);
        }
      } else {
        input.setCustomValidity('');
        input.classList.remove('border-red-500');
        const tooltip = input.nextElementSibling;
        if (tooltip && tooltip.classList.contains('error-tooltip')) {
          tooltip.remove();
        }
      }
    }

    // Validate Latin characters only
    function validateLatin(input) {
      const latinPattern = /^[a-zA-Z\s]+$/;
      const value = input.value;
      
      const cleanValue = value.replace(/[^a-zA-Z\s]/g, '');
      if (value !== cleanValue) {
        input.value = cleanValue;
      }
      
      if (value && !latinPattern.test(value)) {
        input.setCustomValidity('សូមបញ្ចូលតែអក្សរឡាតាំងប៉ុណ្ណោះ / Please enter Latin characters only');
        input.classList.add('border-red-500');
        
        if (!input.nextElementSibling || !input.nextElementSibling.classList.contains('error-tooltip')) {
          const tooltip = document.createElement('div');
          tooltip.className = 'error-tooltip';
          tooltip.textContent = '⚠ សូមបញ្ចូលតែអក្សរឡាតាំង / Latin characters only';
          input.parentNode.insertBefore(tooltip, input.nextSibling);
        }
      } else {
        input.setCustomValidity('');
        input.classList.remove('border-red-500');
        const tooltip = input.nextElementSibling;
        if (tooltip && tooltip.classList.contains('error-tooltip')) {
          tooltip.remove();
        }
      }
    }

    // Faculty and Program mapping
    const facultyPrograms = {
      'agriculture': {
        'Associate': [
          'Agronomy / កសិកម្ម',
          'Animal Production and Health / ផលិតកម្មសត្វ និងសុខភាពសត្វ',
          'Mechanic / មេកានិច',
          'Electronic / អេឡិចត្រូនិច',
          'Electricity / អគ្គិសនី',
          'Agriculture Engineering / គ្រឿងយន្តកសិកម្ម'
        ],
        'Bachelor': [
          'Agronomy / កសិកម្ម',
          'Animal Production and Health / ផលិតកម្មសត្វ និងសុខភាពសត្វ',
          'Food Technology / បច្ចេកវិទ្យាអាហារ'
        ],
        'Master': [
          'Sustainable Agriculture / និរន្តរភាពកសិកម្ម'
        ],
        'Doctoral': [
          'Agriculture Sciences / វិទ្យាសាស្ត្រកសិកម្ម'
        ]
      },
      'arts': {
        'Bachelor': [
          'Khmer Literature / អក្សរសាស្ត្រខ្មែរ',
          'English for Education / ភាសាអង់គ្លេសសម្រាប់ការអប់រំ',
          'English for Communication / ភាសាអង់គ្លេសសម្រាប់ការប្រាស្រ័យទាក់ទង'
        ],
        'Master': [
          'English / ភាសាអង់គ្លេស',
          'Khmer Literature / អក្សរសាស្ត្រខ្មែរ',
          'Education Sciences / វិទ្យាសាស្ត្រអប់រំ'
        ],
        'Doctoral': [
          'Education Sciences / វិទ្យាសាស្ត្រអប់រំ'
        ]
      },
      'management': {
        'Associate': [
          'Accounting / គណនេយ្យ'
        ],
        'Bachelor': [
          'Accounting / គណនេយ្យ',
          'Banking and Finance / ធនាគារនិងហិរញ្ញវត្ថុ',
          'Marketing / ទីផ្សារ',
          'Human Resources Management / គ្រប់គ្រងធនធានមនុស្ស'
        ],
        'Master': [
          'Financial Management / គ្រប់គ្រងហិរញ្ញវត្ថុ',
          'General Management / គ្រប់គ្រងទូទៅ',
          'Marketing Management / គ្រប់គ្រងទីផ្សារ',
          'Family Business Management / គ្រប់គ្រងអាជីវកម្មគ្រួសារ'
        ],
        'Doctoral': [
          'Business Management / គ្រប់គ្រងពាណិជ្ជកម្ម'
        ]
      },
      'science': {
        'Associate': [
          'Computer Science / វិទ្យាសាស្ត្រកុំព្យូទ័រ'
        ],
        'Bachelor': [
          'Computer Sciences / វិទ្យាសារ្តកុំព្យូទ័រ',
          'Mathematics / គណិតវិទ្យា',
          'Computer Networks and System Management / គ្រប់គ្រងបណ្តាញនិងប្រព័ន្ធកុំព្យូទ័រ',
          'Chemistry / គីមីវិទ្យា',
          'Biology / ជីវវិទ្យា'
        ],
        'Master': [
          'Mathematics / គណិតវិទ្យា'
        ]
      },
      'social': {
        'Bachelor': [
          'Public Administrations / រដ្ឋបាលសាធារណៈ',
          'Law / និតិសាស្រ្ត (ច្បាប់)',
          'Rural Development / អភិវឌ្ឍន៍ជនបទ',
          'Economics / សេដ្ឋកិច្ច'
        ],
        'Master': [
          'Public Administration / រដ្ឋបាលសាធារណៈ',
          'Law / និតិសាស្រ្ត'
        ],
        'Doctoral': [
          'Public Administration / រដ្ឋបាលសាធារណៈ'
        ]
      }
    };

    function updatePrograms() {
      const facultySelect = document.getElementById('facultySelect');
      const degreeLevelSelect = document.querySelector('select[name="degree_level"]');
      const programSelect = document.getElementById('programSelect');
      const selectedFaculty = facultySelect.value;
      const selectedDegree = degreeLevelSelect.value;
      
      programSelect.innerHTML = '<option value="">-- <?= $isKhmer ? 'ជ្រើសរើសកម្មវិធីសិក្សា' : 'Select Program' ?> --</option>';
      
      if (selectedFaculty && selectedDegree && facultyPrograms[selectedFaculty] && facultyPrograms[selectedFaculty][selectedDegree]) {
        facultyPrograms[selectedFaculty][selectedDegree].forEach(function(program) {
          const option = document.createElement('option');
          option.value = program;
          option.textContent = program;
          programSelect.appendChild(option);
        });
      }
    }
  </script>
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
              <a href="?lang=en" class="language-option">
                <img src="../images/flage/english.png" alt="EN">
                English
              </a>
              <a href="?lang=km" class="language-option">
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
        <a href="?lang=en" class="mobile-language-option <?= $currentLang === 'en' ? 'active' : '' ?>">
          <img src="../images/flage/english.png" alt="EN">
          <span>EN</span>
        </a>
        <a href="?lang=km" class="mobile-language-option <?= $currentLang === 'km' ? 'active' : '' ?>">
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

  <!-- Main Content - Registration Form -->
  <div class="container-custom py-6 sm:py-8 lg:py-12">
    <div class="flex justify-center">
      <div class="w-full max-w-5xl">
        <div class="bg-card rounded-lg shadow-xl overflow-hidden">
          <!-- Header -->
          <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-6 sm:py-8 px-4">
            <div class="flex flex-col items-center">
              <div class="mb-4 bg-white rounded-full shadow-lg w-20 h-20 flex items-center justify-center">
                <img src="../images/logo_footer/nuck_logo.png" alt="NUCK Logo" class="w-16 h-16 object-contain">
              </div>
              <div class="text-center">
                <h2 class="text-lg sm:text-xl font-semibold mb-1"><?= $isKhmer ? 'សាកលវិទ្យាល័យជាតិជាស៊ីមកំចាយមារ' : 'National University of Cheasim Kamchaymear' ?></h2>
                <h3 class="text-base sm:text-lg opacity-90 mb-3">National University of Cheasim Kamchaymear</h3>
                <div class="w-20 h-1 bg-white/30 mx-auto mb-4"></div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2"><?= $t('admission_application') ?></h1>
                <p class="text-base sm:text-lg lg:text-xl opacity-90">University Admission Application Form</p>
              </div>
            </div>
          </div>
          
          <div class="p-4 sm:p-6 lg:p-8">
            <!-- Alert Messages -->
            <?php if (!empty($_GET['error'])): ?>
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
              <p class="font-medium">⚠ <?php echo htmlspecialchars($_GET['error']); ?></p>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($_GET['success'])): ?>
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
              <p class="font-medium">✓ <?php echo htmlspecialchars($_GET['success']); ?></p>
            </div>
            <?php endif; ?>
            
            <form method="post" action="submit.php" enctype="multipart/form-data" novalidate>
              
              <!-- Photo Upload Section -->
              <div class="section-title py-3 px-4 mb-6 rounded font-semibold text-base sm:text-lg">
                <?= $t('student_photo') ?>
              </div>
              <div class="mb-8 flex flex-col sm:flex-row items-center gap-6">
                <div class="flex-shrink-0">
                  <div class="w-40 h-48 sm:w-48 sm:h-56 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden bg-gray-50 relative">
                    <div class="photo-container">
                      <div id="photoPlaceholder">
                        <svg class="w-12 h-12 sm:w-16 sm:h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <p class="text-xs sm:text-sm font-medium"><?= $t('student_photo') ?></p>
                        <p class="text-xs mt-1">Student Photo</p>
                        <p class="text-xs mt-1 opacity-75">3x4 cm</p>
                      </div>
                      <img id="photoPreview" alt="Student Photo Preview">
                    </div>
                  </div>
                </div>
                <div class="flex-1 w-full">
                  <label class="form-label">
                    <?= $t('select_photo') ?> *
                  </label>
                  <input type="file" name="student_photo" accept="image/*" onchange="previewPhoto(event)" 
                         class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer" required>
                  <p class="mt-2 text-xs text-gray-500">
                    <?= $t('photo_size_info') ?>
                  </p>
                </div>
              </div>

              <!-- Personal Information Section -->
              <div class="section-title py-3 px-4 mb-6 rounded font-semibold text-base sm:text-lg">
                👤 <?= $t('personal_information') ?>
              </div>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-8">
                <div class="sm:col-span-2 lg:col-span-1">
                  <label class="form-label">
                    <?= $t('name_khmer') ?> *
                  </label>
                  <input type="text" name="name_khmer" 
                         class="form-input" 
                         placeholder="<?= $t('example_khmer_name') ?>" 
                         oninput="validateKhmer(this)" required>
                </div>
                <div class="sm:col-span-2 lg:col-span-1">
                  <label class="form-label">
                    <?= $t('name_latin') ?> *
                  </label>
                  <input type="text" name="name_latin" 
                         class="form-input" 
                         placeholder="<?= $t('example_latin_name') ?>" 
                         oninput="validateLatin(this)" required>
                </div>
                <div>
                  <label class="form-label">
                    <?= $t('gender') ?> *
                  </label>
                  <select name="gender" class="form-input" required>
                    <option value="">-- <?= $t('select') ?> --</option>
                    <option value="ប្រុស"><?= $t('male') ?></option>
                    <option value="ស្រី"><?= $t('female') ?></option>
                  </select>
                </div>
                <div>
                  <label class="form-label">
                    <?= $t('dob') ?> *
                  </label>
                  <input type="date" name="dob" class="form-input" required>
                </div>
                <div>
                  <label class="form-label">
                    <?= $t('phone') ?> *
                  </label>
                  <input type="tel" name="phone" class="form-input" placeholder="012 345 678" required>
                </div>
                <div>
                  <label class="form-label">
                    <?= $t('email') ?> *
                  </label>
                  <input type="email" name="email" class="form-input" placeholder="example@email.com" required>
                </div>
                <div>
                  <label class="form-label">
                    <?= $t('place_of_birth') ?> *
                  </label>
                  <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                    <div class="sm:col-span-1">
                      <select name="birth_country" class="form-input" required>
                        <option value="">-- <?= $t('select_country') ?> --</option>
                        <option value="កម្ពុជា / Cambodia"><?= $t('cambodia') ?></option>
                        <option value="ថៃ / Thailand"><?= $t('thailand') ?></option>
                        <option value="វៀតណាម / Vietnam"><?= $t('vietnam') ?></option>
                        <option value="ឡាវ / Laos"><?= $t('laos') ?></option>
                        <option value="ចិន / China"><?= $t('china') ?></option>
                        <option value="ផ្សេងៗ / Other"><?= $t('other') ?></option>
                      </select>
                    </div>
                    <div class="sm:col-span-1">
                      <select name="place_of_birth" class="form-input" required>
                        <option value="">-- <?= $t('select_province') ?> --</option>
                        <?php
                        $provinces = array(
                          "រាជធានីភ្នំពេញ / Phnom Penh",
                          "ខេត្តបន្ទាយមានជ័យ / Banteay Meanchey",
                          "ខេត្តបាត់ដំបង / Battambang",
                          "ខេត្តកំពង់ចាម / Kampong Cham",
                          "ខេត្តកំពង់ឆ្នាំង / Kampong Chhnang",
                          "ខេត្តកំពង់ស្ពឺ / Kampong Speu",
                          "ខេត្តកំពង់ធំ / Kampong Thom",
                          "ខេត្តកំពត / Kampot",
                          "ខេត្តកណ្តាល / Kandal",
                          "ខេត្តកែប / Kep",
                          "ខេត្តកោះកុង / Koh Kong",
                          "ខេត្តក្រចេះ / Kratié",
                          "ខេត្តមណ្ឌលគិរី / Mondulkiri",
                          "ខេត្តឧត្តរមានជ័យ / Oddar Meanchey",
                          "ខេត្តប៉ៃលិន / Pailin",
                          "ខេត្តព្រះសីហនុ / Preah Sihanouk",
                          "ខេត្តព្រះវិហារ / Preah Vihear",
                          "ខេត្តព្រៃវែង / Prey Veng",
                          "ខេត្តពោធិ៍សាត់ / Pursat",
                          "ខេត្តរតនគិរី / Ratanakiri",
                          "ខេត្តសៀមរាប / Siem Reap",
                          "ខេត្តស្ទឹងត្រែង / Stung Treng",
                          "ខេត្តស្វាយរៀង / Svay Rieng",
                          "ខេត្តតាកែវ / Takéo",
                          "ខេត្តត្បូងឃ្មុំ / Tboung Khmum"
                        );
                        
                        foreach ($provinces as $province) {
                          echo '<option value="' . htmlspecialchars($province) . '">' . htmlspecialchars($province) . '</option>';
                        }
                        ?>
                      </select>
                    </div>
                    <div class="sm:col-span-1">
                      <input type="text" name="birth_commune" class="form-input" placeholder="<?= $t('commune') ?>" oninput="validateKhmer(this)" required>
                    </div>
                    <div class="sm:col-span-1">
                      <input type="text" name="birth_village" class="form-input" placeholder="<?= $t('village') ?>" oninput="validateKhmer(this)" required>
                    </div>
                  </div>
                </div>
                <div class="sm:col-span-2">
                  <label class="form-label">
                    <?= $t('occupation') ?>
                  </label>
                  <input type="text" name="occupation" class="form-input" placeholder="<?= $t('occupation_placeholder') ?>">
                </div>
              </div>

              <!-- Educational Background Section -->
              <div class="section-title py-3 px-4 mb-6 rounded font-semibold text-base sm:text-lg">
                🎓 <?= $t('educational_background') ?>
              </div>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-8">
                <div class="sm:col-span-2">
                  <label class="form-label">
                    <?= $t('high_school_name') ?> *
                  </label>
                  <input type="text" name="high_school_khmer" class="form-input" placeholder="<?= $t('high_school_placeholder') ?>" oninput="validateKhmer(this)" required>
                </div>
                <div>
                  <label class="form-label">
                    <?= $t('graduated_year') ?> *
                  </label>
                  <input type="number" name="graduated_year" min="1990" max="2030" class="form-input" placeholder="<?= $t('example_year') ?>" required>
                </div>
                <div>
                  <label class="form-label">
                    <?= $t('student_type') ?> *
                  </label>
                  <select name="student_type" class="form-input" required>
                    <option value="">-- <?= $t('select') ?> --</option>
                    <option value="សិស្សវិទ្យាសាស្ត្រ"><?= $t('science_student') ?></option>
                    <option value="សិស្សវិទ្យាសាស្ត្រសង្គម"><?= $t('social_science_student') ?></option>
                    <option value="សាមណសិស្ស"><?= $t('novice_student') ?></option>
                    <option value="សិស្សបរទេស"><?= $t('foreign_student') ?></option>
                    <option value="សិស្សអាហារូបករណ៍"><?= $t('scholarship_student') ?></option>
                  </select>
                </div>
              </div>

              <!-- Family Information Section -->
              <div class="section-title py-3 px-4 mb-6 rounded font-semibold text-base sm:text-lg flex items-center gap-2">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                </svg>
                <span><?= $t('family_information') ?></span>
              </div>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-8">
                <div>
                  <label class="form-label">
                    <?= $t('father_name_khmer') ?> *
                  </label>
                  <input type="text" name="father_name_khmer" class="form-input" placeholder="<?= $t('father_name_placeholder') ?>" oninput="validateKhmer(this)" required>
                </div>
                <div>
                  <label class="form-label">
                    <?= $t('father_phone') ?> *
                  </label>
                  <input type="tel" name="father_phone" class="form-input" placeholder="012 345 678" required>
                </div>
                <div>
                  <label class="form-label">
                    <?= $t('mother_name_khmer') ?> *
                  </label>
                  <input type="text" name="mother_name_khmer" class="form-input" placeholder="<?= $t('mother_name_placeholder') ?>" oninput="validateKhmer(this)" required>
                </div>
                <div>
                  <label class="form-label">
                    <?= $t('mother_phone') ?> *
                  </label>
                  <input type="tel" name="mother_phone" class="form-input" placeholder="012 345 678" required>
                </div>
              </div>

              <!-- Program Selection Section -->
              <div class="section-title py-3 px-4 mb-6 rounded font-semibold text-base sm:text-lg">
                📚 <?= $t('program_selection') ?>
              </div>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-8">
                <div>
                  <label class="form-label">
                    <?= $t('degree_level') ?> *
                  </label>
                  <select name="degree_level" onchange="updatePrograms()" class="form-input" required>
                    <option value="">-- <?= $t('select_level') ?> --</option>
                    <option value="Associate">Associate Degree - <?= $t('associate') ?></option>
                    <option value="Bachelor">Bachelor's Degree - <?= $t('bachelor') ?></option>
                    <option value="Master">Master's Program - <?= $t('master') ?></option>
                    <option value="Doctoral">Doctoral Degree - <?= $t('doctoral') ?></option>
                  </select>
                </div>
                <div>
                  <label class="form-label">
                    <?= $t('faculty') ?> *
                  </label>
                  <select name="faculty" id="facultySelect" onchange="updatePrograms()" class="form-input" required>
                    <option value="">-- <?= $t('select_faculty') ?> --</option>
                    <option value="arts"><?= $t('faculty_arts') ?></option>
                    <option value="science"><?= $t('faculty_science') ?></option>
                    <option value="social"><?= $t('faculty_social_science') ?></option>
                    <option value="management"><?= $t('faculty_management') ?></option>
                    <option value="agriculture"><?= $t('faculty_agriculture') ?></option>
                  </select>
                </div>
                <div class="sm:col-span-2">
                  <label class="form-label">
                    <?= $t('desired_program') ?> *
                  </label>
                  <select name="program" id="programSelect" class="form-input" required>
                    <option value="">-- <?= $t('select_faculty_first') ?> --</option>
                  </select>
                </div>
                <div class="sm:col-span-2">
                  <label class="form-label">
                    <?= $t('study_time') ?> *
                  </label>
                  <select name="study_time" class="form-input" required>
                    <option value="">-- <?= $t('select') ?> --</option>
                    <option value="Weekdays"><?= $t('weekdays') ?></option>
                    <option value="Weekend"><?= $t('weekend') ?></option>
                  </select>
                </div>
              </div>

              <!-- Declaration Section -->
              <div class="section-title py-3 px-4 mb-6 rounded font-semibold text-base sm:text-lg">
                ✍️ <?= $t('declaration') ?>
              </div>
              <div class="mb-8">
                <div class="flex items-start">
                  <div class="flex items-center h-5 mt-1">
                    <input type="checkbox" name="declaration" value="1" id="declaration" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" required>
                  </div>
                  <label for="declaration" class="ml-3 text-sm sm:text-base">
                    <span class="font-medium"><?= $t('declaration_text') ?></span><br>
                    <span class="text-gray-600 italic">I hereby declare that the information provided above is true and accurate.</span>
                  </label>
                </div>
              </div>

              <!-- Submit Buttons -->
              <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t">
                <a href="../?lang=<?= $currentLang ?>" class="w-full sm:w-auto px-6 py-3 text-center border-2 border-blue-600 text-blue-600 rounded-lg font-semibold hover:bg-blue-50 transition">
                  <?= $t('back_to_home') ?>
                </a>
                <a href="?lang=<?= $currentLang ?>" class="w-full sm:w-auto px-6 py-3 text-center border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                  <?= $t('reset') ?>
                </a>
                <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 transition shadow-lg hover:shadow-xl">
                  <?= $t('submit_application') ?>
                </button>
              </div>
            </form>
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
        mobileThemeLight.classList.toggle('active', !isDark);
        mobileThemeDark.classList.toggle('active', isDark);
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
    window.addEventListener('scroll', () => {
      scrollTopBtn?.classList.toggle('show', window.scrollY > 500);
    });
    scrollTopBtn?.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
  </script>
</body>
</html>