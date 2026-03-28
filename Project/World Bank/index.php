
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NUCK | National University of Cheasim kamchaymear</title>
  <link rel="shortcut icon" href="./../../images/logo_footer/nuck_logo.png" type="image/x-icon">
  <!-- Tailwind CSS Play CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.1/aos.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.1/aos.js"></script>
  <style>
    /* Dropdown animations */
    .dropdown {
      transform: scaleY(0);
      opacity: 0;
      transform-origin: top;
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
      display: block !important;
    }

    .dropdown.open {
      transform: scaleY(1);
      opacity: 1;
    }

    /* Mobile dropdown styling */
    @media (max-width: 767px) {
      #menu {
        max-height: calc(100vh - 4rem);
        overflow-y: auto;
      }

      .dropdown {
        background-color: rgba(29, 78, 216, 0.95) !important;
        margin: 0 1rem;
        width: calc(100% - 2rem);
        position: relative;
      }

      .dropdown li a {
        padding-left: 1.5rem;
        padding-right: 1.5rem;
        font-size: 0.875rem;
      }

      .md\\:hidden + .hidden {
        display: none !important;
      }
      
      #menu li > a,
      #menu li > button {
        padding-top: 1rem;
        padding-bottom: 1rem;
      }
    }
  </style>
</head>
<body class= "bg-white text-gray-900 dark:bg-gray-900 dark:text-white transition-colors duration-300">
  <header class="fixed left-0 top-0 z-50 w-full bg-blue-700 shadow-md dark:bg-gray-800">
    <div class="lg:px-16 px-4 flex flex-wrap items-center justify-between py-4">
      <!-- Logo -->
      <div class="flex-1 flex justify-between items-center">
        <a href="./" class="text-xl text-white font-bold">
          <img src="./../../images/logo/NUCK_Logo_Web.png" alt="Company Logo" class="h-12 w-auto" />
        </a>
      </div>

      <!-- Hamburger Toggle Button -->
      <label for="menu-toggle" class="pointer-cursor md:hidden block">
        <svg class="fill-current text-white" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
          <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path>
        </svg>
      </label>
      <input class="hidden" type="checkbox" id="menu-toggle" />

      <!-- Nav Menu -->
      <div class="hidden md:flex md:items-center md:w-auto w-full" id="menu">
        <nav class="w-full">
          <ul class="md:flex items-center justify-between text-base text-white pt-4 md:pt-0">
            <li><a class="md:p-4 py-3 px-0 block hover:text-blue-200" href="./">HOME</a></li>
            <li><a class="md:p-4 py-3 px-0 block hover:text-blue-200" href="./public/partner/">OUR PARTNERS</a></li>
            <li><a class="md:p-4 py-3 px-0 block hover:text-blue-200" href="./public/new&events/">NEWS & EVENTS</a></li>
            
            <!-- Academics Dropdown -->
            <li class="relative group">
              <button class="md:p-4 py-3 px-0 block hover:text-blue-200 flex items-center gap-1 academic-dropdown-trigger">
                ACADEMICS
                <svg class="h-3 w-3 fill-current transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                  <path d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z"/>
                </svg>
              </button>
              <ul id="academics-dropdown" class="dropdown bg-blue-700 dark:bg-gray-800 mt-2 rounded-md shadow-lg absolute left-0 z-10 min-w-[220px]">
                <li>
                  <a href="./public/Faculty_of_Science_and_Mathematics" class="block px-6 py-3 text-sm text-white hover:bg-blue-600 dark:hover:bg-gray-700 transition-colors">
                    Faculty of Science & Mathematics
                  </a>
                </li>
                <li>
                  <a href="./public/Faculty_of_Arts_Humanitites_and_Languages" class="block px-6 py-3 text-sm text-white hover:bg-blue-600 dark:hover:bg-gray-700 transition-colors">
                    Faculty of Arts, Humanities and Languages
                  </a>
                </li>
                <li>
                  <a href="./public/Faculty_of_Agriculture" class="block px-6 py-3 text-sm text-white hover:bg-blue-600 dark:hover:bg-gray-700 transition-colors">
                    Faculty of Agriculture
                  </a>
                </li>
                <li>
                  <a href="./public/Faculty_of_social_science" class="block px-6 py-3 text-sm text-white hover:bg-blue-600 dark:hover:bg-gray-700 transition-colors">
                    Faculty of Social Science
                  </a>
                </li>
                <li>
                  <a href="./public/Faculty_of_Management" class="block px-6 py-3 text-sm text-white hover:bg-blue-600 dark:hover:bg-gray-700 transition-colors">
                    Faculty of Management
                  </a>
                </li>
              </ul>
            </li>

            <!-- About Dropdown -->
            <li class="relative group">
              <button class="md:p-4 py-3 px-0 block hover:text-blue-200 flex items-center gap-1 academic-dropdown-trigger">
                ABOUT US
                <svg class="h-3 w-3 fill-current transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                  <path d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z"/>
                </svg>
              </button>
              <ul id="about-dropdown" class="dropdown bg-blue-700 dark:bg-gray-800 mt-2 rounded-md shadow-lg absolute left-0 z-10 min-w-[220px]">
                <li>
                  <a href="./public/about/" class="block px-6 py-3 text-sm text-white hover:bg-blue-600 dark:hover:bg-gray-700 transition-colors">
                    MESSAGE FROM RECTOR
                  </a>
                </li>
                <li>
                  <a href="./public/vision-and-mission/" class="block px-6 py-3 text-sm text-white hover:bg-blue-600 dark:hover:bg-gray-700 transition-colors">
                    Vision and Mission
                  </a>
                </li>
                <li>
                  <a href="./public/history_university/" class="block px-6 py-3 text-sm text-white hover:bg-blue-600 dark:hover:bg-gray-700 transition-colors">
                    University History
                  </a>
                </li>
              </ul>
              
            </li>

            
            <!-- Language Switcher -->
            <div class="khmer-text">
              <style>
                .khmer-text {
                    font-family: 'Khmer', sans-serif;
                }
                .animate-scale {
            transition: all 0.3s ease-in-out;
        }
        .animate-scale:hover {
            transform: scale(1.08);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .animate-scale:focus {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.5); /* Blue ring */
        }
        #language-toggle:hover {
    background-color: #3b82f6; /* Darker blue on hover */
}

