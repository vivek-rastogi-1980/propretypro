<?php
use App\Helpers\CSRFHelper;

$companyName = htmlspecialchars($settings['company_name'] ?? '');
$phone = htmlspecialchars($settings['company_phone'] ?? '');
$email = htmlspecialchars($settings['company_email'] ?? '');
$whatsapp = htmlspecialchars($settings['whatsapp_number'] ?? '');
$address = htmlspecialchars($settings['office_address'] ?? '');

$facebook = htmlspecialchars($settings['social_facebook'] ?? '');
$instagram = htmlspecialchars($settings['social_instagram'] ?? '');
$twitter = htmlspecialchars($settings['social_twitter'] ?? '');
$linkedin = htmlspecialchars($settings['social_linkedin'] ?? '');

$footerContent = htmlspecialchars($settings['footer_content'] ?? '');

$seoTitle = htmlspecialchars($settings['seo_title'] ?? '');
$seoDesc = htmlspecialchars($settings['seo_meta_description'] ?? '');
$seoKeywords = htmlspecialchars($settings['seo_meta_keywords'] ?? '');

$logo = htmlspecialchars($settings['company_logo'] ?? '');
$favicon = htmlspecialchars($settings['company_favicon'] ?? '');
?>

<div class="row mb-4">
    <div class="col-12">
        <h1 class="fw-bold mb-1">Website Settings</h1>
        <p class="text-muted mb-0">Configure company metadata, social links, logos, and global SEO attributes.</p>
    </div>
</div>

<!-- Alert messages -->
<?php if (isset($_SESSION['settings_success'])): ?>
    <div class="alert alert-success border-0 glass-card p-3 mb-4 animated-fade-in" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2) !important;">
        <i class="bi bi-check-circle-fill me-2 text-success"></i> <?php echo $_SESSION['settings_success']; unset($_SESSION['settings_success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['settings_error'])): ?>
    <div class="alert alert-danger border-0 glass-card p-3 mb-4" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2) !important;">
        <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i> <?php echo $_SESSION['settings_error']; unset($_SESSION['settings_error']); ?>
    </div>
<?php endif; ?>

<form action="<?php echo BASE_URL; ?>admin/settings" method="POST" enctype="multipart/form-data">
    <!-- CSRF Field -->
    <?php echo CSRFHelper::getField(); ?>

    <div class="row g-4">
        <!-- Column 1: Company Profile and Branding -->
        <div class="col-lg-6">
            <div class="card border-0 glass-card shadow-sm p-4 h-100">
                <h4 class="fw-bold mb-4 text-gradient"><i class="bi bi-building me-2"></i>Company Details</h4>
                
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Company Name</label>
                    <input type="text" name="company_name" class="form-control" value="<?php echo $companyName; ?>" required>
                </div>
                
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">Phone Number</label>
                        <input type="text" name="company_phone" class="form-control" value="<?php echo $phone; ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">WhatsApp Number (with country code)</label>
                        <input type="text" name="whatsapp_number" class="form-control" value="<?php echo $whatsapp; ?>" placeholder="e.g. 15551234567">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Email Address</label>
                    <input type="email" name="company_email" class="form-control" value="<?php echo $email; ?>">
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted">Office Address</label>
                    <textarea name="office_address" rows="3" class="form-control"><?php echo $address; ?></textarea>
                </div>

                <h4 class="fw-bold mb-3 mt-2 text-gradient"><i class="bi bi-image me-2"></i>Branding Assets</h4>
                <div class="row g-3 mb-2">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">Upload Logo (Max 8MB, JPG/PNG/WEBP/HEIC/SVG)</label>
                        <input type="file" name="company_logo" class="form-control mb-2" accept="image/*,.heic,.heif">
                        <?php if ($logo): ?>
                            <div class="p-2 border rounded text-center bg-white" style="max-width: 150px;">
                                <img src="<?php echo BASE_URL . $logo; ?>" alt="Company Logo" class="img-fluid" style="max-height: 40px;">
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">Upload Favicon (Max 8MB, PNG/ICO)</label>
                        <input type="file" name="company_favicon" class="form-control mb-2" accept="image/png,image/x-icon,image/vnd.microsoft.icon,.ico,.png">
                        <?php if ($favicon): ?>
                            <div class="p-2 border rounded text-center bg-white" style="max-width: 80px;">
                                <img src="<?php echo BASE_URL . $favicon; ?>" alt="Favicon" class="img-fluid" style="max-height: 25px;">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Column 2: SEO Settings, Social & Footer -->
        <div class="col-lg-6">
            <div class="card border-0 glass-card shadow-sm p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <h4 class="fw-bold mb-4 text-gradient"><i class="bi bi-search me-2"></i>SEO configurations</h4>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Meta Web Title</label>
                        <input type="text" name="seo_title" class="form-control" value="<?php echo $seoTitle; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Meta Description</label>
                        <textarea name="seo_meta_description" rows="3" class="form-control" required><?php echo $seoDesc; ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">Meta Keywords (comma separated)</label>
                        <input type="text" name="seo_meta_keywords" class="form-control" value="<?php echo $seoKeywords; ?>" required>
                    </div>

                    <h4 class="fw-bold mb-3 text-gradient"><i class="bi bi-share me-2"></i>Social Links</h4>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Facebook URL</label>
                            <input type="url" name="social_facebook" class="form-control" value="<?php echo $facebook; ?>" placeholder="https://facebook.com/...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Instagram URL</label>
                            <input type="url" name="social_instagram" class="form-control" value="<?php echo $instagram; ?>" placeholder="https://instagram.com/...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Twitter URL</label>
                            <input type="url" name="social_twitter" class="form-control" value="<?php echo $twitter; ?>" placeholder="https://twitter.com/...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">LinkedIn URL</label>
                            <input type="url" name="social_linkedin" class="form-control" value="<?php echo $linkedin; ?>" placeholder="https://linkedin.com/company/...">
                        </div>
                    </div>

                    <h4 class="fw-bold mb-3 text-gradient"><i class="bi bi-layout-text-window me-2"></i>Footer details</h4>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Footer Text Content</label>
                        <input type="text" name="footer_content" class="form-control" value="<?php echo $footerContent; ?>">
                    </div>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-premium py-3 rounded-3"><i class="bi bi-save me-2"></i>Save Configuration settings</button>
                </div>
            </div>
        </div>
    </div>
</form>
