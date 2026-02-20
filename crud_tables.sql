-- ============================================
-- Portfolio CRUD Database Tables
-- For HeidiSQL / MySQL / MariaDB
-- Run this on your 'portfolio' database
-- ============================================

-- Make sure you're using the correct database
-- USE portfolio;

-- ============================================
-- 1. USERS TABLE (if not exists)
-- ============================================
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(150) DEFAULT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- 2. PROJECTS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `featured` tinyint(1) NOT NULL DEFAULT 0,
  `type` longtext DEFAULT NULL COMMENT 'JSON array of frameworks/languages',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `user_id` (`user_id`),
  KEY `idx_projects_slug` (`slug`),
  CONSTRAINT `fk_projects_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- 3. PORTFOLIO TABLE (Home, About Me, Contact)
-- ============================================
CREATE TABLE IF NOT EXISTS `portfolio` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  
  -- HOME SECTION
  `hero_title` varchar(255) DEFAULT 'Miguel Andrei del Rosario',
  `hero_subtitle` varchar(255) DEFAULT 'A Web Developer Trainee',
  `profile_image` varchar(255) DEFAULT NULL,
  
  -- ABOUT ME SECTION
  `about_content` text DEFAULT NULL,
  
  -- EDUCATION (About Me)
  `education_elementary` varchar(255) DEFAULT NULL,
  `education_high_school` varchar(255) DEFAULT NULL,
  `education_senior_high` varchar(255) DEFAULT NULL,
  `education_college` varchar(255) DEFAULT NULL,
  `education_certification` varchar(255) DEFAULT NULL,
  
  -- CONTACT / GET IN TOUCH SECTION
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `github_url` varchar(255) DEFAULT NULL,
  `linkedin_url` varchar(255) DEFAULT NULL,
  
  -- TIMESTAMPS
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_portfolio_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- 4. SKILLS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS `skills` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `percent` int(3) NOT NULL DEFAULT 0,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_skills_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- 5. CONTACTS TABLE (for additional contacts)
-- ============================================
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `type` varchar(50) NOT NULL COMMENT 'e.g., email, phone, github_url, linkedin_url, twitter, etc.',
  `label` varchar(100) DEFAULT NULL COMMENT 'Display label',
  `value` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_contacts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- SAMPLE DATA (Optional - uncomment to use)
-- ============================================

-- Insert sample user (password: password123)
-- INSERT INTO `users` (`username`, `email`, `password`, `fullname`, `role`) VALUES
-- ('admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin User', 'admin');

-- Insert sample portfolio for user_id = 1
-- INSERT INTO `portfolio` (`user_id`, `hero_title`, `hero_subtitle`, `about_content`, `email`, `phone`, `github_url`, `linkedin_url`, `education_elementary`, `education_high_school`, `education_senior_high`, `education_college`, `education_certification`) VALUES
-- (1, 'Miguel Andrei del Rosario', 'A Web Developer Trainee', 'I''m a motivated Information Technology student passionate about creating innovative web solutions.', 'miguelandrei@sdca.edu.ph', '639096059630', 'github.com/migs159', 'linkedin.com/in/miguel-andrei-del-rosario-a291693b1', 'Talon Elementary School (2008-2014)', 'City of Bacoor National High School (2015-2019)', 'Las Piñas City National Senior High School (2020-2021)', 'St. Dominic College of Asia - B.S. Information Technology', 'Information Technology Specialist - HTML and CSS');

-- Insert sample skills for user_id = 1
-- INSERT INTO `skills` (`user_id`, `name`, `percent`, `sort_order`) VALUES
-- (1, 'HTML5 / CSS3', 90, 1),
-- (1, 'JavaScript', 85, 2),
-- (1, 'PHP', 80, 3),
-- (1, 'MySQL', 75, 4),
-- (1, 'Bootstrap', 85, 5),
-- (1, 'CodeIgniter', 70, 6);

-- Insert sample projects for user_id = 1
-- INSERT INTO `projects` (`user_id`, `title`, `slug`, `description`, `image`, `url`, `status`, `featured`, `type`) VALUES
-- (1, 'Portfolio Website', 'portfolio-website', 'Personal portfolio built with CodeIgniter 3', NULL, 'https://example.com', 1, 1, '["php","html_css","javascript"]'),
-- (1, 'E-Commerce App', 'e-commerce-app', 'Online shopping platform with cart functionality', NULL, 'https://shop.example.com', 1, 0, '["php","mysql","javascript"]');

-- ============================================
-- USEFUL QUERIES
-- ============================================

-- Get all data for a user's portfolio
-- SELECT p.*, 
--        (SELECT JSON_ARRAYAGG(JSON_OBJECT('name', s.name, 'percent', s.percent)) FROM skills s WHERE s.user_id = p.user_id ORDER BY s.sort_order) as skills
-- FROM portfolio p 
-- WHERE p.user_id = 1;

-- Get all projects for a user
-- SELECT * FROM projects WHERE user_id = 1 AND deleted_at IS NULL ORDER BY created_at DESC;

-- Get all skills for a user
-- SELECT * FROM skills WHERE user_id = 1 ORDER BY sort_order, name;
