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
    
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    
    <style>
        /* Custom styles for employee directory */
        .section-card {
            background: white;
            border-radius: 10px;
            margin-bottom: 30px;
            overflow: hidden;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .section-header {
            padding: 15px 20px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .section-header-director { background: linear-gradient(135deg, #8B0000, #c62828); }
        .section-header-scientists { background: linear-gradient(135deg, #2E7D32, #43a047); }
        .section-header-technical { background: linear-gradient(135deg, #1565C0, #1e88e5); }
        .section-header-ministerial { background: linear-gradient(135deg, #E65100, #fb8c00); }
        .section-header-supporting { background: linear-gradient(135deg, #6A1B9A, #8e24aa); }
        
        .section-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .section-count {
            background: rgba(255,255,255,0.25);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .employee-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .employee-table thead th {
            background: #f8f9fc;
            padding: 12px 15px;
            font-size: 0.8rem;
            font-weight: 700;
            color: #4e73df;
            text-transform: uppercase;
            border-bottom: 2px solid #e3e6f0;
        }
        
        .employee-table tbody td {
            padding: 12px 15px;
            font-size: 0.85rem;
            border-bottom: 1px solid #e3e6f0;
            vertical-align: middle;
        }
        
        .employee-table tbody tr:hover {
            background: #f8f9fc;
        }
        
        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            transition: all 0.2s;
            text-decoration: none;
            margin: 0 2px;
        }
        
        .action-btn-edit {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .action-btn-edit:hover {
            background: #0c5460;
            color: white;
        }
        
        .action-btn-delete {
            background: #f8d7da;
            color: #721c24;
        }
        
        .action-btn-delete:hover {
            background: #721c24;
            color: white;
        }
        
        .contact-link {
            text-decoration: none;
            color: #858796;
            transition: color 0.2s;
        }
        
        .contact-link:hover {
            color: #4e73df;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #858796;
        }
        
        .form-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            overflow: hidden;
            margin-bottom: 30px;
        }
        
        .form-card-header {
            background: #4e73df;
            padding: 15px 20px;
            color: white;
        }
        
        @media (max-width: 768px) {
            .employee-table thead {
                display: none;
            }
            
            .employee-table tbody tr {
                display: block;
                margin-bottom: 15px;
                border: 1px solid #e3e6f0;
                border-radius: 10px;
            }
            
            .employee-table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px 15px;
                border-bottom: 1px dashed #e3e6f0;
            }
            
            .employee-table tbody td:last-child {
                border-bottom: none;
            }
            
            .employee-table tbody td::before {
                content: attr(data-label);
                font-weight: 700;
                color: #4e73df;
                width: 40%;
                font-size: 0.8rem;
            }
            
            .section-header {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
        }
    </style>
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        
        <?php include('inc/sidebar.php'); ?>
        
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            
            <!-- Main Content -->
            <div id="content">
                
                <?php include('inc/top.php'); ?>
                
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">
                            <i class="fas fa-address-book me-2"></i>Employee Directory Management
                        </h1>
                        <span class="badge bg-primary rounded-pill px-3 py-2">
                            <i class="fas fa-users me-1"></i> Total Staff: <?= $total_count ?>
                        </span>
                    </div>
                    
                    <!-- Alert Messages -->
                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="alert alert-<?= $_SESSION['message_type'] ?> alert-dismissible fade show mb-4" role="alert">
                            <i class="fas fa-<?= $_SESSION['message_type'] == 'success' ? 'check-circle' : 'exclamation-triangle' ?> me-2"></i>
                            <?= $_SESSION['message'] ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
                    <?php endif; ?>
                    
                    <!-- Add/Edit Employee Form -->
                    <div class="form-card">
                        <div class="form-card-header">
                            <div class="d-flex align-items-center gap-3">
                                <i class="fas fa-<?= $edit_employee ? 'pen-fancy' : 'user-plus' ?> fa-2x"></i>
                                <div>
                                    <h5 class="mb-0 fw-semibold"><?= $edit_employee ? 'Edit Employee Record' : 'Add New Employee' ?></h5>
                                    <p class="mb-0 opacity-75 small"><?= $edit_employee ? 'Update employee information' : 'Fill in the details to add a staff member' ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="" id="employeeForm">
                                <?php if ($edit_employee): ?>
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="id" value="<?= $edit_employee['id'] ?>">
                                <?php else: ?>
                                    <input type="hidden" name="action" value="create">
                                <?php endif; ?>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            <input type="text" name="name" class="form-control" placeholder="Enter full name" 
                                                   value="<?= $edit_employee ? htmlspecialchars($edit_employee['name']) : '' ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Designation <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-briefcase"></i></span>
                                            <input type="text" name="designation" class="form-control" placeholder="Enter designation"
                                                   value="<?= $edit_employee ? htmlspecialchars($edit_employee['designation']) : '' ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Section <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                            <select name="section" class="form-select" required>
                                                <option value="">Select Section</option>
                                                <option value="Director" <?= ($edit_employee && $edit_employee['section'] == 'Director') ? 'selected' : '' ?>>👑 Director</option>
                                                <option value="Scientists" <?= ($edit_employee && $edit_employee['section'] == 'Scientists') ? 'selected' : '' ?>>🔬 Scientists</option>
                                                <option value="Technical Staff" <?= ($edit_employee && $edit_employee['section'] == 'Technical Staff') ? 'selected' : '' ?>>⚙️ Technical Staff</option>
                                                <option value="Ministerial Staff" <?= ($edit_employee && $edit_employee['section'] == 'Ministerial Staff') ? 'selected' : '' ?>>📋 Ministerial Staff</option>
                                                <option value="Supporting Staff" <?= ($edit_employee && $edit_employee['section'] == 'Supporting Staff') ? 'selected' : '' ?>>🤝 Supporting Staff</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            <input type="email" name="email" class="form-control" placeholder="name@icmr.gov.in"
                                                   value="<?= $edit_employee ? htmlspecialchars($edit_employee['email']) : '' ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Phone Number <span class="text-muted">(Optional)</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            <input type="text" name="phone" class="form-control" placeholder="e.g., 0291-2722403"
                                                   value="<?= $edit_employee ? htmlspecialchars($edit_employee['phone']) : '' ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Display Order <span class="text-muted">(Optional)</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-sort-numeric-down"></i></span>
                                            <input type="number" name="serial_order" class="form-control" placeholder="Lower numbers appear first" min="0"
                                                   value="<?= $edit_employee ? $edit_employee['serial_order'] : '' ?>">
                                        </div>
                                        <small class="text-muted">Leave empty for auto-increment</small>
                                    </div>
                                    
                                    <div class="col-12 mt-3">
                                        <?php if ($edit_employee): ?>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-2"></i>Update Employee
                                            </button>
                                            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary">
                                                <i class="fas fa-times me-2"></i>Cancel
                                            </a>
                                        <?php else: ?>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-plus-circle me-2"></i>Add Employee to Directory
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Directory Display - Section Wise -->
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
                                    <p class="mb-0">No staff members in <?= $section ?> section yet.</p>
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
                                                            <i class="fas fa-envelope me-1"></i>
                                                            <?= htmlspecialchars($emp['email']) ?>
                                                        </a>
                                                    </td>
                                                    <td data-label="Phone">
                                                        <?php if (!empty($emp['phone'])): ?>
                                                            <a href="tel:<?= htmlspecialchars($emp['phone']) ?>" class="contact-link">
                                                                <i class="fas fa-phone me-1"></i>
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
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    
                </div>
                <!-- /.container-fluid -->
                
            </div>
            <!-- End of Main Content -->
            
            <?php include('inc/footer.php'); ?>
            
        </div>
        <!-- End of Content Wrapper -->
        
    </div>
    <!-- End of Page Wrapper -->
    
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body py-4">
                    <p class="mb-0">Are you sure you want to permanently delete this employee record?</p>
                    <p class="text-muted small mt-2 mb-0">This action cannot be undone.</p>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    
    <script>
        // Form validation
        document.getElementById('employeeForm')?.addEventListener('submit', function(e) {
            const section = document.querySelector('select[name="section"]').value;
            const name = document.querySelector('input[name="name"]').value;
            const designation = document.querySelector('input[name="designation"]').value;
            const email = document.querySelector('input[name="email"]').value;
            
            if (!name || !designation || !email || !section) {
                e.preventDefault();
                alert('Please fill all required fields (Name, Designation, Email, Section)');
            }
        });
        
        // Delete confirmation
        function confirmDelete(id) {
            $('#deleteModal').modal('show');
            document.getElementById('confirmDeleteBtn').href = '?delete=' + id;
        }
        
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    </script>
    
</body>
</html>