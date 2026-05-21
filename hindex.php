<?php include('./config/config.php'); ?>
<?php
$year = 2026;
$holidays = [
    "2026-01-01" => ["type" => "RH", "name" => "New Year's Day"],
    "2026-01-03" => ["type" => "RH", "name" => "Hazrat Ali's Birthday"],
    "2026-01-14" => ["type" => "RH", "name" => "Makar Sankranti / Magha Bihu / Pongal"],
    "2026-01-23" => ["type" => "RH", "name" => "Sri Panchami / Basant Panchami"],
    "2026-01-26" => ["type" => "GH", "name" => "Republic Day"],
    "2026-02-01" => ["type" => "RH", "name" => "Guru Ravi Dass Birthday"],
    "2026-02-12" => ["type" => "RH", "name" => "Birthday of Swami Dayananda Saraswati"],
    "2026-02-15" => ["type" => "RH", "name" => "Maha Shivratri"],
    "2026-02-19" => ["type" => "RH", "name" => "Shivaji Jayanti"],
    "2026-03-03" => ["type" => "RH", "name" => "Holika Dahan / Dol Yatra"],
    "2026-03-04" => ["type" => "GH", "name" => "Holi"],
    "2026-03-19" => ["type" => "RH", "name" => "Chaitra Sukladi / Gudi Padava / Ugadi / Cheti Chand"],
    "2026-03-20" => ["type" => "RH", "name" => "Jamat-Ul-Vida"],
    "2026-03-21" => ["type" => "GH", "name" => "Id-ul-Fitr"],
    "2026-03-31" => ["type" => "GH", "name" => "Mahavir Jayanti"],
    "2026-04-03" => ["type" => "GH", "name" => "Good Friday"],
    "2026-04-05" => ["type" => "RH", "name" => "Easter Sunday"],
    "2026-04-14" => ["type" => "RH", "name" => "Vaisakhi / Vishu / Tamil New Year's Day"],
    "2026-04-15" => ["type" => "RH", "name" => "Bohag Bihu (Assam)"],
    "2026-05-01" => ["type" => "GH", "name" => "Buddha Purnima"],
    "2026-05-09" => ["type" => "RH", "name" => "Birthday of Guru Rabindranath Tagore"],
    "2026-05-27" => ["type" => "GH", "name" => "Id-ul-Zuha (Bakrid)"],
    "2026-06-26" => ["type" => "GH", "name" => "Muharram"],
    "2026-07-16" => ["type" => "RH", "name" => "Rath Yatra"],
    "2026-08-15" => ["type" => "GH", "name" => "Independence Day"],
    "2026-08-26" => ["type" => "GH", "name" => "Milad-un-Nabi / Id-e-Milad"],
    "2026-08-28" => ["type" => "RH", "name" => "Raksha Bandhan"],
    "2026-09-04" => ["type" => "GH", "name" => "Janmashtami (Vaishnava)"],
    "2026-09-14" => ["type" => "GH", "name" => "Ganesh Chaturthi"],
    "2026-10-02" => ["type" => "GH", "name" => "Mahatma Gandhi's Birthday"],
    "2026-10-18" => ["type" => "RH", "name" => "Dussehra (Saptami)"],
    "2026-10-19" => ["type" => "RH", "name" => "Dussehra (Mahaptami)"],
    "2026-10-20" => ["type" => "GH", "name" => "Dussehra (Vijaya Dashami)"],
    "2026-10-26" => ["type" => "RH", "name" => "Maharishi Valmiki's Birthday"],
    "2026-10-29" => ["type" => "RH", "name" => "Karaka Chaturthi (Karwa Chouth)"],
    "2026-11-08" => ["type" => "GH", "name" => "Diwali (Deepavali)"],
    "2026-11-09" => ["type" => "RH", "name" => "Govardhan Puja"],
    "2026-11-11" => ["type" => "RH", "name" => "Bhai Duj"],
    "2026-11-15" => ["type" => "RH", "name" => "Pratihar Shashthi / Chhat Puja"],
    "2026-11-24" => ["type" => "GH", "name" => "Guru Nanak's Birthday"],
    "2026-12-23" => ["type" => "RH", "name" => "Hazrat Ali's Birthday"],
    "2026-12-24" => ["type" => "RH", "name" => "Christmas Eve"],
    "2026-12-25" => ["type" => "GH", "name" => "Christmas Day"],
];

/* ── Director data ─────────────────────────────────────────── */
$director_type = 'director';
$q_dir = $dbh->prepare("SELECT * FROM emp_details WHERE emp_type = :type ORDER BY emp_seniority ASC LIMIT 1");
$q_dir->bindParam(':type', $director_type, PDO::PARAM_STR);
$q_dir->execute();
$director_info = $q_dir->fetch(PDO::FETCH_OBJ);

