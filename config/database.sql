-- Database Initialization for Real Estate MVC Website
CREATE DATABASE IF NOT EXISTS `real_estate_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `real_estate_db`;

-- 1. Admins Table
CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `reset_token` VARCHAR(100) DEFAULT NULL,
  `reset_token_expire` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Categories Table
CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(50) NOT NULL UNIQUE,
  `slug` VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- 3. Properties Table
CREATE TABLE IF NOT EXISTS `properties` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `category_id` INT NOT NULL,
  `status` ENUM('For Sale', 'For Rent') NOT NULL DEFAULT 'For Sale',
  `price` DECIMAL(15, 2) NOT NULL,
  `location` VARCHAR(255) NOT NULL,
  `bedrooms` INT NOT NULL DEFAULT 0,
  `bathrooms` INT NOT NULL DEFAULT 0,
  `area` DECIMAL(10, 2) NOT NULL,
  `short_description` VARCHAR(500) NOT NULL,
  `full_description` TEXT NOT NULL,
  `amenities` TEXT DEFAULT NULL, -- JSON array of amenities
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `is_published` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- 4. Property Images Table
CREATE TABLE IF NOT EXISTS `property_images` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `property_id` INT NOT NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 5. Enquiries Table
CREATE TABLE IF NOT EXISTS `enquiries` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `property_id` INT DEFAULT NULL,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `message` TEXT NOT NULL,
  `is_read` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 6. Settings Table
CREATE TABLE IF NOT EXISTS `settings` (
  `key` VARCHAR(50) PRIMARY KEY,
  `value` TEXT NULL
) ENGINE=InnoDB;

-- Insert Default Admin
INSERT INTO `admins` (`username`, `email`, `password`) VALUES
('admin', 'admin@luxehavenestates.com', '$2y$10$TU.BcnFCq5mIqRmXy.UFTuX38SJUkKArZ22yoTen/Soh9/j9UQkLm')
ON DUPLICATE KEY UPDATE `id` = `id`;

-- Insert Default Categories
INSERT INTO `categories` (`name`, `slug`) VALUES
('Apartment', 'apartment'),
('Villa', 'villa'),
('Independent House', 'independent-house'),
('Commercial', 'commercial'),
('Office', 'office'),
('Shop', 'shop'),
('Land', 'land')
ON DUPLICATE KEY UPDATE `id` = `id`;

-- Insert Default Settings
INSERT INTO `settings` (`key`, `value`) VALUES
('company_name', 'LuxeHaven Estates'),
('company_phone', '+1 (555) 123-4567'),
('company_email', 'contact@luxehavenestates.com'),
('whatsapp_number', '15551234567'),
('office_address', '100 Luxury Way, Suite 500, Beverly Hills, CA 90210'),
('social_facebook', 'https://facebook.com/luxehaven'),
('social_instagram', 'https://instagram.com/luxehaven'),
('social_twitter', 'https://twitter.com/luxehaven'),
('social_linkedin', 'https://linkedin.com/company/luxehaven'),
('footer_content', '© 2026 AIAutoMix. All rights reserved. Premium Real Estate Solutions.'),
('seo_title', 'LuxeHaven Estates | Luxury Real Estate & Premium Properties'),
('seo_meta_description', 'Discover premium real estate, apartments, villas, and commercial properties with LuxeHaven Estates. Your trusted luxury property broker.'),
('seo_meta_keywords', 'real estate, luxury villas, premium apartments, independent house, office space, commercial properties, buy property, rent villa'),
('company_logo', ''),
('company_favicon', ''),
('seo_og_image', '')
ON DUPLICATE KEY UPDATE `key` = `key`;

-- Insert Sample Properties
INSERT INTO `properties` (`id`, `title`, `slug`, `category_id`, `status`, `price`, `location`, `bedrooms`, `bathrooms`, `area`, `short_description`, `full_description`, `amenities`, `is_featured`, `is_published`) VALUES
(1, 'The Obsidian Penthouse', 'the-obsidian-penthouse', 1, 'For Sale', 2450000.00, 'Beverly Hills, CA', 3, 4, 4200.00, 'An architecturally significant penthouse boasting panoramic skyline views, high-end marble details, and private infinity pool.', 'Located at the highest peak of Beverly Hills, The Obsidian Penthouse represents the pinnacle of modern luxury living. Featuring 24-foot ceilings, automated floor-to-ceiling glass walls, and a master bathroom lined entirely with book-matched Italian marble. The layout is optimized for entertaining, presenting a double-chef kitchen, private wine vault, and a 1,200 sq. ft. heated terrace featuring a personal infinity pool. Residents enjoy access to private concierge lobby, secure underground parking, and private elevator landing access.', '[\"Swimming Pool\",\"Gymnasium\",\"Covered Parking\",\"24/7 Security\",\"Elevator\",\"Air Conditioning\",\"High Speed Wi-Fi\"]', 1, 1),
(2, 'Villa Seraphina', 'villa-seraphina', 2, 'For Sale', 8900000.00, 'Malibu, CA', 5, 6, 7800.00, 'A modern coastal architectural masterpiece offering absolute privacy, ocean access, and smart home automation.', 'Nestled in the private gated bluff of Malibu, Villa Seraphina represents the ultimate beachfront sanctuary. Architecturally designed to merge indoor luxury with outdoor coastal beauty, the residence utilizes a geometric steel frame construction with massive glass panels. Featuring a state-of-the-art home theater, private wellness spa, wine room, and private steps leading directly to a secluded sandy cove. The landscaped grounds include an outdoor kitchen, fire pits, and a saltwater pool looking out onto the Pacific Ocean.', '[\"Swimming Pool\",\"Private Garden\",\"Covered Parking\",\"24/7 Security\",\"Club House\",\"Air Conditioning\",\"Fire Safety\"]', 1, 1),
(3, 'The Lumina Office Suite', 'the-lumina-office-suite', 5, 'For Rent', 12500.00, 'Downtown Los Angeles, CA', 0, 2, 3100.00, 'A premium corporate office suite in the heart of the business district, optimized for corporate headquarters.', 'Positioned on the 42nd floor of the prestigious Lumina Tower, this corporate office suite offers unmatched views of the Los Angeles skyline. Spanning 3,100 square feet, the open-concept layout includes 4 private executive offices, a glass-walled boardroom, a modern receptionist lobby, and a private kitchenette. Fully equipped with high-speed fiber internet and access to the building\'s executive meeting rooms and secure parking garage.', '[\"Covered Parking\",\"24/7 Security\",\"Elevator\",\"High Speed Wi-Fi\",\"Air Conditioning\",\"Fire Safety\"]', 0, 1)
ON DUPLICATE KEY UPDATE `id` = `id`;

-- Insert Sample Properties Gallery
INSERT INTO `property_images` (`id`, `property_id`, `image_path`, `is_featured`) VALUES
(1, 1, 'assets/images/default_property.png', 1),
(2, 2, 'assets/images/default_property.png', 1),
(3, 3, 'assets/images/default_property.png', 1)
ON DUPLICATE KEY UPDATE `id` = `id`;
