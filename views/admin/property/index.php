<?php
use App\Helpers\CSRFHelper;
?>

<div class="d-flex justify-content-between align-items-center mb-5 pb-3 border-bottom border-secondary border-opacity-10">
    <div>
        <h1 class="display-6 text-white fw-bold font-cinzel mb-1">Properties</h1>
        <p class="text-secondary small mb-0">Manage premium portfolios, images, brochures, and status configurations</p>
    </div>
    <div>
        <a href="<?php echo BASE_URL; ?>admin/properties/create" class="btn btn-gold-solid px-4 py-2 small fw-bold font-cinzel"><i class="fa-solid fa-plus me-2"></i>Add Listing</a>
    </div>
</div>

<!-- Response notifications -->
<?php if (isset($_SESSION['property_success'])): ?>
    <div class="alert alert-success border-0 small p-3 mb-4 rounded-3 text-white glass-card-dark" style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.25) !important;">
        <i class="fa-solid fa-circle-check me-2 text-success"></i><?php echo $_SESSION['property_success']; unset($_SESSION['property_success']); ?>
    </div>
<?php endif; ?>
<?php if (isset($_SESSION['property_error'])): ?>
    <div class="alert alert-danger border-0 small p-3 mb-4 rounded-3 text-white glass-card-dark" style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.25) !important;">
        <i class="fa-solid fa-triangle-exclamation me-2 text-danger"></i><?php echo $_SESSION['property_error']; unset($_SESSION['property_error']); ?>
    </div>
<?php endif; ?>
<?php if (isset($_SESSION['property_warning'])): ?>
    <div class="alert alert-warning border-0 small p-3 mb-4 rounded-3 text-white glass-card-dark" style="background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.25) !important;">
        <i class="fa-solid fa-circle-exclamation me-2 text-warning"></i><?php echo $_SESSION['property_warning']; unset($_SESSION['property_warning']); ?>
    </div>
<?php endif; ?>

<!-- Search Bar -->
<div class="glass-card-dark p-4 rounded-4 border-secondary border-opacity-15 mb-4">
    <form action="<?php echo BASE_URL; ?>admin/properties" method="GET">
        <div class="row g-3">
            <div class="col-md-10">
                <input type="text" name="search" class="form-control luxury-input-text-sm" placeholder="Search by title, location, RERA ID..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-premium w-100 py-2 fs-6"><i class="fa-solid fa-magnifying-glass me-2"></i>Filter</button>
            </div>
        </div>
    </form>
</div>

<!-- Table Card -->
<div class="glass-card-dark p-4 rounded-4 border-secondary border-opacity-15">
    <div class="table-responsive">
        <table class="table table-dark table-hover table-borderless align-middle mb-0">
            <thead>
                <tr class="text-secondary small uppercase border-bottom border-secondary border-opacity-15">
                    <th>Property Title</th>
                    <th>Category</th>
                    <th>Budget</th>
                    <th>Mode</th>
                    <th>Availability</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($properties)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-secondary small py-5">No properties matching filters</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($properties as $prop): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-3 overflow-hidden me-3" style="width: 55px; height: 55px; border: 1px solid rgba(255,255,255,0.1);">
                                        <img src="<?php echo BASE_URL . ($prop['image_path'] ?? 'assets/images/default_property.png'); ?>" onerror="this.onerror=null; this.src='<?php echo BASE_URL; ?>assets/images/default_property.png';" class="w-100 h-100 object-fit-cover">
                                    </div>
                                    <div>
                                        <div class="fw-bold text-white d-flex align-items-center gap-2 flex-wrap">
                                            <span><?php echo htmlspecialchars($prop['title']); ?></span>
                                            <?php if ($prop['is_featured']): ?>
                                                <span class="badge bg-primary text-white py-1 px-1.5" style="font-size: 9px;" title="Featured"><i class="fa-solid fa-star text-warning"></i></span>
                                            <?php endif; ?>
                                            <?php if ($prop['in_slider']): ?>
                                                <span class="badge bg-warning text-dark py-1 px-1.5" style="font-size: 9px;" title="Slideshow"><i class="fa-solid fa-images"></i></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-secondary fs-xs"><i class="fa-solid fa-location-dot me-1 text-warning"></i><?php echo htmlspecialchars($prop['location']); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-light-muted small"><?php echo htmlspecialchars($prop['category_name']); ?></td>
                            <td class="text-warning font-cinzel small fw-bold">₹<?php echo number_format($prop['price']); ?></td>
                            <td class="small"><?php echo htmlspecialchars($prop['status']); ?></td>
                            <td>
                                <span class="badge <?php 
                                    echo ($prop['availability_status'] === 'Available') ? 'bg-success-subtle text-success' : (($prop['availability_status'] === 'Sold') ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning');
                                ?> rounded-pill" style="font-size: 10px;">
                                    <?php echo htmlspecialchars($prop['availability_status']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge <?php echo $prop['is_published'] ? 'bg-success' : 'bg-secondary'; ?> rounded-pill" style="font-size: 10px;">
                                    <?php echo $prop['is_published'] ? 'Published' : 'Draft'; ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <a href="<?php echo BASE_URL; ?>admin/properties/edit/<?php echo $prop['id']; ?>" class="btn btn-sm btn-outline-light d-flex align-items-center" title="Edit Listing"><i class="fa-solid fa-pen-to-square"></i></a>
                                    
                                    <!-- Duplicate Form -->
                                    <form action="<?php echo BASE_URL; ?>admin/properties/duplicate/<?php echo $prop['id']; ?>" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to duplicate this listing?');">
                                        <?php echo CSRFHelper::getTokenField(); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-warning d-flex align-items-center" title="Duplicate Listing"><i class="fa-solid fa-clone"></i></button>
                                    </form>

                                    <!-- Delete Form -->
                                    <form action="<?php echo BASE_URL; ?>admin/properties/delete/<?php echo $prop['id']; ?>" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this property and all associated media?');">
                                        <?php echo CSRFHelper::getTokenField(); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger d-flex align-items-center" title="Delete Listing"><i class="fa-solid fa-trash-can"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="d-flex justify-content-center mt-4">
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-luxury small">
                    <li class="page-item <?php echo ($currentPage <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>&search=<?php echo urlencode($search); ?>"><i class="fa-solid fa-chevron-left"></i></a>
                    </li>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo ($currentPage == $i) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php echo ($currentPage >= $totalPages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>&search=<?php echo urlencode($search); ?>"><i class="fa-solid fa-chevron-right"></i></a>
                    </li>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>