$director_profile_data = null;
if ($director_info) {
    $q_prof = $dbh->prepare("SELECT * FROM director_profile WHERE id = :id LIMIT 1");
    $q_prof->bindParam(':id', $director_info->emp_id, PDO::PARAM_INT);
    $q_prof->execute();
    $director_profile_data = $q_prof->fetch(PDO::FETCH_OBJ);
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ICMR-NIHR Jodhpur</title>
    <meta name="description" content="ICMR-National Institute of Health Research, Jodhpur — advancing health research for a better India.">

    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">

    <!-- CSS -->
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./assets/css/flaticon.css">
    <link rel="stylesheet" href="./assets/css/slicknav.css">
    <link rel="stylesheet" href="./assets/css/animate.min.css">
    <link rel="stylesheet" href="./assets/css/magnific-popup.css">
    <link rel="stylesheet" href="./assets/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="./assets/css/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="./assets/slick/slick.css">
    <link rel="stylesheet" type="text/css" href="./assets/slick/slick-theme.css">
    <link rel="stylesheet" href="./assets/css/nice-select.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./stylenav.css">
    <link rel="stylesheet" href="./index.css">
    <!-- Google Fonts — Plus Jakarta Sans + Playfair Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
</head>

<body id="bg">

<!-- ── Preloader ─────────────────────────────────────────────── -->
<div id="preloader-active">
    <div class="preloader d-flex align-items-center justify-content-center">
        <div class="preloader-inner position-relative">
            <div class="preloader-circle"></div>
            <div class="preloader-img pere-text" style="left: 20% !important;">
                <img src="assets/img/logo/loaderlogo.jpg" alt="Loading">
            </div>
        </div>
    </div>
</div>

<?php include('config/hheader.php'); ?>

<main>

<!-- ═══════════════════════════════════════════════════════════
     HERO + TABS
════════════════════════════════════════════════════════════ -->
<section class="hero-tabs-section py-4">
    <div class="container-fluid">
        <div class="row g-0 g-lg-3">

            <!-- ── Hero Slider ──────────────────────────────── -->
            <div class="col-lg-8 col-md-12 mb-4 mb-lg-0">
                <div class="hero-area h-100">
                    <div class="hero-slideshow owl-carousel h-100">
                        <?php
                        $q_car = $dbh->prepare(
                            "SELECT g.*, (SELECT COUNT(*) FROM carousel_items WHERE gallery_id = g.id) AS photo_count
                             FROM carousel_galleries g ORDER BY g.id DESC"
                        );
                        $q_car->execute();
                        $carousels = $q_car->fetchAll(PDO::FETCH_OBJ);

                        if ($q_car->rowCount() > 0):
                            foreach ($carousels as $gal):
                                $cover = 'admin/uploads/carousel/' . htmlspecialchars($gal->cover_photo, ENT_QUOTES, 'UTF-8');
                        ?>
                        <div class="single-slide"
                             onclick="openGalleryModal(<?= (int)$gal->id ?>)"
                             role="button"
                             tabindex="0"
                             aria-label="Open gallery: <?= htmlspecialchars($gal->title, ENT_QUOTES) ?>">
                            <div class="slide-bg-img h-100">
                                <img src="<?= $cover ?>"
                                     alt="<?= htmlspecialchars($gal->title, ENT_QUOTES) ?>"
                                     loading="lazy">
                            </div>
                            <div class="carousel-caption">
                                <h5><?= htmlspecialchars($gal->title, ENT_QUOTES) ?></h5>
                                <p><?= htmlspecialchars($gal->subtitle, ENT_QUOTES) ?></p>
                            </div>
                        </div>
                        <?php endforeach; else: ?>
                        <div class="single-slide" style="height:540px;">
                            <div class="slide-bg-img h-100">
                                <img src="assets/img/hero/default.jpg" alt="ICMR-NIHR Jodhpur">
                            </div>
                            <div class="carousel-caption">
                                <h5>Welcome to ICMR-NIHR Jodhpur</h5>
                                <p>Advancing Health Research for a Better India</p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- ── Modern Tabs ──────────────────────────────── -->
            <div class="col-lg-4 col-md-12">
                <div class="modern-tab-card">
                    <div class="tab-nav-wrapper">
                        <ul class="modern-tabs-nav" role="tablist">
                            <li class="tab-nav-item" role="presentation">
                                <button class="tab-btn active" data-tab="whatsnew"
                                        role="tab" aria-selected="true" aria-controls="tab-whatsnew">
                                    <i class="fas fa-bullhorn" aria-hidden="true"></i>
                                    <span>नवीनतम जानकारी</span>
                                </button>
                            </li>
                            <li class="tab-nav-item" role="presentation">
                                <button class="tab-btn" data-tab="events"
                                        role="tab" aria-selected="false" aria-controls="tab-events">
                                    <i class="fas fa-calendar-check" aria-hidden="true"></i>
                                    <span>कार्यक्रम</span>
                                </button>
                            </li>
                            <li class="tab-nav-item" role="presentation">
                                <button class="tab-btn" data-tab="circulars"
                                        role="tab" aria-selected="false" aria-controls="tab-circulars">
                                    <i class="fas fa-file-alt" aria-hidden="true"></i>
                                    <span>परिपत्र</span>
                                </button>
                            </li>
                            <li class="tab-nav-item" role="presentation">
                                <button class="tab-btn" data-tab="tenders"
                                        role="tab" aria-selected="false" aria-controls="tab-tenders">
                                    <i class="fas fa-gavel" aria-hidden="true"></i>
                                    <span>निविदाएं</span>
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="modern-tab-content">

                        <?php
                        /* ── Helper: render a tab panel ─────── */
                        function renderTabItems(PDO $dbh, string $type, string $panelId, string $viewAllHref, string $viewAllLabel, bool $active = false): void {
                            $sql  = "SELECT * FROM info_hi WHERE type = :type ORDER BY id DESC LIMIT 15";
                            $stmt = $dbh->prepare($sql);
                            $stmt->bindParam(':type', $type, PDO::PARAM_STR);
                            $stmt->execute();
                            $rows = $stmt->fetchAll(PDO::FETCH_OBJ);
                            $iconClass = ($type === 'event') ? 'event-icon' : '';
                            $icon      = ($type === 'event') ? 'fa-calendar-alt' : 'fa-file-pdf';
                            $activeClass = $active ? 'active' : '';
                        ?>
                        <div class="tab-panel <?= $activeClass ?>" id="tab-<?= htmlspecialchars($panelId) ?>" role="tabpanel">
                            <div class="tab-content-scroll">
                                <?php if ($stmt->rowCount() > 0):
                                    foreach ($rows as $row):
                                        $qd = $dbh->prepare("SELECT * FROM doc_en WHERE doc_id = :id LIMIT 5");
                                        $qd->bindParam(':id', $row->id, PDO::PARAM_INT);
                                        $qd->execute();
                                        $docs = $qd->fetchAll(PDO::FETCH_OBJ);
                                        foreach ($docs as $doc):
                                ?>
                                <div class="modern-list-item">
                                    <a href="admin/en_doc/<?= htmlspecialchars($doc->doc_main, ENT_QUOTES) ?>" target="_blank" rel="noopener">
                                        <div class="list-icon <?= $iconClass ?>">
                                            <i class="fas <?= $icon ?>" aria-hidden="true"></i>
                                        </div>
                                        <div class="list-content">
                                            <h4><?= htmlspecialchars($row->title, ENT_QUOTES) ?></h4>
                                        </div>
                                        <div class="list-arrow">
                                            <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                        </div>
                                    </a>
                                </div>
                                <?php endforeach; endforeach; else: ?>
                                <div class="empty-state-modern">
                                    <i class="fas fa-inbox" aria-hidden="true"></i>
                                    <p>No records found</p>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="view-all-wrapper">
                                <a href="<?= htmlspecialchars($viewAllHref) ?>" class="view-all-btn">
                                    <?= htmlspecialchars($viewAllLabel) ?>
                                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                        <?php } ?>

                        <?php
                        /* What's New */
                        $sql_wn = "SELECT * FROM info_hi WHERE type IN ('whatsnew','recruitment','events') ORDER BY id DESC LIMIT 10";
                        $q_wn   = $dbh->prepare($sql_wn);
                        $q_wn->execute();
                        $wn_rows = $q_wn->fetchAll(PDO::FETCH_OBJ);
                        ?>
                        <div class="tab-panel active" id="tab-whatsnew" role="tabpanel">
                            <div class="tab-content-scroll">
                                <?php if ($q_wn->rowCount() > 0):
                                    foreach ($wn_rows as $wn_row):
                                        $qd = $dbh->prepare("SELECT * FROM doc_en WHERE doc_id = :id LIMIT 5");
                                        $qd->bindParam(':id', $wn_row->id, PDO::PARAM_INT);
                                        $qd->execute();
                                        foreach ($qd->fetchAll(PDO::FETCH_OBJ) as $doc):
                                ?>
                                <div class="modern-list-item">
                                    <a href="admin/en_doc/<?= htmlspecialchars($doc->doc_main, ENT_QUOTES) ?>" target="_blank" rel="noopener">
                                        <div class="list-icon">
                                            <i class="fas fa-file-pdf" aria-hidden="true"></i>
                                        </div>
                                        <div class="list-content">
                                            <h4><?= htmlspecialchars($wn_row->title, ENT_QUOTES) ?></h4>
                                        </div>
                                        <div class="list-arrow">
                                            <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                        </div>
                                    </a>
                                </div>
                                <?php endforeach; endforeach; else: ?>
                                <div class="empty-state-modern">
                                    <i class="fas fa-inbox" aria-hidden="true"></i>
                                    <p>No announcements found</p>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="view-all-wrapper">
                                <a href="viewnews.php" class="view-all-btn">
                                    सभी को देखें <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>

                        <?php renderTabItems($dbh, 'event',    'events',    'view.php',         'सभी घटनाएँ देखें'); ?>
                        <?php renderTabItems($dbh, 'circular', 'circulars', 'viewcircular.php', 'सभी परिपत्र देखें'); ?>
                        <?php renderTabItems($dbh, 'tenders',  'tenders',   'tenders.php',      'सभी निविदाएं देखें'); ?>

                    </div><!-- /.modern-tab-content -->
                </div><!-- /.modern-tab-card -->
            </div>

        </div>
    </div>
</section>

<!-- ── Gallery Modal ─────────────────────────────────────────── -->
<div class="modal fade" id="galleryModal" tabindex="-1" role="dialog" aria-labelledby="galleryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white py-2">
                <h6 class="modal-title mb-0 text-white" id="galleryModalLabel"></h6>
                <button type="button" class="close text-white ml-auto" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div id="galleryCarousel" class="carousel slide h-100" data-ride="carousel">
                    <div class="carousel-inner h-100" id="galleryCarouselInner"></div>
                    <a class="carousel-control-prev" href="#galleryCarousel" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#galleryCarousel" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════
     ANNOUNCEMENT BAR
════════════════════════════════════════════════════════════ -->
<div class="announcement-wrapper">
    <div class="container-fluid px-0">
        <div class="announcement-card-modern">
            <div class="announcement-header">
                <div class="announcement-icon-wrapper">
                    <i class="fas fa-bullhorn" aria-hidden="true"></i>
                    <span class="announcement-title">घोषणाएं</span>
                </div>
            </div>
            <div class="announcement-content-wrapper">
                <div class="announcement-marquee">
                    <div class="marquee-content">
                        <?php
                        $q_ann = $dbh->prepare("SELECT * FROM announcement ORDER BY id DESC");
                        $q_ann->execute();
                        $announcements = $q_ann->fetchAll(PDO::FETCH_OBJ);

                        if ($q_ann->rowCount() > 0):
                            foreach ($announcements as $ann): ?>
                            <div class="announcement-item">
                                <div class="announcement-badge <?= $ann->doc_upload ? 'has-doc' : 'no-doc' ?>">
                                    <i class="fas <?= $ann->doc_upload ? 'fa-file-pdf' : 'fa-info-circle' ?>" aria-hidden="true"></i>
                                </div>
                                <div class="announcement-text">
                                    <?php if ($ann->doc_upload): ?>
                                    <a href="./admin/en_doc/<?= htmlspecialchars($ann->doc_upload, ENT_QUOTES) ?>" target="_blank" rel="noopener">
                                        <?= htmlspecialchars($ann->messages, ENT_QUOTES) ?>
                                        <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                                    </a>
                                    <?php else: ?>
                                    <span><?= htmlspecialchars($ann->messages, ENT_QUOTES) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach;
                            /* Duplicate for seamless loop */
                            foreach ($announcements as $ann): ?>
                            <div class="announcement-item">
                                <div class="announcement-badge <?= $ann->doc_upload ? 'has-doc' : 'no-doc' ?>">
                                    <i class="fas <?= $ann->doc_upload ? 'fa-file-pdf' : 'fa-info-circle' ?>" aria-hidden="true"></i>
                                </div>
                                <div class="announcement-text">
                                    <?php if ($ann->doc_upload): ?>
                                    <a href="./admin/en_doc/<?= htmlspecialchars($ann->doc_upload, ENT_QUOTES) ?>" target="_blank" rel="noopener">
                                        <?= htmlspecialchars($ann->messages, ENT_QUOTES) ?>
                                        <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                                    </a>
                                    <?php else: ?>
                                    <span><?= htmlspecialchars($ann->messages, ENT_QUOTES) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach;
                        else: ?>
                        <div class="announcement-item">
                            <div class="announcement-text">
                                <span>No announcements at this time. Stay tuned!</span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════
     ABOUT INSTITUTE
════════════════════════════════════════════════════════════ -->
<div class="info-section-wrapper">
    <div class="container" style="position:relative;z-index:1">
        <div class="info-unified-card">
            <div class="info-card-glow"></div>

            <!-- Header -->
            <div class="info-header-unified text-center">
                <span class="info-tag-premium">
                    <i class="fas fa-microscope" aria-hidden="true"></i>
                    संस्थान के बारे में
                </span>
                <div class="info-divider-premium">
                    <span class="info-divider-line"></span>
                </div>
            </div>

            <!-- Two-column grid -->
            <div class="info-grid-unified">

                <!-- Director's Desk -->
                <div class="info-column director-column">
                    <div class="column-header">
                        <h3 class="column-title">निदेशक की डेस्क से</h3>
                        <div class="column-line"></div>
                    </div>

                    <div class="director-content">
                        <div class="director-avatar-wrapper">
                            <?php if ($director_info && $director_info->emp_image): ?>
                            <img src="admin/img/our_team/director/<?= htmlspecialchars($director_info->emp_image, ENT_QUOTES) ?>"
                                 alt="<?= htmlspecialchars($director_info->emp_name ?? 'Director', ENT_QUOTES) ?>"
                                 class="director-avatar-img" loading="lazy">
                            <?php endif; ?>
                        </div>

                        <div class="director-info">
                            <strong class="director-name-text">
                                <?= htmlspecialchars($director_info->emp_name_hi ?? 'Director', ENT_QUOTES) ?>
                            </strong>
                            <span class="director-designation">
                                <?= htmlspecialchars($director_info->emp_desig_hi ?? 'Director, ICMR-NIHR Jodhpur', ENT_QUOTES) ?>
                            </span>
                        </div>

                        <p class="director-message-text">
                            <?php
                            if ($director_profile_data && $director_profile_data->director_message_hi) {
                                $msg = strip_tags($director_profile_data->director_message_hi);
                                echo htmlspecialchars(mb_substr($msg, 0, 260), ENT_QUOTES) . '…';
                            } else {
                                echo 'The institute is located in Jodhpur and it replaces the erstwhile Desert Medicine Research Centre. Our focus is on conducting research to identify and innovate methods to tackle the rising threats of non-communicable diseases in the region.';
                            }
                            ?>
                        </p>

                        <a href="about-director.php" class="info-link-btn">
                            <span>पूरा प्रोफाइल देखें</span>
                            <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                <!-- Welcome to NIHR -->
                <div class="info-column welcome-column">
                    <div class="column-header">
                        <h3 class="column-title">आई.सी.एम.आर.-राष्ट्रीय स्वास्थ्य अनुसंधान संस्थान, जोधपुर में आपका स्वागत है</h3>
                        <div class="column-line"></div>
                    </div>

                    <div class="welcome-content">
                        <div class="stats-row">
                            <div class="stat-card">
                                <span class="stat-number">1984</span>
                                <span class="stat-label">स्थापित</span>
                            </div>
                            <div class="stat-card">
                                <span class="stat-number">40+</span>
                                <span class="stat-label">उत्कृष्टता के वर्ष</span>
                            </div>
                            <div class="stat-card">
                                <span class="stat-number">ICMR</span>
                                <span class="stat-label">प्रीमियर संस्थान</span>
                            </div>
                        </div>

                        <div class="welcome-text-block">
                            <p class="welcome-paragraph">
                                राष्ट्रीय स्वास्थ्य अनुसंधान संस्थान (NIHR), जोधपुर में स्थित, मूल रूप से <strong>27 जून 1984 </strong> को डेजर्ट मेडिसिन रिसर्च सेंटर (DMRC) के रूप में स्थापित किया गया था। बाद में <strong>7 दिसंबर 2019 </strong>को इसका नाम बदलकर नेशनल इंस्टीट्यूट फॉर इम्प्लीमेंटेशन रिसर्च ऑन नॉन-कम्युनिकेबल डिज़ीज़ेज़ (NIIRNCD) कर दिया गया तथा <strong>29 अप्रैल 2026 </strong>को इसे पुनः नामित कर राष्ट्रीय स्वास्थ्य अनुसंधान संस्थान (NIHR) किया गया।
                            </p>
                            <p class="welcome-paragraph">
                               संस्थान में माइक्रोबायोलॉजी, बायोकेमिस्ट्री तथा वेक्टर बायोलॉजी में बुनियादी प्रयोगशाला-आधारित अनुसंधान के लिए अत्याधुनिक सुविधाएँ उपलब्ध हैं। साथ ही, यह गैर-संचारी रोगों (NCDs) के क्षेत्र में कार्यान्वयन अनुसंधान को आगे बढ़ाने हेतु अपनी क्षमता और मानव संसाधन को सक्रिय रूप से सुदृढ़ कर रहा है।
                            </p>
                        </div>

                        <a href="about-nihr.php" class="info-link-btn" style="margin:0">
                            <span>Discover More</span>
                            <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

            </div><!-- /.info-grid-unified -->

            <!-- Collaborators -->
            <div class="collaborators-section">
                <div class="collaborators-header">
                    <h4>हमारे सहयोगी और साझेदार</h4>
                    <div class="collaborators-line"></div>
                </div>
                <div class="collaborators-grid">
                    <?php
                    $collaborators = [
                        ['src' => './assets/img/footerlogo/who.jpg',                  'alt' => 'WHO'],
                        ['src' => './assets/img/footerlogo/aiimslogo.png',            'alt' => 'AIIMS'],
                        ['src' => './assets/img/footerlogo/icmr_logo.png',            'alt' => 'ICMR'],
                        ['src' => './assets/img/footerlogo/MOHFW-Recruitment-2017.jpg','alt' => 'MOHFW'],
                        ['src' => './assets/img/footerlogo/dhr.jpg',                  'alt' => 'DHR'],
                    ];
                    foreach ($collaborators as $c): ?>
                    <div class="collaborator-logo">
                        <img src="<?= htmlspecialchars($c['src'], ENT_QUOTES) ?>"
                             alt="<?= htmlspecialchars($c['alt'], ENT_QUOTES) ?>"
                             loading="lazy">
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════
     LEADERSHIP
════════════════════════════════════════════════════════════ -->
<section class="leadership-section">
    <div class="container" style="position:relative;z-index:1">
        <div class="leadership-unified-card">
            <div class="leadership-card-glow"></div>

            <!-- Header -->
            <div class="leadership-header-unified text-center">
                <span class="leadership-tag-premium">
                    <i class="fas fa-user-tie" aria-hidden="true"></i>
                    हमारा नेतृत्व
                </span>
                <div class="leadership-divider-premium">
                    <span class="leadership-divider-line"></span>
                </div>
            </div>

            <!-- Row 1 — Union Ministers -->
            <div class="leadership-grid-unified top">
                <?php
                $ministers = [
                    [
                        'name'  => 'श्री जगत प्रकाश नड्डा',
                        'title' => "माननीय स्वास्थ्य एवं परिवार कल्याण मंत्री",
                        'img'   => 'assets/img/team/minister1.jpg',
                        'url'   => 'https://www.india.gov.in/directory/whos-who/ministers-details?mpno=2204&house=rajyasabha&p=council-of-minister',
                    ],
                    [
                        'name'  => 'श्री प्रतापराव जाधव',
                        'title' => "माननीय राज्य मंत्री",
                        'img'   => 'assets/img/team/minister2.jpg',
                        'url'   => 'https://www.india.gov.in/directory/whos-who/ministers-details?mpno=4373&house=loksabha&p=council-of-minister',
                    ],
                    [
                        'name'  => 'श्रीमती अनुप्रिया पटेल',
                        'title' => "माननीय राज्य मंत्री",
                        'img'   => 'assets/img/team/minister3.jpg',
                        'url'   => 'https://www.india.gov.in/directory/whos-who/ministers-details?mpno=4662&house=loksabha&p=council-of-minister',
                    ],
                ];
                foreach ($ministers as $leader): ?>
                <div class="leadership-card-unified">
                    <div class="leadership-card-inner">
                        <div class="leadership-image-container">
                            <img src="<?= htmlspecialchars($leader['img'], ENT_QUOTES) ?>"
                                 alt="<?= htmlspecialchars($leader['name'], ENT_QUOTES) ?>"
                                 loading="lazy">
                        </div>
                        <div class="leadership-card-content">
                            <h3 class="leadership-name-unified"><?= htmlspecialchars($leader['name'], ENT_QUOTES) ?></h3>
                            <p class="leadership-title-unified"><?= htmlspecialchars($leader['title'], ENT_QUOTES) ?></p>
                            <div class="leadership-read-more">
                                <a href="<?= htmlspecialchars($leader['url'], ENT_QUOTES) ?>" target="_blank" rel="noopener">प्रोफ़ाइल देखें</a>
                                <i class="fas fa-arrow-right" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Row 2 — ICMR Leadership -->
            <div class="leadership-grid-unified bottom">
                <?php
                $icmr_leaders = [
                    [
                        'name'  => 'डॉ. राजीव बहल',
                        'title' => 'Secretary, Department of Health Research &amp; DG, ICMR',
                        'img'   => 'admin/img/our_team/dg-icmr/dg_photo_square.jpg',
                        'url'   => 'https://www.icmr.gov.in/icmr-leadership#ICMRDirector',
                    ],
                    [
                        'name'  => 'डॉ. पंकज भारद्वाज',
                        'title' => 'निदेशक-आईसीएमआर-राष्ट्रीय स्वास्थ्य अनुसंधान संस्थान, जोधपुर',
                        'img'   => 'admin/img/our_team/director/drpankaj.png',
                        'url'   => 'https://niirncd.icmr.org.in/about-director.php',
                    ],
                ];
                foreach ($icmr_leaders as $leader): ?>
                <div class="leadership-card-unified bottomicmr">
                    <div class="leadership-card-inner">
                        <div class="leadership-image-container">
                            <img src="<?= htmlspecialchars($leader['img'], ENT_QUOTES) ?>"
                                 alt="<?= htmlspecialchars($leader['name'], ENT_QUOTES) ?>"
                                 loading="lazy">
                        </div>
                        <div class="leadership-card-content">
                            <h3 class="leadership-name-unified"><?= htmlspecialchars($leader['name'], ENT_QUOTES) ?></h3>
                            <p class="leadership-title-unified"><?= $leader['title'] ?></p>
                            <div class="leadership-read-more">
                                <a href="<?= htmlspecialchars($leader['url'], ENT_QUOTES) ?>" target="_blank" rel="noopener">प्रोफ़ाइल देखें</a>
                                <i class="fas fa-arrow-right" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="leadership-footer-note">
                <i class="fas fa-handshake" aria-hidden="true"></i>
                <span>Committed to excellence in health research and policy implementation</span>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     CONTACT + CALENDAR + SOCIAL
════════════════════════════════════════════════════════════ -->
<section class="contact-social-section">
    <div class="container" style="position:relative;z-index:1">
        <div class="unified-card">
            <div class="unified-card-glow"></div>

            <!-- Header -->
            <div class="section-header-unified text-center">
                <span class="section-tag-premium">
                    <i class="fas fa-satellite-dish" aria-hidden="true"></i>
                    हमारे साथ जुड़ें
                </span>
                <div class="section-divider-premium">
                    <span class="divider-line-premium"></span>
                </div>
            </div>

            <!-- Three-column grid -->
            <div class="unified-grid">

                <!-- Contact -->
                <div class="unified-column contact-column text-center">
                    <h3 class="column-title">हमसे मिलें</h3>
                    <div class="section-divider-premium" style="margin-bottom:20px">
                        <span class="divider-line-premium"></span>
                    </div>

                    <div class="unified-map">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4255.999377738954!2d73.0276054281912!3d26.23391291055097!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39418b8f0bc41b59%3A0x452d769037ea5042!2sNational%20Institute%20for%20Implementation%20Research%20on%20Non-Communicable%20Diseases!5e0!3m2!1sen!2sin!4v1622014051415!5m2!1sen!2sin"
                            allowfullscreen=""
                            loading="lazy"
                            title="ICMR-NIHR Jodhpur Location Map">
                        </iframe>
                    </div>

                    <div class="contact-items">
                        <div class="contact-item">
                            <div class="contact-icon"><i class="fas fa-university" aria-hidden="true"></i></div>
                            <div class="contact-content">
                                <span class="contact-label">पता</span>
                                <p><strong>ICMR-राष्ट्रीय स्वास्थ्य अनुसंधान संस्थान</strong><br>
                                भारतीय आयुर्विज्ञान अनुसंधान परिषद<br>
                                स्वास्थ्य अनुसंधान विभाग<br>
                                स्वास्थ्य एवं परिवार कल्याण मंत्रालय<br>
                                न्यू पाली रोड,, जोधपुर<br>
                                राजस्थान – 342005, INDIA</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon"><i class="fas fa-phone-alt" aria-hidden="true"></i></div>
                            <div class="contact-content">
                                <span class="contact-label">फ़ोन</span>
                                <p>0291-2722403 / 2720618</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon"><i class="fas fa-envelope" aria-hidden="true"></i></div>
                            <div class="contact-content">
                                <span class="contact-label">ईमेल</span>
                                <p>director-niirncd[at]icmr[dot]gov[dot]in</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon"><i class="fas fa-clock" aria-hidden="true"></i></div>
                            <div class="contact-content">
                                <span class="contact-label">कार्य के घंटे</span>
                                <p>Mon – Fri : 9:00 AM – 5:30 PM</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Calendar -->
                <div class="unified-column text-center">
                    <h3 class="column-title">छुट्टियाँ कैलेंडर 2026</h3>
                    <div class="section-divider-premium" style="margin-bottom:20px">
                        <span class="divider-line-premium"></span>
                    </div>

                    <div class="calendar-controls-unified">
                        <button id="prevBtnUnified" class="cal-nav-unified" onclick="prevMonthUnified()" aria-label="Previous month">
                            <i class="fas fa-chevron-left" aria-hidden="true"></i>
                        </button>
                        <h2 id="monthYearUnified" class="month-display-unified"></h2>
                        <button id="nextBtnUnified" class="cal-nav-unified" onclick="nextMonthUnified()" aria-label="Next month">
                            <i class="fas fa-chevron-right" aria-hidden="true"></i>
                        </button>
                    </div>

                    <div id="calendarUnified" class="calendar-grid-unified"></div>

                    <div class="legend-unified">
                        <div class="legend-item"><span class="legend-color gazetted"></span>Gazetted</div>
                        <div class="legend-item"><span class="legend-color restricted"></span>Restricted</div>
                        <div class="legend-item"><span class="legend-color weekend"></span>Weekend</div>
                        <div class="legend-item"><span class="legend-color today"></span>Today</div>
                    </div>

                    <a class="download-btn-unified" href="./doc/Calender2026.pdf" target="_blank">
                        <i class="fas fa-file-pdf" aria-hidden="true"></i>
                        <span>कैलेंडर डाउनलोड करें</span>
                        <i class="fas fa-download" aria-hidden="true"></i>
                    </a>
                </div>

                <!-- Social -->
                <div class="unified-column social-column text-center">
                    <h3 class="column-title">Follow Us</h3>
                    <div class="section-divider-premium" style="margin-bottom:20px">
                        <span class="divider-line-premium"></span>
                    </div>

                    <div class="social-tabs-unified">
                        <button class="social-tab-unified active" data-tab="facebook-unified">
                            <i class="fab fa-facebook-f" aria-hidden="true"></i> फेसबुक
                        </button>
                        <button class="social-tab-unified" data-tab="instagram-unified">
                            <i class="fab fa-instagram" aria-hidden="true"></i> Instagram
                        </button>
                    </div>

                    <div class="social-feeds-unified">
                        <div id="facebook-unified" class="feed-pane active">
                            <div class="fb-page"
                                 data-href="https://www.facebook.com/nihrjodhpur"
                                 data-tabs="timeline"
                                 data-width="320"
                                 data-height="350"
                                 data-small-header="true"
                                 data-adapt-container-width="true"
                                 data-hide-cover="false"
                                 data-show-facepile="true">
                                <blockquote cite="https://www.facebook.com/nihrjodhpur" class="fb-xfbml-parse-ignore">
                                    <a href="https://www.facebook.com/nihrjodhpur">ICMR-NIHR Jodhpur on Facebook</a>
                                </blockquote>
                            </div>
                        </div>
                        <div id="instagram-unified" class="feed-pane">
                            <iframe
                                src="https://www.instagram.com/nihrjodhpur/embed"
                                width="100%"
                                height="350"
                                frameborder="0"
                                scrolling="no"
                                allowtransparency="true"
                                title="ICMR-NIHR Instagram Feed">
                            </iframe>
                        </div>
                    </div>

                    <div class="social-stats-unified">
                        <span><i class="fas fa-newspaper" aria-hidden="true"></i> साप्ताहिक अपडेट</span>
                        <span><i class="fas fa-chart-line" aria-hidden="true"></i> शोध समाचार</span>
                    </div>
                </div>

            </div><!-- /.unified-grid -->
        </div>
    </div>
</section>

<!-- Facebook SDK -->
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v17.0"></script>

<!-- ── Calendar JS ────────────────────────────────────────────── -->
<script>
(function() {
    var currentMonth = new Date().getMonth();
    var currentYear  = new Date().getFullYear();

    var monthNames = ['January','February','March','April','May','June',
                      'July','August','September','October','November','December'];

    function renderUnifiedCalendar() {
        var el = document.getElementById('calendarUnified');
        if (!el) return;
        document.getElementById('monthYearUnified').textContent = monthNames[currentMonth] + ' ' + currentYear;

        var firstDay     = new Date(currentYear, currentMonth, 1).getDay();
        var daysInMonth  = new Date(currentYear, currentMonth + 1, 0).getDate();
        var todayStr     = new Date().toISOString().slice(0, 10);
        var hols         = window.holidays || {};

        var html = '<table class="calendar-table-unified"><thead><tr>';
        ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'].forEach(function(d){ html += '<th>' + d + '</th>'; });
        html += '</tr></thead><tbody><tr>';

        var dayCount = 1;
        for (var i = 0; i < 42; i++) {
            if (i < firstDay || dayCount > daysInMonth) {
                html += '<td class="empty"></td>';
            } else {
                var mm      = String(currentMonth + 1).padStart(2, '0');
                var dd      = String(dayCount).padStart(2, '0');
                var dateStr = currentYear + '-' + mm + '-' + dd;
                var holiday = hols[dateStr] || null;
                var isWknd  = (i % 7 === 0 || i % 7 === 6);
                var isToday = (dateStr === todayStr);
                var cls     = '';
                if (holiday) cls = (holiday.type === 'GH') ? 'gazetted-cell' : 'restricted-cell';
                else if (isWknd) cls = 'weekend-cell';
                if (isToday) cls += (cls ? ' ' : '') + 'today-cell';
                var tip = holiday ? holiday.name : (isWknd ? 'Weekend' : '');
                html += '<td class="' + cls + '"' + (tip ? ' title="' + tip + '"' : '') + '>' + dayCount + '</td>';
                dayCount++;
            }
            if ((i + 1) % 7 === 0 && i < 41) html += '</tr><tr>';
        }
        html += '</tr></tbody></table>';
        el.innerHTML = html;
    }

    window.prevMonthUnified = function() {
        if (--currentMonth < 0) { currentMonth = 11; currentYear--; }
        renderUnifiedCalendar();
    };
    window.nextMonthUnified = function() {
        if (++currentMonth > 11) { currentMonth = 0; currentYear++; }
        renderUnifiedCalendar();
    };
    /* legacy aliases */
    window.prevMonth = window.prevMonthUnified;
    window.nextMonth = window.nextMonthUnified;

    document.addEventListener('DOMContentLoaded', renderUnifiedCalendar);
}());
</script>

</main>

<?php include "./config/footer.php"; ?>

<!-- ── Vendor JS ─────────────────────────────────────────────── -->
<script src="./assets/js/vendor/modernizr-3.5.0.min.js"></script>
<script src="./assets/js/vendor/jquery-1.12.4.min.js"></script>
<script src="./assets/js/bootstrap.min.js"></script>
<script src="./assets/js/jquery.slicknav.min.js"></script>
<script src="./assets/js/owl.carousel.min.js"></script>
<script src="./assets/js/gijgo.min.js"></script>
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
<script type="text/javascript" src="./assets/slick/slick.min.js"></script>

<!-- ── Holiday Calendar data ────────────────────────────────── -->
<script>
var year     = <?= (int)$year ?>;
var holidays = <?= json_encode($holidays, JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) ?>;
</script>
<script src="calendar.js"></script>

<!-- ── Page scripts ──────────────────────────────────────────── -->
<script>
(function ($) {
    'use strict';

    /* ── Font size accessibility ── */
    $('#btn1').on('click', function () { $('#bg, .card-text').css('font-size', '18px'); });
    $('#btn2').on('click', function () { $('#bg, .card-text').css('font-size', '16px'); });
    $('#btn3').on('click', function () { $('#bg, .card-text').css('font-size', '13px'); });

    /* ── Tab switching ── */
    var tabButtons = document.querySelectorAll('.tab-btn');
    var tabPanels  = document.querySelectorAll('.tab-panel');

    tabButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var tabId = this.getAttribute('data-tab');
            tabButtons.forEach(function (b) { b.classList.remove('active'); b.setAttribute('aria-selected', 'false'); });
            tabPanels.forEach(function (p)  { p.classList.remove('active'); });
            this.classList.add('active');
            this.setAttribute('aria-selected', 'true');
            var panel = document.getElementById('tab-' + tabId);
            if (panel) panel.classList.add('active');
        });
        btn.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); btn.click(); }
        });
    });

    /* ── Social tab switching ── */
    document.querySelectorAll('.social-tab-unified').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var tabId = this.getAttribute('data-tab');
            document.querySelectorAll('.social-tab-unified').forEach(function (b) { b.classList.remove('active'); });
            document.querySelectorAll('.feed-pane').forEach(function (p)          { p.classList.remove('active'); });
            this.classList.add('active');
            var pane = document.getElementById(tabId);
            if (pane) pane.classList.add('active');
        });
    });

    /* ── Gallery modal cleanup ── */
    $('#galleryModal').on('hidden.bs.modal', function () {
        var car = $('#galleryCarousel');
        if (car.data('bs.carousel')) car.carousel('dispose');
        $('#galleryCarouselInner').empty();
    });

}(jQuery));

