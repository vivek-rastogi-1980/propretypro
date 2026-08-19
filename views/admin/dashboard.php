
<div class="d-flex justify-content-between align-items-center mb-5 pb-3 border-bottom border-secondary border-opacity-10">
    <div>
        <h1 class="display-6 text-white fw-bold font-cinzel mb-1">Dashboard</h1>
        <p class="text-secondary small mb-0">System metrics and quick operations control panel</p>
    </div>
    <div class="text-white text-end d-none d-sm-block">
        <span class="badge bg-gold-accent text-dark px-3 py-2 rounded-pill font-cinzel fw-bold"><i class="fa-solid fa-clock me-2"></i><?php echo date('l, M j, Y'); ?></span>
    </div>
</div>

<!-- Stats widgets -->
<div class="row g-4 mb-5">
    <div class="col-xl-3 col-sm-6">
        <div class="glass-card-admin-stat p-4 rounded-4 border-secondary border-opacity-15 text-white d-flex align-items-center">
            <div class="admin-stat-icon-luxe bg-primary-luxe"><i class="fa-solid fa-hotel text-primary"></i></div>
            <div class="ms-3">
                <h4 class="font-cinzel text-white fw-bold mb-0"><?php echo $stats['properties']; ?></h4>
                <span class="text-secondary small uppercase tracking-wider">Properties</span>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="glass-card-admin-stat p-4 rounded-4 border-secondary border-opacity-15 text-white d-flex align-items-center">
            <div class="admin-stat-icon-luxe bg-warning-luxe"><i class="fa-solid fa-award text-warning"></i></div>
            <div class="ms-3">
                <h4 class="font-cinzel text-white fw-bold mb-0"><?php echo $stats['featured']; ?></h4>
                <span class="text-secondary small uppercase tracking-wider">Featured</span>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="glass-card-admin-stat p-4 rounded-4 border-secondary border-opacity-15 text-white d-flex align-items-center">
            <div class="admin-stat-icon-luxe bg-success-luxe"><i class="fa-solid fa-message text-success"></i></div>
            <div class="ms-3">
                <h4 class="font-cinzel text-white fw-bold mb-0"><?php echo $stats['enquiries']; ?></h4>
                <span class="text-secondary small uppercase tracking-wider">Enquiries</span>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="glass-card-admin-stat p-4 rounded-4 border-secondary border-opacity-15 text-white d-flex align-items-center">
            <div class="admin-stat-icon-luxe bg-danger-luxe"><i class="fa-solid fa-bell text-danger"></i></div>
            <div class="ms-3">
                <h4 class="font-cinzel text-white fw-bold mb-0"><?php echo $stats['unread']; ?></h4>
                <span class="text-secondary small uppercase tracking-wider">Unread Msg</span>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Quick Actions -->
<div class="row g-4 mb-5">
    <!-- Chart.js widget -->
    <div class="col-lg-8">
        <div class="glass-card-dark p-4 rounded-4 border-secondary border-opacity-15">
            <h5 class="font-cinzel text-white fw-bold mb-4"><i class="fa-solid fa-chart-simple text-warning me-2"></i>Performance Statistics</h5>
            <div style="height: 300px; position: relative;">
                <canvas id="adminStatsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions Panel -->
    <div class="col-lg-4">
        <div class="glass-card-dark p-4 rounded-4 border-secondary border-opacity-15 h-100">
            <h5 class="font-cinzel text-white fw-bold mb-4"><i class="fa-solid fa-rocket text-warning me-2"></i>Quick Actions</h5>
            <div class="d-flex flex-column gap-3">
                <a href="<?php echo BASE_URL; ?>admin/properties/create" class="btn btn-premium py-3 text-start px-4 d-flex align-items-center justify-content-between">
                    <span><i class="fa-solid fa-plus text-warning me-3"></i>Add New Property</span>
                    <i class="fa-solid fa-chevron-right text-secondary small"></i>
                </a>
                <a href="<?php echo BASE_URL; ?>admin/enquiries" class="btn btn-premium py-3 text-start px-4 d-flex align-items-center justify-content-between">
                    <span><i class="fa-solid fa-envelope-open-text text-warning me-3"></i>View Enquiries</span>
                    <i class="fa-solid fa-chevron-right text-secondary small"></i>
                </a>
                <a href="<?php echo BASE_URL; ?>admin/media" class="btn btn-premium py-3 text-start px-4 d-flex align-items-center justify-content-between">
                    <span><i class="fa-solid fa-photo-film text-warning me-3"></i>Browse Media</span>
                    <i class="fa-solid fa-chevron-right text-secondary small"></i>
                </a>
                <a href="<?php echo BASE_URL; ?>admin/settings" class="btn btn-premium py-3 text-start px-4 d-flex align-items-center justify-content-between">
                    <span><i class="fa-solid fa-gears text-warning me-3"></i>System Settings</span>
                    <i class="fa-solid fa-chevron-right text-secondary small"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Recent lists -->
