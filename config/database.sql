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
  `in_slider` TINYINT(1) NOT NULL DEFAULT 0,
  `slider_image` VARCHAR(255) DEFAULT NULL,
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
('admin', 'admin@vigtezreality.com', '$2y$10$TU.BcnFCq5mIqRmXy.UFTuX38SJUkKArZ22yoTen/Soh9/j9UQkLm')
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
('company_name', 'Vigtez Reality Estates'),
('company_phone', '+1 (555) 123-4567'),
('company_email', 'contact@vigtezreality.com'),
('whatsapp_number', '15551234567'),
('office_address', '100 Luxury Way, Suite 500, Beverly Hills, CA 90210'),
('social_facebook', 'https://facebook.com/vigtezreality'),
('social_instagram', 'https://instagram.com/vigtezreality'),
('social_twitter', 'https://twitter.com/vigtezreality'),
('social_linkedin', 'https://linkedin.com/company/vigtezreality'),
('footer_content', '© 2026 AIAutoMix. All rights reserved. Premium Real Estate Solutions.'),
('seo_title', 'Vigtez Reality Estates | Luxury Real Estate & Premium Properties'),
('seo_meta_description', 'Discover premium real estate, apartments, villas, and commercial properties with Vigtez Reality Estates. Your trusted luxury property broker.'),
('seo_meta_keywords', 'real estate, luxury villas, premium apartments, independent house, office space, commercial properties, buy property, rent villa'),
('company_logo', ''),
('company_favicon', ''),
('seo_og_image', ''),
('home_overview_badge', 'ABOUT VIGTEZ REALTY'),
('home_overview_title', 'Shaping Masterpieces of Luxury Living'),
('home_overview_desc_1', 'Vigtez Realty Pvt. Ltd. is a Uttarakhand-based real estate company specializing in premium land, villas, and second-home projects. We create investment opportunities in strategically located destinations across Uttarakhand with a focus on quality, transparency, and long-term value.'),
('home_overview_desc_2', 'incorporated in 2026, is a Uttarakhand-based real estate company focused on premium land, villas, and second-home projects. We aim to deliver trusted, transparent, and value-driven real estate opportunities.'),
('home_overview_stat1_val', '$4.2B+'),
('home_overview_stat1_lbl', 'Total Sales Volume'),
('home_overview_stat2_val', '98.4%'),
('home_overview_stat2_lbl', 'Client Retention Rate'),
('home_overview_image', ''),
('home_overview_quote', '\"An absolute masterpiece of a listing portal. The interface is art itself.\"'),
('home_stat1_num', '4.2'),
('home_stat1_lbl', 'Sales Volume (Billion)'),
('home_stat2_num', '12'),
('home_stat2_lbl', 'Global Cities Active'),
('home_stat3_num', '500'),
('home_stat3_lbl', 'Transactions Closed'),
('home_stat4_num', '35'),
('home_stat4_lbl', 'Industry Awards'),
('home_testimonials_badge', 'ENDORSEMENTS'),
('home_testimonials_title', 'Testimonials from our Clients'),
('home_testimonial1_text', '\"Vigtez Reality provided an elite concierge experience. They negotiated our off-market estate in Malibu with absolute discretion and precision. Their team was professional beyond expectations.\"'),
('home_testimonial1_author', 'Marcus Vance'),
('home_testimonial1_role', 'CEO, Vance Capital Group'),
('home_testimonial2_text', '\"The obsidian penthouse is a true architectural masterpiece. The layout, standard of marble detailing, and the transaction service from Vigtez Reality made buying our dream home an absolute joy.\"'),
('home_testimonial2_author', 'Sophia Loren'),
('home_testimonial2_role', 'Fashion Designer & Investor'),
('home_testimonial_video_image', ''),
('home_testimonial_video_title', 'Video Review - Vance Family Office'),
('home_testimonial_video_youtube_id', 'dQw4w9WgXcQ'),
('home_services_badge', 'OUR EXPERTISE'),
('home_services_title', 'Exclusive concierge Services'),
('home_service1_icon', 'fa-shield-halved'),
('home_service1_title', 'Off-Market Acquisition'),
('home_service1_desc', 'Gain private entry to architectural masterpieces not published on standard MLS databases. Complete confidentiality.'),
('home_service2_icon', 'fa-briefcase'),
('home_service2_title', 'Asset Structuring'),
('home_service2_desc', 'Maximize fiscal shielding and generational transfer advantages by structured ownership models matching legal trusts.'),
('home_service3_icon', 'fa-chart-line'),
('home_service3_title', 'Wealth Management'),
('home_service3_desc', 'Treat real estate properties as financial hedges. Detailed yield analysis, rental automation, and portfolio reports.'),
('home_faq_badge', 'KNOWLEDGEBASE'),
('home_faq_title', 'Frequently Asked Questions'),
('home_faq1_q', 'What is an off-market luxury property?'),
('home_faq1_a', 'Off-market listings are exclusive property records sold privately without general advertising. This maintains complete client privacy and keeps details secure from database scanning systems.'),
('home_faq2_q', 'How can I verify a property\'\'s RERA registration?'),
('home_faq2_a', 'Each applicable property is listed with its verified government RERA identifier. You can enter this ID into the national portal database or ask our brokerage concierge for certified documentation.'),
('home_faq3_q', 'Do you provide concierge translation & legal services?'),
('home_faq3_a', 'Yes. Through our luxury boutique partners, we support international clients with structured corporate legal structuring, trust transfers, certified translations, and corporate bank routing support.'),
('home_awards_badge', 'ACCREDITATION'),
('home_awards_title', 'Our Awards'),
('home_award1_icon', 'fa-medal'),
('home_award1_title', 'CSS Design Award'),
('home_award1_text', 'Best UI/UX Redesign'),
('home_award2_icon', 'fa-trophy'),
('home_award2_title', 'Awwwards Honorable'),
('home_award2_text', 'Luxury Digital Portal'),
('home_award3_icon', 'fa-ribbon'),
('home_award3_title', 'Real Estate Forum'),
('home_award3_text', 'Best Luxury Agency'),
('home_award4_icon', 'fa-crown'),
('home_award4_title', 'International Prop'),
('home_award4_text', 'Outstanding Architecture'),
('home_cta_badge', 'CONTACT WITH US'),
('home_cta_title', 'Invest in Your Next Dream Space'),
('home_cta_desc', 'Experience beautifully crafted spaces where elegant design meets comfort and timeless appeal.'),
('home_cta_video_url', 'https://assets.mixkit.co/videos/preview/mixkit-modern-apartment-building-in-a-city-40718-large.mp4'),
('about_hero_image', ''),
('about_hero_title', 'About Vigtez Realty'),
('about_hero_desc', 'A legacy of beautiful designs, with personal attention and complete client privacy.'),
('about_identity_badge', 'OUR IDENTITY'),
('about_identity_title', 'Vigtez Realty Pvt. Ltd Works of Art'),
('about_identity_desc1', 'Vigtez Realty Pvt. Ltd., incorporated in 2026, is a Uttarakhand-based real estate company focused on premium land, villas, and second-home projects. We aim to deliver trusted, transparent, and value-driven real estate opportunities.'),
('about_identity_desc2', 'Today, we manage a private inventory spanning 5 cities, supporting family offices, and private clients in acquiring ocean bluffs, historical estates, and sustainable automated penthouses.'),
('about_identity_card1_icon', 'fa-handshake-angle'),
('about_identity_card1_title', 'Discretion'),
('about_identity_card1_text', 'We never publish off-market transactional prices or client identities. Absolute privacy.'),
('about_identity_card2_icon', 'fa-compass-drafting'),
('about_identity_card2_title', 'Design Focus'),
('about_identity_card2_text', 'We prioritize structural design, smart home automation, and high-end marble details.'),
('about_leadership_badge', 'THE CONCIERGE'),
('about_leadership_title', 'Executive Leadership'),
('about_team1_name', 'Charles Sterling'),
('about_team1_role', 'Founder & Chief Advisor'),
('about_team1_image', ''),
('about_team2_name', 'Alexandra Vance'),
('about_team2_role', 'Managing Partner (Beverly Hills)'),
('about_team2_image', ''),
('about_team3_name', 'Julien Beaumont'),
('about_team3_role', 'Head of Wealth & Asset Advisory'),
('about_team3_image', ''),
('contact_hero_image', ''),
('contact_hero_badge', 'CONCIERGE DESK'),
('contact_hero_title', 'Connect with Vigtez Reality'),
('contact_hero_desc', 'Schedule private helicopter viewings, charter tours, or off-market portfolios.'),
('contact_channels_badge', 'OUR CHANNELS'),
('contact_channels_title', 'Acquisition Inquiries'),
('contact_channels_desc', 'Connect with our luxury partners. For ultra-high-net-worth portfolio acquisitions or private valuations, please submit the form or contact our concierge line directly.'),
('contact_business_hours', 'Monday – Saturday: 9:00 AM – 6:00 PM PST<br>Private emergency hotline: 24/7 (Registered Clients)'),
('contact_form_badge', 'SUBMIT ENQUIRY'),
('contact_form_title', 'Schedule a Private Viewing')
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