/* ── Gallery AJAX open ── */
function openGalleryModal(galleryId) {
    galleryId = parseInt(galleryId, 10);
    if (!galleryId || galleryId < 1) return;

    $('#galleryCarouselInner').html(
        '<div class="carousel-item active">' +
        '<div class="d-flex justify-content-center align-items-center" style="height:80vh;">' +
        '<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x text-white"></i>' +
        '<p class="mt-3 text-white">Loading gallery…</p></div></div></div>'
    );
    $('#galleryModal').modal('show');

    $.ajax({
        url:      'ajax_get_carousel.php',
        type:     'POST',
        data:     { gallery_id: galleryId },
        dataType: 'json',
        timeout:  12000,
        success: function (resp) {
            if (!resp || !resp.success) {
                showGalleryError((resp && resp.message) ? resp.message : 'Failed to load gallery.');
                return;
            }
            $('#galleryModalLabel').text(resp.title || '');
            var inner    = $('#galleryCarouselInner').empty();
            var basePath = 'admin/uploads/carousel/';
            var isFirst  = true;

            function addSlide(imgSrc, titleText, subText, active) {
                inner.append(
                    '<div class="carousel-item' + (active ? ' active' : '') + '">' +
                    '<img src="' + basePath + esc(imgSrc) + '" class="d-block modal-gallery-img"' +
                    ' onerror="this.src=\'assets/img/no-image.jpg\'"' +
                    ' alt="' + esc(titleText) + '">' +
                    '<div class="carousel-caption"><h5>' + esc(titleText) + '</h5>' +
                    '<p>' + esc(subText) + '</p></div></div>'
                );
            }

            if (resp.cover_photo) {
                addSlide(resp.cover_photo, resp.title || '', resp.subtitle || '', true);
                isFirst = false;
            }
            if (resp.photos && resp.photos.length) {
                $.each(resp.photos, function (i, photo) {
                    if (photo.image_url) {
                        addSlide(photo.image_url, resp.title || '', resp.subtitle || '', isFirst && i === 0 && !resp.cover_photo);
                    }
                });
            }
            setTimeout(function () {
                $('#galleryCarousel').carousel({ interval: false });
            }, 100);
        },
        error: function () {
            showGalleryError('Could not connect to server. Please refresh and try again.');
        }
    });
}

