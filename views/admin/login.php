<?php
use App\Helpers\CSRFHelper;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Console Authentication | Vigtez Reality Estates</title>
    
    <!-- Stylesheets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700&family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    
    <link href="<?php echo BASE_URL; ?>assets/css/style.css" rel="stylesheet">
</head>
<body class="admin-dark-body flex-center" style="min-height: 100vh;">

<div class="container d-flex justify-content-center align-items-center">
    <div class="glass-card-dark p-5 rounded-4 shadow-2xl border-secondary border-opacity-15 w-100" style="max-width: 450px;">
        <div class="text-center mb-4">
            <span class="text-warning display-6 mb-3 d-inline-block"><i class="fa-solid fa-hotel"></i></span>
            <h3 class="font-cinzel text-white fw-bold mb-1">Vigtez Reality</h3>
            <p class="text-secondary small tracking-widest uppercase mb-0">System Authentication</p>
        </div>

        <?php if (!empty($errors['auth'])): ?>
            <div class="alert alert-danger border-0 small text-center p-3 mb-4 rounded-3 text-white" style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.25) !important;">
                <i class="fa-solid fa-triangle-exclamation me-2 text-danger"></i><?php echo $errors['auth']; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo BASE_URL; ?>admin/login" method="POST">
            <?php echo CSRFHelper::getTokenField(); ?>

            <div class="mb-4">
                <label class="form-label text-secondary small fw-bold"><i class="fa-solid fa-user-shield me-2"></i>Username or Email</label>
                <input type="text" name="username" class="form-control luxury-input-text" required placeholder="e.g. admin" value="<?php echo htmlspecialchars($old['username'] ?? ''); ?>">
                <?php if (!empty($errors['username'])): ?>
                    <div class="text-danger small fw-bold mt-1"><?php echo $errors['username']; ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <label class="form-label text-secondary small fw-bold"><i class="fa-solid fa-key me-2"></i>Security Password</label>
                <input type="password" name="password" class="form-control luxury-input-text" required placeholder="••••••••">
                <?php if (!empty($errors['password'])): ?>
                    <div class="text-danger small fw-bold mt-1"><?php echo $errors['password']; ?></div>
                <?php endif; ?>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4 small">
                <div class="form-check">
                    <input class="form-check-input text-warning" type="checkbox" id="rememberMe" style="cursor: pointer;">
                    <label class="form-check-label text-secondary" for="rememberMe" style="cursor: pointer;">Remember Key</label>
                </div>
                <a href="<?php echo BASE_URL; ?>admin/forgot-password" class="text-gold-accent text-decoration-none">Reset Key?</a>
            </div>

            <button type="submit" class="btn btn-gold-solid w-100 py-3 uppercase tracking-wider small fw-bold font-cinzel">
                Access System
            </button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
