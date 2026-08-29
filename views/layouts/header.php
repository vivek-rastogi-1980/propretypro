<?php
use App\Helpers\SEOHelper;
use App\Helpers\AuthHelper;

$title = $pageTitle ?? null;
$metaDesc = $pageDesc ?? null;
$metaKeywords = $pageKeywords ?? null;
$ogImage = $pageOgImage ?? null;

// Dynamic title, description and keywords with fallback to DB settings
$seoTitle = SEOHelper::getTitle($title);
if ($seoTitle === DEFAULT_SEO_TITLE && !empty($globalSettings['seo_title'])) {
    $seoTitle = $globalSettings['seo_title'];
    if (!empty($title)) {
        $seoTitle = $title . ' | ' . $globalSettings['company_name'];
    }
}
$seoDesc = !empty($metaDesc) ? $metaDesc : ($globalSettings['seo_meta_description'] ?? DEFAULT_SEO_DESC);
$seoKeywords = !empty($metaKeywords) ? $metaKeywords : ($globalSettings['seo_meta_keywords'] ?? DEFAULT_SEO_KEYWORDS);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        (function() {
            const theme = localStorage.getItem('frontend-theme') || 'dark';
            if (theme !== 'dark') {
                document.documentElement.classList.add('theme-' + theme);
            }
        })();
    </script>
    
    <!-- Dynamic SEO and Meta Structure -->
    <title><?php echo htmlspecialchars($seoTitle); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($seoDesc); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($seoKeywords); ?>">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo htmlspecialchars($globalSettings['seo_canonical_url'] ?? BASE_URL . trim(explode('?', $_SERVER['REQUEST_URI'])[0], '/')); ?>">
    
    <!-- Open Graph Tags -->
    <meta property="og:title" content="<?php echo htmlspecialchars($seoTitle); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($seoDesc); ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars(!empty($ogImage) ? BASE_URL . $ogImage : (!empty($globalSettings['seo_og_image']) ? BASE_URL . $globalSettings['seo_og_image'] : BASE_URL . 'assets/images/default_property.png')); ?>">
    <meta property="og:url" content="<?php echo htmlspecialchars(BASE_URL . trim(explode('?', $_SERVER['REQUEST_URI'])[0], '/')); ?>">
    <meta property="og:type" content="website">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="<?php echo htmlspecialchars($globalSettings['seo_twitter_card'] ?? 'summary_large_image'); ?>">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($seoTitle); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($seoDesc); ?>">

    <!-- Schema Markup -->
    <?php if (!empty($globalSettings['seo_schema'])): ?>
        <?php echo $globalSettings['seo_schema']; ?>
    <?php endif; ?>

    <!-- Robots Meta -->
    <meta name="robots" content="<?php echo htmlspecialchars($globalSettings['seo_robots'] ?? 'index, follow'); ?>">

    <!-- Favicon -->
    <?php if (!empty($globalSettings['company_favicon'])): ?>
        <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL . $globalSettings['company_favicon']; ?>">
    <?php else: ?>
        <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>assets/images/favicon.png">
    <?php endif; ?>

    <!-- Google Analytics -->
    <?php if (!empty($globalSettings['google_analytics'])): ?>
        <?php echo $globalSettings['google_analytics']; ?>
    <?php endif; ?>

    <!-- Meta Pixel -->
    <?php if (!empty($globalSettings['meta_pixel'])): ?>
        <?php echo $globalSettings['meta_pixel']; ?>
    <?php endif; ?>

    <!-- Google Search Console -->
    <?php if (!empty($globalSettings['google_search_console'])): ?>
        <?php echo $globalSettings['google_search_console']; ?>
    <?php endif; ?>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800;900&family=Poppins:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Stylesheets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
    <link href="<?php echo BASE_URL; ?>assets/css/style.css?v=<?php echo time(); ?>" rel="stylesheet">
