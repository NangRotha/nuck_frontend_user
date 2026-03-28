-- phpMyAdmin SQL Dump
-- MySQL 8.x / XAMPP compatible

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

SET NAMES utf8mb4;

-- ----------------------------------------------------------
-- Database: `nuck_blog`
-- ----------------------------------------------------------

CREATE DATABASE IF NOT EXISTS `nuck_blog`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_0900_ai_ci;

USE `nuck_blog`;

-- ----------------------------------------------------------
-- Table structure for table `admins`
-- ----------------------------------------------------------

DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------------------------------------
-- Dumping data for table `admins`
-- ----------------------------------------------------------

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin123');

-- ----------------------------------------------------------
-- Table structure for table `blogs`
-- ----------------------------------------------------------

DROP TABLE IF EXISTS `blogs`;
CREATE TABLE `blogs` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `category` VARCHAR(50) NOT NULL,
  `publish_date` DATE NOT NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------------------------------------
-- Dumping data for table `blogs`
-- ----------------------------------------------------------

INSERT INTO `blogs`
(`id`, `title`, `content`, `category`, `publish_date`, `image_path`, `slug`) VALUES

(3,
'តនឹងបច្ចា​ឥត​អាវុធ',
'ឈ្លោះ​គ្នា​ក្នុង​គ្រួសារ ដូច​ស្រាត​កាយា​បង្ហាញ​ញាតិ ឈ្លោះគ្នាក្នុង​សង្គមជាតិ ដូច​លាត​កំណប់​បង្ហាញ​ចោរ ។',
'ព័ត៌មានសាលា',
'2025-05-16',
'https://plus.unsplash.com/premium_photo-1688572454849-4348982edf7d',
'tanung-bacha'),

(4,
'កមាសស',
'សចបសដហបចហសសសសសសសសស',
'ព័ត៌មានសាលា',
'2025-05-16',
'https://plus.unsplash.com/premium_photo-1689568126014-06fea9d5d341',
'kamas'),

(7,
'សកម្មភាពការចុះផ្សព្វផ្សាយអាហារូបករណ៍រដ្ឋឆ្នាំសិក្សា ២០២៥-២០២៦',
'សកម្មភាពការចុះផ្សព្វផ្សាយអាហារូបករណ៍រដ្ឋឆ្នាំសិក្សា ២០២៥-២០២៦ និងតម្រង់ទិសមុខជំនាញសិក្សា',
'អាហារូបករណ៍',
'2025-05-14',
'admin/uploads/1746807482_nuck.jpg',
'scholarship-2025-2026'),

(8,
'Real Madrid vs Dortmund',
'UEFA Champions League ៧ គ្រាប់ Vinícius Hat-Trick',
'ព័ត៍មាន',
'2025-05-09',
'https://cdn.sabay.com/media/sabay-news.png',
'real-madrid-vs-dortmund'),

(9,
'ភាពតានតឹង រវាង ឥណ្ឌា និង ប៉ាគីស្ថាន',
'ភាពតានតឹងអាចធ្វើឱ្យតម្លៃអង្ករឡើងថ្លៃ',
'ព័ត៍មានសាលា',
'2025-05-24',
'admin/uploads/1746805482_management.png',
'india-pakistan-rice'),

(10,
'ប្រទេសទាំង ២ ជាអ្នកនាំចេញធំ',
'ភាពតានតឹងរវាងឥណ្ឌា និងប៉ាគីស្ថានអាចប៉ះពាល់ដល់ទីផ្សារអង្ករ',
'ព័ត៍មានសាលា',
'2025-05-17',
'admin/uploads/1746809572_OIP.jfif',
'rice-export-tension');

COMMIT;