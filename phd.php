<?php
include('config/config.php');

// Auto-update status based on last date in database (optional - can be kept or removed)
// Auto-update status based on last date for PhD
try {
    $sql_update_phd = "UPDATE phd_programmes 
                       SET status = 'closed' 
                       WHERE last_date < CURDATE() AND status = 'open'";
    $query_update_phd = $dbh->prepare($sql_update_phd);
    $query_update_phd->execute();
} catch (PDOException $e) {
    error_log("Error auto-updating PhD status: " . $e->getMessage());
}

$phd_programmes = [];
try {
    $sql_phd = "SELECT * FROM phd_programmes ORDER BY id DESC";
    $query_phd = $dbh->prepare($sql_phd);
    $query_phd->execute();
    $phd_programmes = $query_phd->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching PhD programmes: " . $e->getMessage());
}

// Function to check if programme is expired
function isExpired($last_date) {
    return strtotime($last_date) < strtotime(date('Y-m-d'));
}

// Function to get display status based on last date
function getDisplayStatus($status, $last_date) {
    if (isExpired($last_date)) {
        return 'closed'; // Force closed if date has passed
    }
    return $status; // Otherwise return actual status
}
?>
<!doctype html>
<html class="no-js" lang="zxx">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>PhD (AcSIR) | ICMR-NIHR</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

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
        <link rel="stylesheet" href="internship.css">

        <link rel="stylesheet" href="assets/datatables/dataTables.bootstrap4.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    </head>

    <body>
        <?php include('config/header.php'); ?>
        <main>

            <!-- slider Area Start-->
            <div class="slider-area">
                <div class="single-slider slider-height2 d-flex align-items-center" style="background-image:url(assets/img/hero/services_hero.jpg);">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="hero-cap text-center">
                                    <h2>PhD (AcSIR)</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <section class="internship-section py-3">
                <div class="container">

                    <!-- Heading -->
                    <div class="text-center mb-4">
                        <p class="subtitle">
                            AcSIR offers fully funded opportunities to its Ph.D students to pursue a part
                            of their doctoral research for up to one year at top-tier international
                            universities under the Joint Ph.D Degree (Cotutelle) Program.
                        </p>
                        <p class="subtitle">
                        AcSIR is the largest academic institution for doctoral research in India,
                        having awarded 958 Ph.D degrees in 2025 under STEM, with more than
                        7,000 students currently registered in its Ph.D programs.
                        </p>

                    </div>

                    <!-- About Institute -->
                    <div class="card custom-card mb-4 border-0">
                        <div class="card-body">
                            <div class="section-heading">
                                <i class="fas fa-hospital-alt"></i>
                                <h3>About Institute</h3>
                            </div>
                            <p>
                                The National Institute for Health Research (NIHR), located in Jodhpur, was originally established on 27 June 1984 as the Desert Medicine Research Centre (DMRC). It was later renamed the National Institute for Implementation Research on Non-Communicable Diseases (NIIRNCD) on 7 December 2019, and redesignated as NIHR on 29 April 2026.
                            </p>
                            <p>
                                The Institute houses state-of-the-art facilities for conducting basic laboratory-based research in Biotechnology, microbiology, biochemistry, and Virology. It is also actively strengthening its capacity and workforce to advance implementation research in non-communicable diseases (NCDs).
                            </p>
                        </div>
                    </div>

                    <!-- Combined Eligibility & Programme Details Section -->
<div class="card custom-card mb-4 border-0">
    <div class="card-body">

        <!-- Section Heading -->
        <div class="section-heading">
            <i class="fas fa-check-circle"></i>
            <h3>PhD (AcSIR) & Offered Courses</h3>
        </div>

        <!-- Eligibility Cards -->
      <div class="row mb-4">
    <div class="col-12">
        <div class="info-box">

            <p class="mb-3">
                This Institute is recognized as a research centre for the Ph.D. program in Medical Research under the Academy of Scientific and Innovative Research (AcSIR). Ph.D. admissions are conducted twice a year, in two academic cycles. Interested candidates may apply online through the AcSIR Admission Portal.
            </p>

            <p class="mb-3">
                The Ph.D. topic is finalized by the scholar and guide followed by its approval in the Research and Development Committee (RDC) of the respective university. The work carried out during the Ph.D. is approved by the university through the RDC.
            </p>

            <h5 class="mb-2">AcSIR Offers</h5>

            <ul class="mb-0 ps-3">
                <li>
                    <strong>Ph.D (Science)</strong> –
                    Biological Sciences, Chemical Sciences, Physical Sciences,
                    Agricultural Sciences, Mathematical &amp; Information Sciences
                </li>

                <li><strong>Ph.D (Medical Research)</strong></li>

                <li><strong>Ph.D (Engineering)</strong></li>

                <li>
                    <strong>IDDP* (M.Tech + Ph.D in Engineering)</strong>
                    <small class="d-block">* Integrated Dual Degree Program</small>
                </li>

                <li><strong>M.Tech</strong></li>
            </ul>

        </div>
    </div>
</div>
      
    </div>
