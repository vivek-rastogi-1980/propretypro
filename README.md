# Vigtez Reality Estates | Premium Luxury Real Estate Platform

Vigtez Reality Estates is an award-winning, state-of-the-art luxury real estate listing portal and customer relationship management application. Built with high-end digital aesthetics in mind, it delivers a custom bespoke user experience featuring cinematic preloaders, smooth scrolling, hardware-accelerated animations, and dynamic client-side theme selection.

---

## 💎 Features & Visual Experience

### 1. Dynamic Theme Selector
Includes a glassmorphic floating palette switcher (bottom-left) enabling seamless, flash-free client-side theme selection:
- **Luxe Dark (Original)**: Sleek, high-contrast dark navy mode with gold and amber accents.
- **Royal Blue & White**: Crisp, bright white background with rich royal blue/amber accents and dark slate text.
- **Biophilic Westin Green**: An elegant, biophilic cream and pale green theme using deep forest greens, cream backgrounds, and gold accents.

### 2. Cinematic Hero Swiper Slideshow
- Integrates a full-screen properties slideshow powered by Swiper.js, showcasing featured properties.
- Dynamic Ken Burns scale-in panning transitions on slide backgrounds.
- High-contrast, drop-shadowed typography overlays and custom gradients for headings.
- Clickable thumbnail preview dock (bottom-right) and real-time fractional pagination (`1 / 5`).

### 3. Glassmorphic Shiny Header Navigation
- Dark, high-contrast navigation bar utilizing backdrop-blur (`25px`) and saturation saturation multipliers (`220%`).
- Sleek metallic inset reflection line (`box-shadow`) and bottom border reflections.
- Active item highlights matching the current active theme color scheme.

### 4. Interactive Property Cards & Endorsements
- Custom grids for featured and latest properties with smooth typography contrast.
- Video testimonial preview card (Marcus Vance family office video testimonial) with dynamic contrast optimizations.
- Zero-blur, hardware-accelerated transitions on card hover interactions.

---

## 🛠️ Technology Stack
- **Backend Core**: PHP 8.x (Custom MVC Architecture)
- **Database**: MySQL / MariaDB
- **Frontend Structuring**: HTML5, Vanilla CSS3 (curated design system tokens)
- **JavaScript & Interactive Libraries**:
  - jQuery (ajax endpoints & panel toggles)
  - Swiper.js (Hero slideshow & Featured properties slider)
  - GSAP / ScrollTrigger (staggered entrance fades and count-up stats)
  - Lenis (smooth inertia mouse scroll)
  - FontAwesome 6 (vector glyphs)

---

## 📦 Installation & Setup

### Prerequisites
- **Web Server**: Apache/Nginx (e.g. XAMPP, WampServer)
- **PHP Version**: PHP 8.0 or higher
- **Database Server**: MySQL 5.7+ or MariaDB 10.3+

### Setup Instructions
1. **Clone the Repository**:
   ```bash
   git clone https://github.com/vivek-rastogi-1980/propretypro.git
   cd propretypro
   ```
2. **Database Import**:
   - Create a database in phpMyAdmin (e.g., `property_pro`).
   - Import the database schema and seeds from your SQL import file.
3. **Configure Local Variables**:
   - Create/modify your configuration file at `config/config.php` (this file is ignored in git to secure credentials):
     ```php
     <?php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     define('DB_NAME', 'property_pro');
     define('BASE_URL', 'http://localhost:8000/');
     ```
4. **Boot Development Server**:
   Start Apache/MySQL via your control panel, or start a local dev server using the PHP CLI inside the root folder:
   ```bash
   php -S localhost:8000
   ```
5. **Open Browser**:
   Navigate to `http://localhost:8000/` to explore Vigtez Reality Estates.

---

## 🔒 Git Management
The database credentials file is kept safe using `.gitignore` configurations:
```text
config/config.php
```
To run updates or push changes:
```bash
git add .
git commit -m "Your description"
git push origin main
```
