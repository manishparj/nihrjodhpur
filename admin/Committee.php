<?php
session_start();

// Include database configuration (already has $dbh)
include('inc/config.php'); // <- this replaces the manual $dbh setup

// Check login status - MUST be before ANY output
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit;
}

// Handle different actions - MUST be before ANY HTML output
$action = $_POST['action'] ?? $_GET['action'] ?? 'list';

switch($action) {
    case 'create':
        handleCreate($dbh);
        break;
    case 'edit':
        handleEdit($dbh);
        break;
    case 'update':
        handleUpdate($dbh);
        break;
    case 'delete':
        handleDelete($dbh);
        break;
    case 'add_member':
        handleAddMember($dbh);
        break;
    case 'remove_member':
        handleRemoveMember($dbh);
        break;
    default:
        // Don't call listCommittees here if we need to output HTML
        // We'll call it after the HTML starts
        break;
}

// Define all functions BEFORE any output
function handleCreate($dbh) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $committee_name = trim($_POST['committee_name']);
        
        if (empty($committee_name)) {
            $_SESSION['error'] = "Committee name is required";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
        
        try {
            $stmt = $dbh->prepare("INSERT INTO committees (committee_name) VALUES (?)");
            $stmt->execute([$committee_name]);
            $_SESSION['success'] = "Committee created successfully";
        } catch(PDOException $e) {
            $_SESSION['error'] = "Error creating committee: " . $e->getMessage();
        }
        
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

function handleEdit($dbh) {
    $id = $_GET['id'] ?? 0;
    $stmt = $dbh->prepare("SELECT * FROM committees WHERE id = ?");
    $stmt->execute([$id]);
    $committee = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$committee) {
        $_SESSION['error'] = "Committee not found";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
    
    // Get committee members
    $memberStmt = $dbh->prepare("SELECT * FROM committee_members WHERE committee_id = ? ORDER BY id");
    $memberStmt->execute([$id]);
    $members = $memberStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Store data in session or global variables for later use
    $GLOBALS['edit_committee'] = $committee;
    $GLOBALS['edit_members'] = $members;
}

function handleUpdate($dbh) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['committee_id'];
        $committee_name = trim($_POST['committee_name']);
        
        if (empty($committee_name)) {
            $_SESSION['error'] = "Committee name is required";
            header("Location: " . $_SERVER['PHP_SELF'] . "?action=edit&id=" . $id);
            exit;
        }
        
        try {
            $stmt = $dbh->prepare("UPDATE committees SET committee_name = ? WHERE id = ?");
            $stmt->execute([$committee_name, $id]);
            $_SESSION['success'] = "Committee updated successfully";
        } catch(PDOException $e) {
            $_SESSION['error'] = "Error updating committee: " . $e->getMessage();
        }
        
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

function handleDelete($dbh) {
    $id = $_GET['id'] ?? 0;
    
    try {
        // Members will be deleted automatically due to CASCADE constraint
        $stmt = $dbh->prepare("DELETE FROM committees WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['success'] = "Committee deleted successfully";
    } catch(PDOException $e) {
        $_SESSION['error'] = "Error deleting committee: " . $e->getMessage();
    }
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

function handleAddMember($dbh) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $committee_id = $_POST['committee_id'];
        $employee_name_designation = trim($_POST['employee_name_designation']);
        $role = trim($_POST['role']);
        
        if (empty($employee_name_designation) || empty($role)) {
            $_SESSION['error'] = "Employee Name & Designation and Role are required";
            header("Location: " . $_SERVER['PHP_SELF'] . "?action=edit&id=" . $committee_id);
            exit;
        }
        
        try {
            $stmt = $dbh->prepare("INSERT INTO committee_members (committee_id, employee_name_designation, role) VALUES (?, ?, ?)");
            $stmt->execute([$committee_id, $employee_name_designation, $role]);
            $_SESSION['success'] = "Member added successfully";
        } catch(PDOException $e) {
            $_SESSION['error'] = "Error adding member: " . $e->getMessage();
        }
        
        header("Location: " . $_SERVER['PHP_SELF'] . "?action=edit&id=" . $committee_id);
        exit;
    }
}

function handleRemoveMember($dbh) {
    $member_id = $_GET['member_id'] ?? 0;
    $committee_id = $_GET['committee_id'] ?? 0;
    
    try {
        $stmt = $dbh->prepare("DELETE FROM committee_members WHERE id = ?");
        $stmt->execute([$member_id]);
        $_SESSION['success'] = "Member removed successfully";
    } catch(PDOException $e) {
        $_SESSION['error'] = "Error removing member: " . $e->getMessage();
    }
    
    header("Location: " . $_SERVER['PHP_SELF'] . "?action=edit&id=" . $committee_id);
    exit;
}

function listCommittees($dbh) {
    $stmt = $dbh->query("SELECT * FROM committees ORDER BY id DESC");
    $committees = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get member count for each committee
    foreach ($committees as &$committee) {
        $countStmt = $dbh->prepare("SELECT COUNT(*) as member_count FROM committee_members WHERE committee_id = ?");
        $countStmt->execute([$committee['id']]);
        $committee['member_count'] = $countStmt->fetch(PDO::FETCH_ASSOC)['member_count'];
    }
    
    return $committees;
}
?>
<!doctype html>
<html lang="en" class="no-js">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">

    <title>Admin-panel - Committee Management</title>

    <!-- Font awesome -->
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="vendor/datatables/dataTables.bootstrap4.css">
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php 
        // Include sidebar - this will now work because no headers were sent yet
        include('inc/sidebar.php'); 
        ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include('inc/top.php'); ?>

                <?php
                // Now handle the display based on action
                if ($action === 'edit' && isset($edit_committee)) {
                    // Display edit form
                    $committee = $edit_committee;
                    $members = $edit_members;
                    ?>
                    <div class="container-fluid">
                        <?php if(isset($_SESSION['success'])): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php endif; ?>
                        <?php if(isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php endif; ?>

                        <!-- Page Heading -->
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3 mb-0 text-gray-800">Edit Committee: <?php echo htmlspecialchars($committee['committee_name']); ?></h1>
                            <a href="?action=list" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">← Back to Committees</a>
                        </div>

                        <!-- Edit Committee Card -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Edit Committee Details</h6>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="?action=update">
                                    <input type="hidden" name="committee_id" value="<?php echo $committee['id']; ?>">
                                    <div class="form-group">
                                        <label for="committee_name">Committee Name:</label>
                                        <input type="text" class="form-control" id="committee_name" name="committee_name" value="<?php echo htmlspecialchars($committee['committee_name']); ?>" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update Committee Name</button>
                                </form>
                            </div>
                        </div>

                        <!-- Members List Card -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Committee Members (<?php echo count($members); ?>)</h6>
                            </div>
                            <div class="card-body">
                                <?php if(empty($members)): ?>
                                    <div class="alert alert-info">No members added yet. Add members using the form below.</div>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Employee Name & Designation</th>
                                                    <th>Role</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($members as $member): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($member['employee_name_designation']); ?></td>
                                                    <td><?php echo htmlspecialchars($member['role']); ?></td>
                                                    <td>
                                                        <a href="?action=remove_member&member_id=<?php echo $member['id']; ?>&committee_id=<?php echo $committee['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Remove this member from the committee?')">Remove</a>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Add Member Card -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Add New Member</h6>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="?action=add_member">
                                    <input type="hidden" name="committee_id" value="<?php echo $committee['id']; ?>">
                                    <div class="form-group">
                                        <label for="employee_name_designation">Employee Name & Designation:</label>
                                        <input type="text" class="form-control" id="employee_name_designation" name="employee_name_designation" 
                                               placeholder="Example: John Smith - Senior Manager" required>
                                        <small class="form-text text-muted">Enter employee name followed by their designation (e.g., "Jane Doe - Team Lead")</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="role">Role in Committee:</label>
                                        <input type="text" class="form-control" id="role" name="role" placeholder="Example: Chairperson, Secretary, Member, Coordinator" required>
                                        <small class="form-text text-muted">Specify the member's role in this committee</small>
                                    </div>
                                    <button type="submit" class="btn btn-success">Add Member to Committee</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php
                } else {
                    // Default list view
                    $committees = listCommittees($dbh);
                    ?>
                    <div class="container-fluid">
                        <?php if(isset($_SESSION['success'])): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php endif; ?>
                        <?php if(isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php endif; ?>

                        <!-- Page Heading -->
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3 mb-0 text-gray-800">Committee Management</h1>
                        </div>

                        <!-- Create Committee Card -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Create New Committee</h6>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="?action=create" class="row">
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="committee_name" name="committee_name" placeholder="Enter committee name" required>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary btn-block">Create Committee</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Committees Table -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Existing Committees</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Committee Name</th>
                                                <th>Members Count</th>
                                                <th>Created Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($committees as $committee): ?>
                                            <tr>
                                                <td><?php echo $committee['id']; ?></td>
                                                <td><?php echo htmlspecialchars($committee['committee_name']); ?></td>
                                                <td><?php echo $committee['member_count']; ?></td>
                                                <td><?php echo date('Y-m-d', strtotime($committee['created_at'])); ?></td>
                                                <td class="actions">
                                                    <a href="?action=edit&id=<?php echo $committee['id']; ?>" class="btn btn-info btn-sm">View/Edit</a>
                                                    <a href="?action=delete&id=<?php echo $committee['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure? This will delete all committee members too.')">Delete</a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>

    <?php include('inc/footer.php'); ?>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>
</body>
</html>