<?php
use App\Helpers\CSRFHelper;
?>

<div class="d-flex justify-content-between align-items-center mb-5 pb-3 border-bottom border-secondary border-opacity-10">
    <div>
        <h1 class="display-6 text-white fw-bold font-cinzel mb-1">Customer Enquiries</h1>
        <p class="text-secondary small mb-0">Review requests, schedule viewings, and respond to clients</p>
    </div>
    <div>
        <!-- Export to CSV Button -->
        <a href="<?php echo BASE_URL; ?>admin/enquiries/export?search=<?php echo urlencode($search ?? ''); ?>" class="btn btn-gold-solid px-4 py-2 small fw-bold font-cinzel"><i class="fa-solid fa-file-csv me-2"></i>Export CSV</a>
    </div>
</div>

<!-- Search Panel -->
<div class="glass-card-dark p-4 rounded-4 border-secondary border-opacity-15 mb-4">
    <form action="<?php echo BASE_URL; ?>admin/enquiries" method="GET">
        <div class="row g-3">
            <div class="col-md-10">
                <input type="text" name="search" class="form-control luxury-input-text-sm" placeholder="Search by name, email, phone, message content..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
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
                    <th>Client Details</th>
                    <th>Property Context</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($enquiries)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-secondary small py-5">No enquiries matched</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($enquiries as $enq): ?>
                        <tr data-enquiry-id="<?php echo $enq['id']; ?>">
                            <td>
                                <div class="fw-bold text-white"><?php echo htmlspecialchars($enq['name']); ?></div>
                                <div class="text-secondary fs-xs mb-1"><i class="fa-solid fa-envelope me-1"></i><?php echo htmlspecialchars($enq['email']); ?></div>
                                <?php if (!empty($enq['phone'])): ?>
                                    <div class="text-secondary fs-xs"><i class="fa-solid fa-phone me-1"></i><?php echo htmlspecialchars($enq['phone']); ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($enq['property_title'])): ?>
                                    <a href="<?php echo BASE_URL; ?>property/<?php echo $enq['property_slug']; ?>" target="_blank" class="text-warning small text-decoration-none fw-semibold">
                                        <?php echo htmlspecialchars($enq['property_title']); ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-secondary small">General Inquiry</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-light-muted small" style="max-width: 300px; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $enq['id']; ?>">
                                <div class="text-truncate" title="Click to view message: <?php echo htmlspecialchars($enq['message']); ?>">
                                    <?php echo htmlspecialchars($enq['message']); ?>
                                </div>
                                <div class="text-secondary fs-xs mt-1"><?php echo date('M j, Y g:i A', strtotime($enq['created_at'])); ?></div>
                            </td>
                            <td>
                                <span class="badge enquiry-status-badge <?php echo $enq['is_read'] ? 'bg-success text-white' : 'bg-warning text-dark'; ?> rounded-pill px-2 py-1" style="font-size: 10px;">
                                    <?php echo $enq['is_read'] ? 'Read' : 'Unread'; ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <?php if (!$enq['is_read']): ?>
                                        <button type="button" class="btn btn-sm btn-outline-success admin-mark-read-btn" data-id="<?php echo $enq['id']; ?>" data-url="<?php echo BASE_URL; ?>admin/enquiries/mark-read" title="Mark as Read"><i class="fa-solid fa-envelope-open"></i></button>
                                    <?php endif; ?>
                                    
                                    <!-- View Modal Trigger -->
                                    <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $enq['id']; ?>" title="View complete message"><i class="fa-solid fa-eye"></i></button>
                                    
                                    <!-- Reply modal trigger -->
                                    <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#replyModal<?php echo $enq['id']; ?>" title="Reply to client"><i class="fa-solid fa-reply"></i></button>
                                    
                                    <!-- Delete button -->
                                    <button type="button" class="btn btn-sm btn-outline-danger admin-delete-enquiry-btn" data-id="<?php echo $enq['id']; ?>" data-url="<?php echo BASE_URL; ?>admin/enquiries/delete" title="Delete message"><i class="fa-solid fa-trash-can"></i></button>
                                </div>

                                <!-- View Modal -->
                                <div class="modal fade" id="viewModal<?php echo $enq['id']; ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content bg-black text-start border-secondary border-opacity-15">
                                            <div class="modal-header border-bottom border-secondary border-opacity-10">
                                                <h5 class="modal-title font-cinzel text-white">Enquiry Details</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <div class="row g-3 mb-4">
                                                    <div class="col-md-6">
                                                        <span class="text-gold-accent small uppercase tracking-wider fw-bold">Client Name:</span>
                                                        <div class="text-white fw-semibold fs-5"><?php echo htmlspecialchars($enq['name']); ?></div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span class="text-gold-accent small uppercase tracking-wider fw-bold">Date Received:</span>
                                                        <div class="text-secondary"><?php echo date('M j, Y g:i A', strtotime($enq['created_at'])); ?></div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <span class="text-gold-accent small uppercase tracking-wider fw-bold">Email Address:</span>
                                                        <div class="text-white"><a href="mailto:<?php echo $enq['email']; ?>" class="text-decoration-none text-white"><i class="fa-solid fa-envelope me-2 text-warning"></i><?php echo htmlspecialchars($enq['email']); ?></a></div>
                                                    </div>
                                                    <?php if (!empty($enq['phone'])): ?>
                                                        <div class="col-md-6">
                                                            <span class="text-gold-accent small uppercase tracking-wider fw-bold">Phone Number:</span>
                                                            <div class="text-white"><i class="fa-solid fa-phone me-2 text-warning"></i><?php echo htmlspecialchars($enq['phone']); ?></div>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="col-12">
                                                        <span class="text-gold-accent small uppercase tracking-wider fw-bold">Property Context:</span>
                                                        <div>
                                                            <?php if (!empty($enq['property_title'])): ?>
                                                                <a href="<?php echo BASE_URL; ?>property/<?php echo $enq['property_slug']; ?>" target="_blank" class="text-warning text-decoration-none fw-semibold">
                                                                    <?php echo htmlspecialchars($enq['property_title']); ?>
                                                                </a>
                                                            <?php else: ?>
                                                                <span class="text-secondary">General Inquiry</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="border-secondary border-opacity-10 my-4">
                                                <div>
                                                    <span class="text-gold-accent small uppercase tracking-wider fw-bold d-block mb-2">Message:</span>
                                                    <div class="p-4 bg-dark bg-opacity-70 rounded-3 border border-secondary border-opacity-10 text-light-muted lh-lg" style="white-space: pre-wrap;"><?php echo htmlspecialchars($enq['message']); ?></div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top border-secondary border-opacity-10">
                                                <button type="button" class="btn btn-secondary px-4 py-2" data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-gold-solid px-4 py-2" data-bs-toggle="modal" data-bs-target="#replyModal<?php echo $enq['id']; ?>"><i class="fa-solid fa-reply me-2"></i>Reply Now</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reply Modal -->
                                <div class="modal fade" id="replyModal<?php echo $enq['id']; ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content bg-black text-start border-secondary border-opacity-15">
                                            <form class="ajax-reply-form" action="<?php echo BASE_URL; ?>admin/enquiries/reply" method="POST">
                                                <?php echo CSRFHelper::getTokenField(); ?>
                                                <input type="hidden" name="id" value="<?php echo $enq['id']; ?>">
                                                
                                                <div class="modal-header border-bottom border-secondary border-opacity-10">
                                                    <h5 class="modal-title font-cinzel text-white">Reply to <?php echo htmlspecialchars($enq['name']); ?></h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="p-3 bg-dark bg-opacity-70 rounded-3 mb-4 border border-secondary border-opacity-10">
                                                        <span class="text-gold-accent small uppercase tracking-wider fw-bold">Original Message:</span>
                                                        <p class="text-secondary small mb-0 mt-1" style="white-space: pre-wrap;"><?php echo htmlspecialchars($enq['message']); ?></p>
                                                    </div>
                                                    
                                                    <!-- Form fields -->
                                                    <div class="mb-3">
                                                        <label class="form-label text-secondary small fw-bold">Client Email</label>
                                                        <input type="text" class="form-control luxury-input-text-sm bg-dark text-muted" value="<?php echo htmlspecialchars($enq['email']); ?>" readonly>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label text-secondary small fw-bold">Subject *</label>
                                                        <input type="text" name="subject" class="form-control luxury-input-text-sm" required value="Reply from Vigtez Reality regarding <?php echo htmlspecialchars($enq['property_title'] ?? 'General inquiry'); ?>">
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label text-secondary small fw-bold">Your Response *</label>
                                                        <textarea name="message" rows="6" class="form-control luxury-input-text-sm" required placeholder="Type your professional response here..."></textarea>
                                                    </div>

                                                    <div class="reply-response-message my-3" style="display: none;"></div>
                                                </div>
                                                <div class="modal-footer border-top border-secondary border-opacity-10 justify-content-between">
                                                    <div>
                                                        <?php if (!empty($enq['phone'])): ?>
                                                            <?php 
                                                            $waPhone = preg_replace('/[^0-9]/', '', $enq['phone']);
                                                            $waText = rawurlencode("Hi " . $enq['name'] . ", this is Vigtez Reality responding to your inquiry...");
                                                            ?>
                                                            <a href="https://wa.me/<?php echo $waPhone; ?>?text=<?php echo $waText; ?>" target="_blank" class="btn btn-sm btn-outline-success">
                                                                <i class="fa-brands fa-whatsapp me-2"></i>WhatsApp Chat
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="d-flex gap-2">
                                                        <button type="button" class="btn btn-secondary px-4 py-2" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-gold-solid px-4 py-2"><i class="fa-solid fa-paper-plane me-2"></i>Send Email Reply</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
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

