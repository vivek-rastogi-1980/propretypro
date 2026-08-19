<?php
use App\Helpers\CSRFHelper;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Security Key | LuxeHaven Estates</title>
    
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
            <span class="text-warning display-6 mb-3 d-inline-block"><i class="fa-solid fa-lock-open"></i></span>
            <h3 class="font-cinzel text-white fw-bold mb-1">Key Reset</h3>
            <p class="text-secondary small tracking-widest uppercase mb-0">Password Retrieval Console</p>
        </div>

        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success border-0 small text-center p-3 mb-4 rounded-3 text-white" style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.25) !important;">
                <i class="fa-solid fa-circle-check me-2 text-success"></i><?php echo $successMessage; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors['email'])): ?>
            <div class="alert alert-danger border-0 small text-center p-3 mb-4 rounded-3 text-white" style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.25) !important;">
                <i class="fa-solid fa-triangle-exclamation me-2 text-danger"></i><?php echo $errors['email']; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo BASE_URL; ?>admin/forgot-password" method="POST">
            <?php echo CSRFHelper::getTokenField(); ?>

            <div class="mb-4">
                <label class="form-label text-secondary small fw-bold"><i class="fa-solid fa-envelope me-2"></i>Administrator Email</label>
                <input type="email" name="email" class="form-control luxury-input-text" required placeholder="e.g. admin@luxehavenestates.com">
            </div>

            <button type="submit" class="btn btn-gold-solid w-100 py-3 uppercase tracking-wider small fw-bold font-cinzel mb-3">
                Request Reset Key
            </button>

            <div class="text-center">
                <a href="<?php echo BASE_URL; ?>admin/login" class="text-secondary small text-decoration-none"><i class="fa-solid fa-arrow-left-long me-2"></i>Return to Login</a>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
