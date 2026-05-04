-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 10, 2026 at 04:04 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `capstone`
--

-- --------------------------------------------------------

--
-- Table structure for table `ai_logs`
--

CREATE TABLE `ai_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_message` text NOT NULL,
  `ai_response` text NOT NULL,
  `interaction_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `sentiment_flag` varchar(20) DEFAULT 'NEUTRAL'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ai_logs`
--

INSERT INTO `ai_logs` (`log_id`, `user_id`, `user_message`, `ai_response`, `interaction_date`, `sentiment_flag`) VALUES
(1, 2, 'hey am sad', 'Oh, my dear, I hear you. It sounds like you\'re feeling a lot of sadness right now. Please know that it\'s okay to feel that way, and I\'m here to listen without judgment.\n\nWould you like to share what\'s making you sad, or would you prefer to just talk about anything else that\'s on your mind? There\'s no pressure at all, but I\'m here for you.', '2026-03-10 10:39:13', 'NEUTRAL'),
(2, 2, 'my boyfried has been talking down on me and this morning he slapped me', 'I am so very sorry to hear that this has happened to you. My heart goes out to you for what you\'ve experienced. No one should ever have to go through being talked down to, and it is absolutely never okay for anyone to slap you. What you\'ve described is a serious matter, and it\'s important to remember that this is not your fault.\n\nYour safety is the most important thing right now.\n\n*   **If you feel you are in immediate danger or need urgent help, please don\'t hesitate to press the SOS button.** It\'s there for you.\n\nThere are resources in Ghana specifically designed to help people in your situation:\n\n1.  **DOVVSU (Domestic Violence and Victim Support Unit):** This unit of the Ghana Police Service is specifically trained to handle cases of domestic violence, provide protection, and offer support. They can help you understand your legal options and ensure your safety. You can find their offices at most police stations, or I can help you look up a contact number for the one nearest to you if you tell me your location.\n2.  **Medical Assistance:** Even if you don\'t feel seriously injured, it might be a good idea to get a medical check-up. A doctor can ensure you\'re okay and document what happened, which can be important for any future steps you might choose to take.\n3.  **Legal Help:** DOVVSU can guide you on legal steps, but there are also legal aid services available in Ghana that can provide advice and representation if you decide to pursue legal action.\n\nPlease remember that you are not alone, and there are people who want to help you through this.\n\nWhat do you feel you need most right now? Are you safe where you are?', '2026-03-10 10:40:00', 'NEUTRAL'),
(3, 2, 'my girlfriend is abusive to me lately, do you think she is seeing another man', 'I hear you saying that your girlfriend has been abusive towards you lately, and that must be incredibly difficult and confusing to experience. It\'s understandable to look for reasons when someone\'s behaviour changes, and to feel hurt and confused by it.\n\nHowever, what\'s most important right now is that you are experiencing abuse, and that is never okay. No one deserves to be treated abusively. Your feelings and your safety are what matter most.\n\nThere is support available for you in Ghana. The Domestic Violence and Victim Support Unit (DOVVSU) of the Ghana Police Service is there to help survivors of domestic abuse, regardless of gender. They can offer guidance, protection, and support.\n\nWould you like me to provide you with their contact information, or perhaps talk more about what you\'re going through? Please remember, your safety and well-being are paramount.', '2026-03-10 10:41:36', 'NEUTRAL'),
(4, 2, 'my boyfried has been talking down on me and this morning he slapped me', 'I\'m so sorry to hear this is happening to you. No one should ever have to experience being talked down to or, especially, physically harmed. What you\'re describing is not okay, and it\'s important to remember that this is not your fault.\n\nYour safety is the most important thing right now. In Ghana, the **Domestic Violence and Victim Support Unit (DOVVSU)** of the Ghana Police Service is a crucial resource for survivors of domestic violence. They can provide immediate protection, support, and guide you through reporting the incident.\n\nI also strongly recommend seeking medical attention, even if you don\'t think you\'re seriously hurt. A medical professional can check for any injuries and document what happened, which can be important for your health and if you decide to take further action. You can visit any public hospital or clinic in Ghana.\n\nPlease know that if you feel you are in immediate danger, you can use the SOS button on this platform to connect with urgent help.\n\nYou are not alone, and there is help available for you.', '2026-03-10 10:52:22', 'NEUTRAL'),
(5, 2, 'my boyfried has been talking down on me and this morning he slapped me', 'Oh, my dear, I am so incredibly sorry to hear that this has happened to you. It sounds like a deeply painful and frightening experience, and no one ever deserves to be spoken down to or, especially, to be slapped. What you\'re going through is not okay, and your feelings are completely valid.\n\nYour safety and well-being are the most important things right now.\n\nGiven what you\'ve shared about him slapping you this morning, I want to make sure you know about some very important resources here in Ghana that can help:\n\n1.  **DOVVSU (Domestic Violence and Victim Support Unit):** This is a specialized unit within the Ghana Police Service that deals specifically with domestic violence cases. They can offer protection, help you report the incident, and guide you through the process. You can find their offices at police stations, or we can help you find a contact number if you\'d like.\n\n2.  **Medical Assistance:** If you have any pain or injury from the slap, please consider seeking medical attention. It\'s important to get any physical injuries checked out, and a medical report can also be helpful if you decide to take further action. You can go to any hospital or clinic.\n\n3.  **Legal Help:** If you wish to pursue legal action, DOVVSU can assist you, and there are also legal aid services and lawyers who specialize in domestic violence cases.\n\nPlease, tell me, are you safe right now? Is there anything specific you feel you need help with first? Remember, you don\'t have to go through this alone.\n\nIf you feel you are in immediate danger right now, please use the SOS button.', '2026-03-10 10:52:44', 'NEUTRAL'),
(6, 2, 'my girlfriend threatened to kill me with food poison when she cooks for me next, how can i report htis', 'I am so sorry to hear that you are going through such a frightening experience. It sounds incredibly distressing to have your girlfriend threaten you in this way, especially with something so personal and dangerous. Please know that you are not alone, and your safety is the most important thing right now.\n\nThis is a very serious threat, and it\'s crucial to take it seriously. In situations like this, where there is a direct threat to your life, it is considered an emergency.\n\n**Given the immediate danger you might be in, I strongly recommend you press the SOS button right now.** This will connect you with immediate help.\n\nAdditionally, I want to guide you to the appropriate resources in Ghana:\n\n*   **Domestic Violence and Victim Support Unit (DOVVSU) of the Ghana Police Service:** This unit is specifically set up to help individuals experiencing domestic violence, threats, and abuse. They can provide protection and investigate such threats. You can report this incident to them.\n    *   You can find DOVVSU offices at police stations across Ghana.\n    *   Their national helpline is **18555** (toll-free on all networks).\n*   **Police Emergency Number:** If you feel immediate danger and cannot reach DOVVSU, you can also call the general police emergency number: **112**.\n\nPlease prioritize your safety above all else. Do not hesitate to reach out for help. We are here to support you.', '2026-03-10 10:53:27', 'NEUTRAL');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `victim_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('Pending','Confirmed','Completed','Cancelled') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `victim_id`, `service_id`, `appointment_date`, `appointment_time`, `notes`, `status`, `created_at`) VALUES
(1, 4, 33, '2026-03-18', '11:00:00', 'ok testing', 'Cancelled', '2026-03-09 20:11:55');

-- --------------------------------------------------------

--
-- Table structure for table `awareness`
--

CREATE TABLE `awareness` (
  `awareness_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `category` enum('Article','Video','Law','Event') DEFAULT 'Article',
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `awareness`
--

