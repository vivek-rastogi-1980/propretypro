<?php
use App\Helpers\CSRFHelper;
?>

<div class="d-flex justify-content-between align-items-center mb-5 pb-3 border-bottom border-secondary border-opacity-10">
    <div>
        <h1 class="display-6 text-white fw-bold font-cinzel mb-1">Media Library</h1>
        <p class="text-secondary small mb-0">Browse, preview, copy paths, and delete uploaded files or PDF brochures</p>
    </div>
</div>

<!-- Search Bar -->
<div class="glass-card-dark p-4 rounded-4 border-secondary border-opacity-15 mb-4">
    <form action="<?php echo BASE_URL; ?>admin/media" method="GET">
        <div class="row g-3">
            <div class="col-md-10">
                <input type="text" name="search" class="form-control luxury-input-text-sm" placeholder="Search files by name..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-premium w-100 py-2 fs-6"><i class="fa-solid fa-magnifying-glass me-2"></i>Search</button>
            </div>
        </div>
    </form>
</div>

<!-- Gallery Grid -->
<div class="glass-card-dark p-4 rounded-4 border-secondary border-opacity-15">
    <?php if (empty($mediaFiles)): ?>
        <div class="text-center text-secondary small py-5">
            <i class="fa-solid fa-photo-film text-warning display-4 mb-3"></i>
            <h5 class="text-white font-cinzel">No Media Uploads Found</h5>
            <p>Once you upload properties images, PDFs, or floor plans, they will appear here.</p>
        </div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($mediaFiles as $file): ?>
                <div class="col-xl-3 col-lg-4 col-sm-6 media-item-card-wrapper" id="media-card-<?php echo md5($file['relative_path']); ?>">
                    <div class="media-luxury-card rounded-3 overflow-hidden glass-card-dark border-secondary border-opacity-10 position-relative" style="height: 250px;">
                        
                        <!-- Thumbnail Preview -->
                        <div class="media-preview-holder flex-center bg-black bg-opacity-30 position-relative" style="height: 160px;">
                            <?php if ($file['type'] === 'image'): ?>
                                <img src="<?php echo $file['url']; ?>" class="w-100 h-100 object-fit-cover">
                            <?php elseif ($file['type'] === 'pdf'): ?>
                                <div class="text-danger display-5 text-center"><i class="fa-solid fa-file-pdf"></i></div>
                            <?php elseif ($file['type'] === 'video'): ?>
                                <div class="text-primary display-5 text-center"><i class="fa-solid fa-video"></i></div>
                            <?php else: ?>
                                <div class="text-secondary display-5 text-center"><i class="fa-solid fa-file-invoice"></i></div>
                            <?php endif; ?>
                            
                            <!-- Overlay Hover Action buttons -->
                            <div class="media-hover-overlay position-absolute w-100 h-100 top-0 start-0 flex-center bg-black bg-opacity-60" style="opacity: 0; transition: opacity 0.2s;">
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-warning copy-media-path-btn" data-path="<?php echo htmlspecialchars($file['relative_path']); ?>" title="Copy relative path"><i class="fa-solid fa-copy"></i></button>
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-media-file-btn" data-path="<?php echo htmlspecialchars($file['relative_path']); ?>" data-url="<?php echo BASE_URL; ?>admin/media/delete" title="Delete permanently"><i class="fa-solid fa-trash-can"></i></button>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Details -->
                        <div class="p-3 text-white">
                            <div class="fw-bold small text-truncate" title="<?php echo htmlspecialchars($file['name']); ?>"><?php echo htmlspecialchars($file['name']); ?></div>
                            <div class="d-flex justify-content-between align-items-center mt-2 text-secondary fs-xs">
                                <span><?php echo round($file['size'] / 1024, 1); ?> KB</span>
                                <span><?php echo date('M j, Y', strtotime($file['date'])); ?></span>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- JS Code for copy/delete media actions -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Copy path to clipboard
    const copyBtns = document.querySelectorAll('.copy-media-path-btn');
    copyBtns.forEach(btn => {
        btn.onclick = function () {
            const path = btn.getAttribute('data-path');
            navigator.clipboard.writeText(path).then(() => {
                alert('Relative path copied to clipboard: ' + path);
            }).catch(err => {
                console.error('Failed to copy text: ', err);
            });
        };
    });

    // Delete media file via AJAX
    const deleteBtns = document.querySelectorAll('.delete-media-file-btn');
    deleteBtns.forEach(btn => {
        btn.onclick = function () {
            if (!confirm('Are you sure you want to delete this file? This action is permanent and cannot be undone.')) {
                return;
            }

            const path = btn.getAttribute('data-path');
            const url = btn.getAttribute('data-url');
            const cardId = 'media-card-' + btoa(path).replace(/=/g, ''); // Simple hash matching selector

            // Fetch CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const formData = new FormData();
            formData.append('file_path', path);
            formData.append('csrf_token', csrfToken);

            fetch(url, {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Remove card container from screen
                    const card = document.querySelector('[id^="media-card-"]'); // Select appropriate matching card
                    window.location.reload(); // Simple refresh to clean lists
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Failed to process deletion request.');
            });
        };
    });
});
</script>

