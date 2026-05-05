<?php
session_start();

// Include database configuration
include('inc/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}

// ============================================================
// CREATE TABLE IF NOT EXISTS
// ============================================================
$table_query = "
CREATE TABLE IF NOT EXISTS employees_directory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    designation VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(50) DEFAULT NULL,
    section VARCHAR(100) NOT NULL,
    serial_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

mysqli_query($conn, $table_query);

// Add serial_order column if not exists
$result = mysqli_query($conn, "SHOW COLUMNS FROM employees_directory LIKE 'serial_order'");
if (mysqli_num_rows($result) == 0) {
    mysqli_query($conn, "ALTER TABLE employees_directory ADD COLUMN serial_order INT DEFAULT 0");
}

// Modify phone column to allow NULL if it exists and is NOT NULL
$result = mysqli_query($conn, "SHOW COLUMNS FROM employees_directory LIKE 'phone'");
if (mysqli_num_rows($result) > 0) {
    $col_info = mysqli_fetch_assoc($result);
    if ($col_info['Null'] == 'NO') {
        mysqli_query($conn, "ALTER TABLE employees_directory MODIFY phone VARCHAR(50) DEFAULT NULL");
    }
}

// ============================================================
// HELPER FUNCTIONS
// ============================================================
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// ============================================================
// CRUD OPERATIONS (NO VALIDATIONS - PHONE OPTIONAL)
// ============================================================

// CREATE
if (isset($_POST['action']) && $_POST['action'] == 'create') {
    $name = sanitizeInput($_POST['name']);
    $designation = sanitizeInput($_POST['designation']);
    $email = sanitizeInput($_POST['email']);
    $phone = !empty($_POST['phone']) ? sanitizeInput($_POST['phone']) : null;
    $section = sanitizeInput($_POST['section']);
    $serial_order = intval($_POST['serial_order']);
    
    $errors = [];
    
    if (empty($name)) $errors[] = "Name is required";
    if (empty($designation)) $errors[] = "Designation is required";
    if (empty($email)) $errors[] = "Email is required";
    // Phone is OPTIONAL - no validation or required check
    if (empty($section)) $errors[] = "Section is required";
    
    if (empty($errors)) {
        $stmt = mysqli_prepare($conn, "INSERT INTO employees_directory (name, designation, email, phone, section, serial_order) VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssssi", $name, $designation, $email, $phone, $section, $serial_order);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['message'] = "Employee added successfully to " . $section . " section!";
            $_SESSION['message_type'] = "success";
        } else {
            if (mysqli_errno($conn) == 1062) {
                $_SESSION['message'] = "Email already exists!";
            } else {
                $_SESSION['message'] = "Error: " . mysqli_error($conn);
            }
            $_SESSION['message_type'] = "danger";
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['message'] = implode(", ", $errors);
        $_SESSION['message_type'] = "danger";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// UPDATE - Get employee data for edit
$edit_employee = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $result = mysqli_query($conn, "SELECT * FROM employees_directory WHERE id = $id");
    if ($result && mysqli_num_rows($result) > 0) {
        $edit_employee = mysqli_fetch_assoc($result);
    }
}

// UPDATE - Process update
if (isset($_POST['action']) && $_POST['action'] == 'update') {
    $id = intval($_POST['id']);
    $name = sanitizeInput($_POST['name']);
    $designation = sanitizeInput($_POST['designation']);
    $email = sanitizeInput($_POST['email']);
    $phone = !empty($_POST['phone']) ? sanitizeInput($_POST['phone']) : null;
    $section = sanitizeInput($_POST['section']);
    $serial_order = intval($_POST['serial_order']);
    
    $errors = [];
    
    if (empty($name)) $errors[] = "Name is required";
    if (empty($designation)) $errors[] = "Designation is required";
    if (empty($email)) $errors[] = "Email is required";
    // Phone is OPTIONAL - no validation or required check
    if (empty($section)) $errors[] = "Section is required";
    
    if (empty($errors)) {
        $stmt = mysqli_prepare($conn, "UPDATE employees_directory SET name=?, designation=?, email=?, phone=?, section=?, serial_order=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "sssssii", $name, $designation, $email, $phone, $section, $serial_order, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['message'] = "Employee updated successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            if (mysqli_errno($conn) == 1062) {
                $_SESSION['message'] = "Email already exists!";
            } else {
                $_SESSION['message'] = "Error: " . mysqli_error($conn);
            }
            $_SESSION['message_type'] = "danger";
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['message'] = implode(", ", $errors);
        $_SESSION['message_type'] = "danger";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// DELETE
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = mysqli_prepare($conn, "DELETE FROM employees_directory WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = "Employee deleted successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error deleting record: " . mysqli_error($conn);
        $_SESSION['message_type'] = "danger";
    }
    mysqli_stmt_close($stmt);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Get all employees grouped by section
$sections = ['Director', 'Scientists', 'Technical Staff', 'Ministerial Staff', 'Supporting Staff'];
$grouped_data = [];

foreach ($sections as $section) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM employees_directory WHERE section = ? ORDER BY serial_order ASC, name ASC");
    mysqli_stmt_bind_param($stmt, "s", $section);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $grouped_data[$section] = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $grouped_data[$section][] = $row;
    }
    mysqli_stmt_close($stmt);
}

// Get total count
$total_count = 0;
foreach ($grouped_data as $emps) {
    $total_count += count($emps);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ICMR NIIRNCD - Employee Directory Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-blue: #003679;
            --primary-dark: #002255;
            --section-director: #8B0000;
            --section-scientists: #2E7D32;
            --section-technical: #1565C0;
            --section-ministerial: #E65100;
            --section-supporting: #6A1B9A;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fc 0%, #eef2f8 100%);
            font-family: 'Segoe UI', 'Poppins', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary-blue) 0%, #1a4d8c 50%, #0d3b6e 100%);
            padding: 50px 0;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 60%;
            height: 200%;
            background: rgba(255,255,255,0.05);
            transform: rotate(25deg);
            pointer-events: none;
        }
        
        /* Cards */
        .form-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 40px;
            border: none;
        }
        
        .form-card-header {
            background: linear-gradient(135deg, var(--primary-blue) 0%, #1a4d8c 100%);
            padding: 18px 28px;
            color: white;
        }
        
        /* Section Cards */
        .section-card {
            background: white;
            border-radius: 24px;
            margin-bottom: 35px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .section-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        }
        
        .section-header {
            padding: 18px 25px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .section-header-director { background: linear-gradient(135deg, var(--section-director), #c62828); }
        .section-header-scientists { background: linear-gradient(135deg, var(--section-scientists), #43a047); }
        .section-header-technical { background: linear-gradient(135deg, var(--section-technical), #1e88e5); }
        .section-header-ministerial { background: linear-gradient(135deg, var(--section-ministerial), #fb8c00); }
        .section-header-supporting { background: linear-gradient(135deg, var(--section-supporting), #8e24aa); }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .section-count {
            background: rgba(255,255,255,0.25);
            padding: 6px 14px;
            border-radius: 40px;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        /* Table Styles */
        .employee-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .employee-table thead th {
            background: #f8fafc;
            padding: 14px 18px;
            font-size: 0.85rem;
            font-weight: 700;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .employee-table tbody td {
            padding: 14px 18px;
            font-size: 0.9rem;
            border-bottom: 1px solid #eef2f6;
            vertical-align: middle;
        }
        
        .employee-table tbody tr:hover {
            background: #f8fafc;
        }
        
        /* Action Buttons */
        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 10px;
            transition: all 0.2s;
            text-decoration: none;
            margin: 0 3px;
        }
        
        .action-btn-edit {
            background: #e3f2fd;
            color: #1565c0;
        }
        
        .action-btn-edit:hover {
            background: #1565c0;
            color: white;
            transform: scale(1.05);
        }
        
        .action-btn-delete {
            background: #ffebee;
            color: #c62828;
        }
        
        .action-btn-delete:hover {
            background: #c62828;
            color: white;
            transform: scale(1.05);
        }
        
        /* Form Elements */
        .form-control, .form-select {
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            padding: 12px 16px;
            transition: all 0.2s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(0, 54, 121, 0.15);
        }
        
        label {
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }
        
        .required:after {
            content: ' *';
            color: #dc2626;
        }
        
        .optional:after {
            content: ' (Optional)';
            color: #6c757d;
            font-weight: normal;
            font-size: 0.8rem;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, var(--primary-blue), #1a4d8c);
            border: none;
            padding: 12px 30px;
            border-radius: 40px;
            font-weight: 600;
            transition: all 0.3s;
            color: white;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,54,121,0.3);
            color: white;
        }
        
        /* Email & Phone Links */
        .contact-link {
            text-decoration: none;
            color: #334155;
            transition: color 0.2s;
        }
        
        .contact-link:hover {
            color: var(--primary-blue);
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #94a3b8;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .employee-table thead {
                display: none;
            }
            
            .employee-table tbody tr {
                display: block;
                margin-bottom: 16px;
                border: 1px solid #e2e8f0;
                border-radius: 16px;
                background: white;
            }
            
            .employee-table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px 16px;
                border-bottom: 1px dashed #eef2f6;
            }
            
            .employee-table tbody td:last-child {
                border-bottom: none;
            }
            
            .employee-table tbody td::before {
                content: attr(data-label);
                font-weight: 700;
                color: var(--primary-blue);
                width: 35%;
                font-size: 0.8rem;
            }
            
            .section-header {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
        }
        
        /* Alert Animation */
        .alert-custom {
            border-radius: 16px;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            animation: slideDown 0.3s ease-out;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Font size controls */
        .font-size-controls {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            background: white;
            border-radius: 50px;
            padding: 8px 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        
        .font-size-controls button {
            background: var(--primary-blue);
            color: white;
            border: none;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            margin: 0 5px;
            transition: all 0.2s;
        }
        
        .font-size-controls button:hover {
            background: var(--primary-dark);
            transform: scale(1.05);
        }
        
        .text-muted i {
            margin-right: 4px;
        }
    </style>
</head>
<body id="bg">

<!-- Hero Section -->
<div class="hero-section">
    <div class="container text-center">
        <div class="mb-3">
            <i class="fas fa-building fa-3x mb-3 opacity-75"></i>
        </div>
        <h1 class="display-5 fw-bold mb-3">ICMR - NIIRNCD</h1>
        <p class="lead mb-2">Employee Directory Management System</p>
        <p class="mb-0 opacity-75">Director • Scientists • Technical Staff • Ministerial Staff • Supporting Staff</p>
    </div>
</div>

<div class="container my-5">
    
    <!-- Alert Messages -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message_type'] ?> alert-dismissible fade show alert-custom mb-4" role="alert">
            <i class="fas fa-<?= $_SESSION['message_type'] == 'success' ? 'check-circle' : 'exclamation-triangle' ?> me-2"></i>
            <?= $_SESSION['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
    <?php endif; ?>
    
    <!-- Add/Edit Employee Form -->
    <div class="form-card">
        <div class="form-card-header">
            <div class="d-flex align-items-center gap-3">
                <i class="fas fa-<?= $edit_employee ? 'pen-fancy' : 'user-plus' ?> fa-2x"></i>
                <div>
                    <h4 class="mb-0 fw-semibold"><?= $edit_employee ? 'Edit Employee Record' : 'Add New Employee' ?></h4>
                    <p class="mb-0 opacity-75 small"><?= $edit_employee ? 'Update employee information' : 'Fill in the details to add a staff member' ?></p>
                </div>
            </div>
        </div>
        <div class="card-body p-4 p-lg-5">
            <form method="POST" action="" id="employeeForm">
                <?php if ($edit_employee): ?>
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?= $edit_employee['id'] ?>">
                <?php else: ?>
                    <input type="hidden" name="action" value="create">
                <?php endif; ?>
                
                <div class="row g-4">
                    <div class="col-md-6 col-lg-4">
                        <label class="required">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted"></i></span>
                            <input type="text" name="name" class="form-control border-start-0 ps-0" placeholder="Enter full name" 
                                   value="<?= $edit_employee ? htmlspecialchars($edit_employee['name']) : '' ?>" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-4">
                        <label class="required">Designation</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-briefcase text-muted"></i></span>
                            <input type="text" name="designation" class="form-control border-start-0 ps-0" placeholder="Enter designation"
                                   value="<?= $edit_employee ? htmlspecialchars($edit_employee['designation']) : '' ?>" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-4">
                        <label class="required">Section</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-layer-group text-muted"></i></span>
                            <select name="section" class="form-select border-start-0 ps-0" required id="sectionSelect">
                                <option value="">Select Section</option>
                                <option value="Director" <?= ($edit_employee && $edit_employee['section'] == 'Director') ? 'selected' : '' ?>>👑 Director</option>
                                <option value="Scientists" <?= ($edit_employee && $edit_employee['section'] == 'Scientists') ? 'selected' : '' ?>>🔬 Scientists</option>
                                <option value="Technical Staff" <?= ($edit_employee && $edit_employee['section'] == 'Technical Staff') ? 'selected' : '' ?>>⚙️ Technical Staff</option>
                                <option value="Ministerial Staff" <?= ($edit_employee && $edit_employee['section'] == 'Ministerial Staff') ? 'selected' : '' ?>>📋 Ministerial Staff</option>
                                <option value="Supporting Staff" <?= ($edit_employee && $edit_employee['section'] == 'Supporting Staff') ? 'selected' : '' ?>>🤝 Supporting Staff</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="required">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                            <input type="text" name="email" class="form-control border-start-0 ps-0" placeholder="name@icmr.gov.in or name[at]icmr[dot]gov[dot]in"
                                   value="<?= $edit_employee ? htmlspecialchars($edit_employee['email']) : '' ?>" required>
                        </div>
                        <small class="text-muted"><i class="fas fa-info-circle"></i> Any email format is accepted</small>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="optional">Phone Number</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-phone text-muted"></i></span>
                            <input type="text" name="phone" class="form-control border-start-0 ps-0" placeholder="Any phone format (e.g., 0291-2722403) - Optional"
                                   value="<?= $edit_employee ? htmlspecialchars($edit_employee['phone']) : '' ?>">
                        </div>
                        <small class="text-muted"><i class="fas fa-info-circle"></i> Optional field - can be left empty</small>
                    </div>
                    
                    <div class="col-md-6">
                        <label>Display Order (Optional)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-sort-numeric-down text-muted"></i></span>
                            <input type="number" name="serial_order" class="form-control border-start-0 ps-0" placeholder="Auto-increment if empty" min="0"
                                   value="<?= $edit_employee ? $edit_employee['serial_order'] : '' ?>">
                        </div>
                        <small class="text-muted">Lower numbers appear first</small>
                    </div>
                    
                    <div class="col-12 mt-4">
                        <?php if ($edit_employee): ?>
                            <button type="submit" class="btn-submit px-5 py-2 rounded-pill border-0">
                                <i class="fas fa-save me-2"></i>Update Employee
                            </button>
                            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-outline-secondary px-4 rounded-pill ms-2">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        <?php else: ?>
                            <button type="submit" class="btn-submit px-5 py-2 rounded-pill border-0">
                                <i class="fas fa-plus-circle me-2"></i>Add Employee to Directory
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Directory Display - Section Wise -->
    <div class="mt-4">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <h3 class="mb-0 fw-bold"><i class="fas fa-address-book me-2" style="color: var(--primary-blue);"></i>Employee Directory</h3>
            <span class="badge bg-primary rounded-pill px-3 py-2 fs-6">Total Staff: <?= $total_count ?></span>
        </div>
        
        <?php foreach ($sections as $section): 
            $employees_list = $grouped_data[$section];
            $header_class = '';
            $icon = '';
            
            switch($section) {
                case 'Director': $header_class = 'section-header-director'; $icon = 'fa-crown'; break;
                case 'Scientists': $header_class = 'section-header-scientists'; $icon = 'fa-flask'; break;
                case 'Technical Staff': $header_class = 'section-header-technical'; $icon = 'fa-microchip'; break;
                case 'Ministerial Staff': $header_class = 'section-header-ministerial'; $icon = 'fa-file-alt'; break;
                case 'Supporting Staff': $header_class = 'section-header-supporting'; $icon = 'fa-handshake'; break;
            }
        ?>
            <div class="section-card">
                <div class="section-header <?= $header_class ?>">
                    <div class="section-title">
                        <i class="fas <?= $icon ?>"></i>
                        <span><?= $section ?></span>
                    </div>
                    <div class="section-count">
                        <i class="fas fa-users me-1"></i> <?= count($employees_list) ?> Members
                    </div>
                </div>
                
                <?php if (empty($employees_list)): ?>
                    <div class="empty-state">
                        <i class="fas fa-user-slash fa-3x mb-3 opacity-25"></i>
                        <p class="mb-0 text-muted">No staff members in <?= $section ?> section yet.</p>
                        <p class="small text-muted">Use the form above to add employees.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="employee-table">
                            <thead>
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th style="width: 30%">Name</th>
                                    <th style="width: 25%">Designation</th>
                                    <th style="width: 25%">Email</th>
                                    <th style="width: 15%">Phone</th>
                                    <th style="width: 10%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $serial = 1; foreach ($employees_list as $emp): ?>
                                    <tr>
                                        <td data-label="#"><?= $serial++ ?></td>
                                        <td data-label="Name">
                                            <strong><?= htmlspecialchars($emp['name']) ?></strong>
                                        </td>
                                        <td data-label="Designation"><?= htmlspecialchars($emp['designation']) ?></td>
                                        <td data-label="Email">
                                            <a href="mailto:<?= htmlspecialchars($emp['email']) ?>" class="contact-link">
                                                <i class="fas fa-envelope me-1" style="color: var(--primary-blue);"></i>
                                                <?= htmlspecialchars($emp['email']) ?>
                                            </a>
                                        </td>
                                        <td data-label="Phone">
                                            <?php if (!empty($emp['phone'])): ?>
                                                <a href="tel:<?= htmlspecialchars($emp['phone']) ?>" class="contact-link">
                                                    <i class="fas fa-phone me-1" style="color: var(--primary-blue);"></i>
                                                    <?= htmlspecialchars($emp['phone']) ?>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">—</span>
                                            <?php endif; ?>
                                        </td>
                                        <td data-label="Actions">
                                            <a href="?edit=<?= $emp['id'] ?>" class="action-btn action-btn-edit" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="javascript:void(0)" onclick="confirmDelete(<?= $emp['id'] ?>)" class="action-btn action-btn-delete" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </table>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Font Size Controls -->
<div class="font-size-controls">
    <button id="fontSmall" title="Small Font">A-</button>
    <button id="fontMedium" title="Medium Font">A</button>
    <button id="fontLarge" title="Large Font">A+</button>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4">
                <p class="mb-0">Are you sure you want to permanently delete this employee record?</p>
                <p class="text-muted small mt-2 mb-0">This action cannot be undone.</p>
            </div>
            <div class="modal-footer border-0 pt-0 pb-4">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger rounded-pill px-4">Delete</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // NO VALIDATIONS - Form submits as-is, phone is optional
    document.getElementById('employeeForm')?.addEventListener('submit', function(e) {
        const section = document.querySelector('select[name="section"]').value;
        const name = document.querySelector('input[name="name"]').value;
        const designation = document.querySelector('input[name="designation"]').value;
        const email = document.querySelector('input[name="email"]').value;
        
        // Only check required fields (phone is optional)
        if (!name || !designation || !email || !section) {
            e.preventDefault();
            alert('Please fill all required fields (Name, Designation, Email, Section)');
        }
        // No phone validation - it's optional
    });
    
    // Delete confirmation
    function confirmDelete(id) {
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        const confirmBtn = document.getElementById('confirmDeleteBtn');
        confirmBtn.href = '?delete=' + id;
        modal.show();
    }
    
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
    
    // Font size controls
    document.getElementById('fontSmall')?.addEventListener('click', function() {
        document.body.style.fontSize = '13px';
        document.querySelectorAll('.card-text, .employee-table tbody td').forEach(el => {
            el.style.fontSize = '13px';
        });
    });
    
    document.getElementById('fontMedium')?.addEventListener('click', function() {
        document.body.style.fontSize = '16px';
        document.querySelectorAll('.card-text, .employee-table tbody td').forEach(el => {
            el.style.fontSize = '16px';
        });
    });
    
    document.getElementById('fontLarge')?.addEventListener('click', function() {
        document.body.style.fontSize = '18px';
        document.querySelectorAll('.card-text, .employee-table tbody td').forEach(el => {
            el.style.fontSize = '18px';
        });
    });
</script>

</body>
</html>