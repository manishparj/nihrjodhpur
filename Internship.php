<?php
include('config/config.php');

// Auto-update status based on last date in database (optional - can be kept or removed)
try {
    // Update programmes where last_date has passed and status is still 'open'
    $sql_update = "UPDATE internship_programmes 
                   SET status = 'closed' 
                   WHERE last_date < CURDATE() AND status = 'open'";
    $query_update = $dbh->prepare($sql_update);
    $query_update->execute();
} catch (PDOException $e) {
    error_log("Error auto-updating status: " . $e->getMessage());
}

// Fetch internship programmes from database
$internship_programmes = [];
try {
    $sql = "SELECT * FROM internship_programmes ORDER BY id DESC";
    $query = $dbh->prepare($sql);
    $query->execute();
    $internship_programmes = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching internships: " . $e->getMessage());
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
        <title>Internship/Dissertation | ICMR-NIHR</title>
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
                                    <h2>Internship/Dissertation</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <section class="internship-section py-3">
                <div class="container">

                    <!-- About Institute -->
                    <div class="card custom-card mb-4 border-0">
                        <div class="card-body">
                            <div class="section-heading">
                                <i class="fas fa-hospital-alt"></i>
                                <h3>Basic Details</h3>
                            </div>
                            <p>
                                ICMR-NIHR, Jodhpur invites applications from eligible postgraduate students for Dissertation / Internship Training for a period of 2 months & 6 months. This program provides structured research exposure in the field of Non-Communicable Diseases, Public Health, Epidemiology, Implementation Research, Biostatistics, Laboratory Sciences, Nutrition, Social & Behavioral Sciences, and Biomedical Research.
                            </p>
                        </div>
                    </div>

                    <!-- Combined Eligibility & Programme Details Section -->
<div class="card custom-card mb-4 border-0">
    <div class="card-body">

        <!-- Section Heading -->
        <div class="section-heading">
            <i class="fas fa-check-circle"></i>
            <h3>Eligibility & Programme Details</h3>
        </div>

        <!-- Eligibility Cards -->
        <div class="row mb-4">
            <div class="col-lg-6 mb-4">
                <div class="info-box h-100">
                    <div class="info-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h5>6-Month Programme</h5>
                    <p>
                        Final semester Postgraduate students (M.Sc., MPH, M.Tech, etc.)
                        from recognized Universities/Colleges/Institutes in Biotechnology,
                        Biochemistry, Microbiology, Life Sciences, Public Health & Allied
                        Subjects or any other relevant field.
                    </p>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="info-box h-100">
                    <div class="info-icon">
                        <i class="fas fa-user-clock"></i>
                    </div>
                    <h5>2-Month Programme</h5>
                    <p>
                        Undergraduate and Postgraduate students in Biotechnology,
                        Biochemistry, Microbiology, Life Sciences, Public Health & Allied
                        Subjects or any other relevant field from any recognized institute.
                    </p>
                </div>
            </div>
        </div>

        <!-- Programme Details Table -->
        <div class="table-wrapper">

            <div class="table-responsive-custom">
                <table class="unified-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> S.No.</th>
                            <th><i class="fas fa-graduation-cap"></i> Programme</th>
                            <th><i class="fas fa-calendar"></i> Sessions</th>
                            <th><i class="fas fa-clock"></i> Duration</th>
                            <th><i class="fas fa-users"></i> Slots</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>
                                <i class="fas fa-flask text-primary"></i>
                                Internship / Dissertation
                            </td>

                            <td>
                                <ul class="table-list">
                                    <li>
                                        <i class="fas fa-angle-right"></i>
                                        January - June
                                    </li>
                                    <li>
                                        <i class="fas fa-angle-right"></i>
                                        July - December
                                    </li>
                                </ul>
                            </td>

                            <td>
                                <span class="duration-badge">6 Months</span>
                            </td>

                            <td>
                                <span class="badge-success-custom badge-custom">
                                    20 Slots
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td>2</td>
                            <td>
                                <i class="fas fa-user-md text-success"></i>
                                Internship / Observership
                            </td>

                            <td>
                                <ul class="table-list">
                                    <li>
                                        <i class="fas fa-angle-right"></i>
                                        May - June
                                    </li>
                                    <li>
                                        <i class="fas fa-angle-right"></i>
                                        August - September
                                    </li>
                                </ul>
                            </td>

                            <td>
                                <span class="duration-badge">2 Months</span>
                            </td>

                            <td>
                                <span class="badge-success-custom badge-custom">
                                    20 Slots
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Note -->
            <div class="alert custom-alert m-4">
                <i class="fas fa-exclamation-triangle"></i>
                Number of slots may vary depending upon mentor availability.
            </div>
        </div>

    </div>
</div>
                    <!-- Active Internship Programmes Table -->
                    <div class="table-wrapper">
                        <div class="section-heading p-4 mb-0">
                            <i class="fas fa-bullhorn"></i>
                            <h3>Internship Programmes</h3>
                        </div>
                        
                        <div class="table-responsive-custom">
                            <table class="unified-table" id="internshipDataTable">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-hashtag"></i> S.No.</th>
                                        <th><i class="fas fa-graduation-cap"></i> Programme Title</th>
                                        <th><i class="fas fa-toggle-on"></i> Status</th>
                                        <th><i class="fas fa-calendar-alt"></i> Last Date</th>
                                        <th><i class="fas fa-clock"></i> Duration</th>
                                        <th><i class="fas fa-file-alt"></i> Documents</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($internship_programmes) > 0): ?>
                                        <?php $sno = 1; foreach ($internship_programmes as $programme): 
                                            // Get documents for this programme
                                            $documents = [];
                                            try {
                                                $sql_docs = "SELECT * FROM internship_documents WHERE internship_id = :id ORDER BY id DESC";
                                                $query_docs = $dbh->prepare($sql_docs);
                                                $query_docs->bindParam(':id', $programme['id']);
                                                $query_docs->execute();
                                                $documents = $query_docs->fetchAll(PDO::FETCH_ASSOC);
                                            } catch (PDOException $e) {
                                                error_log("Error fetching documents: " . $e->getMessage());
                                            }
                                            
                                            // Calculate display status based on last date
                                            $is_expired = isExpired($programme['last_date']);
                                            $display_status = getDisplayStatus($programme['status'], $programme['last_date']);
                                            
                                            // Set status class and text based on display status
                                            if ($is_expired) {
                                                $status_class = 'status-closed';
                                                $status_text = 'Closed';
                                            } else {
                                                $status_class = ($display_status == 'open') ? 'status-open' : 'status-closed';
                                                $status_text = ($display_status == 'open') ? 'Open' : 'Closed';
                                            }
                                            
                                            $duration_text = str_replace('_', ' ', ucfirst($programme['duration']));
                                            $today = date('Y-m-d');
                                            $last_date = $programme['last_date'];
                                            $days_left = ceil((strtotime($last_date) - strtotime($today)) / (60 * 60 * 24));
                                        ?>
                                            <tr>
                                                <td><?php echo $sno++; ?></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($programme['title']); ?></strong>
                                                    <?php if ($is_expired): ?>
                                                        <span class="badge-danger-custom badge-custom ml-2">Expired</span>
                                                    <?php elseif ($display_status == 'open' && $days_left >= 0 && $days_left <= 7): ?>
                                                        <span class="badge-warning-custom badge-custom ml-2">Last Week</span>
                                                    <?php elseif ($display_status == 'open' && $days_left > 0): ?>
                                                        <span class="badge-info-custom badge-custom ml-2"><?php echo $days_left; ?> days left</span>
                                                    <?php endif; ?>
                                                 </td>
                                                <td>
                                                    <span class="status-badge <?php echo $status_class; ?>">
                                                        <i class="fas <?php echo ($is_expired) ? 'fa-calendar-times' : (($display_status == 'open') ? 'fa-check-circle' : 'fa-times-circle'); ?>"></i>
                                                        <?php echo $status_text; ?>
                                                        <?php if ($is_expired): ?>
                                                            <small>(Date Passed)</small>
                                                        <?php endif; ?>
                                                    </span>
                                                 </td>
                                                <td>
                                                    <?php echo date('d M Y', strtotime($programme['last_date'])); ?>
                                                    <?php if ($days_left >= 0 && $days_left <= 7 && !$is_expired && $display_status == 'open'): ?>
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
                                                 </td>
                                                <td>
                                                    <span class="duration-badge">
                                                        <?php echo $duration_text; ?>
                                                    </span>
                                                 </td>
                                                <td>
                                                    <?php if (count($documents) > 0): ?>
                                                        <div class="document-list-inline">
                                                            <?php foreach ($documents as $doc): ?>
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
                                                 </td>
                                             </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6">
                                                <div class="empty-state">
                                                    <i class="fas fa-inbox"></i>
                                                    <h4>No Internship Programmes Available</h4>
                                                    <p>Please check back later for new opportunities.</p>
                                                </div>
                                             </td>
                                         </tr>
                                    <?php endif; ?>
                                </tbody>
                             </table>
                        </div>
                    </div>

                 

                    <!-- Application Process -->
                    <div class="card custom-card mb-4 border-0">
                        <div class="card-body">
                            <div class="section-heading">
                                <i class="fas fa-file-signature"></i>
                                <h3>Application Process</h3>
                            </div>
                            <ul class="custom-list">
                                <li>
                                    <i class="fas fa-paper-plane"></i>
                                    1. Interested candidates are required to submit the application form. There is no application fee for submitting the application form.
                                </li>
                                <li>
                                    <i class="fas fa-university"></i>
                                    2. The candidate must submit the duly filled application Form attested and endorsed by the Head of the Institution/Department.
                                    <br>
                                    <span class="note-text">
                                        *Applications without official endorsement/attestation will not be considered.
                                    </span>
                                </li>
                                <li>
                                    <i class="fas fa-file-alt"></i>
                                    3. Along with the application form, applicants must include:
                                    <ul class="inner-list">
                                        <li><i class="fas fa-check"></i> A brief Curriculum Vitae (CV)</li>
                                        <li><i class="fas fa-check"></i> A mandatory 500-word Statement of Purpose (SOP) indicating research interest and motivation.</li>
                                    </ul>
                                </li>
                                <li>
                                    <i class="fas fa-calendar-check"></i>
                                    4. Detailed information regarding application timelines, submission deadlines, selection process, and commencement dates will be notified through the Advertisement/Notification of the respective session.
                                </li>
                                <li>
                                    <i class="fas fa-bullhorn"></i>
                                    5. Candidates may check the Active Session / Current Advertisement section below for ongoing application opportunities.
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Terms & Conditions -->
                    <div class="card custom-card mb-4 border-0">
                        <div class="card-body">
                            <div class="section-heading">
                                <i class="fas fa-gavel"></i>
                                <h3>General Terms & Conditions</h3>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <ul class="custom-list">
                                        <li><i class="fas fa-user-tie"></i> 1. Each selected student will be assigned a mentor and shall work exclusively under the guidance of the assigned mentor.</li>
                                        <li><i class="fas fa-user-check"></i> 2. A minimum of 80% attendance is mandatory for the successful completion of the program.</li>
                                        <li><i class="fas fa-users-cog"></i> 3. The number of seats may be increased or decreased at any moment by the approval of the competent Authority.</li>
                                        <li><i class="fas fa-book-medical"></i> 4. Candidates are encouraged to prepare and publish manuscripts with the assistance and guidance of their assigned mentors only.</li>
                                        <li><i class="fas fa-chalkboard-teacher"></i> 5. Each selected candidate will be required to present one paper every week as per the Journal Club schedule.</li>
                                        <li><i class="fas fa-microscope"></i> 6. A weekly lecture series will be conducted by an ICMR Scientist as part of the academic program.</li>
                                        <li><i class="fas fa-shield-alt"></i> 7. Students must strictly adhere to all laboratory rules, regulations, working hours, and safety protocols.</li>
                                    </ul>
                                </div>
                                <div class="col-lg-6">
                                    <ul class="custom-list">
                                           <li><i class="fas fa-shield-alt"></i> 7. Students must strictly adhere to all laboratory rules, regulations, working hours, and safety protocols.</li>
                                        <li><i class="fas fa-home"></i> 8. Hostel and guest house facilities are not available at present. The selected candidates must make their own accommodation arrangements.</li>
                                        <li><i class="fas fa-file-upload"></i> 9. Students are required to present their research work and submit a dissertation/internship report at the end of the training.</li>
                                        <li><i class="fas fa-wallet"></i> 10. No financial support, stipend, hostel, boarding, or lodging facilities will be provided.</li>
                                        <li><i class="fas fa-times-circle"></i> 11. ICMR–NIHR reserves the right to terminate the engagement of any student at any stage in case of non-compliance.</li>
                                        <li><i class="fas fa-ban"></i> 12. Canvassing in any form and at any stage will lead to the direct disqualification of the candidate.</li>
                                    </ul>
                                </div>
                            </div>
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
                            <strong>Dr. Janesh Kumar Gautam</strong>
                        </p>
                        <p class="mb-1">
                            <i class="fas fa-briefcase"></i>
                            Scientist-C & Academic Officer
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-envelope"></i>
                            academic-niirncd@icmr.gov.in
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-phone"></i>
                            0291-2729739
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
                <?php if (count($internship_programmes) > 0): ?>
                $('#internshipDataTable').DataTable({
                    "pageLength": 10,
                    "responsive": true,
                    "language": {
                        "search": "Search:",
                        "lengthMenu": "Show _MENU_ entries",
                        "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                        "emptyTable": "No internship programmes available"
                    },
                    "order": [[3, 'asc']] // Sort by last date
                });
                <?php endif; ?>
            });
        </script>
    </body>
</html>