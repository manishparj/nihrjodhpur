<?php
include('config/config.php');

$committeeQuery = $conn->query("SELECT * FROM committees ORDER BY id ASC");
$committees = [];

while ($row = $committeeQuery->fetch_assoc()) {
    $committeeId = (int)$row['id'];
    $committeeName = htmlspecialchars($row['committee_name'], ENT_QUOTES, 'UTF-8');

    $stmt = $conn->prepare("SELECT employee_name_designation, role FROM committee_members WHERE committee_id = ? ORDER BY id ASC");
    $stmt->bind_param("i", $committeeId);
    $stmt->execute();
    $membersResult = $stmt->get_result();
    $members = [];
    while ($member = $membersResult->fetch_assoc()) {
        $members[] = [
            htmlspecialchars($member['employee_name_designation'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($member['role'], ENT_QUOTES, 'UTF-8')
        ];
    }
    $stmt->close();

    $committees[$committeeId] = [
        'title' => $committeeName,
        'members' => $members
    ];
}

$nodalStmt = $conn->prepare("SELECT id, role, name_designation FROM nodal_officers ORDER BY id ASC");
$nodalStmt->execute();
$nodalResult = $nodalStmt->get_result();
$nodalOfficers = [];
while ($row = $nodalResult->fetch_assoc()) {
    $nodalOfficers[] = [
        'id'               => (int)$row['id'],
        'role'             => htmlspecialchars($row['role'], ENT_QUOTES, 'UTF-8'),
        'name_designation' => htmlspecialchars($row['name_designation'], ENT_QUOTES, 'UTF-8')
    ];
}
$nodalStmt->close();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Committees &amp; Nodal Officers | ICMR-NIIRNCD</title>
    <meta name="description" content="Committees and Nodal Officers at ICMR-NIIRNCD">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&family=Lora:wght@500;600&display=swap" rel="stylesheet">

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
   
</head>
<body id="bg">

    <!-- Preloader -->
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

    <?php include('config/header.php'); ?>

    <main>
        <!-- Hero -->
        <div class="page-hero single-slider slider-height2 d-flex align-items-center"
             style="background-image:url(assets/img/hero/services_hero.jpg);">
            <div class="container">
                <h1>Committees &amp; Nodal Officers</h1>
                <p>Institutional governance, roles, and responsibilities at ICMR-NIIRNCD</p>
            </div>
        </div>

        <!-- Page Body -->
        <div class="page-body">

            <!-- Tab Bar -->
            <div class="tab-bar" role="tablist">
                <button class="tab-btn active" id="tab-committees" role="tab"
                        aria-selected="true" aria-controls="panel-committees"
                        onclick="switchTab('committees')">
                    <span class="tab-icon" aria-hidden="true"><i class="fas fa-building"></i></span>
                    <span class="tab-text">Committees</span>
                    <span class="badge"><?php echo count($committees); ?></span>
                </button>
                <button class="tab-btn" id="tab-nodal" role="tab"
                        aria-selected="false" aria-controls="panel-nodal"
                        onclick="switchTab('nodal')">
                    <span class="tab-icon" aria-hidden="true"><i class="fas fa-user-tie"></i></span>
                    <span class="tab-text">Nodal Officers</span>
                    <span class="badge"><?php echo count($nodalOfficers); ?></span>
                </button>
            </div>

            <!-- ── COMMITTEES PANEL ── -->
            <div class="tab-panel active" id="panel-committees" role="tabpanel">
                <div class="toolbar">
                    <div class="search-wrap">
                        <i class="fas fa-search"></i>
                        <input type="text" class="search-input" id="memberSearch"
                               placeholder="Search members by name, designation or role…"
                               oninput="searchMembers()" autocomplete="off">
                    </div>
                    <select class="filter-select" id="committeeSelect" onchange="filterCommittees()"
                            aria-label="Filter by committee">
                        <option value="all">All Committees</option>
                        <?php foreach ($committees as $id => $committee): ?>
                            <option value="<?= $id ?>"><?= $committee['title'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="count-pill">
                        <strong id="visibleCommitteeCount"><?= count($committees) ?></strong>&nbsp;shown
                    </span>
                </div>

                <div id="committeesContainer">
                    <?php foreach ($committees as $id => $committee):
                        $mc = count($committee['members']);
                    ?>
                    <div class="committee-card" data-committee-id="<?= $id ?>" id="ccard-<?= $id ?>">
                        <div class="card-header" onclick="toggleCard(<?= $id ?>)"
                             aria-expanded="true" role="button"
                             aria-controls="cbody-<?= $id ?>">
                            <div class="card-header-left">
                                <div class="card-header-icon" aria-hidden="true">
                                    <i class="fas fa-layer-group"></i>
                                </div>
                                <h3 title="<?= $committee['title'] ?>"><?= $committee['title'] ?></h3>
                            </div>
                            <div class="card-header-right">
                                <span class="members-pill">
                                    <i class="fas fa-users" aria-hidden="true"></i>
                                    <?= $mc ?> member<?= $mc != 1 ? 's' : '' ?>
                                </span>
                                <i class="fas fa-chevron-down chevron-icon" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="card-body" id="cbody-<?= $id ?>">
                            <?php if (!empty($committee['members'])): ?>
                            <div class="table-responsive">
                                <table class="data-table" aria-label="<?= $committee['title'] ?> members">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name &amp; Designation</th>
                                            <th>Role</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($committee['members'] as $idx => $member): ?>
                                        <tr>
                                            <td class="sn-cell" data-label="#">
                                                <span class="sn"><?= str_pad($idx + 1, 2, '0', STR_PAD_LEFT) ?></span>
                                            </td>
                                            <td data-label="Name">
                                                <span class="member-name"><?= $member[0] ?></span>
                                            </td>
                                            <td data-label="Role">
                                                <span class="role-tag">
                                                    <i class="fas fa-circle" aria-hidden="true"
                                                       style="font-size:5px;opacity:.6;"></i>
                                                    <?= $member[1] ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="fas fa-user-slash" aria-hidden="true"></i>
                                    <p>No members listed for this committee.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <?php if (empty($committees)): ?>
                        <div class="empty-state">
                            <i class="fas fa-folder-open" aria-hidden="true"></i>
                            <p>No committees have been added yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ── NODAL OFFICERS PANEL ── -->
            <div class="tab-panel" id="panel-nodal" role="tabpanel">
                <div class="toolbar">
                    <div class="search-wrap">
                        <i class="fas fa-search"></i>
                        <input type="text" class="search-input" id="nodalSearch"
                               placeholder="Search by role, name or designation…"
                               oninput="searchNodal()" autocomplete="off">
                    </div>
                    <span class="count-pill">
                        <strong id="visibleNodalCount"><?= count($nodalOfficers) ?></strong>&nbsp;officers
                    </span>
                </div>

                <?php if (!empty($nodalOfficers)): ?>
                <div class="nodal-table-wrap">
                    <div class="table-responsive">
                        <table class="nodal-table" id="nodalTable"
                               aria-label="Nodal Officers">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Role</th>
                                    <th>Officer Name &amp; Designation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($nodalOfficers as $i => $officer):
                                    $initials = implode('', array_map(fn($w) => strtoupper($w[0]), array_slice(explode(' ', $officer['name_designation']), 0, 2)));
                                ?>
                                <tr class="nodal-row">
                                    <td data-label="#">
                                        <span class="sn"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></span>
                                    </td>
                                    <td data-label="NRole">
                                        <span class="nodal-role-tag">
                                            <i class="fas fa-tasks" aria-hidden="true"></i>
                                            <?= $officer['role'] ?>
                                        </span>
                                    </td>
                                    <td data-label="NOfficer">
                                        <div class="officer-name">
                                            <div class="officer-avatar" aria-hidden="true">
                                                <i class="fas fa-user" aria-hidden="true"></i>
                                            </div>
                                            <span><?= $officer['name_designation'] ?></span>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-user-tie" aria-hidden="true"></i>
                        <p>No nodal officers have been added yet.</p>
                    </div>
                <?php endif; ?>
            </div>

        </div><!-- /page-body -->
    </main>

    <?php include "./config/footer.php"; ?>

    <!-- JS -->
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

    <script>
    // ── Card Body Heights (set initial max-height) ──
    document.querySelectorAll('.card-body').forEach(function(body) {
        body.style.maxHeight = body.scrollHeight + 'px';
    });

    // ── Tab Switch ──
    function switchTab(name) {
        document.querySelectorAll('.tab-btn').forEach(function(btn) {
            var isActive = btn.id === 'tab-' + name;
            btn.classList.toggle('active', isActive);
            btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
        });
        document.querySelectorAll('.tab-panel').forEach(function(panel) {
            panel.classList.toggle('active', panel.id === 'panel-' + name);
        });
    }

    // ── Toggle Card Collapse ──
    function toggleCard(id) {
        var card = document.getElementById('ccard-' + id);
        var body = document.getElementById('cbody-' + id);
        var isCollapsed = card.classList.contains('collapsed');
        if (isCollapsed) {
            card.classList.remove('collapsed');
            body.style.maxHeight = body.scrollHeight + 'px';
        } else {
            card.classList.add('collapsed');
            body.style.maxHeight = '0';
        }
        card.querySelector('.card-header').setAttribute('aria-expanded', isCollapsed ? 'true' : 'false');
    }

    // ── Filter by Committee ──
    function filterCommittees() {
        var sel = document.getElementById('committeeSelect').value;
        var cards = document.querySelectorAll('#committeesContainer .committee-card');
        var visible = 0;
        cards.forEach(function(card) {
            var show = sel === 'all' || card.getAttribute('data-committee-id') === sel;
            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        document.getElementById('visibleCommitteeCount').textContent = visible;
        document.getElementById('memberSearch').value = '';
    }

    // ── Search Committee Members ──
    function searchMembers() {
        var q = document.getElementById('memberSearch').value.trim().toLowerCase();
        var cards = document.querySelectorAll('#committeesContainer .committee-card');
        var sel = document.getElementById('committeeSelect').value;
        var visible = 0;

        cards.forEach(function(card) {
            if (sel !== 'all' && card.getAttribute('data-committee-id') !== sel) return;
            var rows = card.querySelectorAll('tbody tr');
            var anyMatch = false;

            rows.forEach(function(row) {
                var match = !q || row.textContent.toLowerCase().includes(q);
                row.style.display = match ? '' : 'none';
                if (match) anyMatch = true;
            });

            card.style.display = anyMatch || !q ? '' : 'none';
            if (card.style.display !== 'none') visible++;

            // Expand if search has results
            if (q && anyMatch) {
                var body = card.querySelector('.card-body');
                card.classList.remove('collapsed');
                body.style.maxHeight = body.scrollHeight + 'px';
            }
        });

        document.getElementById('visibleCommitteeCount').textContent = visible;
    }

    // ── Search Nodal Officers ──
    function searchNodal() {
        var q = document.getElementById('nodalSearch').value.trim().toLowerCase();
        var rows = document.querySelectorAll('#nodalTable tbody tr');
        var visible = 0;

        rows.forEach(function(row) {
            var match = !q || row.textContent.toLowerCase().includes(q);
            row.style.display = match ? '' : 'none';
            if (match) visible++;
        });
        document.getElementById('visibleNodalCount').textContent = visible;
    }

    // ── Font size accessibility ──
    $(document).ready(function() {
        $('#btn1').on('click', function() { $('body').css('font-size', '18px'); });
        $('#btn2').on('click', function() { $('body').css('font-size', '16px'); });
        $('#btn3').on('click', function() { $('body').css('font-size', '13px'); });
    });
    </script>
</body>
</html>