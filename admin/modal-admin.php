<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Check database connection first
include('inc/config.php');

// Test database connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if user is logged in
if (strlen($_SESSION['alogin']) == 0) {
    header('location:Index.php');
    exit();
}

// Check if modals table exists, if not create it
$tableCheck = mysqli_query($conn, "SHOW TABLES LIKE 'modals'");
if (mysqli_num_rows($tableCheck) == 0) {
    $createTable = "CREATE TABLE IF NOT EXISTS modals (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        modal_title VARCHAR(255) NOT NULL,
        modal_content TEXT NOT NULL,
        footer_text VARCHAR(100) DEFAULT 'Close',
        is_enabled TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    if (!mysqli_query($conn, $createTable)) {
        die("Error creating table: " . mysqli_error($conn));
    }
}

// Handle CRUD Operations
$modalMessage = '';
$modalMessageType = '';

// CREATE - Add new modal record
if (isset($_POST['add_modal'])) {
    $modalTitle = mysqli_real_escape_string($conn, $_POST['modal_title']);
    $modalContent = mysqli_real_escape_string($conn, $_POST['modal_content']);
    $footerText = mysqli_real_escape_string($conn, $_POST['footer_text']);
    $isEnabled = isset($_POST['is_enabled']) ? 1 : 0;
    
    $query = "INSERT INTO modals (modal_title, modal_content, footer_text, is_enabled, created_at) 
              VALUES ('$modalTitle', '$modalContent', '$footerText', '$isEnabled', NOW())";
    
    if (mysqli_query($conn, $query)) {
        $modalMessage = "Modal added successfully!";
        $modalMessageType = "success";
        header("Location: modal-admin.php?msg=added");
        exit();
    } else {
        $modalMessage = "Error: " . mysqli_error($conn);
        $modalMessageType = "error";
    }
}

// UPDATE - Edit existing modal
if (isset($_POST['edit_modal'])) {
    $modalId = intval($_POST['modal_id']);
    $modalTitle = mysqli_real_escape_string($conn, $_POST['modal_title']);
    $modalContent = mysqli_real_escape_string($conn, $_POST['modal_content']);
    $footerText = mysqli_real_escape_string($conn, $_POST['footer_text']);
    $isEnabled = isset($_POST['is_enabled']) ? 1 : 0;
    
    $query = "UPDATE modals SET 
              modal_title = '$modalTitle',
              modal_content = '$modalContent',
              footer_text = '$footerText',
              is_enabled = '$isEnabled',
              updated_at = NOW()
              WHERE id = $modalId";
    
    if (mysqli_query($conn, $query)) {
        $modalMessage = "Modal updated successfully!";
        $modalMessageType = "success";
        header("Location: modal-admin.php?msg=updated");
        exit();
    } else {
        $modalMessage = "Error: " . mysqli_error($conn);
        $modalMessageType = "error";
    }
}

// DELETE - Remove modal
if (isset($_GET['delete_id'])) {
    $deleteId = intval($_GET['delete_id']);
    $query = "DELETE FROM modals WHERE id = $deleteId";
    
    if (mysqli_query($conn, $query)) {
        header("Location: modal-admin.php?msg=deleted");
        exit();
    } else {
        $modalMessage = "Error: " . mysqli_error($conn);
        $modalMessageType = "error";
    }
}

// TOGGLE Enable/Disable
if (isset($_GET['toggle_id'])) {
    $toggleId = intval($_GET['toggle_id']);
    $query = "UPDATE modals SET is_enabled = NOT is_enabled WHERE id = $toggleId";
    
    if (mysqli_query($conn, $query)) {
        header("Location: modal-admin.php?msg=toggled");
        exit();
    } else {
        $modalMessage = "Error: " . mysqli_error($conn);
        $modalMessageType = "error";
    }
}

// Check for message in URL
if (isset($_GET['msg'])) {
    switch($_GET['msg']) {
        case 'added':
            $modalMessage = "Modal added successfully!";
            $modalMessageType = "success";
            break;
        case 'updated':
            $modalMessage = "Modal updated successfully!";
            $modalMessageType = "success";
            break;
        case 'deleted':
            $modalMessage = "Modal deleted successfully!";
            $modalMessageType = "success";
            break;
        case 'toggled':
            $modalMessage = "Modal status toggled successfully!";
            $modalMessageType = "success";
            break;
    }
}

// Fetch all modals
$modalsQuery = "SELECT * FROM modals ORDER BY created_at DESC";
$modalsResult = mysqli_query($conn, $modalsQuery);

