<?php
include('config/config.php');
?>
<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>ICMR NIHR Directory</title>
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
    <link rel="stylesheet" href="assets/css/templete.css">

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="stylenav.css">
    <link rel="stylesheet" href="assets/datatables/dataTables.bootstrap4.css">
    <style>
/* ===== Styling ===== */

.directory-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 20px 45px rgba(0,0,0,0.06);
    margin-bottom: 40px;
    overflow: hidden;
}
table {
    width: 100%;
    border-collapse: collapse;
}
caption {
    padding: 18px 22px;
    font-size: clamp(18px, 2.4vw, 22px);
    font-weight: 700;
    color: #003679;
    background: linear-gradient(135deg, #eef4ff, #ffffff);
    text-align: left;
}
thead th {
    background: #003679;
    color: #fff;
    padding: 12px 14px;
    text-align: left;
    font-size: 14px;
}
tbody td {
    padding: 12px 14px;
    font-size: 14px;
    border-bottom: 1px solid #eef2f7;
}
tbody tr:nth-child(even) { background: #f9fbff; }

/* Responsive */
@media (max-width: 768px) {
    thead { display: none; }
    table, tbody, tr, td { display: block; width: 100%; }
    tbody tr {
        background: #fff;
        margin-bottom: 16px;
        padding: 12px 14px;
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    }
    td {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        font-size: 13px;
    }
    td::before {
        content: attr(data-label);
        font-weight: 600;
        color: #6b7280;
        font-size: 12px;
        text-transform: uppercase;
    }
}
caption {
    caption-side: top;   /* forces caption above <thead> */
    padding: 18px 22px;
    font-size: clamp(18px, 2.4vw, 22px);
    font-weight: 700;
    color: #003679;
    text-align: left;
    background: linear-gradient(135deg, #eef4ff, #ffffff);
    border-bottom: 2px solid #003679;
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

<!-- Hero -->
<div class="slider-area">
    <div class="single-slider slider-height2 d-flex align-items-center" style="background-image:url(assets/img/hero/services_hero.jpg);">
        <div class="container text-center">
            <h2 class="text-white">ICMR NIHR Directory</h2>
        </div>
    </div>
</div>

<div class="section-full bg-white">
    <div class="container mt-5">
        
        <?php
        // Fetch all employees grouped by section
        $sections = ['Director', 'Scientists', 'Technical Staff', 'Ministerial Staff', 'Supporting Staff'];
        
        foreach ($sections as $section) {
            // Prepare query to fetch employees for this section
            $stmt = mysqli_prepare($conn, "SELECT name, designation, email, phone FROM employees_directory WHERE section = ? ORDER BY serial_order ASC, name ASC");
            mysqli_stmt_bind_param($stmt, "s", $section);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            // Check if section has any employees
            if (mysqli_num_rows($result) > 0) {
        ?>
                <div class="directory-card" style="background: #fff; border-radius: 20px; box-shadow: 0 20px 45px rgba(0,0,0,0.06); margin-bottom: 40px; overflow: hidden;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <caption style="caption-side: top; padding: 18px 22px; font-size: clamp(18px, 2.4vw, 22px); font-weight: 700; color: #003679; background: linear-gradient(135deg, #eef4ff, #ffffff); text-align: left; border-bottom: 2px solid #003679;">
                            <i class="fas fa-users me-2"></i> <?= htmlspecialchars($section) ?> 
                            <span style="font-size: 14px; background: #003679; color: white; padding: 4px 12px; border-radius: 20px; margin-left: 10px;">
                                <?= mysqli_num_rows($result) ?> Members
                            </span>
                        </caption>
                        <thead>
                            <tr style="background: #003679; color: #fff;">
                                <th style="padding: 12px 14px; text-align: left;">Sr. No.</th>
                                <th style="padding: 12px 14px; text-align: left;">Name</th>
                                <th style="padding: 12px 14px; text-align: left;">Designation</th>
                                <th style="padding: 12px 14px; text-align: left;">Email</th>
                                <th style="padding: 12px 14px; text-align: left;">Phone</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i = 1;
                            while ($row = mysqli_fetch_assoc($result)) { 
                            ?>
                                <tr style="border-bottom: 1px solid #eef2f7;">
                                    <td data-label="Sr. No." style="padding: 12px 14px;"><?= $i++ ?></td>
                                    <td data-label="Name" style="padding: 12px 14px;">
                                        <strong><?= htmlspecialchars($row['name']) ?></strong>
                                    </td>
                                    <td data-label="Designation" style="padding: 12px 14px;"><?= htmlspecialchars($row['designation']) ?></td>
                                    <td data-label="Email" style="padding: 12px 14px;">
                                        <a href="mailto:<?= htmlspecialchars($row['email']) ?>" style="text-decoration: none; color: #003679;">
                                            <i class="fas fa-envelope me-1"></i> <?= htmlspecialchars($row['email']) ?>
                                        </a>
                                    </td>
                                    <td data-label="Phone" style="padding: 12px 14px;">
                                        <?php if (!empty($row['phone'])): ?>
                                            <a href="tel:<?= htmlspecialchars($row['phone']) ?>" style="text-decoration: none; color: #003679;">
                                                <i class="fas fa-phone me-1"></i> <?= htmlspecialchars($row['phone']) ?>
                                            </a>
                                        <?php else: ?>
                                            <span style="color: #999;">—</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
        <?php
            }
            mysqli_stmt_close($stmt);
        }
        
        // Check if no data at all
        $check_query = "SELECT COUNT(*) as total FROM employees_directory";
        $check_result = mysqli_query($conn, $check_query);
        $total_rows = mysqli_fetch_assoc($check_result)['total'];
        
        if ($total_rows == 0) {
            echo '<div class="alert alert-info text-center py-5" style="border-radius: 20px;">
                    <i class="fas fa-info-circle fa-3x mb-3" style="color: #003679;"></i>
                    <h4>No employees found in the directory</h4>
                    <p>Please add employees using the form above.</p>
                  </div>';
        }
        
        mysqli_close($conn);
        ?>

    </div>
</div>

<style>
    /* Responsive table styles */
    @media (max-width: 768px) {
        .directory-card table thead {
            display: none;
        }
        
        .directory-card table tbody tr {
            display: block;
            margin-bottom: 16px;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            background: white;
            padding: 10px;
        }
        
        .directory-card table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 12px !important;
            border-bottom: 1px dashed #eef2f6;
        }
        
        .directory-card table tbody td:last-child {
            border-bottom: none;
        }
        
        .directory-card table tbody td::before {
            content: attr(data-label);
            font-weight: 700;
            color: #003679;
            width: 35%;
            font-size: 0.8rem;
        }
        
        .directory-card caption {
            font-size: 18px !important;
        }
    }
    
    .directory-card tr:hover {
        background: #f8fbff;
    }
    
    .directory-card a:hover {
        text-decoration: underline !important;
    }
</style>

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
				$("#bg").css("fontSize", "18px");
                $(".card-text").css("fontSize", "18px");

			});
			$('#btn2').click(function() {
				$("#bg").css("fontSize", "16px");
				$(".card-text").css("fontSize", "16px");


			});
			$('#btn3').click(function() {
				$("#bg").css("fontSize", "13px");
				$(".card-text").css("fontSize", "13px");


			});

		});
	</script>

</body>

</html>
