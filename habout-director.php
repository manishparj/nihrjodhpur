<?php
include('config/config.php');
?>
<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>ICMR-NIHR | Director's Profile</title>
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
    <link rel="stylesheet" href="assets/datatables/dataTables.bootstrap4.css">

    <!-- Google Fonts for modern typography -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* Global Styles Enhancement */

        /* Director Profile Section */
        .director-profile-section {
            background: linear-gradient(135deg, #f5f7fc 0%, #ffffff 100%);
            padding: 4rem 0;
            position: relative;
            overflow: hidden;
        }

        .director-profile-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 60%;
            height: 100%;
            background: radial-gradient(ellipse at 100% 0%, rgba(0, 123, 255, 0.03), transparent 70%);
            pointer-events: none;
        }

        /* Profile Card Modern */
        .profile-card {
            background: #ffffff;
            border-radius: 32px;
            overflow: hidden;
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.12), 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: all 0.4s cubic-bezier(0.2, 0, 0, 1);
            border: 1px solid rgba(0, 123, 255, 0.08);
        }

        .profile-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 32px 48px -16px rgba(0, 0, 0, 0.2);
            border-color: rgba(0, 123, 255, 0.2);
        }

        /* Image Container */
        .profile-image-container {
            padding: 2rem 2rem 0 2rem;
            background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
            position: relative;
            text-align: center;
        }

        .profile-image-container img {
            max-width: 100%;
            border-radius: 28px;
            box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
            object-fit: cover;
            width: 100%;
            height: auto;
            aspect-ratio: 1 / 1;
            object-position: top center;
        }

        .profile-card:hover .profile-image-container img {
            transform: scale(1.02);
        }

        /* Card Body */
        .profile-card-body {
            padding: 1.8rem 2rem 2rem;
            text-align: center;
        }

        .profile-name {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
            -webkit-background-clip: text;
            background-clip: text;
            color: #003679;
        }

        .profile-designation {
            font-size: 1.2rem;
            font-weight: 600;
            color: #003679;
            margin-bottom: 1.2rem;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        .info-divider {
            border-top: 2px solid #eef2ff;
            margin: 1.2rem 0;
        }

        .info-item {
            margin-bottom: 1rem;
        }

        .info-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c757d;
            margin-bottom: 0.25rem;
        }

        .info-value {
            font-size: 0.9rem;
            font-weight: 500;
            color: #1f2937;
            word-break: break-word;
        }

        .info-value a {
            color: #2563eb;
            text-decoration: none;
            transition: color 0.2s;
        }

        .info-value a:hover {
            color: #1e3a8a;
            text-decoration: underline;
        }

        /* Content Card */
        .content-card {
            background: #ffffff;
            border-radius: 32px;
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.08);
            height: 100%;
            border: 1px solid rgba(0, 123, 255, 0.08);
            transition: box-shadow 0.3s ease;
        }

        .content-card:hover {
            box-shadow: 0 24px 48px -12px rgba(0, 0, 0, 0.12);
        }

        .content-card-body {
            padding: 2rem;
        }

        /* Modern Tabs */
        .nav-pills-custom {
            background: #f8fafc;
            padding: 0.5rem;
            border-radius: 60px;
            display: inline-flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }

        .nav-pills-custom .nav-link {
            border-radius: 40px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            color: #4b5563;
            background: transparent;
            font-family: 'Poppins', sans-serif;
        }

        .nav-pills-custom .nav-link.active {
            background: linear-gradient(135deg, #2563eb, #003679);
            color: white;
            box-shadow: 0 6px 14px rgba(37, 99, 235, 0.25);
        }

        .nav-pills-custom .nav-link:not(.active):hover {
            background: #eef2ff;
            color: #1e40af;
        }

        /* Director Content */
        .director-content-text {
            font-size: 1rem;
            line-height: 1.8;
            color: #374151;
            text-align: justify;
        }

        /* Responsive Enhancements */
        @media (max-width: 991px) {
            .director-profile-section {
                padding: 2.5rem 0;
            }
            .profile-name {
                font-size: 1.5rem;
            }
            .content-card-body {
                padding: 1.5rem;
            }
            .nav-pills-custom .nav-link {
                padding: 0.6rem 1.2rem;
                font-size: 0.85rem;
            }
        }

        @media (max-width: 768px) {
            .profile-card-body {
                padding: 1.2rem;
            }
            .profile-image-container {
                padding: 1.5rem 1.5rem 0 1.5rem;
            }
            .profile-name {
                font-size: 1.3rem;
            }
            .profile-designation {
                font-size: 0.85rem;
            }
            .director-content-text {
                font-size: 0.9rem;
                line-height: 1.7;
            }
            .nav-pills-custom {
                border-radius: 30px;
                width: 100%;
                justify-content: center;
            }
            .nav-pills-custom .nav-link {
                flex: 1;
                text-align: center;
                min-width: 120px;
            }
        }

        @media (max-width: 576px) {
            .content-card-body {
                padding: 1.2rem;
            }
            .nav-pills-custom .nav-link {
                padding: 0.5rem 1rem;
                font-size: 0.8rem;
                min-width: 100px;
            }
            .info-label {
                font-size: 0.7rem;
            }
            .info-value {
                font-size: 0.8rem;
            }
        }

        /* Animation */
        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Smooth Scroll */
        html {
            scroll-behavior: smooth;
        }
    </style>

</head>

<body id="bg">

    <!-- Preloader Start -->
    <div id="preloader-active">
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="preloader-inner position-relative">
                <div class="preloader-circle"></div>
                <div class="preloader-img pere-text">
                    <img src="assets/img/logo/loaderlogo.jpg" alt="Loading">
                </div>
            </div>
        </div>
    </div>
    <!-- Preloader Start -->

    <?php include('config/hheader.php'); ?>

    <main>

        <!-- slider Area Start-->
        <div class="slider-area">
            <div class="single-slider slider-height2 d-flex align-items-center" style="background-image:url(assets/img/hero/services_hero.jpg); background-size: cover; background-position: center;">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="hero-cap text-center">
                                <h2>निदेशक की प्रोफ़ाइल</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- slider Area End-->

        <section class="director-profile-section">
            <div class="container">

                <?php
                $name = 'director';

                $sql = "SELECT * from emp_details where emp_type = (:type) ORDER BY emp_seniority ASC";
                $query = $dbh->prepare($sql);
                $query->bindParam(':type', $name, PDO::PARAM_STR);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);

                if ($query->rowCount() > 0) {
                    foreach ($results as $result) {
                ?>

                        <div class="row align-items-stretch fade-in-up">
                            
                            <!-- LEFT PROFILE CARD - Enhanced -->
                            <div class="col-lg-4 mb-4 mb-lg-0">
                                <div class="profile-card h-100">
                                    <div class="profile-image-container">
                                        <img src="admin/img/our_team/<?= $name ?>/<?= htmlentities($result->emp_image) ?>" alt="<?php echo htmlentities($result->emp_name); ?>">
                                    </div>
                                    <div class="profile-card-body">
                                        <h3 class="profile-name">
                                            <?php echo htmlentities($result->emp_name_hi); ?>
                                        </h3>
                                        <div class="profile-designation">
                                            <?php echo htmlentities($result->emp_desig_hi); ?>
                                        </div>
                                        <div class="info-divider"></div>
                                        <div class="info-item">
                                            <div class="info-label">संस्थान</div>
                                            <div class="info-value">ICMR-NIHR, Jodhpur</div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">Email</div>
                                            <div class="info-value">
                                                <a href="mailto:<?php echo htmlentities($result->emp_email); ?>">
                                                    <?php echo htmlentities($result->emp_email); ?>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">Telephone</div>
                                            <div class="info-value">
                                                <?php echo htmlentities($result->emp_contact); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- RIGHT CONTENT - Enhanced -->
                            <div class="col-lg-8">
                                <div class="content-card h-100">
                                    <div class="content-card-body">
                                        
                                        <!-- Modern Tabs Design -->
                                        <ul class="nav nav-pills-custom" id="directorTab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link active" 
                                                    id="profile-tab-<?php echo $result->emp_id; ?>"
                                                    data-toggle="tab"
                                                    href="#profile-<?php echo $result->emp_id; ?>"
                                                    role="tab">
                                                    <i class="fas fa-user-graduate mr-2"></i> प्रोफ़ाइल
                                                </a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link" 
                                                    id="message-tab-<?php echo $result->emp_id; ?>"
                                                    data-toggle="tab"
                                                    href="#message-<?php echo $result->emp_id; ?>"
                                                    role="tab">
                                                    <i class="fas fa-envelope-open-text mr-2"></i> संदेश
                                                </a>
                                            </li>
                                        </ul>

                                        <?php
                                        $sql9 = "SELECT * from director_profile where id = :id";
                                        $query9 = $dbh->prepare($sql9);
                                        $query9->bindParam(':id', $result->emp_id, PDO::PARAM_INT);
                                        $query9->execute();
                                        $results9 = $query9->fetchAll(PDO::FETCH_OBJ);

                                        if ($query9->rowCount() > 0) {
                                            foreach ($results9 as $result20) {
                                        ?>

                                                <div class="tab-content">
                                                    <!-- PROFILE TAB -->
                                                    <div class="tab-pane fade show active" 
                                                        id="profile-<?php echo $result->emp_id; ?>" 
                                                        role="tabpanel">
                                                        <div class="director-content-text">
                                                            <?php echo nl2br(htmlentities($result20->director_profile_hi)); ?>
                                                        </div>
                                                    </div>

                                                    <!-- MESSAGE TAB -->
                                                    <div class="tab-pane fade" 
                                                        id="message-<?php echo $result->emp_id; ?>" 
                                                        role="tabpanel">
                                                        <div class="director-content-text">
                                                            <?php echo nl2br(htmlentities($result20->director_message_hi)); ?>
                                                        </div>
                                                    </div>
                                                </div>

                                        <?php
                                            }
                                        }
                                        ?>

                                    </div>
                                </div>
                            </div>

                        </div>

                <?php
                    }
                }
                ?>

            </div>
        </section>

    </main>

    <footer>
        <?php include('./config/footer.php'); ?>
    </footer>

    <!-- JS here -->
    <script src="./assets/js/vendor/modernizr-3.5.0.min.js"></script>
    <script src="./assets/js/vendor/jquery-1.12.4.min.js"></script>
    <script src="./assets/js/popper.min.js"></script>
    <script src="./assets/js/bootstrap.min.js"></script>
    <script src="./assets/js/jquery.slicknav.min.js"></script>
    <script src="./assets/js/owl.carousel.min.js"></script>
    <script src="./assets/js/slick.min.js"></script>
    <script src="./assets/js/wow.min.js"></script>
    <script src="./assets/js/animated.headline.js"></script>
    <script src="./assets/js/jquery.magnific-popup.js"></script>
    <script src="./assets/js/jquery.scrollUp.min.js"></script>
    <script src="./assets/js/jquery.nice-select.min.js"></script>
    <script src="./assets/js/jquery.sticky.js"></script>
    <script src="./assets/js/contact.js"></script>
    <script src="./assets/js/jquery.form.js"></script>
    <script src="./assets/js/jquery.validate.min.js"></script>
    <script src="./assets/js/mail-script.js"></script>
    <script src="./assets/js/jquery.ajaxchimp.min.js"></script>
    <script src="./assets/js/plugins.js"></script>
    <script src="./assets/js/main.js"></script>
    <script src="./assets/js/active.js"></script>
    <script src="./assets/js/datatables-demo.js"></script>
    <script src="./assets/datatables/jquery.dataTables.min.js"></script>
    <script src="./assets/datatables/dataTables.bootstrap4.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Font size controls
            $('#btn1').click(function() {
                $("#bg").css("fontSize", "18px");
                $(".card-text").css("fontSize", "18px");
                $(".director-content-text").css("fontSize", "18px");
            });

            $('#btn2').click(function() {
                $("#bg").css("fontSize", "16px");
                $(".card-text").css("fontSize", "16px");
                $(".director-content-text").css("fontSize", "16px");
            });

            $('#btn3').click(function() {
                $("#bg").css("fontSize", "13px");
                $(".card-text").css("fontSize", "13px");
                $(".director-content-text").css("fontSize", "13px");
            });

            // Add animation on tab change
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                $(e.target).addClass('active');
                $($(e.target).attr('href')).find('.director-content-text').addClass('fade-in-up');
            });
        });
    </script>

</body>

</html>