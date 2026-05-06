<?php
include('config/config.php');

// Fetch RTI Officers from database
$query = "SELECT * FROM rti_officers ORDER BY display_order ASC, id ASC";
$result = mysqli_query($conn, $query);
$rti_officers = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rti_officers[] = $row;
}

// Fetch Nodal Officer
$nodal_query = "SELECT * FROM rti_nodal_officer LIMIT 1";
$nodal_result = mysqli_query($conn, $nodal_query);
$nodal_officer = mysqli_fetch_assoc($nodal_result);

// Fetch Documents
$doc_query = "SELECT * FROM rti_documents";
$doc_result = mysqli_query($conn, $doc_query);
$documents = [];
while ($row = mysqli_fetch_assoc($doc_result)) {
    $documents[$row['document_type']] = $row;
}
?>
<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>RTI-ACT | ICMR-NIIRNCD </title>
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

<!-- Custom CSS -->
<style>
.rti-section {
    background: linear-gradient(to right, #f8f9fa, #eef4ff);
}

.bg-gradient {
    background: #003679;
}

.rti-table {
    border-radius: 12px;
    overflow: hidden;
}

.rti-table thead {
    background-color: #003679;
    color: #fff;
}

.rti-table th,
.rti-table td {
    vertical-align: middle;
    padding: 16px;
}

.rti-table tbody tr {
    transition: 0.2s ease-in-out;
}

.rti-table tbody tr:hover {
    background-color: #f1f5ff;
}

.rti-nodal-card {
    background-color: #ffffff;
    border: 1px solid #eef1f6;
}

a {
    text-decoration: none;
    font-weight: 500;
}

a:hover {
    text-decoration: underline;
}

.document-link {
    transition: all 0.3s ease;
    display: inline-block;
}

.document-link:hover {
    transform: translateX(5px);
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
                    <img src="assets/img/logo/loaderlogo.jpg" alt="">
                </div>
            </div>
        </div>
    </div>
    <!-- Preloader Start -->

    <?php include('config/header.php'); ?>


    <main>

        <!-- slider Area Start-->
        <div class="slider-area">
            <!-- Mobile Menu -->
            <div class="single-slider slider-height2 d-flex align-items-center" style="background-image:url(assets/img/hero/services_hero.jpg);">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="hero-cap text-center">
                                <h2>Right to Information Act-2005</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- slider Area End-->

        <section class="rti-section py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-12">

                        <!-- Card Container -->
                        <div class="card shadow-lg border-0 rounded-4">

                            <!-- Header -->
                            <div class="card-header bg-gradient text-white text-center py-4 rounded-top-4" style="background-color:#134b8a">
                                <h3 class="mb-0 fw-bold">Right to Information (RTI) Act - 2005</h3>
                                <p class="mb-0" style="color: #fff;">Designated Authorities & Important Links</p>
                            </div>

                            <!-- Intro -->
                            <div class="card-body px-4 py-4">
                                <p class="text-muted text-justify">
                                    In pursuance of Section 5(1) and Section 19(1) of the Right to Information Act, 2005,
                                    the following officers of ICMR – National Institute of Health Research, Jodhpur have been designated as Central Public Information
                                    Officers and Appellate Authorities.
                                </p>

                                <!-- Dynamic Table -->
                                <div class="table-responsive mt-4">
                                    <table class="table rti-table align-middle">
                                        <thead>
                                            <tr>
                                                <th style="width:5%">#</th>
                                                <th style="width:20%">Subject Matter</th>
                                                <th style="width:50%">Central Public Information Officer (CPIO)</th>
                                                <th style="width:60%">First Appellate Authority (FAA)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(empty($rti_officers)): ?>
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted py-4">
                                                        No RTI officers found in the database.
                                                    </td>
                                                </tr>
                                            <?php else: ?>
                                                <?php $counter = 1; foreach($rti_officers as $officer): ?>
                                                <tr>
                                                    <td class="text-center fw-bold"><?php echo $counter++; ?></td>
                                                    <td>
                                                        <strong><?php echo htmlspecialchars($officer['subject_matter']); ?></strong>
                                                    </td>
                                                    <td>
                                                        <strong><?php echo htmlspecialchars($officer['cpio_name']); ?></strong><br>
                                                        <span class="text-muted"><?php echo htmlspecialchars($officer['cpio_designation']); ?></span><br>
                                                        📞 <?php echo htmlspecialchars($officer['cpio_phone']); ?><br>
                                                        ✉️ <?php echo htmlspecialchars($officer['cpio_email']); ?>
                                                    </td>
                                                    <td>
                                                        <strong><?php echo htmlspecialchars($officer['faa_name']); ?></strong><br>
                                                        <span class="text-muted"><?php echo htmlspecialchars($officer['faa_designation']); ?></span><br>
                                                        📞 <?php echo htmlspecialchars($officer['faa_phone']); ?><br>
                                                        ✉️ <?php echo htmlspecialchars($officer['faa_email']); ?>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Nodal Officer Card -->
                                <div class="rti-nodal-card p-4 rounded-4 shadow-sm mt-4">
                                    <h5 class="fw-bold mb-3 text-primary text-center" style="color: #003679 !important;">
                                        Nodal Officer (RTI) – Coordination & Online Portal
                                    </h5>

                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <div class="p-3 bg-light rounded-3 h-100">
                                                <?php if($nodal_officer): ?>
                                                    <strong><?php echo htmlspecialchars($nodal_officer['name']); ?></strong><br>
                                                    <span class="text-muted"><?php echo htmlspecialchars($nodal_officer['designation']); ?></span><br><br>
                                                    📞 <?php echo htmlspecialchars($nodal_officer['phone']); ?><br>
                                                    ✉️ <?php echo htmlspecialchars($nodal_officer['email']); ?>
                                                <?php else: ?>
                                                    <p class="text-muted">Nodal officer information not available.</p>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- RTI Links -->
                                        <div class="col-md-6">
    <div class="p-3 bg-light rounded-3 h-100">
        <strong>RTI Documents</strong>
        <ul class="list-unstyled mt-3">
            <?php if(isset($documents['english_act']) && !empty($documents['english_act'])): ?>
                <li class="mb-2 document-link">
                    <a href="admin/<?php echo htmlspecialchars($documents['english_act']['file_path']); ?>" target="_blank">
                        📄 <?php echo htmlspecialchars($documents['english_act']['title']); ?>
                    </a>
                </li>
            <?php else: ?>
                <li class="mb-2 text-muted">📄 English Version (Not available)</li>
            <?php endif; ?>
            
            <?php if(isset($documents['hindi_act']) && !empty($documents['hindi_act'])): ?>
                <li class="mb-2 document-link">
                    <a href="admin/<?php echo htmlspecialchars($documents['hindi_act']['file_path']); ?>" target="_blank">
                        📄 <?php echo htmlspecialchars($documents['hindi_act']['title']); ?>
                    </a>
                </li>
            <?php else: ?>
                <li class="mb-2 text-muted">📄 Hindi Version (Not available)</li>
            <?php endif; ?>
            
            <?php if(isset($documents['office_order']) && !empty($documents['office_order'])): ?>
                <li class="mb-2 document-link">
                    <a href="admin/<?php echo htmlspecialchars($documents['office_order']['file_path']); ?>" target="_blank">
                        📄 <?php echo htmlspecialchars($documents['office_order']['title']); ?>
                    </a>
                </li>
            <?php else: ?>
                <li class="mb-2 text-muted">📄 RTI Office Order (Not available)</li>
            <?php endif; ?>
        </ul>
    </div>
</div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>

    </main>
    <footer>
        <!-- Footer Start-->

        <!-- footer-bottom aera -->
        <?php include('./config/footer.php'); ?>

        <!-- Footer End-->
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
    <script src="./assets/js/jquery-2.2.4.min.js"></script>
    <script src="./assets/js/plugins.js"></script>
    <script src="./assets/js/main.js"></script>
    <script src="./assets/js/active.js"></script>

    <script src="./assets/js/datatables-demo.js"></script>
    <script src="./assets/datatables/jquery.dataTables.min.js"></script>
    <script src="./assets/datatables/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#btn1').click(function() {
                $(".table").css("fontSize", "18px");
                $(".card-text").css("fontSize", "18px");
            });

            $('#btn2').click(function() {
                $(".table").css("fontSize", "16px");
                $(".card-text").css("fontSize", "16px");
            });

            $('#btn3').click(function() {
                $(".table").css("fontSize", "13px");
                $(".card-text").css("fontSize", "13px");
            });
        });
    </script>
</body>

</html>