function showGalleryError(msg) {
    $('#galleryCarouselInner').html(
        '<div class="carousel-item active">' +
        '<div class="d-flex justify-content-center align-items-center" style="height:80vh;">' +
        '<div class="text-center text-danger">' +
        '<i class="fa fa-exclamation-triangle fa-3x"></i>' +
        '<p class="mt-3">' + esc(msg) + '</p>' +
        '<button class="btn btn-primary mt-2" onclick="location.reload()">Refresh Page</button>' +
        '</div></div></div>'
    );
}

function esc(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}
</script>

<!-- ── Pop-up Modal trigger ──────────────────────────────────── -->
<script>
window.addEventListener('load', function () {
    setTimeout(function () {
        var overlay = document.getElementById('customModalOverlay');
        if (!overlay) return;
        overlay.classList.add('active');
        document.body.classList.add('modal-open');

        function closeModal() {
            overlay.classList.remove('active');
            document.body.classList.remove('modal-open');
        }
        var closeBtn  = document.getElementById('modalCloseBtn');
        var footerBtn = document.getElementById('modalFooterBtn');
        if (closeBtn)  closeBtn.addEventListener('click',  closeModal);
        if (footerBtn) footerBtn.addEventListener('click', closeModal);
        overlay.addEventListener('click', function (e) { if (e.target === overlay) closeModal(); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeModal(); });
    }, 2000);
});
</script>

<?php
/* ── Pop-up Modal from DB ─────────────────────────────────── */
$q_modal = mysqli_query($conn, "SELECT * FROM modals WHERE is_enabled = 1 ORDER BY id DESC LIMIT 1");
if ($q_modal && mysqli_num_rows($q_modal) > 0):
    $modal = mysqli_fetch_assoc($q_modal);
?>
<div id="customModalOverlay" class="custom-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="popupTitle">
    <div class="custom-modal-box">
        <button class="custom-modal-close" id="modalCloseBtn" aria-label="Close">&times;</button>
        <div class="custom-modal-header">
            <h2 id="popupTitle"><?= htmlspecialchars($modal['modal_title'], ENT_QUOTES) ?></h2>
        </div>
        <div class="custom-modal-body">
            <?= html_entity_decode($modal['modal_content']) ?>
        </div>
        <div class="custom-modal-footer">
            <button id="modalFooterBtn"><?= htmlspecialchars($modal['footer_text'], ENT_QUOTES) ?></button>
        </div>
    </div>
</div>
<?php endif; ?>

</body>
</html>
