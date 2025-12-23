-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 24, 2025 at 04:01 PM
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
-- Database: `sis`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `faculty_code` varchar(10) NOT NULL,
  `abbreviation` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`faculty_code`, `abbreviation`) VALUES
('BUS', 'Business'),
('CS', 'CompSci'),
('ENG', 'Engineering'),
('LAW', 'Law'),
('MED', 'Medicine');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hesk_tickets`
--

CREATE TABLE `hesk_tickets` (
  `id` int(11) NOT NULL,
  `trackid` varchar(20) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `priority` tinyint(4) NOT NULL,
  `custom1` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hesk_tickets`
--

INSERT INTO `hesk_tickets` (`id`, `trackid`, `subject`, `status`, `priority`, `custom1`) VALUES
(1, 'TCK-1001', 'Password Reset Request', 0, 1, 1),
(2, 'TCK-1002', 'Can’t access portal', 1, 2, 2),
(3, 'TCK-1003', 'Exam schedule issue', 1, 4, 3),
(4, 'TCK-1004', 'Transcript request', 2, 3, 4),
(5, 'TCK-1005', 'Account suspended', 0, 1, 5),
(6, 'TCK-1006', 'Library card renewal', 1, 2, 1),
(7, 'TCK-1007', 'Grade not updated', 0, 4, 2),
(8, 'TCK-1008', 'Change email address', 2, 3, 3),
(9, 'TCK-1009', 'Internship approval', 1, 2, 4),
(10, 'TCK-1010', 'Course registration', 0, 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `major`
--

CREATE TABLE `major` (
  `major_code` varchar(10) NOT NULL,
  `faculty_code` varchar(10) NOT NULL,
  `abbreviation` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `major`
--

INSERT INTO `major` (`major_code`, `faculty_code`, `abbreviation`) VALUES
('ACC', 'BUS', 'Accounting'),
('AI', 'CS', 'Artificial Intel'),
('CE', 'ENG', 'Civil Eng'),
('CRIM', 'LAW', 'Criminology'),
('SE', 'CS', 'Software Eng');

-- --------------------------------------------------------

--
-- Table structure for table `majors`
--

CREATE TABLE `majors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `major` varchar(255) NOT NULL,
  `major_code` varchar(255) DEFAULT NULL,
  `faculty` varchar(255) DEFAULT NULL,
  `faculty_code` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `majors`
--

INSERT INTO `majors` (`id`, `major`, `major_code`, `faculty`, `faculty_code`, `description`, `created_at`, `updated_at`) VALUES
(1, 'SCI', '01', 'SCI', '01', NULL, NULL, NULL),
(2, 'ENG', '02', 'ENG', '02', NULL, NULL, NULL),
(3, 'BIO', '03', 'ENG', '01', NULL, NULL, NULL),
(4, 'MECH', '04', 'ENG', '02', NULL, NULL, NULL),
(5, 'ELECT', '04', 'ENG', '01', NULL, NULL, NULL),
(6, 'DENG', '06', 'ENG', '02', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_08_21_092824_create_students_table', 1),
(5, '2025_08_21_092833_create_parents_table', 1),
(6, '2025_08_21_092841_create_staff_table', 1),
(7, '2025_08_21_092848_create_voice_calls_table', 1),
(8, '2025_08_21_092856_create_tickets_table', 1),
(9, '2025_08_21_160330_add_ticket_number_to_voice_calls_table', 1),
(13, '2025_08_23_100243_add_role_to_users_table', 2),
(14, '2025_08_25_221945_create_majors_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `parents`
--

CREATE TABLE `parents` (
  `parent_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `relation_to_student` varchar(100) DEFAULT NULL,
  `stud_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parents`
--

INSERT INTO `parents` (`parent_id`, `full_name`, `email`, `phone`, `relation_to_student`, `stud_id`, `created_at`) VALUES
(1, 'Mohammed Hassan', 'mhassan@example.com', '091111111', 'Father', 1, '2025-08-23 16:17:01'),
(2, 'Amina Omar', 'aomar@example.com', '092222222', 'Mother', 2, '2025-08-23 16:17:01');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `id` int(11) NOT NULL,
  `stud_id` int(11) NOT NULL,
  `batch` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  `CGPA` decimal(3,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`id`, `stud_id`, `batch`, `semester`, `status`, `CGPA`) VALUES
(1, 1, 2022, 1, 'Passed', 2.80),
(2, 1, 2022, 2, 'Passed', 3.00),
(3, 1, 2022, 3, 'Passed', 3.10),
(4, 1, 2022, 4, 'Passed', 3.20),
(5, 1, 2022, 5, 'Passed', 3.30),
(6, 1, 2022, 6, 'Passed', 3.40),
(7, 1, 2022, 7, 'Probation', 2.50),
(11, 2, 2023, 1, 'Passed', 3.10),
(12, 2, 2023, 2, 'Passed', 3.20),
(13, 2, 2023, 3, 'Passed', 3.30),
(14, 2, 2023, 4, 'Passed', 3.40),
(15, 2, 2023, 5, 'Probation', 2.60),
(21, 3, 2021, 1, 'Passed', 2.90),
(22, 3, 2021, 2, 'Passed', 3.00),
(23, 3, 2021, 3, 'Pass', 3.10),
(26, 3, 2021, 6, 'Passed', 3.00),
(27, 3, 2021, 7, 'Passed', 3.10),
(28, 3, 2021, 8, 'Passed', 3.20),
(29, 3, 2021, 9, 'Passed', 3.30),
(30, 3, 2021, 10, 'Graduated', 3.40),
(31, 4, 2024, 1, 'Passed', 3.00),
(32, 4, 2024, 2, 'Passed', 3.20),
(33, 4, 2024, 3, 'Passed', 3.30),
(34, 4, 2024, 4, 'Passed', 3.40),
(35, 4, 2024, 5, 'In Progress', 3.50),
(36, 5, 2020, 1, 'Passed', 2.70),
(37, 5, 2020, 2, 'Passed', 2.90),
(38, 5, 2020, 3, 'Probation', 2.40),
(39, 5, 2020, 4, 'Passed', 3.00),
(40, 5, 2020, 5, 'In Progress', 3.20);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('aT3R3QTzi8nD82ZUCnzwF1YruGSTIFcLxQnelEzT', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicVVVVmVlcUlNNkhHUndybnVZdU5UZ1MyV2FIZEo1MmtaUHJqSkdTSiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zdHVkZW50Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1758722242);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_code` int(11) NOT NULL,
  `staff_id` varchar(20) DEFAULT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_code`, `staff_id`, `full_name`, `email`, `phone`, `department`, `created_at`) VALUES
(1, 'STF001', 'Dr. Ahmed Ali', 'ahmed.ali@voiceline.com', '090000111', 'Computer Science', '2025-08-23 16:17:01'),
(2, 'STF002', 'Eng. Mariam Khalid', 'mariam.khalid@voiceline.com', '090000222', 'Electrical Engineering', '2025-08-23 16:17:01');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `stud_id` int(11) NOT NULL,
  `stud_name` varchar(100) NOT NULL,
  `stud_surname` varchar(100) DEFAULT NULL,
  `familyname` varchar(100) DEFAULT NULL,
  `status_code` varchar(10) DEFAULT NULL,
  `curr_sem` int(11) DEFAULT NULL,
  `faculty_code` varchar(10) DEFAULT NULL,
  `major_code` varchar(10) DEFAULT NULL,
  `batch` varchar(10) DEFAULT NULL,
  `stud_gpa` decimal(3,2) DEFAULT NULL,
  `stud_cgpa` decimal(3,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`stud_id`, `stud_name`, `stud_surname`, `familyname`, `status_code`, `curr_sem`, `faculty_code`, `major_code`, `batch`, `stud_gpa`, `stud_cgpa`) VALUES
(1, 'Ali', 'Hassan', 'Mohammed', 'ACT', 4, 'ENG', 'BIO', '2020', 3.60, 3.40),
(2, 'Sara', 'Omar', 'Abdalla', 'ACT', 2, 'SCI', 'SCI', '2023', 3.20, 3.10),
(2020, 'Mohamed', NULL, NULL, NULL, NULL, 'ENG', 'BIO', '2018', NULL, NULL),
(2023, 'Mazin', NULL, NULL, NULL, NULL, 'IT', 'DIT', '2022', NULL, NULL),
(22222, 'anas', NULL, NULL, NULL, NULL, 'IT', 'IT', '2917', NULL, NULL),
(201822001, 'Aya', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student_profile_common`
--

CREATE TABLE `student_profile_common` (
  `stud_id` int(11) NOT NULL,
  `faculty_code` varchar(10) NOT NULL,
  `major_code` varchar(10) NOT NULL,
  `batch` int(11) DEFAULT NULL,
  `curr_sem` int(11) DEFAULT NULL,
  `status_code` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_profile_common`
--

INSERT INTO `student_profile_common` (`stud_id`, `faculty_code`, `major_code`, `batch`, `curr_sem`, `status_code`) VALUES
(1, 'CS', 'SE', 2022, 5, 1),
(2, 'CS', 'AI', 2023, 3, 1),
(3, 'ENG', 'CE', 2021, 7, 0),
(4, 'BUS', 'ACC', 2024, 1, 1),
(5, 'LAW', 'CRIM', 2020, 9, 0);

-- --------------------------------------------------------

--
-- Table structure for table `student_profile_e`
--

CREATE TABLE `student_profile_e` (
  `stud_id` int(11) NOT NULL,
  `stud_name` varchar(100) NOT NULL,
  `stud_surname` varchar(100) DEFAULT NULL,
  `familyname` varchar(100) DEFAULT NULL,
  `lastName` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_profile_e`
--

INSERT INTO `student_profile_e` (`stud_id`, `stud_name`, `stud_surname`, `familyname`, `lastName`) VALUES
(1, 'Ali', 'Hassan', 'Hussein', 'Ali'),
(2, 'Sara', 'Mohamed', 'Youssef', 'Sara'),
(3, 'Omar', 'Mahmoud', 'Ibrahim', 'Omar'),
(4, 'Laila', 'Khaled', 'Mostafa', 'Laila'),
(5, 'Huda', 'Samir', 'Fahmy', 'Huda');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `ticket_serial_no` int(11) NOT NULL,
  `voice_call_id` int(11) NOT NULL,
  `ticket_number` varchar(50) DEFAULT NULL,
  `ticket_id` varchar(15) NOT NULL,
  `ticket_category` varchar(50) DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `opened_type` enum('student','parent','staff') NOT NULL DEFAULT 'student',
  `opened_by_whois` int(11) NOT NULL,
  `Ticket_status` enum('open','in_progress','on_hold','resolved','closed') NOT NULL DEFAULT 'open',
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ticket_url` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`ticket_serial_no`, `voice_call_id`, `ticket_number`, `ticket_id`, `ticket_category`, `issue_date`, `opened_type`, `opened_by_whois`, `Ticket_status`, `priority`, `created_at`, `updated_at`, `ticket_url`) VALUES
(1, 1, '1', 'TK001', 'technical', '2025-08-23', 'student', 1, 'open', 'high', '2025-08-23 16:17:01', '2025-08-31 15:50:42', 'http://voiceline.test/tickets/1'),
(2, 2, '2', 'TK002', 'administrative', '2025-08-23', 'staff', 1, 'in_progress', 'medium', '2025-08-23 16:17:01', '2025-09-01 22:31:12', 'http://voiceline.test/tickets/2'),
(4, 4, '4', '4', '4', '2025-08-27', 'student', 2, 'open', 'medium', '2025-08-31 16:22:49', '2025-08-31 16:22:49', 'teetett'),
(6, 1, '3', '232', '232323', '2025-08-31', 'student', 1, 'in_progress', 'medium', '2025-08-31 16:23:52', '2025-09-01 22:31:19', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@voiceline.com', NULL, '$2y$12$FkxzPku97eZnQnnS4/RSROlYN9fKT5URocquxKGZGN3NPmDkK8iEm', 'admin', 'u8zSlGii5el0aIC7A06Bta7mfkn9rxkAjwBDhEOYW0V5alTCazX0frP3lv3E', '2025-08-23 16:17:00', '2025-08-25 15:04:26'),
(2, 'supervisor', 'supervisor@voiceline.com', NULL, '$2y$12$HYiyS8K4SfpAa33/kp4T3uiNgURYye9/mhUc/DSN2YKqprnef4Wjy', 'supervisor', NULL, '2025-08-23 16:17:00', '2025-08-23 16:17:00'),
(3, 'Support Agent /Zoom', 'agent@voiceline.com', NULL, '$2y$12$FkxzPku97eZnQnnS4/RSROlYN9fKT5URocquxKGZGN3NPmDkK8iEm', 'user', NULL, '2025-08-23 16:17:01', '2025-08-25 15:08:48');

-- --------------------------------------------------------

--
-- Table structure for table `voice_calls`
--

CREATE TABLE `voice_calls` (
  `call_id` int(11) NOT NULL,
  `ticket_number` varchar(50) DEFAULT NULL,
  `customer_type` enum('student','parent','staff','general') NOT NULL,
  `stud_id` int(11) DEFAULT NULL,
  `staff_ID` int(11) DEFAULT NULL,
  `category` int(11) NOT NULL,
  `issue` text NOT NULL,
  `Solution_Note` text DEFAULT NULL,
  `Found_Status` varchar(25) DEFAULT NULL,
  `Final_Status` varchar(25) DEFAULT NULL,
  `priority` varchar(25) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `parent_name` varchar(50) DEFAULT NULL,
  `parent_phone` varchar(30) DEFAULT NULL,
  `handled_by_user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `voice_calls`
--

INSERT INTO `voice_calls` (`call_id`, `ticket_number`, `customer_type`, `stud_id`, `staff_ID`, `category`, `issue`, `Solution_Note`, `Found_Status`, `Final_Status`, `priority`, `parent_id`, `parent_name`, `parent_phone`, `handled_by_user_id`, `created_at`, `updated_at`) VALUES
(1, '1', 'parent', NULL, NULL, 42, 'Withdrawal status', 'solved, and satisfied', 'open', '3', 'high', NULL, NULL, '20201515151', 1, '2025-09-06 09:49:43', '2025-09-06 09:49:43'),
(2, '1', 'parent', NULL, NULL, 42, 'Withdrawal status', 'solved, and satisfied', 'open', '3', 'high', NULL, NULL, '20201515151', 1, '2025-09-06 09:50:34', '2025-09-06 09:50:34'),
(3, '1', 'student', NULL, NULL, 1, 'transfer', 'done', 'open', '1', 'high', NULL, NULL, NULL, 1, '2025-09-06 09:55:19', '2025-09-06 09:55:19'),
(4, '1', 'parent', NULL, NULL, 1, 'transfer', 'done', 'open', '1', 'high', NULL, NULL, '20201515151', 1, '2025-09-06 09:56:06', '2025-09-06 09:56:06'),
(5, '1', 'parent', NULL, NULL, 1, 'aaaa', 'aaaaa', 'open', '1', 'high', NULL, 'Sara Omar Abdalla', '20201515151', 1, '2025-09-06 09:56:54', '2025-09-06 09:56:54'),
(6, '1', 'general', NULL, NULL, 42, 'fdggfdsavsavasv', 'fsgdsfgsaf\r\nsfdgfdsgfds\r\nsfdgsdfg', 'open', '3', 'high', NULL, 'Abdalla Omer', '09995000', 1, '2025-09-06 10:00:43', '2025-09-06 10:00:43'),
(7, '1', 'parent', NULL, NULL, 42, 'ggggg', '5665656', 'open', NULL, 'high', NULL, 'Ali Hassan Mohammed', '20201515151', 1, '2025-09-08 08:24:10', '2025-09-08 08:24:10'),
(8, '1', 'student', NULL, NULL, 1, 'ggggg', '5665656', 'open', NULL, 'high', NULL, 'aaa', NULL, 1, '2025-09-08 08:26:42', '2025-09-08 08:26:42'),
(9, '1', 'student', NULL, NULL, 1, 'ggggg', '5665656', 'open', NULL, 'high', NULL, 'aaa', NULL, 1, '2025-09-08 08:29:16', '2025-09-08 08:29:16'),
(10, '1', 'student', NULL, NULL, 1, 'ggggg', '5665656', 'open', NULL, 'high', NULL, 'aaa', NULL, 1, '2025-09-08 08:31:01', '2025-09-08 08:31:01'),
(11, '1', 'general', NULL, NULL, 1, 'ggggg', '5665656', 'open', '1', 'high', NULL, 'aaa', '20201515151', 1, '2025-09-08 08:33:25', '2025-09-08 08:33:25'),
(12, '1', 'student', NULL, NULL, 1, 'ggggg', '5665656', 'open', NULL, 'high', NULL, 'aaa', NULL, 1, '2025-09-08 08:34:00', '2025-09-08 08:34:00'),
(13, '1', 'student', NULL, NULL, 1, 'ggggg', '5665656', 'open', NULL, 'high', NULL, 'aaa', NULL, 1, '2025-09-08 08:35:29', '2025-09-08 08:35:29'),
(14, '1', 'student', NULL, NULL, 1, 'ggggg', '5665656', 'open', '1', 'high', NULL, 'aaa', NULL, 1, '2025-09-08 08:40:01', '2025-09-08 08:40:01'),
(15, '1', 'student', NULL, NULL, 1, 'ggggg', '5665656', 'open', '1', 'high', NULL, 'aaa', NULL, 1, '2025-09-08 08:42:54', '2025-09-08 08:42:54'),
(16, '1', 'student', NULL, NULL, 1, 'lkjlk;', '.,m/.,m', 'open', '1', 'high', NULL, 'Ali Hassan Mohammed', NULL, 1, '2025-09-08 08:43:29', '2025-09-08 08:43:29'),
(17, '1', 'student', NULL, NULL, 1, 'SZ', '5665656', 'open', '1', 'high', NULL, 'MURTADA MOHAMMED HAMAD AHMED', NULL, 1, '2025-09-11 13:43:06', '2025-09-11 13:43:06'),
(18, 'TCK-1001', 'student', NULL, NULL, 42, 'fdfdadsf', 'TCK-1001', NULL, '2', 'TCK-1001', NULL, 'Ali Hassan Hussein Ali', NULL, 1, '2025-09-23 16:42:02', '2025-09-23 16:42:02'),
(19, 'TCK-1001', 'student', NULL, NULL, 1, 'xx', 'xx', NULL, '1', 'Normal', NULL, 'Ali Hassan Hussein Ali', NULL, 1, '2025-09-23 20:05:12', '2025-09-23 20:05:12'),
(20, 'TCK-1001', 'parent', NULL, NULL, 1, 'disscount', 'Solution Note', NULL, '3', 'Normal', NULL, 'Ali Hassan Hussein Ali', '20201515151', 1, '2025-09-23 20:06:29', '2025-09-23 20:06:29'),
(21, 'Q9H-B3P-BQV4', 'student', NULL, NULL, 1, 'تخفيض', 'تمت المعالجة', NULL, '1', 'High', NULL, 'Yousif Osama Abd Elsamie Yassin', NULL, 1, '2025-09-24 10:53:25', '2025-09-24 10:53:25'),
(22, 'HSZ-R1A-GN9R', 'student', NULL, NULL, 1, 'Issue', 'TCK-1001', NULL, '2', 'High', NULL, 'Yousif Osama Abd Elsamie Yassin', NULL, 1, '2025-09-24 10:55:18', '2025-09-24 10:55:18'),
(23, 'N2R-NJ3-95JV', 'parent', NULL, NULL, 1, 'discount', 'done', NULL, '1', 'High', NULL, 'Yousif Osama Abd Elsamie Yassin', '0999', 1, '2025-09-24 10:56:17', '2025-09-24 10:56:17'),
(24, 'Q9H-B3P-BQV4', 'parent', NULL, NULL, 1, 'تقسيط', 'تم', NULL, '2', 'High', NULL, 'ANAS Yousif Osama Abd Elsamie Yassin', '20201515151', 1, '2025-09-24 10:57:22', '2025-09-24 10:57:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`faculty_code`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `hesk_tickets`
--
ALTER TABLE `hesk_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `major`
--
ALTER TABLE `major`
  ADD PRIMARY KEY (`major_code`,`faculty_code`),
  ADD KEY `fk_major_faculty` (`faculty_code`);

--
-- Indexes for table `majors`
--
ALTER TABLE `majors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parents`
--
ALTER TABLE `parents`
  ADD PRIMARY KEY (`parent_id`),
  ADD KEY `parents_stud_id_index` (`stud_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_results_student` (`stud_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_code`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`stud_id`);

--
-- Indexes for table `student_profile_common`
--
ALTER TABLE `student_profile_common`
  ADD PRIMARY KEY (`stud_id`);

--
-- Indexes for table `student_profile_e`
--
ALTER TABLE `student_profile_e`
  ADD PRIMARY KEY (`stud_id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`ticket_serial_no`),
  ADD UNIQUE KEY `tickets_ticket_number_unique` (`ticket_number`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `voice_calls`
--
ALTER TABLE `voice_calls`
  ADD PRIMARY KEY (`call_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hesk_tickets`
--
ALTER TABLE `hesk_tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `majors`
--
ALTER TABLE `majors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `parents`
--
ALTER TABLE `parents`
  MODIFY `parent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_code` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `stud_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201822002;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `ticket_serial_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `voice_calls`
--
ALTER TABLE `voice_calls`
  MODIFY `call_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `major`
--
ALTER TABLE `major`
  ADD CONSTRAINT `fk_major_faculty` FOREIGN KEY (`faculty_code`) REFERENCES `faculty` (`faculty_code`);

--
-- Constraints for table `parents`
--
ALTER TABLE `parents`
  ADD CONSTRAINT `parents_stud_id_foreign` FOREIGN KEY (`stud_id`) REFERENCES `students` (`stud_id`);

--
-- Constraints for table `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `fk_results_student` FOREIGN KEY (`stud_id`) REFERENCES `student_profile_e` (`stud_id`);

--
-- Constraints for table `student_profile_common`
--
ALTER TABLE `student_profile_common`
  ADD CONSTRAINT `fk_spc_student` FOREIGN KEY (`stud_id`) REFERENCES `student_profile_e` (`stud_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