if (!$modalsResult) {
    die("Query failed: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Admin - Modal Manager</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="stylesheet" href="vendor/datatables/dataTables.bootstrap4.css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        .errorWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #dd3d36;
            color: #fff;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        }
        .succWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #5cb85c;
            color: #fff;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        }
        .modal-preview {
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
            background: #f9f9f9;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-enabled {
            background: #28a745;
            color: white;
        }
        .status-disabled {
            background: #dc3545;
            color: white;
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include('inc/sidebar.php'); ?>
        
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include('inc/top.php'); ?>
                
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Modal Popup Manager</h1>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#addModalDialog">
                            <i class="fas fa-plus"></i> Create New Modal
                        </button>
                    </div>

                    <!-- Display Messages -->
                    <?php if ($modalMessage != ''): ?>
                        <div class="alert alert-<?php echo $modalMessageType == 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($modalMessage); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <!-- Modals List Table -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">All Modal Configurations</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Modal Title</th>
                                            <th>Footer Button Text</th>
                                            <th>Status</th>
                                            <th>Created Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if ($modalsResult && mysqli_num_rows($modalsResult) > 0):
                                            while ($row = mysqli_fetch_assoc($modalsResult)): 
                                        ?>
                                        <tr>
                                            <td><?php echo $row['id']; ?></td>
                                            <td><?php echo htmlspecialchars($row['modal_title']); ?></td>
                                            <td><?php echo htmlspecialchars($row['footer_text']); ?></td>
                                            <td>
                                                <span class="status-badge <?php echo $row['is_enabled'] ? 'status-enabled' : 'status-disabled'; ?>">
                                                    <?php echo $row['is_enabled'] ? 'Enabled' : 'Disabled'; ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('Y-m-d H:i', strtotime($row['created_at'])); ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-info edit-modal-btn" 
                                                        data-id="<?php echo $row['id']; ?>"
                                                        data-title="<?php echo htmlspecialchars($row['modal_title']); ?>"
                                                        data-content="<?php echo htmlspecialchars($row['modal_content']); ?>"
                                                        data-footer="<?php echo htmlspecialchars($row['footer_text']); ?>"
                                                        data-enabled="<?php echo $row['is_enabled']; ?>">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <a href="?delete_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('Are you sure you want to delete this modal?')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
                                                <a href="?toggle_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-power-off"></i> Toggle
                                                </a>
                                                <button class="btn btn-sm btn-secondary test-modal-btn"
                                                        data-title="<?php echo htmlspecialchars($row['modal_title']); ?>"
                                                        data-content="<?php echo htmlspecialchars($row['modal_content']); ?>"
                                                        data-footer="<?php echo htmlspecialchars($row['footer_text']); ?>">
                                                    <i class="fas fa-eye"></i> Test
                                                </button>
                                            </td>
                                        </table>
                                        <?php 
                                            endwhile;
                                        else: 
                                        ?>
                                        <tr>
                                            <td colspan="6" class="text-center">No modals found. Create your first modal!</td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include('inc/footer.php'); ?>
        </div>
    </div>

    <!-- ADD MODAL DIALOG -->
    <div class="modal fade" id="addModalDialog" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Create New Modal Popup</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Modal Title *</label>
                            <input type="text" name="modal_title" class="form-control" required placeholder="Enter modal title">
                        </div>
                        <div class="form-group">
                            <label>Modal Content (HTML allowed) *</label>
                            <textarea name="modal_content" class="form-control" rows="5" required placeholder="Enter modal content. HTML tags allowed."></textarea>
                        </div>
                        <div class="form-group">
                            <label>Footer Button Text *</label>
                            <input type="text" name="footer_text" class="form-control" required placeholder="e.g., Close, OK, Got it" value="Close">
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="isEnabled" name="is_enabled" checked>
                                <label class="custom-control-label" for="isEnabled">Enable this modal popup</label>
                            </div>
                        </div>
                        
                        <div class="modal-preview">
                            <h6>Live Preview:</h6>
                            <div class="preview-container">
                                <div class="custom-modal-preview" style="border:1px solid #ddd; padding:15px; border-radius:5px; background:white;">
                                    <h4 class="preview-title">Modal Title</h4>
                                    <div class="preview-content">Modal content will appear here...</div>
                                    <button class="btn btn-secondary mt-2 preview-footer-btn">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_modal" class="btn btn-primary">Save Modal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- EDIT MODAL DIALOG -->
    <div class="modal fade" id="editModalDialog" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Modal Popup</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="">
                    <input type="hidden" name="modal_id" id="edit_modal_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Modal Title *</label>
                            <input type="text" name="modal_title" id="edit_modal_title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Modal Content (HTML allowed) *</label>
                            <textarea name="modal_content" id="edit_modal_content" class="form-control" rows="5" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Footer Button Text *</label>
                            <input type="text" name="footer_text" id="edit_footer_text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="edit_is_enabled" name="is_enabled">
                                <label class="custom-control-label" for="edit_is_enabled">Enable this modal popup</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="edit_modal" class="btn btn-primary">Update Modal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- TEST MODAL -->
    <div id="testDisplayModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="testModalTitle">Modal Title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="testModalContent">
                    Modal content goes here...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="testModalFooterBtn">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Live preview for add modal form
            $('input[name="modal_title"], textarea[name="modal_content"], input[name="footer_text"]').on('keyup change', function() {
                var title = $('input[name="modal_title"]').val() || 'Modal Title';
                var content = $('textarea[name="modal_content"]').val() || 'Modal content will appear here...';
                var footerText = $('input[name="footer_text"]').val() || 'Close';
                
                $('.preview-title').text(title);
                $('.preview-content').html(content);
                $('.preview-footer-btn').text(footerText);
            });

            // Edit modal - populate form
            $('.edit-modal-btn').click(function() {
                var id = $(this).data('id');
                var title = $(this).data('title');
                var content = $(this).data('content');
                var footer = $(this).data('footer');
                var enabled = $(this).data('enabled');
                
                $('#edit_modal_id').val(id);
                $('#edit_modal_title').val(title);
                $('#edit_modal_content').val(content);
                $('#edit_footer_text').val(footer);
                $('#edit_is_enabled').prop('checked', enabled == 1);
                
                $('#editModalDialog').modal('show');
            });

            // Test modal button
            $('.test-modal-btn').click(function() {
                var title = $(this).data('title');
                var content = $(this).data('content');
                var footer = $(this).data('footer');
                
                $('#testModalTitle').text(title);
                $('#testModalContent').html(content);
                $('#testModalFooterBtn').text(footer);
                
                $('#testDisplayModal').modal('show');
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });
    </script>
</body>
</html>