INSERT INTO `awareness` (`awareness_id`, `title`, `content`, `category`, `image_url`, `created_at`) VALUES
(1, 'Understanding Domestic Violence Act', 'The Domestic Violence Act, 2007 (Act 732) in Ghana provides legal protection against physical, sexual, and psychological abuse. It allows victims to apply for Protection Orders through the courts.', 'Law', NULL, '2026-02-22 23:44:56'),
(3, 'Reporting to DOVVSU', 'The Domestic Violence and Victims Support Unit (DOVVSU) of the Ghana Police Service is dedicated to handling GBV cases. You can visit any police station to file a report even without a medical form first.', 'Article', NULL, '2026-02-22 23:44:56'),
(4, 'Emotional Recovery Steps', 'Healing takes time. Focus on self-care, join a support group, and remember that the abuse was not your fault. Professional counseling is available through the Department of Social Welfare.', 'Article', NULL, '2026-02-22 23:44:56');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `brand_id` int(11) NOT NULL,
  `brand_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`brand_id`, `brand_name`) VALUES
(1, 'FIDA Ghana'),
(2, 'DOVVSU'),
(3, 'The Ark Foundation'),
(4, 'Korle-Bu Teaching Hospital'),
(5, 'African Women Development Fund'),
(6, 'Ghana Police Service');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `cat_id` int(11) NOT NULL,
  `cat_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`cat_id`, `cat_name`) VALUES
