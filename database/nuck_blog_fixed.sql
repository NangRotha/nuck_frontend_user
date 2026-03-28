-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2025 at 08:51 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nuck_blog` 
--

-- --------------------------------------------------------

--
-- Table structure for table `admins` 
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins` 
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- --------------------------------------------------------

--
-- Table structure for table `blogs` 
--

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `category` varchar(100) NOT NULL,
  `publish_date` date NOT NULL,
  `image_path` varchar(500) DEFAULT NULL,
  `image_path2` varchar(500) DEFAULT NULL,
  `image_path3` varchar(500) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `image_path4` varchar(255) DEFAULT NULL,
  `image_path5` varchar(255) DEFAULT NULL,
  `image_path6` varchar(255) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `status` enum('published','draft','archived') DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blogs` 
--

INSERT INTO `blogs` (`id`, `title`, `content`, `category`, `publish_date`, `image_path`, `image_path2`, `image_path3`, `slug`, `image_path4`, `image_path5`, `image_path6`, `author_id`) VALUES
(12, 'ពិធីចុះអនុស្សរណៈ នៃការយោគយល់គ្នា រវាងវិទ្យាស្ថានអភិវឌ្ឍសហគ្រិនភាពនិងបណ្តុះបណ្តាលធុរកិច្ច និងសហគ្រិនខ្មែរ', 'ពិធីចុះអនុស្សរណៈ នៃការយោគយល់គ្នា រវាងវិទ្យាស្ថានអភិវឌ្ឍសហគ្រិនភាពនិងបណ្តុះបណ្តាលធុរកិច្ច និងសហគ្រិនខ្មែរ\r\n………………..\r\nនៅព្រឹកថ្ងៃទី២២ ខែកក្កដា ឆ្នាំ២០២៥ នេះ ឯកឧត្តមអភិសន្តិបណ្ឌិត ស សុខា ឧបនាយករដ្ឋមន្ត្រី រដ្ឋមន្ត្រីក្រសួងមហាផ្ទៃ ឯកឧត្តម ជា សុមេធី រដ្ឋមន្ត្រីក្រសួងសង្គមកិច្ច អតីតយុទ្ធជន និងយុវនីតិសម្បទា និងឯកឧត្តម ហែម វណ្ណឌី រដ្ឋមន្ត្រីក្រសួងឧស្សាហកម្ម វិទ្យាសាស្ត្រ បច្ចេកវិទ្យា និងនវានុវត្តន៍ បានអញ្ជើញជាអធិបតីពិធីចុះហត្ថលេខាលើអនុស្សរណៈ នៃការយោគយល់គ្នា រវាងវិទ្យាស្ថានអភិវឌ្ឍសហគ្រិនភាពនិងបណ្តុះបណ្តាលធុរកិច្ច នៃសាកលវិទ្យាល័យជាតិ ជា ស៊ីម កំចាយមារ និងសហគ្រិនខ្មែរ នៅសាលប្រជុំសាកលវិទ្យាល័យជាតិ ជា ស៊ីម កំចាយមារ ស្ថិតនៅក្នុងភូមិថ្នល់កែង ឃុំស្មោងខាងជើង ស្រុកកំចាយមារ ខេត្តព្រៃវែង។', 'ព័ត៍មានសាលា', '2025-07-23', 'admin/uploads/img_693ce8d90c03b0.54921531.png', 'admin/uploads/img_693ce8d90d4b49.52162778.png', 'admin/uploads/img_693ce8d90dc826.27760144.png', 'memorandum-signing-ceremony-2025', 'admin/uploads/img_693cd0dc0b8234.52997383.png', 'admin/uploads/img_693cd0dc0bc749.06307837.png', 'admin/uploads/img_693cd0dc0bf9c0.88884374.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `nuck_blog` 
--

CREATE TABLE `nuck_blog` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `posted_date` date NOT NULL,
  `deadline_date` date DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `author_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nuck_blog` 
--

INSERT INTO `nuck_blog` (`id`, `title`, `content`, `posted_date`, `deadline_date`, `status`, `author_id`) VALUES
(1, 'ERASMUS+ HIGHER EDUCATION MOBILITY', 'The National University of Battambang (NUBB) has the great honor to announce...', '2024-12-10', '2024-12-31', 'active', 1);

-- --------------------------------------------------------

--
-- Table structure for table `students` 
--

CREATE TABLE `students` (
  `id` int(10) UNSIGNED NOT NULL,
  `photo_path` varchar(255) DEFAULT NULL COMMENT 'Path to student photo file',
  `name_khmer` varchar(150) NOT NULL COMMENT 'Name in Khmer',
  `name_latin` varchar(150) NOT NULL COMMENT 'Name in Latin',
  `gender` varchar(20) NOT NULL COMMENT 'Gender (ប្រុស/ស្រី)',
  `dob` date NOT NULL COMMENT 'Date of Birth',
  `phone` varchar(30) NOT NULL COMMENT 'Phone Number',
  `email` varchar(150) NOT NULL COMMENT 'Email Address',
  `birth_country` varchar(100) NOT NULL DEFAULT 'កម្ពុជា / Cambodia',
  `place_of_birth` varchar(100) NOT NULL COMMENT 'Place of Birth',
  `birth_commune` varchar(100) DEFAULT NULL,
  `birth_village` varchar(100) DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL COMMENT 'Current Occupation',
  `high_school_khmer` varchar(200) NOT NULL COMMENT 'High School Name in Khmer',
  `graduated_year` int(11) DEFAULT NULL COMMENT 'High School Graduation Year',
  `student_type` varchar(100) DEFAULT NULL COMMENT 'Student Type (សិស្សវិទ្យាសាស្ត្រ/សិស្សវិទ្យាសាស្ត្រសង្គម/សាមណសិស្ស/សិស្សបរទេស/សិស្សអាហារូបករណ៍)',
  `father_name_khmer` varchar(150) NOT NULL COMMENT 'Father Name in Khmer',
  `father_phone` varchar(30) NOT NULL COMMENT 'Father Phone Number',
  `mother_name_khmer` varchar(150) NOT NULL COMMENT 'Mother Name in Khmer',
  `mother_phone` varchar(30) NOT NULL COMMENT 'Mother Phone Number',
  `degree_level` varchar(50) NOT NULL COMMENT 'Degree Level (Associate/Bachelor/Master/Doctoral)',
  `faculty` varchar(200) NOT NULL COMMENT 'Faculty (មហាវិទ្យាល័យ)',
  `program` varchar(200) NOT NULL COMMENT 'Desired Program',
  `study_time` varchar(50) DEFAULT NULL COMMENT 'Study Time (Weekdays/Weekend)',
  `payment_status` varchar(20) NOT NULL DEFAULT 'not_paid' COMMENT 'Payment status: not_paid, pending, paid, verified',
  `payment_amount` decimal(10,2) DEFAULT NULL COMMENT 'Payment amount in USD',
  `payment_date` timestamp NULL DEFAULT NULL COMMENT 'Payment date',
  `payment_reference` varchar(100) DEFAULT NULL COMMENT 'Payment reference/transaction ID',
  `payment_proof_path` varchar(255) DEFAULT NULL COMMENT 'Path to payment receipt screenshot',
  `declaration` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Declaration checkbox',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='University Student Admission Records';

-- Clean sample data for students table (removed fake/test data)
-- INSERT INTO `students` (`id`, `photo_path`, `name_khmer`, `name_latin`, `gender`, `dob`, `phone`, `email`, `birth_country`, `place_of_birth`, `high_school_khmer`, `graduated_year`, `student_type`, `father_name_khmer`, `father_phone`, `mother_name_khmer`, `mother_phone`, `degree_level`, `faculty`, `program`, `study_time`, `payment_status`, `payment_amount`, `payment_date`, `payment_reference`, `payment_proof_path`, `declaration`) VALUES
-- (Sample data removed - please insert real student data)

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins` 
--
ALTER TABLE `admins` 
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `blogs` 
--
ALTER TABLE `blogs` 
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_author_id` (`author_id`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_publish_date` (`publish_date`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `nuck_blog` 
--
ALTER TABLE `nuck_blog` 
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_author_id` (`author_id`),
  ADD KEY `idx_posted_date` (`posted_date`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `students` 
--
ALTER TABLE `students` 
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_name_latin` (`name_latin`),
  ADD KEY `idx_program` (`program`),
  ADD KEY `idx_faculty` (`faculty`),
  ADD KEY `idx_degree_level` (`degree_level`),
  ADD KEY `idx_payment_status` (`payment_status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins` 
--
ALTER TABLE `admins` 
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `blogs` 
--
ALTER TABLE `blogs` 
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `nuck_blog` 
--
ALTER TABLE `nuck_blog` 
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `students` 
--
ALTER TABLE `students` 
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blogs`
--
ALTER TABLE `blogs`
  ADD CONSTRAINT `blogs_author_id_fk` FOREIGN KEY (`author_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `nuck_blog`
--
ALTER TABLE `nuck_blog`
  ADD CONSTRAINT `nuck_blog_author_id_fk` FOREIGN KEY (`author_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