<div class="row g-4">
    <!-- Recent Enquiries -->
    <div class="col-lg-6">
        <div class="glass-card-dark p-4 rounded-4 border-secondary border-opacity-15 h-100">
            <h5 class="font-cinzel text-white fw-bold mb-4 d-flex justify-content-between align-items-center">
                <span><i class="fa-solid fa-envelope-open-text text-warning me-2"></i>Recent Messages</span>
                <a href="<?php echo BASE_URL; ?>admin/enquiries" class="text-gold-accent small text-decoration-none" style="font-size: 11px;">View All</a>
            </h5>
            <div class="table-responsive">
                <table class="table table-dark table-hover table-borderless align-middle mb-0">
                    <thead>
                        <tr class="text-secondary small uppercase border-bottom border-secondary border-opacity-15">
                            <th>Sender</th>
                            <th>Property</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentEnquiries)): ?>
                            <tr>
                                <td colspan="3" class="text-center text-secondary small py-4">No recent messages</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentEnquiries as $enq): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-white"><?php echo htmlspecialchars($enq['name']); ?></div>
                                        <div class="text-secondary fs-xs"><?php echo htmlspecialchars($enq['email']); ?></div>
                                    </td>
                                    <td class="text-light-muted small"><?php echo htmlspecialchars($enq['property_title'] ?? 'General Contact'); ?></td>
                                    <td>
                                        <span class="badge <?php echo $enq['is_read'] ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning'; ?> rounded-pill px-2 py-1" style="font-size: 10px;">
                                            <?php echo $enq['is_read'] ? 'Read' : 'Unread'; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Listings -->
    <div class="col-lg-6">
        <div class="glass-card-dark p-4 rounded-4 border-secondary border-opacity-15 h-100">
            <h5 class="font-cinzel text-white fw-bold mb-4 d-flex justify-content-between align-items-center">
                <span><i class="fa-solid fa-hotel text-warning me-2"></i>Recent Listings</span>
                <a href="<?php echo BASE_URL; ?>admin/properties" class="text-gold-accent small text-decoration-none" style="font-size: 11px;">View All</a>
            </h5>
            <div class="table-responsive">
                <table class="table table-dark table-hover table-borderless align-middle mb-0">
                    <thead>
                        <tr class="text-secondary small uppercase border-bottom border-secondary border-opacity-15">
                            <th>Property</th>
                            <th>Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentProperties)): ?>
                            <tr>
                                <td colspan="3" class="text-center text-secondary small py-4">No properties listed yet</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentProperties as $prop): ?>
                                <tr>
                                    <td class="d-flex align-items-center">
                                        <div class="rounded-3 overflow-hidden me-3" style="width: 50px; height: 50px; border: 1px solid rgba(255,255,255,0.1);">
                                            <img src="<?php echo BASE_URL . ($prop['image_path'] ?? 'assets/images/default_property.png'); ?>" class="w-100 h-100 object-fit-cover">
                                        </div>
                                        <div>
                                            <div class="fw-bold text-white small"><?php echo htmlspecialchars($prop['title']); ?></div>
                                            <div class="text-secondary fs-xs"><?php echo htmlspecialchars($prop['location']); ?></div>
                                        </div>
                                    </td>
                                    <td class="text-warning font-cinzel small fw-bold">$<?php echo number_format($prop['price']); ?></td>
                                    <td>
                                        <span class="badge <?php echo $prop['is_published'] ? 'bg-success' : 'bg-secondary'; ?> rounded-pill" style="font-size: 10px;">
                                            <?php echo $prop['is_published'] ? 'Published' : 'Draft'; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Setup Chart.js trend simulation
    const ctx = document.getElementById('adminStatsChart').getContext('2d');
    const statsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [{
                label: 'Enquiries Received',
                data: [12, 19, 15, 25, 32, 28, 45],
                borderColor: '#2563EB',
                backgroundColor: 'rgba(37, 99, 235, 0.08)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }, {
                label: 'Property Views (x10)',
                data: [8, 12, 10, 18, 22, 25, 38],
                borderColor: '#F59E0B',
                backgroundColor: 'rgba(245, 158, 11, 0.05)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: { color: '#E2E8F0', font: { family: 'Outfit' } }
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                    ticks: { color: '#64748B', font: { family: 'Outfit' } }
                },
                y: {
                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                    ticks: { color: '#64748B', font: { family: 'Outfit' } }
                }
            }
        }
    });
});
</script>