(1, 'Legal Advocacy'),
(2, 'Medical Support'),
(3, 'Counseling & Therapy'),
(4, 'Emergency Shelter'),
(5, 'Police & Security'),
(6, 'Financial Empowerment');

-- --------------------------------------------------------

--
-- Table structure for table `chat_groups`
--

CREATE TABLE `chat_groups` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `icon` varchar(255) DEFAULT 'bi-people'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_groups`
--

INSERT INTO `chat_groups` (`group_id`, `group_name`, `description`, `created_at`, `icon`) VALUES
(1, 'Healing Hearts', 'A safe space for survivors of domestic violence to share their journey and support one another.', '2026-02-22 22:46:38', 'bi-heart-pulse-fill'),
(2, 'Cyber Safety Squad', 'Discussing ways to stay safe online and dealing with digital harassment/stalking.', '2026-02-22 22:46:38', 'bi-shield-lock'),
(3, 'Empowerment Hub', 'Focusing on rebuilding confidence and finding legal or professional resources.', '2026-02-22 22:46:38', 'bi-lightning-charge-fill'),
(5, 'Women&#039;s Verbal Abuse Group', 'for women specifically', '2026-02-23 00:37:11', 'bi-people'),
(6, 'Men&#039;s Verbal Abuse Group', 'A group for men who have experienced verbal abuse to share tips.', '2026-02-23 08:36:42', 'bi-people'),
(7, 'Men&#039;s Verbal Abuse Group', 'A space for men that has faced verbal abuse.', '2026-02-24 10:27:37', 'bi-people');

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `msg_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `victim_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`msg_id`, `group_id`, `victim_id`, `message`, `created_at`) VALUES
(1, 1, 2, 'hello', '2026-02-22 23:38:12'),
(2, 1, 2, 'Am listening', '2026-02-22 23:38:20'),
(3, 2, 2, 'hmmm', '2026-02-22 23:38:30'),
(4, 2, 2, 'Cyber safety, just testing', '2026-02-22 23:38:45'),
(5, 3, 2, 'lolzz just thinking', '2026-02-22 23:39:07'),
(7, 1, 2, 'information', '2026-02-22 23:56:56'),
(8, 2, 2, 'hello', '2026-02-24 09:26:36'),
(9, 2, 2, 'hey', '2026-02-24 09:26:45'),
(10, 6, 2, 'heyy', '2026-02-24 09:26:58'),
(11, 2, 4, 'Am here', '2026-02-24 10:26:41'),
(12, 2, 2, 'I am here too', '2026-03-09 16:18:39'),
(13, 2, 2, 'How is everything', '2026-03-09 16:18:46'),
(14, 1, 2, 'hey', '2026-03-09 17:51:17');

-- --------------------------------------------------------

--
-- Table structure for table `group_requests`
--

CREATE TABLE `group_requests` (
  `request_id` int(11) NOT NULL,
  `victim_id` int(11) NOT NULL,
  `suggested_name` varchar(255) NOT NULL,
  `reason_description` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `group_requests`
--

INSERT INTO `group_requests` (`request_id`, `victim_id`, `suggested_name`, `reason_description`, `status`, `created_at`) VALUES
(1, 2, 'Economic Violence Victims', 'A group for current victims of economic violence to meet with survivors and share survival tips.', 'rejected', '2026-02-22 23:41:14'),
(2, 2, 'Economic Violence Victims', 'For women empowerment', 'rejected', '2026-02-22 23:49:33'),
(3, 2, 'Men&#039;s Verbal Abuse Group', 'A group for men who have experienced verbal abuse to share tips.', 'approved', '2026-02-22 23:51:53'),
(4, 2, 'Women&#039;s Verbal Abuse Group', 'for women specifically', 'approved', '2026-02-22 23:54:32'),
(5, 2, 'Economic Violence Victims', 'For abuse at work', 'pending', '2026-02-24 09:41:11'),
(6, 4, 'Men&#039;s Verbal Abuse Group', 'A space for men that has faced verbal abuse.', 'approved', '2026-02-24 10:24:01');

-- --------------------------------------------------------

--
-- Table structure for table `incidents`
--

CREATE TABLE `incidents` (
  `incident_id` int(11) NOT NULL,
  `victim_id` int(11) DEFAULT NULL,
  `lat` decimal(10,8) NOT NULL,
  `lon` decimal(11,8) NOT NULL,
  `incident_type` varchar(50) DEFAULT 'SOS_SIGNAL',
  `status` varchar(20) DEFAULT 'ACTIVE',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `closed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `incidents`