#language-options {
    display: none; /* Initially hidden */
}

#language-options.show {
    display: block; /* Show when toggled */
}
              </style>
              
              <div class="relative inline-block w-35">
                <div id="language-toggle" 
                     class="bg-blue-600 dark:bg-gray-800 text-white px-4 py-2 rounded-md text-sm animate-scale focus:outline-none focus:ring-2 focus:ring-blue-400 dark:focus:ring-gray-600 cursor-pointer">
                    <span id="selected-language">English</span>
                    <span class="ml-2">
                        <img id="selected-flag" src="./images/flage/english.png" alt="English Flag" class="inline w-4 h-4">
                    </span>
                </div>
                <ul id="language-options" class="bg-blue-800 rounded-md text-sm animate-scale focus:outline-none focus:ring-2 focus:ring-blue-400 dark:focus:ring-gray-600 cursor-pointer absolute hidden bg-white dark:bg-blue-800 text-black dark:text-white rounded-md shadow-lg mt-1 w-full z-10">
                    <li class="flex items-center px-4 py-2 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700" data-value="en">
                        <a href="./" class="flex items-center w-full">
                            <img src="./images/flage/english.png" alt="English Flag" class="w-4 h-4 mr-2"> English
                        </a>
                    </li>
                    <li class="flex items-center px-4 py-2 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700" data-value="km">
                        <a href="./km/" class="flex items-center w-full">
                            <img src="./images/flage/cam.png" alt="Cambodia Flag" class="w-4 h-4 mr-2"> ភាសាខ្មែរ
                        </a>
                    </li>
                </ul>
            </div>
          <script>
            document.getElementById('language-toggle').addEventListener('click', function() {
    const options = document.getElementById('language-options');
    options.classList.toggle('show');
});

document.querySelectorAll('#language-options li').forEach(item => {
    item.addEventListener('click', function() {
        const selectedValue = this.getAttribute('data-value');
        const selectedText = this.innerText.trim();
        const selectedFlag = this.querySelector('img').src;

        document.getElementById('selected-language').innerText = selectedText;
        document.getElementById('selected-flag').src = selectedFlag;

        // Optionally, you can handle the value change here
        console.log('Selected language:', selectedValue);

        // Close the dropdown
        document.getElementById('language-options').classList.remove('show');
            });
        });

        // Close the dropdown if clicked outside
        window.addEventListener('click', function(event) {
            if (!event.target.closest('.relative')) {
                document.getElementById('language-options').classList.remove('show');
            }
        });
          </script>
          
        
            <!-- Mobile Theme/Search -->
            <li class="md:hidden flex items-center gap-4 mt-4">
              <div class="hidden md:flex items-center gap-4 ml-4">
                <!-- Add a 2px margin to create space -->
                <div class="mr-2"></div> <!-- This creates a 2px space -->
                
                <!-- Theme Toggle Button -->
                <button id="theme-toggle" class="p-2 rounded-full bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 transition-colors text-gray-900 dark:text-white">
                  <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24">
                    <path id="moon-icon" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    <path id="sun-icon" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                  </svg>
                </button>
            </li>
          </ul>
        </nav>

        <!-- Desktop Theme/Search -->
        <div class="hidden md:flex items-center gap-4 ml-4">
          <button id="theme-toggle" class=" p-2 rounded-full hover: transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <!-- <path id="moon-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/> -->
              <path id="sun-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
          </button>
        </div>
      </div>
    </div>
  </header>