</div>
                    <!-- Active Internship Programmes Table -->
                       <div class="table-wrapper">
    <div class="section-heading p-4 mb-0">
        <i class="fas fa-graduation-cap"></i>
        <h3>PhD (AcSIR) Programmes</h3>
    </div>
    
    <div class="table-responsive-custom">
        <table class="unified-table" id="phdDataTable">
            <thead>
                <tr>
                    <th><i class="fas fa-hashtag"></i> S.No.</th>
                    <th><i class="fas fa-graduation-cap"></i> Programme / Session</th>
                    <th><i class="fas fa-toggle-on"></i> Status</th>
                    <th><i class="fas fa-calendar-alt"></i> Last Date</th>
                    <th><i class="fas fa-file-alt"></i> Documents</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($phd_programmes) > 0): ?>
                    <?php $sno = 1; foreach ($phd_programmes as $programme): 
                        // Get documents for this programme
                        $phd_documents = [];
                        try {
                            $sql_docs = "SELECT * FROM phd_documents WHERE phd_programme_id = :id ORDER BY id DESC";
                            $query_docs = $dbh->prepare($sql_docs);
                            $query_docs->bindParam(':id', $programme['id']);
                            $query_docs->execute();
                            $phd_documents = $query_docs->fetchAll(PDO::FETCH_ASSOC);
                        } catch (PDOException $e) {
                            error_log("Error fetching documents: " . $e->getMessage());
                        }
                        
                        // Check if expired
                        $is_expired = strtotime($programme['last_date']) < strtotime(date('Y-m-d'));
                        $today = date('Y-m-d');
                        $last_date = $programme['last_date'];
                        $days_left = ceil((strtotime($last_date) - strtotime($today)) / (60 * 60 * 24));
                        
                        // Set status class and text
                        if ($is_expired) {
                            $status_class = 'status-closed';
                            $status_text = 'Closed';
                        } else {
                            $status_class = ($programme['status'] == 'open') ? 'status-open' : 'status-closed';
                            $status_text = ($programme['status'] == 'open') ? 'Open' : 'Closed';
                        }
                    ?>
                        <tr>
                            <td><?php echo $sno++; ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($programme['programme_name']); ?></strong><br>
                                    <i class="fas fa-calendar-alt"></i> <strong>Session: <?php echo htmlspecialchars($programme['session']); ?></strong>
                                <?php if ($is_expired): ?>
                                    <span class="badge-danger-custom badge-custom ml-2">Expired</span>
                                <?php elseif ($programme['status'] == 'open' && $days_left >= 0 && $days_left <= 7): ?>
                                    <span class="badge-warning-custom badge-custom ml-2">Last Week</span>
                                <?php elseif ($programme['status'] == 'open' && $days_left > 0): ?>
                                    <span class="badge-info-custom badge-custom ml-2"><?php echo $days_left; ?> days left</span>
                                <?php endif; ?>
                             </div>
                            </td>
                            <td>
                                <span class="status-badge <?php echo $status_class; ?>">
                                    <i class="fas <?php echo ($is_expired) ? 'fa-calendar-times' : (($programme['status'] == 'open') ? 'fa-check-circle' : 'fa-times-circle'); ?>"></i>
                                    <?php echo $status_text; ?>
                                    <?php if ($is_expired): ?>
                                        <small>(Date Passed)</small>
                                    <?php endif; ?>
                                </span>
                             </div>
                            <td>
                                <?php echo date('d M Y', strtotime($programme['last_date'])); ?>
                                <?php if ($days_left >= 0 && $days_left <= 7 && !$is_expired && $programme['status'] == 'open'): ?>
                                    <div class="countdown-timer mt-1">
                                        <small class="text-warning">
                                            <i class="fas fa-hourglass-half"></i> 
                                            <?php echo $days_left; ?> day<?php echo ($days_left > 1) ? 's' : ''; ?> remaining
                                        </small>
                                    </div>
                                <?php elseif ($is_expired): ?>
                                    <br><small class="text-danger">
                                        <i class="fas fa-ban"></i> Application Closed
                                    </small>
                                <?php endif; ?>
                             </div>
                            <td>
                                <?php if (count($phd_documents) > 0): ?>
                                    <div class="document-list-inline">
                                        <?php foreach ($phd_documents as $doc): ?>
                                            <a href="admin/<?php echo htmlspecialchars($doc['doc_file']); ?>" target="_blank" class="document-link" title="<?php echo htmlspecialchars($doc['doc_title']); ?>">
                                                <i class="fas fa-file-pdf"></i> <?php echo htmlspecialchars(substr($doc['doc_title'], 0, 25)); ?>
                                            </a> <br />
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="no-docs">
                                        <i class="fas fa-times-circle"></i> No documents uploaded
                                    </span>
                                <?php endif; ?>
                             </div>
                         </>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <h4>No PhD (AcSIR) Programmes Available</h4>
                                <p>Please check back later for new opportunities.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


                    <!-- Contact -->
                    <div class="contact-box text-center">
                        <div class="contact-icon">
                            <i class="fas fa-envelope-open-text"></i>
                        </div>
                        <h3>Contact Information</h3>
                        <p class="mb-1">
                            <i class="fas fa-user"></i>
                            <strong>Dr. Ramesh Kumar Huda</strong>
                        </p>
                        <p class="mb-1">
                            <i class="fas fa-briefcase"></i>
                            Scientist-D & AcSIR Coordinator
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-envelope"></i>
                            rameshk.h@icmr.gov.in
                        </p>
                    </div>

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
        <script src="./assets/datatables/jquery.dataTables.min.js"></script>
        <script src="./assets/datatables/dataTables.bootstrap4.min.js"></script>

     
        
<script>
    $(document).ready(function() {
        // Initialize DataTable for PhD programmes
        <?php if (count($phd_programmes) > 0): ?>
        $('#phdDataTable').DataTable({
            "pageLength": 10,
            "responsive": true,
            "language": {
                "search": "Search:",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "emptyTable": "No PhD programmes available"
            },
            "order": [[3, 'asc']] // Sort by last date
        });
        <?php endif; ?>
    });
</script>
    </body>
</html>