/**
 * Vigtez Reality Estates Luxury Digital Interactions
 */
$(document).ready(function () {
    
    // --- 1. Cinematic Preloader Dismissal ---
    $(window).on('load', function () {
        setTimeout(function() {
            $('#preloader').addClass('fade-out');
        }, 300);
    });

    // Fallback in case window load doesn't fire fast enough
    setTimeout(function() {
        $('#preloader').addClass('fade-out');
    }, 2000);

    // --- 2. Glass Navbar Scroll Effect ---
    $(window).on('scroll', function () {
        if ($(window).scrollTop() > 50) {
            $('.glass-nav').addClass('nav-scrolled');
        } else {
            $('.glass-nav').removeClass('nav-scrolled');
        }
    });


    // --- 3. Lenis Smooth Scroll Initialization ---
    if (typeof Lenis !== 'undefined') {
        const lenis = new Lenis({
            duration: 1.2,
            easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
            direction: 'vertical',
            gestureDirection: 'vertical',
            smooth: true,
            mouseMultiplier: 1,
            smoothTouch: false,
            touchMultiplier: 2,
            infinite: false,
        });

        function raf(time) {
            lenis.raf(time);
            requestAnimationFrame(raf);
        }
        requestAnimationFrame(raf);
    }

    // --- 4. GSAP & ScrollTrigger Animations ---
    if (typeof gsap !== 'undefined') {
        // Register ScrollTrigger plugin
        if (typeof ScrollTrigger !== 'undefined') {
            gsap.registerPlugin(ScrollTrigger);
        }

        // Hero items staggered fade in (graceful degradation using gsap.from)
        gsap.from('.hero-section-cinematic .animated-item', {
            opacity: 0,
            y: 35,
            duration: 1.2,
            stagger: 0.2,
            ease: 'power3.out',
            delay: 0.3
        });

        // Scroll reveals: Left translation
        document.querySelectorAll('.scroll-reveal-left').forEach(el => {
            gsap.fromTo(el, { opacity: 0, x: -60 }, {
                opacity: 1,
                x: 0,
                duration: 1.2,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: el,
                    start: 'top 85%',
                    toggleActions: 'play none none none'
                }
            });
        });

        // Scroll reveals: Right translation
        document.querySelectorAll('.scroll-reveal-right').forEach(el => {
            gsap.fromTo(el, { opacity: 0, x: 60 }, {
                opacity: 1,
                x: 0,
                duration: 1.2,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: el,
                    start: 'top 85%',
                    toggleActions: 'play none none none'
                }
            });
        });

        // Scroll reveals: Simple fade in
        document.querySelectorAll('.scroll-reveal-fade').forEach(el => {
            gsap.fromTo(el, { opacity: 0, y: 30 }, {
                opacity: 1,
                y: 0,
                duration: 1.2,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: el,
                    start: 'top 85%',
                    toggleActions: 'play none none none'
                }
            });
        });

        // Counters animation on scroll
        if (document.querySelector('.stat-scroll-trigger')) {
            gsap.from('.stat-counter', {
                scrollTrigger: {
                    trigger: '.stat-scroll-trigger',
                    start: 'top 80%'
                },
                onStart: function() {
                    $('.stat-counter').each(function () {
                        const $this = $(this);
                        const countTo = parseFloat($this.attr('data-count'));
                        
                        $({ countNum: 0 }).animate({
                            countNum: countTo
                        }, {
                            duration: 2000,
                            easing: 'linear',
                            step: function () {
                                if (countTo % 1 === 0) {
                                    $this.text(Math.floor(this.countNum));
                                } else {
                                    $this.text(this.countNum.toFixed(1));
                                }
                            },
                            complete: function () {
                                $this.text(countTo);
                            }
                        });
                    });
                }
            });
        }
    }

    // --- 5. Swiper Sliders Setup ---
    
    // Full Screen Hero Slideshow
    if (document.querySelector('.hero-main-swiper')) {
        const heroThumbsSwiper = new Swiper('.hero-thumbs-swiper', {
            spaceBetween: 12,
            slidesPerView: 3,
            freeMode: true,
            watchSlidesProgress: true,
        });

        const heroMainSwiper = new Swiper('.hero-main-swiper', {
            spaceBetween: 0,
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            speed: 1200,
            autoplay: {
                delay: 6000,
                disableOnInteraction: false,
            },
            thumbs: {
                swiper: heroThumbsSwiper,
            },
            navigation: {
                nextEl: '.hero-next-btn',
                prevEl: '.hero-prev-btn',
            },
            on: {
                init: function () {
                    updateFraction(this);
                },
                slideChange: function () {
                    updateFraction(this);
                }
            }
        });

        function updateFraction(swiper) {
            const current = swiper.realIndex + 1;
            const total = swiper.slides.length;
            $('.hero-swiper-fraction').text(current + ' / ' + total);
        }
    }

    // Homepage Featured Carousel
    if (document.querySelector('.featured-swiper')) {
        new Swiper('.featured-swiper', {
            slidesPerView: 1,
            spaceBetween: 24,
            loop: true,
            speed: 800,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                }
            }
        });
    }

    // Homepage Testimonials Carousel
    if (document.querySelector('.testimonials-swiper')) {
        new Swiper('.testimonials-swiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            speed: 800,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.testimonials-pagination',
                clickable: true,
            }
        });
    }

    // Detail Page Gallery Carousel
    if (document.querySelector('.detail-gallery-swiper')) {
        new Swiper('.detail-gallery-swiper', {
            slidesPerView: 1,
            loop: true,
            speed: 1000,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.detail-gallery-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            }
        });
    }

    // --- 6. Three.js Floating Gold Dust Particles ---
    if (document.getElementById('hero-three-canvas') && typeof THREE !== 'undefined') {
        try {
            const canvas = document.getElementById('hero-three-canvas');
            const scene = new THREE.Scene();
            
            // Transparent Scene
            const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
            const renderer = new THREE.WebGLRenderer({ canvas: canvas, alpha: true, antialias: true });
            
            renderer.setSize(window.innerWidth, window.innerHeight);
            renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

            // Create particles geometry
            const particlesCount = 250;
            const positions = new Float32Array(particlesCount * 3);
            const velocity = [];

            for (let i = 0; i < particlesCount * 3; i += 3) {
                // Distribute randomly
                positions[i] = (Math.random() - 0.5) * 15;
                positions[i + 1] = (Math.random() - 0.5) * 10;
                positions[i + 2] = (Math.random() - 0.5) * 10;
                
                // Random direction speeds
                velocity.push({
                    x: (Math.random() - 0.5) * 0.003,
                    y: (Math.random() - 0.5) * 0.003,
                    z: (Math.random() - 0.5) * 0.003
                });
            }

            const particleGeometry = new THREE.BufferGeometry();
            particleGeometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));

            // Material (Gold Dust Point style)
            const particleMaterial = new THREE.PointsMaterial({
                size: 0.08,
                color: 0xF59E0B, // Amber gold color
                transparent: true,
                opacity: 0.7,
                blending: THREE.AdditiveBlending
            });

            const particleMesh = new THREE.Points(particleGeometry, particleMaterial);
            scene.add(particleMesh);

            camera.position.z = 5;

            // Animation Loop
            function animate() {
                requestAnimationFrame(animate);

                // Animate points positions slightly
                const positionsArr = particleGeometry.attributes.position.array;
                for (let i = 0; i < particlesCount; i++) {
                    const i3 = i * 3;
                    positionsArr[i3] += velocity[i].x;
                    positionsArr[i3 + 1] += velocity[i].y;
                    positionsArr[i3 + 2] += velocity[i].z;

                    // Simple boundaries checks
                    if (Math.abs(positionsArr[i3]) > 8) velocity[i].x *= -1;
                    if (Math.abs(positionsArr[i3 + 1]) > 5) velocity[i].y *= -1;
                }
                particleGeometry.attributes.position.needsUpdate = true;

                // Rotate entire mesh
                particleMesh.rotation.y += 0.0005;
                particleMesh.rotation.x += 0.0002;

                renderer.render(scene, camera);
            }

            animate();

            // Responsive Resize
            window.addEventListener('resize', () => {
                camera.aspect = window.innerWidth / window.innerHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(window.innerWidth, window.innerHeight);
            });
        } catch (threeError) {
            console.warn("Three.js particle canvas failed to initialize: ", threeError);
            // Hide the failed canvas gracefully
            const canvasEl = document.getElementById('hero-three-canvas');
            if (canvasEl) canvasEl.style.display = 'none';
        }
    }

    // --- 7. Existing Core AJAX Logic (Preserved) ---
    
    // AJAX Enquiry Submissions
    $('.ajax-enquiry-form').on('submit', function (e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const formResponse = form.find('.form-response-message');
        
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').remove();
        formResponse.hide().html('');

        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...');

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function (response) {
                submitBtn.prop('disabled', false).html(submitBtn.data('original-text') || 'Submit Enquiry');
                if (response.success) {
                    formResponse.html(`
                        <div class="alert alert-success border-0 glass-card-dark p-3 animated-fade-in text-white" style="background: rgba(16, 185, 129, 0.15) !important; border: 1px solid rgba(16, 185, 129, 0.25) !important; color: #ffffff !important;">
                            <i class="fa-solid fa-circle-check me-2 text-success"></i> ${response.message}
                        </div>
                    `).fadeIn();
                    form[0].reset();
                }
            },
            error: function (xhr) {
                submitBtn.prop('disabled', false).html(submitBtn.data('original-text') || 'Submit Enquiry');
                
                const response = xhr.responseJSON;
                if (xhr.status === 422 && response && response.errors) {
                    $.each(response.errors, function (field, message) {
                        const input = form.find(`[name="${field}"]`);
                        input.addClass('is-invalid');
                        input.after(`<div class="invalid-feedback fw-bold text-danger mt-1 small">${message}</div>`);
                    });
                } else {
                    const errorMsg = (response && response.message) ? response.message : 'An unexpected error occurred. Please try again later.';
                    formResponse.html(`
                        <div class="alert alert-danger border-0 glass-card-dark p-3" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2) !important;">
                            <i class="fa-solid fa-triangle-exclamation me-2 text-danger"></i> ${errorMsg}
                        </div>
                    `).fadeIn();
                }
            }
        });
    });

    $('.ajax-enquiry-form button[type="submit"]').each(function() {
        $(this).data('original-text', $(this).html());
    });

    // Image Selection Preview with HEIC & 8MB support
    $('#property-images-input').on('change', async function () {
        const previewContainer = $('#image-preview-container');
        previewContainer.html('');
        
        const files = Array.from(this.files || []);
        if (files.length > 20) {
            alert('You can select a maximum of 20 images.');
            this.value = '';
            return;
        }

        const MAX_SIZE = 8 * 1024 * 1024; // 8MB
        const invalidFiles = [];
        const validFiles = [];

        // Check file sizes
        files.forEach(function (file) {
            if (file.size > MAX_SIZE) {
                invalidFiles.push(file.name);
            } else {
                validFiles.push(file);
            }
        });

        if (invalidFiles.length > 0) {
            alert('The following file(s) exceed the 8MB limit and will be skipped:\n- ' + invalidFiles.join('\n- '));
        }

        if (validFiles.length === 0) {
            this.value = '';
            return;
        }

        // Process files for preview and HEIC conversion if supported
        const dt = new DataTransfer();
        for (let i = 0; i < validFiles.length; i++) {
            const file = validFiles[i];
            const isHeic = file.name.match(/\.(heic|heif)$/i) || file.type === 'image/heic' || file.type === 'image/heif';

            if (isHeic && typeof heic2any !== 'undefined') {
                const previewId = 'heic-preview-' + i;
                previewContainer.append(`
                    <div class="position-relative d-inline-block m-1" id="${previewId}">
                        <div class="img-thumbnail d-flex flex-column align-items-center justify-content-center text-center p-1" style="width: 80px; height: 80px; border: 1px solid rgba(255,255,255,0.15); background: rgba(0,0,0,0.3);">
                            <div class="spinner-border spinner-border-sm text-warning mb-1" role="status"></div>
                            <span class="text-xs text-muted" style="font-size: 9px;">HEIC</span>
                        </div>
                    </div>
                `);

                try {
                    const convertedBlob = await heic2any({
                        blob: file,
                        toType: 'image/jpeg',
                        quality: 0.85
                    });
                    const blob = Array.isArray(convertedBlob) ? convertedBlob[0] : convertedBlob;
                    const convertedFile = new File([blob], file.name.replace(/\.(heic|heif)$/i, '.jpg'), { type: 'image/jpeg' });
                    dt.items.add(convertedFile);

                    const previewUrl = URL.createObjectURL(blob);
                    $(`#${previewId}`).html(`
                        <img src="${previewUrl}" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover; border: 1px solid rgba(255,255,255,0.15); background: transparent;">
                    `);
                } catch (err) {
                    console.warn('Client-side HEIC conversion failed, keeping raw HEIC:', err);
                    dt.items.add(file);
                    $(`#${previewId}`).html(`
                        <div class="img-thumbnail d-flex flex-column align-items-center justify-content-center text-center p-1" style="width: 80px; height: 80px; border: 1px solid rgba(255,255,255,0.15); background: rgba(0,0,0,0.3);">
                            <i class="fa-solid fa-file-image text-warning mb-1"></i>
                            <span class="text-xs text-muted" style="font-size: 9px;">HEIC</span>
                        </div>
                    `);
                }
            } else {
                dt.items.add(file);
                if (isHeic) {
                    previewContainer.append(`
                        <div class="position-relative d-inline-block m-1">
                            <div class="img-thumbnail d-flex flex-column align-items-center justify-content-center text-center p-1" style="width: 80px; height: 80px; border: 1px solid rgba(255,255,255,0.15); background: rgba(0,0,0,0.3);">
                                <i class="fa-solid fa-file-image text-warning mb-1"></i>
                                <span class="text-xs text-muted" style="font-size: 9px;">HEIC</span>
                            </div>
                        </div>
                    `);
                } else {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewContainer.append(`
                            <div class="position-relative d-inline-block m-1">
                                <img src="${e.target.result}" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover; border: 1px solid rgba(255,255,255,0.15); background: transparent;">
                            </div>
                        `);
                    };
                    reader.readAsDataURL(file);
                }
            }
        }

        // Update the file input files with converted/validated files if supported
        try {
            this.files = dt.files;
        } catch (e) {
            // Some older browsers might restrict programmatic input.files assignment
        }
    });

    // Admin Action: Mark Enquiry Read
    $('.admin-mark-read-btn').on('click', function () {
        const btn = $(this);
        const enquiryId = btn.data('id');
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        
        btn.prop('disabled', true);

        $.ajax({
            url: btn.data('url'),
            method: 'POST',
            data: {
                id: enquiryId,
                csrf_token: csrfToken
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    const row = btn.closest('tr');
                    row.find('.enquiry-status-badge')
                       .removeClass('bg-warning text-dark')
                       .addClass('bg-success text-white')
                       .text('Read');
                    btn.remove();
                }
            },
            error: function () {
                btn.prop('disabled', false);
                alert('Failed to update enquiry status.');
            }
        });
    });

    // Admin Action: Auto Mark Read when View Modal opens
    $(document).on('show.bs.modal', '.modal', function () {
        const modal = $(this);
        const idAttr = modal.attr('id');
        if (idAttr && idAttr.startsWith('viewModal')) {
            const enquiryId = idAttr.replace('viewModal', '');
            const row = $(`tr[data-enquiry-id="${enquiryId}"]`);
            const markReadBtn = row.find('.admin-mark-read-btn');
            if (markReadBtn.length > 0 && !markReadBtn.prop('disabled')) {
                markReadBtn.trigger('click');
            }
        }
    });

    // Admin Action: Send Email Reply AJAX
    $(document).on('submit', '.ajax-reply-form', function (e) {
        e.preventDefault();
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const responseMsg = form.find('.reply-response-message');
        
        submitBtn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i>Sending...');
        responseMsg.hide().removeClass('alert alert-success alert-danger');

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function (response) {
                submitBtn.prop('disabled', false).html('<i class="fa-solid fa-paper-plane me-2"></i>Send Email Reply');
                if (response.success) {
                    responseMsg.addClass('alert alert-success').text(response.message).show();
                    form.find('textarea[name="message"]').val('');
                    
                    // Mark status badge as read on screens
                    const enquiryId = form.find('input[name="id"]').val();
                    const row = $(`tr[data-enquiry-id="${enquiryId}"]`);
                    row.find('.enquiry-status-badge')
                       .removeClass('bg-warning text-dark')
                       .addClass('bg-success text-white')
                       .text('Read');
                    row.find('.admin-mark-read-btn').remove();

                    setTimeout(function() {
                        const modalEl = form.closest('.modal')[0];
                        if (modalEl) {
                            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                                const modalInstance = bootstrap.Modal.getInstance(modalEl) || bootstrap.Modal.getOrCreateInstance(modalEl);
                                modalInstance.hide();
                            } else if (typeof $(modalEl).modal === 'function') {
                                $(modalEl).modal('hide');
                            }
                        }
                        responseMsg.hide().removeClass('alert alert-success alert-danger');
                    }, 1800);
                } else {
                    responseMsg.addClass('alert alert-danger').text(response.message || 'Failed to send reply.').show();
                }
            },
            error: function (xhr) {
                submitBtn.prop('disabled', false).html('<i class="fa-solid fa-paper-plane me-2"></i>Send Email Reply');
                let errMsg = 'Failed to send reply. Server error.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errMsg = xhr.responseJSON.message;
                }
                responseMsg.addClass('alert alert-danger').text(errMsg).show();
            }
        });
    });

    // Admin Action: Delete Enquiry
    $('.admin-delete-enquiry-btn').on('click', function () {
        if (!confirm('Are you sure you want to delete this enquiry?')) {
            return;
        }

        const btn = $(this);
        const enquiryId = btn.data('id');
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        const row = btn.closest('tr');

        $.ajax({
            url: btn.data('url'),
            method: 'POST',
            data: {
                id: enquiryId,
                csrf_token: csrfToken
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    row.fadeOut(400, function () {
                        row.remove();
                        if ($('tbody tr').length === 0) {
                            location.reload();
                        }
                    });
                }
            },
            error: function () {
                alert('Failed to delete enquiry.');
            }
        });
    });

    // Admin Action: Delete Gallery Image
    $('.admin-delete-gallery-img').on('click', function (e) {
        e.preventDefault();
        
        if (!confirm('Are you sure you want to delete this image from the gallery?')) {
            return;
        }

        const btn = $(this);
        const imageId = btn.data('image-id');
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        const container = btn.closest('.admin-gallery-item-wrapper');

        $.ajax({
            url: btn.data('action'),
            method: 'POST',
            data: {
                image_id: imageId,
                csrf_token: csrfToken
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    container.fadeOut(300, function () {
                        container.remove();
                    });
                }
            },
            error: function (xhr) {
                const response = xhr.responseJSON;
                alert(response && response.message ? response.message : 'Failed to delete gallery image.');
            }
        });
    });

    // Admin Action: Set Featured Image
    $('.admin-set-featured-img').on('click', function (e) {
        e.preventDefault();

        const btn = $(this);
        const propertyId = btn.data('property-id');
        const imageId = btn.data('image-id');
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: btn.data('action'),
            method: 'POST',
            data: {
                property_id: propertyId,
                image_id: imageId,
                csrf_token: csrfToken
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('.admin-gallery-badge').remove();
                    btn.closest('.admin-gallery-item').append('<span class="admin-gallery-badge"><i class="fa-solid fa-star me-1"></i>Featured</span>');
                    alert('Featured image updated successfully.');
                    window.location.reload(); // Simple refresh to align visual highlights
                }
            },
            error: function (xhr) {
                const response = xhr.responseJSON;
                alert(response && response.message ? response.message : 'Failed to update featured image.');
            }
        });
    });

    // Admin Action: Set Slider Image
    $('.admin-set-slider-img').on('click', function (e) {
        e.preventDefault();

        const btn = $(this);
        const propertyId = btn.data('property-id');
        const imageId = btn.data('image-id');
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: btn.data('action'),
            method: 'POST',
            data: {
                property_id: propertyId,
                image_id: imageId,
                csrf_token: csrfToken
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    alert('Slider image updated successfully.');
                    window.location.reload();
                }
            },
            error: function (xhr) {
                const response = xhr.responseJSON;
                alert(response && response.message ? response.message : 'Failed to update slider image.');
            }
        });
    });

    // Dynamic Slug Generator
    $('#property-title-input').on('blur', function() {
        const titleInput = $(this);
        const slugInput = $('#property-slug-input');
        
        if (slugInput.val().trim() === '') {
            let slug = titleInput.val()
                .toLowerCase()
                .replace(/[^a-z0-9 -]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
            
            slugInput.val(slug);
        }
    });

    // --- Admin Theme Toggle ---
    function updateThemeUI(theme) {
        const toggleBtn = $('#admin-theme-toggle');
        const mobileToggleBtn = $('#admin-mobile-theme-toggle');
        
        if (theme === 'light') {
            $('html').addClass('admin-light-theme');
            if (toggleBtn.length) {
                toggleBtn.html('<i class="fa-solid fa-moon me-2 text-primary"></i><span>Dark Mode</span>');
                toggleBtn.removeClass('btn-outline-secondary').addClass('btn-outline-dark');
            }
            if (mobileToggleBtn.length) {
                mobileToggleBtn.html('<i class="fa-solid fa-moon text-primary"></i>');
            }
        } else {
            $('html').removeClass('admin-light-theme');
            if (toggleBtn.length) {
                toggleBtn.html('<i class="fa-solid fa-sun me-2 text-warning"></i><span>Light Mode</span>');
                toggleBtn.removeClass('btn-outline-dark').addClass('btn-outline-secondary');
            }
            if (mobileToggleBtn.length) {
                mobileToggleBtn.html('<i class="fa-solid fa-sun text-warning"></i>');
            }
        }
    }

    // Only apply theme management on Admin panel pages
    if ($('body').hasClass('admin-dark-body')) {
        const currentTheme = localStorage.getItem('admin-theme') || 'dark';
        updateThemeUI(currentTheme);

        $(document).on('click', '#admin-theme-toggle, #admin-mobile-theme-toggle', function (e) {
            e.preventDefault();
            const newTheme = $('html').hasClass('admin-light-theme') ? 'dark' : 'light';
            localStorage.setItem('admin-theme', newTheme);
            updateThemeUI(newTheme);
        });
    } else {
        // Ensure admin light theme class is not present on public pages
        $('html').removeClass('admin-light-theme');

        // --- Frontend Theme Switcher ---
        const activeTheme = localStorage.getItem('frontend-theme') || 'dark';
        applyFrontendTheme(activeTheme);

        // Append the Floating Theme Switcher HTML dynamically to the body
        const switcherHTML = `
            <div class="theme-switcher-wrapper">
                <button class="theme-switcher-btn" id="theme-switcher-toggle" aria-label="Switch Theme">
                    <i class="fa-solid fa-palette"></i>
                </button>
                <div class="theme-switcher-panel" id="theme-switcher-panel">
                    <div class="theme-switcher-title">Select Theme</div>
                    <div class="theme-options-list">
                        <button class="theme-option-btn ${activeTheme === 'dark' ? 'active' : ''}" data-theme="dark">
                            <span>Dark Luxe</span>
                            <span class="theme-preview-circle theme-preview-dark"></span>
                        </button>
                        <button class="theme-option-btn ${activeTheme === 'blue-white' ? 'active' : ''}" data-theme="blue-white">
                            <span>Blue & White</span>
                            <span class="theme-preview-circle theme-preview-blue"></span>
                        </button>
                        <button class="theme-option-btn ${activeTheme === 'green' ? 'active' : ''}" data-theme="green">
                            <span>Westin Green</span>
                            <span class="theme-preview-circle theme-preview-green"></span>
                        </button>
                        <button class="theme-option-btn ${activeTheme === 'sand-beige' ? 'active' : ''}" data-theme="sand-beige">
                            <span>Sand & Taupe</span>
                            <span class="theme-preview-circle theme-preview-sand"></span>
                        </button>
                    </div>
                </div>
            </div>
        `;
        $('body').append(switcherHTML);

        // Toggle panel visibility
        $(document).on('click', '#theme-switcher-toggle', function (e) {
            e.stopPropagation();
            $('#theme-switcher-panel').toggleClass('active');
        });

        // Close panel when clicking outside
        $(document).on('click', function (e) {
            if (!$(e.target).closest('.theme-switcher-wrapper').length) {
                $('#theme-switcher-panel').removeClass('active');
            }
        });

        // Handle option click
        $(document).on('click', '.theme-option-btn', function () {
            const selectedTheme = $(this).data('theme');
            $('.theme-option-btn').removeClass('active');
            $(this).addClass('active');
            
            applyFrontendTheme(selectedTheme);
            localStorage.setItem('frontend-theme', selectedTheme);
            
            // Close panel with a slight delay for better transition feel
            setTimeout(function () {
                $('#theme-switcher-panel').removeClass('active');
            }, 300);
        });

        function applyFrontendTheme(theme) {
            // Remove previous theme classes
            $('html').removeClass('theme-blue-white theme-green theme-sand-beige');
            
            if (theme === 'blue-white') {
                $('html').addClass('theme-blue-white');
            } else if (theme === 'green') {
                $('html').addClass('theme-green');
            } else if (theme === 'sand-beige') {
                $('html').addClass('theme-sand-beige');
            }
        }
    }

    // --- Video Modal Playback Control ---
    // Instantly stops YouTube/Vimeo audio and video playback whenever a modal is closed
    $(document).on('hidden.bs.modal hide.bs.modal', '.modal', function () {
        const modal = $(this);
        
        // Handle iframe embeds (YouTube, Vimeo, etc.)
        modal.find('iframe').each(function () {
            const iframe = $(this);
            const currentSrc = iframe.attr('src');
            if (currentSrc) {
                // Try postMessage to pause if supported
                try {
                    this.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
                    this.contentWindow.postMessage('{"event":"command","func":"stopVideo","args":""}', '*');
                } catch (e) {}
                
                // Reset source to fully terminate background audio
                iframe.attr('src', '');
                iframe.attr('src', currentSrc);
            }
        });

        // Handle HTML5 video elements
        modal.find('video').each(function () {
            this.pause();
            this.currentTime = 0;
        });
    });

});