<!-- Hero Section -->
<section class="hero-gradient text-white py-8 md:py-16 mt-20">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto text-center">
            <h1 class="text-2xl md:text-4xl lg:text-5xl font-bold mb-4 md:mb-6 text-black dark:text-white">Second Higher Education Improvement Project</h1>
            <p class="text-base md:text-xl lg:text-2xl mb-6 md:mb-8 opacity-90 max-w-4xl mx-auto text-black dark:text-white">Improving quality, relevance, and research in STEM and agriculture programs while strengthening institutional governance.</p>
            <div class="flex flex-wrap justify-center gap-2 md:gap-4">
                <div class="bg-white text-blue-900 font-semibold py-2 px-4 md:py-2 md:px-6 rounded-full shadow-lg text-sm md:text-base">
                    <i class="fas fa-bullseye mr-2"></i> Quality & Relevance
                </div>
                <div class="bg-white text-blue-900 font-semibold py-2 px-4 md:py-2 md:px-6 rounded-full shadow-lg text-sm md:text-base">
                    <i class="fas fa-flask mr-2"></i> Research Commercialization
                </div>
                <div class="bg-white text-blue-900 font-semibold py-2 px-4 md:py-2 md:px-6 rounded-full shadow-lg text-sm md:text-base">
                    <i class="fas fa-university mr-2"></i> Institutional Governance
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Overview Section -->
<section id="overview" class="py-12 md:py-16 bg-white">
    <div class="container mx-auto px-4 max-w-6xl">
        <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-center text-blue-900 mb-8 md:mb-12">Project Overview</h2>
        <div class="grid lg:grid-cols-2 gap-8 md:gap-12">
            <div>
                <h3 class="text-xl md:text-2xl font-semibold text-gray-800 mb-4 md:mb-6">Project Aims</h3>
                <p class="text-gray-700 mb-4 md:mb-6 leading-relaxed">
                    The HEIP2 aims to improve the quality, relevance, and research of academic programs, mainly in STEM and agriculture, and to strengthen the institutional governance of target higher education institutions.
                </p>
                <p class="text-gray-700 mb-6 md:mb-8 leading-relaxed">
                    Additionally, the project provides an immediate and effective response in case of an Eligible Crisis or Emergency through its Contingent Emergency Response Component (CERC).
                </p>
                <div class="bg-blue-50 p-4 md:p-6 rounded-lg border border-blue-100">
                    <h4 class="font-bold text-blue-900 mb-2 md:mb-3 text-lg md:text-xl">Key Focus Areas</h4>
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                            <span>STEM and agriculture program enhancement</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                            <span>Research commercialization and innovation</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                            <span>Institutional governance strengthening</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                            <span>Emergency preparedness and response</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div>
                <h3 class="text-xl md:text-2xl font-semibold text-gray-800 mb-4 md:mb-6">Project Structure</h3>
                <div class="space-y-4 md:space-y-6">
                    <div class="flex items-start p-4 bg-blue-50 rounded-lg border border-blue-100">
                        <div class="bg-blue-900 text-white p-3 rounded-lg mr-4">
                            <i class="fas fa-cogs text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-blue-900 text-lg md:text-xl mb-1">Three Main Components</h4>
                            <p class="text-gray-700">The project is organized into three comprehensive components addressing different aspects of higher education improvement.</p>
                        </div>
                    </div>
                    <div class="flex items-start p-4 bg-green-50 rounded-lg border border-green-100">
                        <div class="bg-green-700 text-white p-3 rounded-lg mr-4">
                            <i class="fas fa-chart-line text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-green-900 text-lg md:text-xl mb-1">Measurable Outcomes</h4>
                            <p class="text-gray-700">Clear PDO indicators track progress in academic standards, research commercialization, and institutional governance.</p>
                        </div>
                    </div>
                    <div class="flex items-start p-4 bg-purple-50 rounded-lg border border-purple-100">
                        <div class="bg-purple-700 text-white p-3 rounded-lg mr-4">
                            <i class="fas fa-hands-helping text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-purple-900 text-lg md:text-xl mb-1">Targeted Implementation</h4>
                            <p class="text-gray-700">Specific institutions like NUCK implement focused sub-components based on their needs and capacities.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Components Section -->
