<?php
include('config/config.php');

// Fetch all committees
$committeeQuery = $conn->query("SELECT * FROM committees ORDER BY id ASC");
$committees = [];

while ($row = $committeeQuery->fetch_assoc()) {
    $committeeId = $row['id'];
    $committeeName = $row['committee_name'];

    // Fetch members for this committee
    $membersQuery = $conn->query("SELECT employee_name_designation, role FROM committee_members WHERE committee_id = $committeeId ORDER BY id ASC");
    $members = [];
    while ($member = $membersQuery->fetch_assoc()) {
        $members[] = [$member['employee_name_designation'], $member['role']];
    }

    // Store committee data
    $committees[$committeeId] = [
        'title' => $committeeName,
        'columns' => ['Sl. No.', 'Name & Designation', 'Role'],
        'members' => $members
    ];
}
?>
<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Committee | ICMR-NIIRNCD </title>
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
    <link rel="stylesheet" href="./committee.css">
    <link rel="stylesheet" href="./config/footer.css">
    <link rel="stylesheet" href="assets/datatables/dataTables.bootstrap4.css">
    

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

        <!-- Committee Header -->
        <div class="committee-header single-slider slider-height2 d-flex align-items-center" style="background-image:url(assets/img/hero/services_hero.jpg);"">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <h2>Institute Committees</h2>
                        <p>Discover our diverse committees driving excellence and innovation</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="container mt-5">
            <div class="filter-section">
                <div class="row align-items-end">
                     <div class="col-md-3 col-3">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo count($committees); ?></div>
                        <div class="stat-label">Total Committees</div>
                    </div>
                </div>
                
                    <div class="col-lg-5 col-md-6 mb-3">
                        <label class="filter-label">
                            <i class="fas fa-filter"></i> Select Committee
                        </label>
                        <select class="form-select-custom w-100" id="committeeSelect" onchange="filterCommittees()" style="border: 2px solid #e0e0e0; border-radius: 12px; padding: 12px 15px;">
                            <option value="all">📋 All Committees</option>
                            <?php foreach ($committees as $id => $committee): ?>
                                <option value="<?= $id ?>">📌 <?= $committee['title'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <label class="filter-label">
                            <i class="fas fa-search"></i> Search Members
                        </label>
                        <input type="text"
                            id="memberSearch"
                            class="form-control-custom w-100"
                            placeholder="🔍 Search by name, designation or role..."
                            onkeyup="searchMembers()"
                            style="border: 2px solid #e0e0e0; border-radius: 12px; padding: 12px 15px;">
                    </div>
                    
                </div>
            </div>
        </div>

        <!-- Committees Display -->
        <div class="container mt-5 pb-5">
    <div class="row" id="committeesContainer">
        <?php 
        // Calculate total members across all committees
        $total_all_members = 0;
        foreach ($committees as $committee) {
            $total_all_members += count($committee['members']);
        }
        krsort($committees);
        ?>
        <?php foreach ($committees as $id => $committee): 
            $member_count = count($committee['members']);
        ?>
            <div class="col-12 committee-wrapper" data-committee-id="<?= $id ?>">
                <div class="committee-card">
                    <div class="card-header-custom" data-bs-toggle="collapse" data-bs-target="#committee<?= $id ?>" aria-expanded="true">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h3 class="mb-0">
                                <span>
                                    <i class="fas fa-building me-2"></i> <?= htmlspecialchars($committee['title']) ?>
                                </span>
                            </h3>
                            <div class="committee-stats">
                                <span class="member-count-badge">
                                    <i class="fas fa-users me-1"></i> <?= $member_count ?> Member<?= $member_count != 1 ? 's' : '' ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div id="committee<?= $id ?>" class="collapse show">
                        <div class="table-responsive">
                            <table class="table table-custom">
                                <thead>
                                    <tr>
                                        <?php foreach ($committee['columns'] as $col): ?>
                                            <th><?= htmlspecialchars($col) ?></th>
                                        <?php endforeach; ?>
                                        <!-- Optional: Add a column for member count per row? Not needed -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($committee['members'] as $index => $member): ?>
                                        <tr>
                                            <td class="serial-number"><?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?></td>
                                            <td>
                                                <strong><?= htmlspecialchars($member[0]) ?></strong>
                                            </td>
                                            <td>
                                                <span class="role-badge">
                                                    <i class="fas fa-tag me-1"></i> <?= htmlspecialchars($member[1]) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        
        <?php if(empty($committees)): ?>
            <div class="empty-state">
                <i class="fas fa-folder-open"></i>
                <h4>No Committees Found</h4>
                <p>Committees will be displayed here once added.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    /* Summary Bar Styles */
    .summary-bar {
        background: linear-gradient(135deg, #003679 0%, #1a4d8c 100%);
        color: white;
        padding: 15px 25px;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    
    .summary-bar h5 {
        font-size: 1rem;
    }
    
    .summary-bar strong {
        font-size: 1.2rem;
        background: rgba(255,255,255,0.2);
        padding: 4px 12px;
        border-radius: 20px;
        margin-left: 8px;
    }
    
    /* Committee Card Styles */
    .committee-card {
        background: white;
        border-radius: 20px;
        margin-bottom: 25px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .committee-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.12);
    }
    
    .card-header-custom {
        background: linear-gradient(135deg, #003679 0%, #1a4d8c 100%);
        padding: 18px 25px;
        cursor: pointer;
        transition: all 0.3s;
        border-radius: 20px 20px 0 0;
    }
    
    .card-header-custom:hover {
        background: linear-gradient(135deg, #002255 0%, #0d3b6e 100%);
    }
    
    .card-header-custom h3 {
        font-size: 1.3rem;
        font-weight: 600;
        color: white;
        margin: 0;
        display: inline-block;
    }
    
    .committee-stats {
        display: inline-block;
    }
    
    .member-count-badge {
        background: rgba(255,255,255,0.2);
        padding: 6px 16px;
        border-radius: 30px;
        font-size: 0.85rem;
        font-weight: 500;
        color: white;
        backdrop-filter: blur(5px);
    }
    
    /* Table Styles */
    .table-custom {
        width: 100%;
        margin-bottom: 0;
    }
    
    .table-custom thead th {
        background: #f8fafc;
        color: #1e293b;
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 15px 20px;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .table-custom tbody td {
        padding: 14px 20px;
        vertical-align: middle;
        border-bottom: 1px solid #eef2f6;
        font-size: 0.9rem;
    }
    
    .table-custom tbody tr:hover {
        background: #f8fbff;
    }
    
    .serial-number {
        color: #64748b;
        font-weight: 600;
        width: 70px;
    }
    
    .role-badge {
        background: #eef2ff;
        color: #003679;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .table-footer {
        background: #f8fafc;
    }
    
    .table-footer td {
        padding: 12px 20px;
        border-top: 2px solid #e2e8f0;
    }
    
    .footer-stats {
        color: #475569;
        font-size: 0.85rem;
    }
    
    .footer-stats strong {
        color: #003679;
        font-size: 1rem;
        margin-left: 5px;
    }
    
    /* Collapse Animation */
    .collapse {
        transition: all 0.3s ease;
    }
    
    /* Responsive Styles */
    @media (max-width: 768px) {
        .card-header-custom {
            padding: 15px 20px;
        }
        
        .card-header-custom h3 {
            font-size: 1.1rem;
        }
        
        .member-count-badge {
            font-size: 0.75rem;
            padding: 4px 12px;
        }
        
        .summary-bar {
            text-align: center;
        }
        
        .summary-bar .col-md-6.text-md-end {
            text-align: center !important;
            margin-top: 10px;
        }
        
        .committee-stats {
            margin-top: 8px;
        }
        
        /* Mobile table view */
        .table-custom thead {
            display: none;
        }
        
        .table-custom tbody tr {
            display: block;
            margin-bottom: 15px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: white;
        }
        
        .table-custom tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            border-bottom: 1px dashed #eef2f6;
        }
        
        .table-custom tbody td:last-child {
            border-bottom: none;
        }
        
        .table-custom tbody td::before {
            content: attr(data-label);
            font-weight: 700;
            color: #003679;
            width: 40%;
            font-size: 0.8rem;
        }
        
        .serial-number {
            width: auto;
        }
    }
</style>

<script>
    // Optional: Add data-label attributes for mobile view
    document.addEventListener('DOMContentLoaded', function() {
        const tables = document.querySelectorAll('.table-custom');
        tables.forEach(table => {
            const headers = table.querySelectorAll('thead th');
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                cells.forEach((cell, index) => {
                    if (headers[index]) {
                        cell.setAttribute('data-label', headers[index].innerText);
                    }
                });
            });
        });
    });
</script>

    </main>

    <!-- Footer -->
    <?php include "./config/footer.php"; ?>

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
        // Font size controls
        $(document).ready(function() {
            $('#btn1').click(function() {
                $("#bg").css("fontSize", "18px");
                $(".card-text").css("fontSize", "18px");
                $(".table-custom").css("fontSize", "1rem");
            });
            $('#btn2').click(function() {
                $("#bg").css("fontSize", "16px");
                $(".card-text").css("fontSize", "16px");
                $(".table-custom").css("fontSize", "0.95rem");
            });
            $('#btn3').click(function() {
                $("#bg").css("fontSize", "13px");
                $(".card-text").css("fontSize", "13px");
                $(".table-custom").css("fontSize", "0.85rem");
            });
            
            // Initialize all cards as expanded
            $('.collapse').collapse('show');
            
            // Back to top button visibility
            $(window).scroll(function() {
                if ($(this).scrollTop() > 300) {
                    $('.back-to-top').addClass('show');
                } else {
                    $('.back-to-top').removeClass('show');
                }
            });
        });
        
        function filterCommittees() {
            const selected = document.getElementById('committeeSelect').value;
            const committees = document.querySelectorAll('.committee-wrapper');
            
            if (selected === 'all') {
                committees.forEach(committee => {
                    committee.style.display = 'block';
                });
            } else {
                committees.forEach(committee => {
                    if (committee.getAttribute('data-committee-id') === selected) {
                        committee.style.display = 'block';
                    } else {
                        committee.style.display = 'none';
                    }
                });
            }
            
            // Reset search when changing committee
            document.getElementById('memberSearch').value = '';
            searchMembers();
        }
        
        function searchMembers() {
            const searchTerm = document.getElementById('memberSearch').value.toLowerCase();
            const visibleCommittees = document.querySelectorAll('.committee-wrapper[style="display: block"], .committee-wrapper:not([style*="display"])');
            
            visibleCommittees.forEach(committee => {
                const rows = committee.querySelectorAll('tbody tr');
                let hasVisibleRow = false;
                
                rows.forEach(row => {
                    const text = row.innerText.toLowerCase();
                    if (text.includes(searchTerm)) {
                        row.style.display = '';
                        hasVisibleRow = true;
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                // Hide committee card if no matching rows
                const card = committee.querySelector('.committee-card');
                if (hasVisibleRow) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
        
    </script>

</body>

</html>