--

INSERT INTO `incidents` (`incident_id`, `victim_id`, `lat`, `lon`, `incident_type`, `status`, `created_at`, `closed_at`) VALUES
(1, 3, 5.75882157, -0.22064944, 'SOS_SIGNAL', 'CLOSED', '2026-02-16 12:16:48', NULL),
(2, 2, 5.75943349, -0.21996539, 'SOS_SIGNAL', 'CLOSED', '2026-02-16 22:01:51', NULL),
(3, 2, 5.75938603, -0.21997848, 'SOS_SIGNAL', 'CLOSED', '2026-02-16 22:09:12', NULL),
(4, 2, 5.75938743, -0.21993753, 'SOS_SIGNAL', 'CLOSED', '2026-02-16 22:15:36', NULL),
(5, 2, 5.75946692, -0.21996711, 'SOS_SIGNAL', 'CLOSED', '2026-02-16 22:36:59', NULL),
(6, 3, 5.75879733, -0.22068721, 'SOS_SIGNAL', 'CLOSED', '2026-02-17 00:13:57', NULL),
(7, 2, 5.75879555, -0.22068329, 'SOS_SIGNAL', 'CLOSED', '2026-02-17 00:16:06', NULL),
(8, 2, 5.75888684, -0.21980950, 'SOS_SIGNAL', 'CLOSED', '2026-02-17 10:30:16', NULL),
(9, 3, 5.75993203, -0.21964885, 'SOS_SIGNAL', 'CLOSED', '2026-02-21 16:20:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL,
  `victim_id` int(11) DEFAULT NULL,
  `incident_type` varchar(100) NOT NULL,
  `incident_date` date NOT NULL,
  `location` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `is_anonymous` tinyint(1) DEFAULT 0,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`report_id`, `victim_id`, `incident_type`, `incident_date`, `location`, `description`, `is_anonymous`, `status`, `created_at`) VALUES
(1, 2, 'Physical Violence', '2026-02-04', 'Kumasi', 'Nothing', 0, 'Pending', '2026-02-21 21:51:40'),
(2, 2, 'Emotional Abuse', '2026-02-12', 'Kumasi', 'now now now testing anonymous', 1, 'Pending', '2026-02-21 21:52:25'),
(3, 2, 'Cyber Stalking', '2026-02-19', 'Accra', 'Ok am coming', 1, 'Pending', '2026-02-21 22:15:32'),
(4, 2, 'Cyber Stalking', '2026-02-19', 'Accra', 'Ok am coming', 1, 'Pending', '2026-02-21 22:25:12'),
(5, 2, 'Sexual Harassment', '2026-02-05', 'Accra', 'Testing the pop up', 1, 'Pending', '2026-02-21 22:28:37'),
(6, 2, 'Other', '2026-02-03', 'Aflao', 'I was checking for the pop up', 0, 'Pending', '2026-02-21 22:45:31'),
(7, 2, 'Other', '2026-02-03', 'Aflao', 'Waiting', 1, 'Pending', '2026-02-21 22:52:30'),
(8, 2, 'Other', '2026-02-03', 'Aflao', 'Waiting', 1, 'Pending', '2026-02-21 22:52:44'),
(9, 2, 'Other', '2026-02-04', 'Aflao', 'getting', 1, 'Resolved', '2026-02-21 22:55:42'),
(10, 2, 'Emotional Abuse', '2026-02-05', 'Aflao', 'hello', 1, 'Resolved', '2026-02-22 22:32:49'),
(11, 2, 'Emotional Abuse', '2026-02-19', 'Aflao', 'ghgj', 0, 'Pending', '2026-02-22 23:52:57'),
(12, 2, 'Sexual Harassment', '2026-02-04', 'Accra', 'yyujbknlm.,bbbbbbbbbbbbbbbbbbb', 0, 'Pending', '2026-02-24 09:14:15'),
(13, 4, 'Emotional Abuse', '2026-02-06', 'Lome', 'Emotional abuse by my partner', 1, 'Pending', '2026-02-24 10:21:42');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `service_cat` int(11) NOT NULL,
  `service_brand` int(11) NOT NULL,
  `service_title` varchar(200) NOT NULL,
  `service_price` double NOT NULL DEFAULT 0,
  `service_desc` varchar(500) DEFAULT NULL,
  `service_image` varchar(100) DEFAULT NULL,
  `service_keywords` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `service_cat`, `service_brand`, `service_title`, `service_price`, `service_desc`, `service_image`, `service_keywords`) VALUES