<section id="components" class="py-12 md:py-16 bg-gray-50">
    <div class="container mx-auto px-4 max-w-6xl">
        <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-center text-blue-900 mb-4 md:mb-8">Project Components</h2>
        <p class="text-gray-600 text-center max-w-3xl mx-auto mb-8 md:mb-12">HEIP2 is organized into three main components with specific sub-components addressing different aspects of higher education improvement.</p>
        
        <!-- Component Tabs -->
        <div class="flex flex-wrap justify-center mb-6 md:mb-8 gap-2">
            <button class="component-tab active-tab py-2 px-3 md:py-3 md:px-6 rounded-t-lg font-medium text-sm md:text-base" data-component="1">
                Component 1
            </button>
            <button class="component-tab bg-gray-200 text-gray-800 py-2 px-3 md:py-3 md:px-6 rounded-t-lg font-medium text-sm md:text-base" data-component="2">
                Component 2
            </button>
            <button class="component-tab bg-gray-200 text-gray-800 py-2 px-3 md:py-3 md:px-6 rounded-t-lg font-medium text-sm md:text-base" data-component="3">
                Component 3
            </button>
        </div>
        
        <!-- Component Content -->
        <div id="component-content" class="bg-white rounded-lg shadow-lg p-4 md:p-6 lg:p-8">
            <!-- Component 1 Content (Default) -->
            <div id="component-1" class="component-details">
                <div class="mb-6 md:mb-8">
                    <h3 class="text-xl md:text-2xl font-bold text-blue-900 mb-2">Component 1: Improving the quality and relevance of academic programs and research</h3>
                    <p class="text-gray-700">This component focuses on enhancing academic programs and research outputs in target institutions, with particular emphasis on STEM and agriculture fields.</p>
                </div>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                    <div class="component-card bg-blue-50 border border-blue-100 rounded-lg p-4 md:p-6">
                        <div class="text-blue-900 mb-3">
                            <i class="fas fa-book-open text-2xl md:text-3xl"></i>
                        </div>
                        <h4 class="font-bold text-lg md:text-xl text-blue-900 mb-2 md:mb-3">Subcomponent 1.1</h4>
                        <p class="text-gray-700 mb-3 md:mb-4 text-sm md:text-base">Improving quality and relevance of academic programs through curriculum development, faculty training, and infrastructure enhancement.</p>
                        <div class="text-blue-900 font-medium text-sm md:text-base">Implemented by NUCK</div>
                    </div>
                    
                    <div class="component-card bg-blue-50 border border-blue-100 rounded-lg p-4 md:p-6">
                        <div class="text-blue-900 mb-3">
                            <i class="fas fa-flask text-2xl md:text-3xl"></i>
                        </div>
                        <h4 class="font-bold text-lg md:text-xl text-blue-900 mb-2 md:mb-3">Subcomponent 1.2</h4>
                        <p class="text-gray-700 mb-3 md:mb-4 text-sm md:text-base">Improving quality and relevance of research by strengthening research methodologies, facilities, and industry-academia linkages.</p>
                    </div>
                    
                    <div class="component-card bg-blue-50 border border-blue-100 rounded-lg p-4 md:p-6 md:col-span-2 lg:col-span-1">
                        <div class="text-blue-900 mb-3">
                            <i class="fas fa-university text-2xl md:text-3xl"></i>
                        </div>
                        <h4 class="font-bold text-lg md:text-xl text-blue-900 mb-2 md:mb-3">Subcomponent 1.3</h4>
                        <p class="text-gray-700 mb-3 md:mb-4 text-sm md:text-base">Strengthening institutional governance through policy development, leadership training, and administrative system improvements.</p>
                        <div class="text-blue-900 font-medium text-sm md:text-base">Implemented by NUCK</div>
                    </div>
                </div>
            </div>
            
            <!-- Component 2 Content -->
            <div id="component-2" class="component-details hidden">
                <div class="mb-6 md:mb-8">
                    <h3 class="text-xl md:text-2xl font-bold text-blue-900 mb-2">Component 2: Strengthening higher education sectoral governance and research management capacity</h3>
                    <p class="text-gray-700">This component focuses on system-level improvements to enhance governance, research management, and project evaluation across the higher education sector.</p>
                </div>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                    <div class="component-card bg-green-50 border border-green-100 rounded-lg p-4 md:p-6">
                        <div class="text-green-900 mb-3">
                            <i class="fas fa-chart-network text-2xl md:text-3xl"></i>
                        </div>
                        <h4 class="font-bold text-lg md:text-xl text-green-900 mb-2 md:mb-3">Subcomponent 2.1</h4>
                        <p class="text-gray-700 mb-3 md:mb-4 text-sm md:text-base">Strengthening higher education sectoral governance through policy frameworks, regulatory systems, and quality assurance mechanisms.</p>
                    </div>
                    
                    <div class="component-card bg-green-50 border border-green-100 rounded-lg p-4 md:p-6">
                        <div class="text-green-900 mb-3">
                            <i class="fas fa-microscope text-2xl md:text-3xl"></i>
                        </div>
                        <h4 class="font-bold text-lg md:text-xl text-green-900 mb-2 md:mb-3">Subcomponent 2.2</h4>
                        <p class="text-gray-700 mb-3 md:mb-4 text-sm md:text-base">Building research management capacity through training, infrastructure development, and establishment of research centers of excellence.</p>
                    </div>
                    
                    <div class="component-card bg-green-50 border border-green-100 rounded-lg p-4 md:p-6 md:col-span-2 lg:col-span-1">
                        <div class="text-green-900 mb-3">
                            <i class="fas fa-clipboard-check text-2xl md:text-3xl"></i>
                        </div>
                        <h4 class="font-bold text-lg md:text-xl text-green-900 mb-2 md:mb-3">Subcomponent 2.3</h4>
                        <p class="text-gray-700 mb-3 md:mb-4 text-sm md:text-base">Strengthening project management and evaluation through monitoring systems, impact assessment, and performance tracking.</p>
                    </div>
                </div>
            </div>
            
            <!-- Component 3 Content -->
            <div id="component-3" class="component-details hidden">
                <div class="mb-6 md:mb-8">
                    <h3 class="text-xl md:text-2xl font-bold text-blue-900 mb-2">Component 3: Contingent Emergency Response Component (CERC)</h3>
                    <p class="text-gray-700">This component provides a mechanism for immediate and effective response in case of an Eligible Crisis or Emergency, ensuring continuity of higher education services.</p>
                </div>
                
                <div class="bg-red-50 border border-red-100 rounded-lg p-6 md:p-8">
                    <div class="flex flex-col md:flex-row items-center">
                        <div class="md:w-1/4 mb-6 md:mb-0 flex justify-center">
                            <div class="bg-red-700 text-white p-5 md:p-6 rounded-full">
                                <i class="fas fa-first-aid text-4xl md:text-5xl"></i>
                            </div>
                        </div>
                        <div class="md:w-3/4 md:pl-6 lg:pl-8">
                            <h4 class="font-bold text-xl md:text-2xl text-red-900 mb-3 md:mb-4">Emergency Response Framework</h4>
                            <p class="text-gray-700 mb-4 md:mb-6">The CERC component allows for rapid reallocation of project funds to address eligible crises such as natural disasters, public health emergencies, or other disruptive events affecting higher education institutions.</p>
                            <ul class="space-y-2">
                                <li class="flex items-start">
                                    <i class="fas fa-bolt text-red-600 mt-1 mr-3"></i>
                                    <span>Rapid response mechanism for crisis situations</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-shield-alt text-red-600 mt-1 mr-3"></i>
                                    <span>Continuity planning for academic programs</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-handshake text-red-600 mt-1 mr-3"></i>
                                    <span>Coordination with national emergency systems</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- PDO Indicators Section -->
