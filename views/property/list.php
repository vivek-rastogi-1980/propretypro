<!-- Cinematic Header -->
<section class="luxury-inner-hero position-relative d-flex align-items-center overflow-hidden">
    <div class="inner-hero-bg" style="background-image: url('https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=1200&q=80');"></div>
    <div class="video-overlay-tint"></div>
    
    <div class="container position-relative z-1 text-center py-5">
        <h1 class="display-3 font-cinzel fw-bold" style="color:white">Bespoke Properties</h1>
        <p class="lead mx-auto" style="max-width: 600px; color: white;">Explore our certified portfolio of architectural masterpieces and private sanctuaries.</p>
    </div>
</section>

<!-- Collection Browser -->
<section class="py-5 bg-black text-white">
    <div class="container py-5">
        <div class="row g-4">
            
            <!-- Advanced Sidebar Filters -->
            <div class="col-lg-3">
                <div class="glass-card-dark p-4 rounded-4 border-secondary border-opacity-15 sticky-lg-top" style="top: 100px; z-index: 9;">
                    <h5 class="font-cinzel text-white fw-bold mb-4 d-flex justify-content-between align-items-center">
                        <span><i class="fa-solid fa-sliders text-warning me-2"></i>Filters</span>
                        <a href="<?php echo BASE_URL; ?>properties" class="text-gold-accent small text-decoration-none" style="font-size: 11px;">Reset All</a>
                    </h5>
                    
                    <form action="<?php echo BASE_URL; ?>properties" method="GET" class="row g-3">
                        <div class="col-12">
                            <label class="form-label text-secondary small fw-bold">Keywords</label>
                            <input type="text" name="keywords" class="form-control luxury-input-text-sm" placeholder="e.g. penthouse, pool" value="<?php echo htmlspecialchars($filters['keywords'] ?? ''); ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-secondary small fw-bold">Location</label>
                            <input type="text" name="location" class="form-control luxury-input-text-sm" placeholder="e.g. Malibu, Aspen" value="<?php echo htmlspecialchars($filters['location'] ?? ''); ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-secondary small fw-bold">Category</label>
                            <select name="category" class="form-select luxury-input-text-sm">
                                <option value="">All Categories</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>" <?php echo (isset($filters['category']) && $filters['category'] == $cat['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-secondary small fw-bold">Listing Status</label>
                            <select name="status" class="form-select luxury-input-text-sm">
                                <option value="">All Statuses</option>
                                <option value="For Sale" <?php echo (isset($filters['status']) && $filters['status'] === 'For Sale') ? 'selected' : ''; ?>>For Sale</option>
                                <option value="For Rent" <?php echo (isset($filters['status']) && $filters['status'] === 'For Rent') ? 'selected' : ''; ?>>For Rent</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-secondary small fw-bold">Max Budget</label>
                            <input type="number" name="budget_max" class="form-control luxury-input-text-sm" placeholder="e.g. 5000000" value="<?php echo htmlspecialchars($filters['budget_max'] ?? ''); ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-secondary small fw-bold">Beds (Min)</label>
                            <select name="bedrooms" class="form-select luxury-input-text-sm">
                                <option value="">Any</option>
                                <?php for ($i = 1; $i <= 8; $i++): ?>
                                    <option value="<?php echo $i; ?>" <?php echo (isset($filters['bedrooms']) && $filters['bedrooms'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?>+</option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-secondary small fw-bold">Baths (Min)</label>
                            <select name="bathrooms" class="form-select luxury-input-text-sm">
                                <option value="">Any</option>
                                <?php for ($i = 1; $i <= 8; $i++): ?>
                                    <option value="<?php echo $i; ?>" <?php echo (isset($filters['bathrooms']) && $filters['bathrooms'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?>+</option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-secondary small fw-bold">Min Area (SqFt)</label>
                            <input type="number" name="area" class="form-control luxury-input-text-sm" placeholder="e.g. 2000" value="<?php echo htmlspecialchars($filters['area'] ?? ''); ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-secondary small fw-bold">Construction Status</label>
                            <select name="construction_status" class="form-select luxury-input-text-sm">
                                <option value="">Any</option>
                                <option value="Ready To Move" <?php echo (isset($filters['construction_status']) && $filters['construction_status'] === 'Ready To Move') ? 'selected' : ''; ?>>Ready To Move</option>
                                <option value="Under Construction" <?php echo (isset($filters['construction_status']) && $filters['construction_status'] === 'Under Construction') ? 'selected' : ''; ?>>Under Construction</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-secondary small fw-bold">RERA Registered Number</label>
                            <input type="text" name="rera_number" class="form-control luxury-input-text-sm" placeholder="e.g. RERA123" value="<?php echo htmlspecialchars($filters['rera_number'] ?? ''); ?>">
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-gold-solid w-100 py-3 uppercase tracking-wider small fw-bold">
                                Apply Filters
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Properties Listings Grid -->
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-secondary border-opacity-15">
                    <p class="text-secondary small mb-0">Showing <strong><?php echo $totalProperties; ?></strong> exclusive listings matching filters</p>
                    <div class="d-flex align-items-center">
                        <span class="text-secondary small me-2 d-none d-sm-inline">Sort by:</span>
                        <select class="form-select luxury-input-text-sm py-1 px-3 border-0 bg-transparent text-white" style="width: auto; cursor: pointer;">
                            <option value="newest" class="bg-dark text-white">Newest First</option>
                        </select>
                    </div>
                </div>

                <?php if (empty($properties)): ?>
                    <div class="glass-card-dark p-5 rounded-4 border-secondary border-opacity-15 text-center my-5">
                        <i class="fa-solid fa-hotel text-warning display-4 mb-4"></i>
                        <h4 class="font-cinzel text-white fw-bold">No Listings Matched</h4>
                        <p class="text-light-muted mb-4">No estates matched the specified search filters. Try refining your keywords or budget limits.</p>
                        <a href="<?php echo BASE_URL; ?>properties" class="btn btn-gold-solid px-4 py-2 small">Reset Search</a>
                    </div>
                <?php else: ?>
                    <div class="row g-4">
                        <?php foreach ($properties as $prop): ?>
                            <div class="col-md-6 scroll-reveal-fade">
                                <div class="property-luxury-card rounded-4 overflow-hidden glass-card-dark position-relative h-100">
                                    <span class="property-status-tag"><?php echo htmlspecialchars($prop['status']); ?></span>
                                    <?php if ($prop['is_featured']): ?>
                                        <span class="property-featured-tag"><i class="fa-solid fa-award me-1"></i>FEATURED</span>
                                    <?php endif; ?>
                                    
                                    <div class="property-image-holder" style="height: 240px;">
                                        <img src="<?php echo BASE_URL . ($prop['image_path'] ?? 'assets/images/default_property.png'); ?>" alt="<?php echo htmlspecialchars($prop['title']); ?>" class="w-100 h-100 object-fit-cover">
                                        <div class="image-gradient-shade"></div>
                                        <span class="property-construction-tag"><?php echo htmlspecialchars($prop['construction_status']); ?></span>
                                    </div>
                                    
                                    <div class="property-content-holder p-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-gold-accent uppercase small tracking-widest fw-semibold"><?php echo htmlspecialchars($prop['category_name']); ?></span>
                                            <?php if (!empty($prop['rera_number'])): ?>
                                                <span class="text-secondary small fw-semibold" style="font-size: 10px;"><i class="fa-solid fa-circle-check text-success me-1"></i>RERA: <?php echo htmlspecialchars($prop['rera_number']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <h4 class="font-cinzel text-white fw-bold mb-3"><?php echo htmlspecialchars($prop['title']); ?></h4>
                                        <p class="text-light-muted small mb-4 line-clamp-2"><?php echo htmlspecialchars($prop['short_description']); ?></p>
                                        
                                        <div class="d-flex justify-content-between align-items-center border-top border-secondary border-opacity-15 pt-3">
                                            <h5 class="text-warning font-cinzel mb-0 fw-bold">$<?php echo number_format($prop['price']); ?></h5>
                                            <div class="property-specs text-secondary fs-xs">
                                                <?php if ($prop['bedrooms']): ?>
                                                    <span class="me-3"><i class="fa-solid fa-bed text-gold-accent me-1"></i><?php echo $prop['bedrooms']; ?> Beds</span>
                                                <?php endif; ?>
                                                <span><i class="fa-solid fa-maximize text-gold-accent me-1"></i><?php echo number_format($prop['area']); ?> SqFt</span>
                                            </div>
                                        </div>
                                        <a href="<?php echo BASE_URL; ?>property/<?php echo $prop['slug']; ?>" class="stretched-link"></a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <div class="d-flex justify-content-center mt-5 pt-4">
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-luxury">
                                    <li class="page-item <?php echo ($currentPage <= 1) ? 'disabled' : ''; ?>">
                                        <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $currentPage - 1])); ?>"><i class="fa-solid fa-chevron-left"></i></a>
                                    </li>
                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                        <li class="page-item <?php echo ($currentPage == $i) ? 'active' : ''; ?>">
                                            <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    <li class="page-item <?php echo ($currentPage >= $totalPages) ? 'disabled' : ''; ?>">
                                        <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $currentPage + 1])); ?>"><i class="fa-solid fa-chevron-right"></i></a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>