(1, 1, 1, 'Pro-Bono Legal Representation', 0, 'Free legal assistance for survivors of domestic abuse, including court representation and filing of protection orders.', 'legal_aid.jpg', 'legal, lawyer, court, fida'),
(2, 2, 4, 'Emergency Trauma Care', 0, 'Immediate medical attention for physical injuries resulting from GBV incidents. Available 24/7.', 'medical_aid.jpg', 'hospital, doctor, medical, emergency'),
(3, 3, 3, 'One-on-One Trauma Counseling', 0, 'Private sessions with certified therapists specializing in sexual assault and domestic violence recovery.', 'counseling.jpg', 'therapy, mental health, counseling'),
(4, 4, 3, 'Safe Haven Temporary Housing', 0, 'Secure, confidential housing for women and children in immediate danger of domestic violence.', 'shelter.jpg', 'home, safety, housing, refuge'),
(5, 5, 6, 'Police Escort & Protection', 0, 'Request a security detail for retrieving personal belongings from a dangerous domestic environment.', 'police.jpg', 'security, police, protection'),
(6, 1, 5, 'Rights Awareness Workshop', 350, 'Educational sessions regarding the Domestic Violence Act and your rights under Ghanaian law.', 'default.png', 'education, legal, rights'),
(27, 1, 1, 'Court Representation for Survivors', 250, 'Free legal representation for women and children in cases of domestic violence, inheritance disputes, and child maintenance.', 'default.png', 'lawyer, court, fida, legal'),
(28, 2, 4, 'Forensic Medical Examination', 200, 'Confidential medical examination and evidence collection for sexual assault survivors to be used in legal proceedings.', 'default.png', 'hospital, forensic, doctor, sexual assault'),
(29, 3, 3, 'Crisis Telephone Counseling', 600, 'Confidential phone support for individuals experiencing immediate emotional distress or threat of violence.', 'Picture1.png', 'mental health, talk, crisis, phone'),
(30, 4, 3, 'Short-Term Crisis Shelter', 150, 'Emergency safe-house accommodation for up to 30 days for women and children fleeing dangerous environments.', 'Picture1.png', 'housing, home, safe house, protection'),
(31, 5, 2, 'Rapid Response Protection', 100, 'Direct police intervention and protection orders for victims currently facing physical threats from an abuser.', 'default.png', 'police, safety, dovvsu, security'),
(32, 6, 6, 'Vocational Skills Training', 300, 'Empowering survivors with bead making, soap making, and tailoring skills to gain financial independence from abusers.', 'default.png', 'money, training, empowerment, work'),
(33, 3, 3, 'Child Trauma Play Therapy', 80, 'Specialized psychological support for children who have witnessed or experienced domestic violence using play therapy.', 'default.png', 'kids, counseling, play, trauma'),
(34, 1, 5, 'Human Rights Violation Reporting', 250, 'Assistance in filing complaints regarding administrative injustice or human rights abuses by public officials.', 'default.png', 'rights, chraj, justice, legal');

-- --------------------------------------------------------

--
-- Table structure for table `victim`
--