<section id="indicators" class="py-12 md:py-16 bg-white">
    <div class="container mx-auto px-4 max-w-6xl">
        <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-center text-blue-900 mb-8 md:mb-12">Project Development Objective (PDO) Indicators</h2>
        <p class="text-gray-600 text-center max-w-3xl mx-auto mb-8 md:mb-12">The success of HEIP2 is measured through three key indicators that track progress toward the project's development objectives.</p>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            <div class="indicator-card bg-white border border-gray-200 rounded-lg p-6 md:p-8 shadow-md">
                <div class="flex items-center mb-4 md:mb-6">
                    <div class="bg-blue-100 text-blue-900 p-3 md:p-4 rounded-lg mr-4">
                        <i class="fas fa-star text-xl md:text-2xl"></i>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-blue-900">Indicator 1</h3>
                </div>
                <h4 class="font-semibold text-gray-800 mb-3 md:mb-4 text-lg md:text-xl">Academic Programs Meeting Standards</h4>
                <p class="text-gray-700 mb-4 md:mb-6">Number of academic programs meeting national or international quality standards in STEM and agriculture fields.</p>
                <div class="pt-4 md:pt-6 border-t border-gray-200">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600 text-sm md:text-base">Target Progress</span>
                        <span class="font-bold text-blue-900 text-lg">75%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 md:h-3 mt-2">
                        <div class="bg-blue-600 h-2.5 md:h-3 rounded-full" style="width: 75%"></div>
                    </div>
                </div>
            </div>
            
            <div class="indicator-card bg-white border border-gray-200 rounded-lg p-6 md:p-8 shadow-md">
                <div class="flex items-center mb-4 md:mb-6">
                    <div class="bg-green-100 text-green-900 p-3 md:p-4 rounded-lg mr-4">
                        <i class="fas fa-industry text-xl md:text-2xl"></i>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-green-900">Indicator 2</h3>
                </div>
                <h4 class="font-semibold text-gray-800 mb-3 md:mb-4 text-lg md:text-xl">Research Commercialization</h4>
                <p class="text-gray-700 mb-4 md:mb-6">Number of research products commercialized through partnerships with industry and private sector entities.</p>
                <div class="pt-4 md:pt-6 border-t border-gray-200">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600 text-sm md:text-base">Target Progress</span>
                        <span class="font-bold text-green-900 text-lg">60%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 md:h-3 mt-2">
                        <div class="bg-green-600 h-2.5 md:h-3 rounded-full" style="width: 60%"></div>
                    </div>
                </div>
            </div>
            
            <div class="indicator-card bg-white border border-gray-200 rounded-lg p-6 md:p-8 shadow-md md:col-span-2 lg:col-span-1">
                <div class="flex items-center mb-4 md:mb-6">
                    <div class="bg-purple-100 text-purple-900 p-3 md:p-4 rounded-lg mr-4">
                        <i class="fas fa-chart-bar text-xl md:text-2xl"></i>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-purple-900">Indicator 3</h3>
                </div>
                <h4 class="font-semibold text-gray-800 mb-3 md:mb-4 text-lg md:text-xl">Institutional Governance Standards</h4>
                <p class="text-gray-700 mb-4 md:mb-6">Number of HEIs meeting institutional governance standards for transparency, accountability, and management effectiveness.</p>
                <div class="pt-4 md:pt-6 border-t border-gray-200">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600 text-sm md:text-base">Target Progress</span>
                        <span class="font-bold text-purple-900 text-lg">80%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 md:h-3 mt-2">
                        <div class="bg-purple-600 h-2.5 md:h-3 rounded-full" style="width: 80%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- NUCK Implementation Section -->
