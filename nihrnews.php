<?php
include('config/config.php');

// Fetch all newspaper photos from database
$sql = "SELECT * FROM newspaper_photos ORDER BY upload_date DESC";
$query = $dbh->prepare($sql);
$query->execute();
$photos = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html class="no-js" lang="zxx">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Photo Gallery | ICMR-NIIRNCD</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="manifest" href="site.webmanifest">
        <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">

        <!-- CSS here -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
        <link rel="stylesheet" href="assets/css/flaticon.css">
        <link rel="stylesheet" href="assets/css/slicknav.css">
        <link rel="stylesheet" href="assets/css/animate.min.css">
        <link rel="stylesheet" href="assets/css/magnific-popup.css">
        <link rel="stylesheet" href="assets/css/fontawesome-all.min.css">
        <link rel="stylesheet" href="assets/css/themify-icons.css">
        <link rel="stylesheet" href="assets/css/slick.css">
        <link rel="stylesheet" href="assets/css/nice-select.css">
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="stylenav.css">
        <link rel="stylesheet" href="assets/css/responsive.css">
        <link rel="stylesheet" href="assets/datatables/dataTables.bootstrap4.css">

        <style>
            /* Photo Gallery Styles */
            .gallery-section {
                padding: 30px 0;
                background: #f8f9fa;
            }
            
            .gallery-title {
                text-align: center;
            }
            
            .gallery-title h2 {
                font-size: 36px;
                color: #333;
                position: relative;
                display: inline-block;
                padding-bottom: 15px;
            }
            
            .gallery-title h2:after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 50%;
                transform: translateX(-50%);
                width: 80px;
                height: 3px;
                background: #1a76d1;
            }
            
            /* Gallery Grid */
            .gallery-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
                gap: 25px;
                margin-top: 30px;
            }
            
            /* Gallery Item */
            .gallery-item {
                position: relative;
                overflow: hidden;
                border-radius: 12px;
                background: #fff;
                box-shadow: 0 5px 20px rgba(0,0,0,0.08);
                transition: all 0.3s ease;
                cursor: pointer;
            }
            
            .gallery-item:hover {
                transform: translateY(-8px);
                box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            }
            
            .gallery-item:hover .gallery-overlay {
                opacity: 1;
            }
            
            .gallery-img {
                width: 100%;
                height: 260px;
                object-fit: cover;
                display: block;
                transition: transform 0.4s ease;
            }
            
            .gallery-item:hover .gallery-img {
                transform: scale(1.05);
            }
            
            .gallery-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.6);
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            
            .zoom-icon {
                width: 55px;
                height: 55px;
                background: rgba(255,255,255,0.9);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 22px;
                color: #1a76d1;
                transition: all 0.3s ease;
            }
            
            .zoom-icon:hover {
                background: #1a76d1;
                color: #fff;
                transform: scale(1.1);
            }
            
            .gallery-info {
                padding: 15px;
                background: #fff;
                border-top: 1px solid #eee;
            }
            
            .gallery-date {
                font-size: 13px;
                color: #888;
                margin: 0;
                display: flex;
                align-items: center;
                gap: 8px;
            }
            
            .gallery-date i {
                color: #1a76d1;
                font-size: 14px;
            }
            
            /* Lightbox Modal */
            .lightbox-modal {
                display: none;
                position: fixed;
                z-index: 9999;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.95);
                cursor: pointer;
            }
            
            .lightbox-modal.active {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .lightbox-content {
                max-width: 90%;
                max-height: 85vh;
                text-align: center;
                position: relative;
            }
            
            .lightbox-img {
                max-width: 100%;
                max-height: 85vh;
                object-fit: contain;
                border-radius: 8px;
                box-shadow: 0 5px 30px rgba(0,0,0,0.3);
            }
            
            .lightbox-close {
                position: absolute;
                top: -40px;
                right: -40px;
                width: 45px;
                height: 45px;
                background: rgba(255,255,255,0.2);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 28px;
                color: #fff;
                cursor: pointer;
                transition: all 0.3s ease;
            }
            
            .lightbox-close:hover {
                background: #ff4444;
                transform: rotate(90deg);
            }
            
            .lightbox-nav {
                position: fixed;
                top: 50%;
                transform: translateY(-50%);
                width: 50px;
                height: 50px;
                background: rgba(255,255,255,0.2);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 28px;
                color: #fff;
                cursor: pointer;
                transition: all 0.3s ease;
                z-index: 10000;
            }
            
            .lightbox-nav:hover {
                background: #1a76d1;
            }
            
            .lightbox-prev {
                left: 30px;
            }
            
            .lightbox-next {
                right: 30px;
            }
            
            .lightbox-caption {
                position: absolute;
                bottom: -50px;
                left: 0;
                right: 0;
                text-align: center;
                color: #fff;
                font-size: 14px;
                background: rgba(0,0,0,0.7);
                padding: 10px;
                border-radius: 8px;
            }
            
            .lightbox-counter {
                position: fixed;
                bottom: 20px;
                left: 50%;
                transform: translateX(-50%);
                background: rgba(0,0,0,0.6);
                color: #fff;
                padding: 8px 16px;
                border-radius: 30px;
                font-size: 14px;
                z-index: 10000;
            }
            
            /* No Photos Message */
            .no-photos {
                text-align: center;
                padding: 80px 20px;
                background: #fff;
                border-radius: 12px;
                box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            }
            
            .no-photos i {
                font-size: 64px;
                color: #ccc;
                margin-bottom: 20px;
            }
            
            .no-photos h4 {
                color: #666;
                margin-bottom: 10px;
            }
            
            .no-photos p {
                color: #999;
            }
            
            /* Loading Spinner */
            .gallery-loading {
                text-align: center;
                padding: 60px;
            }
            
            .spinner {
                width: 50px;
                height: 50px;
                border: 3px solid #f3f3f3;
                border-top: 3px solid #1a76d1;
                border-radius: 50%;
                animation: spin 1s linear infinite;
                margin: 0 auto 20px;
            }
            
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            
            @media (max-width: 768px) {
                .gallery-grid {
                    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                    gap: 15px;
                }
                
                .gallery-img {
                    height: 220px;
                }
                
                .lightbox-nav {
                    width: 40px;
                    height: 40px;
                    font-size: 20px;
                }
                
                .lightbox-prev {
                    left: 10px;
                }
                
                .lightbox-next {
                    right: 10px;
                }
                
                .lightbox-close {
                    top: -30px;
                    right: -10px;
                    width: 35px;
                    height: 35px;
                    font-size: 22px;
                }
                
                .lightbox-caption {
                    bottom: -40px;
                    font-size: 12px;
                }
            }
        </style>
    </head>

    <body>
        <!-- Preloader Start -->
        <!-- <div id="preloader-active">
            <div class="preloader d-flex align-items-center justify-content-center">
                <div class="preloader-inner position-relative">
                    <div class="preloader-circle"></div>
                    <div class="preloader-img pere-text">
                        <img src="assets/img/logo/loaderlogo.jpg" alt="">
                    </div>
                </div>
            </div>
        </div> -->
        <!-- Preloader Start -->

        <?php include('config/header.php'); ?>

        <main>
            <!-- slider Area Start-->
            <div class="slider-area">
                <div class="single-slider slider-height2 d-flex align-items-center" style="background-image:url(assets/img/hero/services_hero.jpg);">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="hero-cap text-center">
                                    <h2>Newspaper Gallery</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- slider Area End-->

            <!-- Photo Gallery Section Start -->
                <div class="gallery-section">
                    <div class="container">
                        <div class="gallery-title">
                            <p class="text-muted">Browse through our collection of newspaper photos</p>
                        </div>

                        <?php if(count($photos) > 0): ?>
                            <div class="gallery-grid" id="galleryGrid">
                                <?php foreach($photos as $index => $photo): ?>
                                    <div class="gallery-item" data-index="<?php echo $index; ?>" data-photo-id="<?php echo $photo['id']; ?>">
                                        <img class="gallery-img" 
                                            src="admin/<?php echo htmlspecialchars($photo['photo_path']); ?>" 
                                            alt="Newspaper Photo <?php echo $index+1; ?>"
                                            loading="lazy">
                                        <div class="gallery-overlay">
                                            <div class="zoom-icon">
                                                <i class="fas fa-search-plus"></i>
                                            </div>
                                        </div>
                                        <div class="gallery-info">
                                            <p class="gallery-date">
                                                <i class="far fa-calendar-alt"></i>
                                                <?php echo $photo['newspaper_date'] ? 'Published: ' . date('d F Y', strtotime($photo['newspaper_date'])) : 'Date not specified'; ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="no-photos">
                                <i class="fas fa-newspaper"></i>
                                <h4>No Newspaper Photos Available</h4>
                                <p>Newspaper cuttings and publications will be displayed here once added.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <!-- Photo Gallery Section End -->

            <!-- Lightbox Modal for Fullscreen View -->
            <div class="lightbox-modal" id="lightboxModal">
                <div class="lightbox-close" id="lightboxClose">
                    <i class="fas fa-times"></i>
                </div>
                <div class="lightbox-nav lightbox-prev" id="lightboxPrev">
                    <i class="fas fa-chevron-left"></i>
                </div>
                <div class="lightbox-nav lightbox-next" id="lightboxNext">
                    <i class="fas fa-chevron-right"></i>
                </div>
                <div class="lightbox-content">
                    <img class="lightbox-img" id="lightboxImg" src="" alt="">
                    <div class="lightbox-caption" id="lightboxCaption"></div>
                </div>
                <div class="lightbox-counter" id="lightboxCounter"></div>
            </div>
        </main>

        <footer>
            <!-- Footer Start -->
            <!-- footer-bottom aera -->
            <?php include('./config/footer.php'); ?>
            <!-- Footer End -->
        </footer>

        <!-- JS here -->
        <!-- All JS Custom Plugins Link Here here -->
        <script src="./assets/js/vendor/modernizr-3.5.0.min.js"></script>

        <!-- Jquery, Popper, Bootstrap -->
        <script src="./assets/js/vendor/jquery-1.12.4.min.js"></script>
        <script src="./assets/js/popper.min.js"></script>
        <script src="./assets/js/bootstrap.min.js"></script>
        <!-- Jquery Mobile Menu -->
        <script src="./assets/js/jquery.slicknav.min.js"></script>

        <!-- Jquery Slick , Owl-Carousel Plugins -->
        <script src="./assets/js/owl.carousel.min.js"></script>
        <script src="./assets/js/slick.min.js"></script>

        <!-- One Page, Animated-HeadLin -->
        <script src="./assets/js/wow.min.js"></script>
        <script src="./assets/js/animated.headline.js"></script>
        <script src="./assets/js/jquery.magnific-popup.js"></script>

        <!-- Scrollup, nice-select, sticky -->
        <script src="./assets/js/jquery.scrollUp.min.js"></script>
        <script src="./assets/js/jquery.nice-select.min.js"></script>
        <script src="./assets/js/jquery.sticky.js"></script>

        <!-- contact js -->
        <script src="./assets/js/contact.js"></script>
        <script src="./assets/js/jquery.form.js"></script>
        <script src="./assets/js/jquery.validate.min.js"></script>
        <script src="./assets/js/mail-script.js"></script>
        <script src="./assets/js/jquery.ajaxchimp.min.js"></script>

        <!-- Jquery Plugins, main Jquery -->
        <script src="./assets/js/plugins.js"></script>
        <script src="./assets/js/main.js"></script>
        <script src="./assets/js/active.js"></script>

        <script src="./assets/js/datatables-demo.js"></script>
        <script src="./assets/datatables/jquery.dataTables.min.js"></script>
        <script src="./assets/datatables/dataTables.bootstrap4.min.js"></script>
        <script src="./assets/js/imagesloaded/imagesloaded.js"></script>
        <script src="./assets/js/masonry/masonry-3.1.4.js"></script>
        <script src="./assets/js/masonry/masonry.filter.js"></script>

        <script>
            $(document).ready(function() {
                // Gallery Items Data
                const galleryData = <?php echo json_encode($photos); ?>;
                let currentIndex = 0;
                const $lightboxModal = $('#lightboxModal');
                const $lightboxImg = $('#lightboxImg');
                const $lightboxCaption = $('#lightboxCaption');
                const $lightboxCounter = $('#lightboxCounter');
                
                // Function to open lightbox
                function openLightbox(index) {
                    if (!galleryData.length) return;
                    
                    currentIndex = index;
                    const photo = galleryData[currentIndex];
                    
                    $lightboxImg.attr('src', 'admin/' + photo.photo_path);
                    
                    // Set caption
                    let caption = '';
                    if (photo.newspaper_date) {
                        caption = 'Published: ' + new Date(photo.newspaper_date).toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                    } else {
                        caption = 'Newspaper Cutting';
                    }
                    $lightboxCaption.text(caption);
                    
                    // Update counter
                    $lightboxCounter.text((currentIndex + 1) + ' of ' + galleryData.length);
                    
                    // Show modal
                    $lightboxModal.addClass('active');
                    $('body').css('overflow', 'hidden');
                }
                
                // Close lightbox
                function closeLightbox() {
                    $lightboxModal.removeClass('active');
                    $('body').css('overflow', '');
                }
                
                // Navigate to previous photo
                function prevPhoto() {
                    currentIndex--;
                    if (currentIndex < 0) {
                        currentIndex = galleryData.length - 1;
                    }
                    openLightbox(currentIndex);
                }
                
                // Navigate to next photo
                function nextPhoto() {
                    currentIndex++;
                    if (currentIndex >= galleryData.length) {
                        currentIndex = 0;
                    }
                    openLightbox(currentIndex);
                }
                
                // Click on gallery item to open lightbox
                $('.gallery-item').on('click', function(e) {
                    if (!$(e.target).closest('.gallery-info').length) {
                        const index = $(this).data('index');
                        openLightbox(index);
                    }
                });
                
                // Click on zoom icon
                $('.zoom-icon').on('click', function(e) {
                    e.stopPropagation();
                    const index = $(this).closest('.gallery-item').data('index');
                    openLightbox(index);
                });
                
                // Lightbox controls
                $('#lightboxClose').on('click', closeLightbox);
                $('#lightboxPrev').on('click', prevPhoto);
                $('#lightboxNext').on('click', nextPhoto);
                
                // Keyboard navigation
                $(document).on('keydown', function(e) {
                    if ($lightboxModal.hasClass('active')) {
                        if (e.key === 'Escape') {
                            closeLightbox();
                        } else if (e.key === 'ArrowLeft') {
                            prevPhoto();
                        } else if (e.key === 'ArrowRight') {
                            nextPhoto();
                        }
                    }
                });
                
                // Close on modal background click
                $lightboxModal.on('click', function(e) {
                    if ($(e.target).is($lightboxModal)) {
                        closeLightbox();
                    }
                });
                
                // Image loading animation
                $('.gallery-img').each(function() {
                    $(this).on('load', function() {
                        $(this).addClass('loaded');
                    });
                });
                
                // Initialize gallery with smooth loading
                if ($('.gallery-item').length > 0) {
                    $('.gallery-item').css('opacity', '0').animate({ opacity: 1 }, 500);
                }
            });
        </script>
    </body>
</html>