</head>
<body>

    <!-- Cinematic Luxury Preloader -->
    <div id="preloader">
        <div class="preloader-inner">
            <div class="preloader-brand">
                <span class="preloader-logo-text"><?php echo htmlspecialchars($globalSettings['company_name'] ?? 'Vigtez Reality'); ?></span>
                <span class="preloader-subtitle">ESTATES</span>
            </div>
            <div class="preloader-bar">
                <div class="preloader-bar-fill"></div>
            </div>
        </div>
    </div>

    <!-- Glassmorphic Header Navigation -->
    <nav class="navbar navbar-expand-lg glass-nav fixed-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="<?php echo BASE_URL; ?>">
                <?php if (!empty($globalSettings['company_logo'])): ?>
                    <img src="<?php echo BASE_URL . $globalSettings['company_logo']; ?>" alt="<?php echo htmlspecialchars($globalSettings['company_name'] ?? 'Vigtez Reality'); ?>" height="45" class="me-2 brand-logo">
                <?php else: ?>
                    <span class="brand-text text-gradient"><i class="fa-solid fa-hotel me-2"></i><?php echo htmlspecialchars($globalSettings['company_name'] ?? 'Vigtez Reality'); ?></span>
                <?php endif; ?>
            </a>
            
            <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="fa-solid fa-bars-staggered fs-3 text-white"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                    <?php
                    $currentUri = $_SERVER['REQUEST_URI'];
                    $homeActive = empty(trim(substr($currentUri, strlen(BASE_PATH)), '/')) ? 'active' : '';
                    $aboutActive = str_contains($currentUri, 'about') ? 'active' : '';
                    $propertiesActive = str_contains($currentUri, 'properties') || str_contains($currentUri, 'property/') ? 'active' : '';
                    $contactActive = str_contains($currentUri, 'contact') ? 'active' : '';
                    ?>
                    <li class="nav-item px-3">
                        <a class="nav-link nav-link-luxury <?php echo $homeActive; ?>" href="<?php echo BASE_URL; ?>">Home</a>
                    </li>
                    <li class="nav-item px-3">
                        <a class="nav-link nav-link-luxury <?php echo $aboutActive; ?>" href="<?php echo BASE_URL; ?>about">About</a>
                    </li>
                    <li class="nav-item px-3">
                        <a class="nav-link nav-link-luxury <?php echo $propertiesActive; ?>" href="<?php echo BASE_URL; ?>properties">Collection</a>
                    </li>
                    <li class="nav-item px-3">
                        <a class="nav-link nav-link-luxury <?php echo $contactActive; ?>" href="<?php echo BASE_URL; ?>contact">Contact</a>
                    </li>
                    
                    <?php if (AuthHelper::isLoggedIn()): ?>
                        <li class="nav-item px-2 dropdown ms-lg-3">
                            <a class="nav-link dropdown-toggle btn btn-premium px-4 py-2 text-white d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-circle-user me-2 text-warning"></i> Admin
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end border-0 glass-card shadow mt-2">
                                <li><a class="dropdown-item fw-semibold py-2" href="<?php echo BASE_URL; ?>admin/dashboard"><i class="fa-solid fa-chart-line me-2 text-primary"></i>Dashboard</a></li>
                                <li><a class="dropdown-item fw-semibold py-2" href="<?php echo BASE_URL; ?>admin/properties"><i class="fa-solid fa-city me-2 text-success"></i>Properties</a></li>
                                <li><a class="dropdown-item fw-semibold py-2" href="<?php echo BASE_URL; ?>admin/settings"><i class="fa-solid fa-sliders me-2 text-warning"></i>Settings</a></li>
                                <li><hr class="dropdown-divider border-secondary border-opacity-20"></li>
                                <li><a class="dropdown-item fw-semibold text-danger py-2" href="<?php echo BASE_URL; ?>admin/logout"><i class="fa-solid fa-power-off me-2"></i>Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item px-2 ms-lg-3">
                            <a class="btn btn-premium d-flex align-items-center" href="<?php echo BASE_URL; ?>properties"><i class="fa-solid fa-magnifying-glass me-2"></i>Explore</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