<section id="nuck" class="py-12 md:py-16 bg-gray-50">
    <div class="container mx-auto px-4 max-w-6xl">
        <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-center text-blue-900 mb-4 md:mb-8">NUCK Implementation Focus</h2>
        <p class="text-gray-600 text-center max-w-3xl mx-auto mb-8 md:mb-12">Exclusively, with the support of the HEIP2 project, NUCK implements two specific sub-components:</p>
        
        <div class="grid md:grid-cols-2 gap-6 md:gap-8 mb-8 md:mb-12">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-blue-900 text-white p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="bg-white text-blue-900 p-3 rounded-lg mr-4">
                            <i class="fas fa-book-open text-xl md:text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl md:text-2xl font-bold">Subcomponent 1.1</h3>
                            <p class="text-blue-200">Improving quality and relevance of academic programs</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 md:p-6">
                    <h4 class="font-bold text-lg md:text-xl text-gray-800 mb-3 md:mb-4">NUCK Implementation Focus Areas</h4>
                    <ul class="space-y-2 md:space-y-3 mb-4 md:mb-6">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span>Curriculum review and updating for STEM programs</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span>Faculty development and capacity building</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span>Laboratory and teaching facility upgrades</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span>Integration of industry-relevant skills</span>
                        </li>
                    </ul>
                    <div class="bg-blue-50 p-3 md:p-4 rounded-lg">
                        <p class="text-blue-900 font-medium text-sm md:text-base">
                            <i class="fas fa-bullseye mr-2"></i> 
                            Target: Increase the number of accredited academic programs by 40%
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-purple-900 text-white p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="bg-white text-purple-900 p-3 rounded-lg mr-4">
                            <i class="fas fa-university text-xl md:text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl md:text-2xl font-bold">Subcomponent 1.3</h3>
                            <p class="text-purple-200">Strengthening institutional governance</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 md:p-6">
                    <h4 class="font-bold text-lg md:text-xl text-gray-800 mb-3 md:mb-4">NUCK Implementation Focus Areas</h4>
                    <ul class="space-y-2 md:space-y-3 mb-4 md:mb-6">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span>Governance policy and framework development</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span>Leadership and management training programs</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span>Financial management system improvements</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span>Transparency and accountability mechanisms</span>
                        </li>
                    </ul>
                    <div class="bg-purple-50 p-3 md:p-4 rounded-lg">
                        <p class="text-purple-900 font-medium text-sm md:text-base">
                            <i class="fas fa-bullseye mr-2"></i> 
                            Target: Achieve full compliance with national governance standards
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-lg p-6 md:p-8">
            <h3 class="text-xl md:text-2xl lg:text-3xl font-bold text-center text-blue-900 mb-6 md:mb-8">Expected Outcomes from NUCK Implementation</h3>
            <div class="grid md:grid-cols-3 gap-4 md:gap-6">
                <div class="text-center p-4 md:p-6">
                    <div class="bg-blue-100 text-blue-900 p-3 md:p-4 rounded-full inline-block mb-3 md:mb-4">
                        <i class="fas fa-user-graduate text-2xl md:text-3xl"></i>
                    </div>
                    <h4 class="font-bold text-gray-800 mb-2 text-lg md:text-xl">Enhanced Graduate Employability</h4>
                    <p class="text-gray-700 text-sm md:text-base">Improved curriculum will better align with labor market needs, increasing graduate employment rates.</p>
                </div>
                <div class="text-center p-4 md:p-6">
                    <div class="bg-green-100 text-green-900 p-3 md:p-4 rounded-full inline-block mb-3 md:mb-4">
                        <i class="fas fa-award text-2xl md:text-3xl"></i>
                    </div>
                    <h4 class="font-bold text-gray-800 mb-2 text-lg md:text-xl">Program Accreditation</h4>
                    <p class="text-gray-700 text-sm md:text-base">More academic programs will meet national and international accreditation standards.</p>
                </div>
                <div class="text-center p-4 md:p-6">
                    <div class="bg-purple-100 text-purple-900 p-3 md:p-4 rounded-full inline-block mb-3 md:mb-4">
                        <i class="fas fa-balance-scale text-2xl md:text-3xl"></i>
                    </div>
                    <h4 class="font-bold text-gray-800 mb-2 text-lg md:text-xl">Governance Excellence</h4>
                    <p class="text-gray-700 text-sm md:text-base">Strengthened institutional governance will improve decision-making and resource management.</p>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Footer Section -->
