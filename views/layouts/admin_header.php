<?php
use App\Helpers\CSRFHelper;
use App\Models\Enquiry;

$unreadCount = Enquiry::getUnreadCount();
$companyName = htmlspecialchars($globalSettings['company_name'] ?? 'Vigtez Reality');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Admin Panel'; ?> | Vigtez Reality Admin</title>
    
    <!-- CSRF Token Meta -->
    <meta name="csrf-token" content="<?php echo CSRFHelper::generateToken(); ?>">

    <!-- Favicon -->
    <?php if (!empty($globalSettings['company_favicon'])): ?>
        <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL . $globalSettings['company_favicon']; ?>">
    <?php endif; ?>

    <!-- Stylesheets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Outfit Font -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <link href="<?php echo BASE_URL; ?>assets/css/style.css?v=<?php echo time(); ?>" rel="stylesheet">
    <script>
        (function () {
            const savedTheme = localStorage.getItem('admin-theme');
            if (savedTheme === 'light') {
                document.documentElement.classList.add('admin-light-theme');
            }
        })();
    </script>
</head>
<body class="admin-dark-body">

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Navigation -->
        <aside class="col-md-3 col-lg-2 px-0 position-fixed start-0 top-0 d-md-block d-none admin-sidebar-luxury">
            <div class="px-4 py-4 border-bottom border-secondary border-opacity-10 mb-4 text-center">
                <a href="<?php echo BASE_URL; ?>" class="text-decoration-none">
                    <span class="fs-4 fw-extrabold text-gradient-light"><i class="fa-solid fa-hotel me-2"></i>Vigtez Reality</span>
                </a>
                <div class="small text-muted mt-2 tracking-widest uppercase fs-xs">System Console</div>
                <div class="mt-3">
                    <button id="admin-theme-toggle" class="btn btn-sm btn-outline-secondary px-3 py-1 rounded-pill small fw-bold">
                        <i class="fa-solid fa-sun me-2 text-warning"></i><span>Light Mode</span>
                    </button>
                </div>
            </div>

            <nav class="nav flex-column px-2 admin-nav-links">
                <a class="nav-link <?php echo str_contains($_SERVER['REQUEST_URI'], 'admin/dashboard') || (trim(substr($_SERVER['REQUEST_URI'], strlen(BASE_PATH)), '/') === 'admin') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/dashboard">
                    <i class="fa-solid fa-chart-pie me-3"></i> Dashboard
                </a>
                <a class="nav-link <?php echo str_contains($_SERVER['REQUEST_URI'], 'admin/properties') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/properties">
                    <i class="fa-solid fa-hotel me-3"></i> Properties
                </a>
                <a class="nav-link d-flex justify-content-between align-items-center <?php echo str_contains($_SERVER['REQUEST_URI'], 'admin/enquiries') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/enquiries">
                    <span><i class="fa-solid fa-envelope-open-text me-3"></i> Enquiries</span>
                    <?php if ($unreadCount > 0): ?>
                        <span class="badge bg-danger rounded-pill px-2 py-1 admin-badge-unread"><?php echo $unreadCount; ?></span>
                    <?php endif; ?>
                </a>
                <a class="nav-link <?php echo str_contains($_SERVER['REQUEST_URI'], 'admin/media') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/media">
                    <i class="fa-solid fa-photo-film me-3"></i> Media Library
                </a>
                <a class="nav-link <?php echo str_contains($_SERVER['REQUEST_URI'], 'admin/pages') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/pages">
                    <i class="fa-solid fa-file-pen me-3"></i> Manage Pages
                </a>
                <a class="nav-link <?php echo str_contains($_SERVER['REQUEST_URI'], 'admin/settings') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/settings">
                    <i class="fa-solid fa-gears me-3"></i> Settings
                </a>
                
                <hr class="border-secondary border-opacity-10 my-4">
                
                <a class="nav-link text-info" href="<?php echo BASE_URL; ?>" target="_blank">
                    <i class="fa-solid fa-globe me-3"></i> Public Site
                </a>
                <a class="nav-link text-danger" href="<?php echo BASE_URL; ?>admin/logout">
                    <i class="fa-solid fa-right-from-bracket me-3"></i> Logout
                </a>
            </nav>
        </aside>

        <!-- Main Workspace Area -->
        <main class="col-md-9 col-lg-10 ms-auto px-md-4 px-3" style="padding-top: 30px; min-height: 100vh;">
            <header class="d-md-none d-flex justify-content-between align-items-center mb-4 py-3 px-3 glass-card rounded-4 border-secondary border-opacity-15">
                <a href="<?php echo BASE_URL; ?>" class="text-decoration-none">
                    <span class="fs-4 fw-bold text-gradient-light"><i class="fa-solid fa-hotel me-2"></i>Vigtez Reality</span>
                </a>
                
                <div class="d-flex align-items-center gap-2">
                    <!-- Mobile Theme Toggle -->
                    <button id="admin-mobile-theme-toggle" class="btn btn-sm btn-outline-light rounded-circle animate-fade-in" style="width: 38px; height: 38px; padding: 0; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-sun text-warning"></i>
                    </button>

                    <!-- Dropdown Navigation Trigger -->
                    <div class="dropdown">
                        <button class="btn btn-outline-light dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-bars me-2"></i> Menu
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end glass-card border-secondary border-opacity-15 shadow-lg">
                        <li><a class="dropdown-item fw-semibold" href="<?php echo BASE_URL; ?>admin/dashboard"><i class="fa-solid fa-chart-pie me-2"></i>Dashboard</a></li>
                        <li><a class="dropdown-item fw-semibold" href="<?php echo BASE_URL; ?>admin/properties"><i class="fa-solid fa-hotel me-2"></i>Properties</a></li>
                        <li><a class="dropdown-item fw-semibold" href="<?php echo BASE_URL; ?>admin/enquiries"><i class="fa-solid fa-envelope-open-text me-2"></i>Enquiries</a></li>
                        <li><a class="dropdown-item fw-semibold" href="<?php echo BASE_URL; ?>admin/media"><i class="fa-solid fa-photo-film me-2"></i>Media</a></li>
                        <li><a class="dropdown-item fw-semibold" href="<?php echo BASE_URL; ?>admin/pages"><i class="fa-solid fa-file-pen me-2"></i>Manage Pages</a></li>
                        <li><a class="dropdown-item fw-semibold" href="<?php echo BASE_URL; ?>admin/settings"><i class="bi bi-gear me-2"></i>Settings</a></li>
                        <li><hr class="dropdown-divider border-secondary border-opacity-10"></li>
                        <li><a class="dropdown-item fw-semibold text-danger" href="<?php echo BASE_URL; ?>admin/logout"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
            </header>