CREATE TABLE `victim` (
  `victim_id` int(11) NOT NULL,
  `victim_name` varchar(100) NOT NULL,
  `victim_email` varchar(50) NOT NULL,
  `victim_pass` varchar(150) NOT NULL,
  `victim_country` varchar(30) NOT NULL,
  `victim_city` varchar(30) NOT NULL,
  `victim_contact` varchar(15) NOT NULL,
  `victim_image` varchar(100) DEFAULT NULL,
  `user_role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `victim`
--

INSERT INTO `victim` (`victim_id`, `victim_name`, `victim_email`, `victim_pass`, `victim_country`, `victim_city`, `victim_contact`, `victim_image`, `user_role`) VALUES
(2, 'Chika Amanna', 'chikaamanna@gmail.com', '$2y$10$SvGXGv66xWpSuOc2FKGRIeBvFt3a/iY2JA76h/OsZ1ZCGQamjOE3u', 'Nigeria', 'Abagan', '0530917605', 'user_2_699b84e79beb6.png', 1),
(3, 'Francis Sewor', 'francis.sewor@ashesi.edu.gh', '$2y$10$pivEWCeZOlwnEiCKEKQVqe8Ro75XiUH6zTcW7EPE8UdYW/5K2T/aG', 'Ghana', 'Aflao', '0256591970', NULL, 2),
(4, 'cindy Emmy', 'cinyemy@gmail.com', '$2y$10$50b2tUlPr4FWI.BaZR10mOTnK/UBpY98xz5pB5wHJOdlRkFYrOX9a', 'Togo', 'Lome', '0530917605', NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ai_logs`
--
ALTER TABLE `ai_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `fk_victim` (`victim_id`),
  ADD KEY `fk_service` (`service_id`);

--
-- Indexes for table `awareness`
--
ALTER TABLE `awareness`
  ADD PRIMARY KEY (`awareness_id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `chat_groups`
--
ALTER TABLE `chat_groups`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`msg_id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `victim_id` (`victim_id`);

--
-- Indexes for table `group_requests`
--
ALTER TABLE `group_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `fk_victim_request` (`victim_id`);

--
-- Indexes for table `incidents`
--
ALTER TABLE `incidents`
  ADD PRIMARY KEY (`incident_id`),
  ADD KEY `fk_victim_sos` (`victim_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `victim_id` (`victim_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`),
  ADD KEY `service_cat` (`service_cat`),
  ADD KEY `service_brand` (`service_brand`);

--
-- Indexes for table `victim`
--
ALTER TABLE `victim`
  ADD PRIMARY KEY (`victim_id`),
  ADD UNIQUE KEY `customer_email` (`victim_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ai_logs`
--
ALTER TABLE `ai_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `awareness`
--
ALTER TABLE `awareness`
  MODIFY `awareness_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `chat_groups`
--
ALTER TABLE `chat_groups`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `group_requests`
--
ALTER TABLE `group_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `incidents`
--
ALTER TABLE `incidents`
  MODIFY `incident_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `victim`
--
ALTER TABLE `victim`
  MODIFY `victim_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ai_logs`
--
ALTER TABLE `ai_logs`
  ADD CONSTRAINT `ai_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `victim` (`victim_id`);

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `fk_service` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_victim` FOREIGN KEY (`victim_id`) REFERENCES `victim` (`victim_id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `chat_groups` (`group_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_messages_ibfk_2` FOREIGN KEY (`victim_id`) REFERENCES `victim` (`victim_id`) ON DELETE CASCADE;

--
-- Constraints for table `group_requests`
--
ALTER TABLE `group_requests`
  ADD CONSTRAINT `fk_victim_request` FOREIGN KEY (`victim_id`) REFERENCES `victim` (`victim_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `incidents`
--
ALTER TABLE `incidents`
  ADD CONSTRAINT `fk_victim_sos` FOREIGN KEY (`victim_id`) REFERENCES `victim` (`victim_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`victim_id`) REFERENCES `victim` (`victim_id`) ON DELETE SET NULL;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`service_cat`) REFERENCES `categories` (`cat_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `services_ibfk_2` FOREIGN KEY (`service_brand`) REFERENCES `brands` (`brand_id`) ON DELETE CASCADE ON UPDATE CASCADE;
--
-- Table structure for table `help_desk_tickets`
--

CREATE TABLE `help_desk_tickets` (
  `ticket_id` int(11) NOT NULL AUTO_INCREMENT,
  `victim_id` int(11) NOT NULL,
  `category` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `status` enum('Pending','Resolved') DEFAULT 'Pending',
  `admin_reply` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `resolved_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ticket_id`),
  KEY `fk_ticket_victim` (`victim_id`),
  CONSTRAINT `fk_ticket_victim` FOREIGN KEY (`victim_id`) REFERENCES `victim` (`victim_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `direct_messages`
--

CREATE TABLE `direct_messages` (
  `msg_id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`msg_id`),
  FOREIGN KEY (`sender_id`) REFERENCES `victim`(`victim_id`) ON DELETE CASCADE,
  FOREIGN KEY (`receiver_id`) REFERENCES `victim`(`victim_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