<footer class="px-4 pt-16 mx-auto sm:max-w-xl md:max-w-full lg:max-w-screen-xl md:px-24 lg:px-8">
  <div class="grid gap-10 row-gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-4">
      <!-- Logo and Address Section -->
      <div class="sm:col-span-2">
          <a href="./" aria-label="Go home" title="National University of Cheasim Kamchaymear" class="inline-flex items-center">
              <svg class="w-8 text-purple-600" viewBox="0 0 24 24" stroke-linejoin="round" stroke-width="2" stroke-linecap="round" stroke-miterlimit="10" stroke="currentColor" fill="none">
                  <image xlink:href="./../../images/logo_footer/nuck_logo.png" width="24" height="24" />
              </svg>
              <span class="ml-2 text-sm font-bold tracking-wide text-gray-800 uppercase dark:text-white">
                  National University of Cheasim Kamchaymear
              </span>
          </a>
          <div class="mt-6 lg:max-w-sm">
              <p class="mt-4 text-sm text-gray-800 dark:text-gray-200">Address</p>
              <p class="text-sm text-gray-800 dark:text-gray-200">
                  National Road 8, Thnal Keng Village, Smoang Cheung Commune, Kamchaymear District, Prey Veng Province, CAMBODIA.
              </p>
          </div>
      </div>

      <!-- Contacts Section -->
      <div class="space-y-2 text-sm">
          <p class="text-base font-bold tracking-wide text-gray-900 dark:text-white">Contacts</p>
          <div class="flex">
              <p class="mr-1 text-gray-800 dark:text-gray-200">Phone:</p>
              <a href="tel:097 828 1168" aria-label="Our phone" title="Our phone" class="transition-colors duration-300 text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-300">
                  097 828 1168
              </a>
          </div>
          <div class="flex">
              <p class="mr-1 text-gray-800 dark:text-gray-200">Email:</p>
              <a href="mailto:info@nuck.edu.kh" aria-label="Our email" title="Our email" class="transition-colors duration-300 text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-300">
                  info@nuck.edu.kh
              </a>
          </div>
      </div>

      <!-- Social Section -->
      <div>
          <span class="text-base font-bold tracking-wide text-gray-900 dark:text-white">Social</span>
          <div class="flex items-center mt-1 space-x-3">
              <a href="https://t.me/officialstudentassociationofcsuk" class="text-gray-500 transition-colors duration-300 hover:text-purple-600 dark:text-gray-300 dark:hover:text-purple-400 hover-scale">
                  <svg viewBox="0 0 24 24" fill="currentColor" class="h-5">
                      <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.16.16-.295.295-.605.295l.213-3.05 5.56-5.02c.24-.213-.054-.334-.373-.12l-6.87 4.33-2.96-.92c-.64-.203-.658-.64.135-.954l11.57-4.46c.538-.196 1.006.128.832.94z" />
                  </svg>
              </a>
              <a href="https://youtube.com/@nuck6666?si=LkbegKPL8Ek2yMbq" class="text-gray-500 transition-colors duration-300 hover:text-purple-600 dark:text-gray-300 dark:hover:text-purple-400 hover-scale">
                  <svg viewBox="0 0 24 24" fill="currentColor" class="h-5">
                      <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                  </svg>
              </a>
              <a href="https://www.instagram.com/national_university_of_cheasim?igsh=MXB3cmgxb3FzNXU1Nw==" class="text-gray-500 transition-colors duration-300 hover:text-purple-600 dark:text-gray-300 dark:hover:text-purple-400 hover-scale">
                  <svg viewBox="0 0 30 30" fill="currentColor" class="h-6">
                      <circle cx="15" cy="15" r="4" />
                      <path d="M19.999,3h-10C6.14,3,3,6.141,3,10.001v10C3,23.86,6.141,27,10.001,27h10C23.86,27,27,23.859,27,19.999v-10   C27,6.14,23.859,3,19.999,3z M15,21c-3.309,0-6-2.691-6-6s2.691-6,6-6s6,2.691,6,6S18.309,21,15,21z M22,9c-0.552,0-1-0.448-1-1   c0-0.552,0.448-1,1-1s1,0.448,1,1C23,8.552,22.552,9,22,9z" />
                  </svg>
              </a>
              <a href="https://web.facebook.com/@NationalUniversityofCheasimkamchaymear" class="text-gray-500 transition-colors duration-300 hover:text-purple-600 dark:text-gray-300 dark:hover:text-purple-400 hover-scale">
                  <svg viewBox="0 0 24 24" fill="currentColor" class="h-5">
                      <path d="M22,0H2C0.895,0,0,0.895,0,2v20c0,1.105,0.895,2,2,2h11v-9h-3v-4h3V8.413c0-3.1,1.893-4.788,4.659-4.788 c1.325,0,2.463,0.099,2.795,0.143v3.24l-1.918,0.001c-1.504,0-1.795,0.715-1.795,1.763V11h4.44l-1,4h-3.44v9H22c1.105,0,2-0.895,2-2 V2C24,0.895,23.105,0,22,0z" />
                  </svg>
              </a>
          </div>
      </div>
  </div>

  <!-- Footer Bottom Section -->
  <div class="flex flex-col-reverse justify-between pt-5 pb-10 border-t lg:flex-row dark:border-gray-700">
      <p class="text-sm text-gray-600 dark:text-gray-300">
          © Copyright 2025 National University of CheaSim Kamchaymear. All rights reserved.
      </p>
      <ul class="flex flex-col mb-3 space-y-2 lg:mb-0 sm:space-y-0 sm:space-x-5 sm:flex-row">
          <li>
              <a href="./" class="text-sm text-gray-600 transition-colors duration-300 hover:text-purple-600 dark:text-gray-300 dark:hover:text-purple-400">
                  F.A.Q
              </a>
          </li>
          <li>
              <a href="./" class="text-sm text-gray-600 transition-colors duration-300 hover:text-purple-600 dark:text-gray-300 dark:hover:text-purple-400">
                  Privacy Policy
              </a>
          </li>
          <li>
              <a href="./" class="text-sm text-gray-600 transition-colors duration-300 hover:text-purple-600 dark:text-gray-300 dark:hover:text-purple-400">
                  Terms &amp; Conditions
              </a>
          </li>
      </ul>
  </div>
</footer>
  <!-- Scroll-to-Top Button -->
  <style>
    /* Hide the button by default */
    #scroll-top {
      display: none;
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 1000;
      transition: opacity 0.3s ease-in-out;
    }
  </style>
  <a
    href="#"
    id="scroll-top"
    class="fixed bottom-8 right-8 p-3 bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700 transition-colors"
  >
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
    </svg>
  </a>

  <!-- JavaScript to Show/Hide Button and Smooth Scroll -->
  <script>
    const scrollTopButton = document.getElementById('scroll-top');

    // Show the button when the user scrolls down 200px
    window.addEventListener('scroll', () => {
      if (window.scrollY > 200) {
        scrollTopButton.style.display = 'flex';
      } else {
        scrollTopButton.style.display = 'none';
      }
    });

    // Smooth scroll to top when the button is clicked
    scrollTopButton.addEventListener('click', (e) => {
      e.preventDefault();
      window.scrollTo({
        top: 0,
        behavior: 'smooth',
      });
    });
  </script>
  <script src="./../../main.js"></script>
</body>
</html>