<?php
session_start();
include('inc/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}

// Create uploads directory if not exists
$rti_upload_dir = 'uploads/rti_documents/';
if (!file_exists($rti_upload_dir)) {
    mkdir($rti_upload_dir, 0777, true);
}

// Handle CRUD Operations
$action = $_POST['action'] ?? $_GET['action'] ?? 'list';

switch($action) {
    case 'add_officer':
        addRtiOfficer($conn);
        break;
    case 'edit_officer':
        editRtiOfficer($conn);
        break;
    case 'update_officer':
        updateRtiOfficer($conn);
        break;
    case 'delete_officer':
        deleteRtiOfficer($conn);
        break;
    case 'update_nodal':
        updateNodalOfficer($conn);
        break;
    case 'upload_document':
        uploadDocument($conn, $rti_upload_dir);
        break;
    case 'delete_document':
        deleteDocument($conn, $rti_upload_dir);
        break;
    default:
        // Get data for display
        $rti_officers = getRtiOfficers($conn);
        $nodal_officer = getNodalOfficer($conn);
        $documents = getDocuments($conn);
        break;
}

// Function to get all RTI officers
function getRtiOfficers($conn) {
    $result = mysqli_query($conn, "SELECT * FROM rti_officers ORDER BY display_order ASC");
    $officers = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $officers[] = $row;
    }
    return $officers;
}

// Function to get nodal officer
function getNodalOfficer($conn) {
    $result = mysqli_query($conn, "SELECT * FROM rti_nodal_officer LIMIT 1");
    return mysqli_fetch_assoc($result);
}

// Function to get documents
function getDocuments($conn) {
    $result = mysqli_query($conn, "SELECT * FROM rti_documents ORDER BY document_type");
    $docs = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $docs[$row['document_type']] = $row;
    }
    return $docs;
}

// Add RTI Officer
function addRtiOfficer($conn) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $subject_matter = mysqli_real_escape_string($conn, $_POST['subject_matter']);
        $cpio_name = mysqli_real_escape_string($conn, $_POST['cpio_name']);
        $cpio_designation = mysqli_real_escape_string($conn, $_POST['cpio_designation']);
        $cpio_email = mysqli_real_escape_string($conn, $_POST['cpio_email']);
        $cpio_phone = mysqli_real_escape_string($conn, $_POST['cpio_phone']);
        $faa_name = mysqli_real_escape_string($conn, $_POST['faa_name']);
        $faa_designation = mysqli_real_escape_string($conn, $_POST['faa_designation']);
        $faa_email = mysqli_real_escape_string($conn, $_POST['faa_email']);
        $faa_phone = mysqli_real_escape_string($conn, $_POST['faa_phone']);
        $display_order = intval($_POST['display_order']);
        
        $query = "INSERT INTO rti_officers (subject_matter, cpio_name, cpio_designation, cpio_email, cpio_phone, faa_name, faa_designation, faa_email, faa_phone, display_order) 
                  VALUES ('$subject_matter', '$cpio_name', '$cpio_designation', '$cpio_email', '$cpio_phone', '$faa_name', '$faa_designation', '$faa_email', '$faa_phone', $display_order)";
        
        if (mysqli_query($conn, $query)) {
            $_SESSION['success'] = "RTI Officer added successfully!";
        } else {
            $_SESSION['error'] = "Error: " . mysqli_error($conn);
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Edit RTI Officer - Get data
function editRtiOfficer($conn) {
    $id = intval($_GET['id']);
    $result = mysqli_query($conn, "SELECT * FROM rti_officers WHERE id = $id");
    $GLOBALS['edit_officer'] = mysqli_fetch_assoc($result);
}

// Update RTI Officer
function updateRtiOfficer($conn) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = intval($_POST['id']);
        $subject_matter = mysqli_real_escape_string($conn, $_POST['subject_matter']);
        $cpio_name = mysqli_real_escape_string($conn, $_POST['cpio_name']);
        $cpio_designation = mysqli_real_escape_string($conn, $_POST['cpio_designation']);
        $cpio_email = mysqli_real_escape_string($conn, $_POST['cpio_email']);
        $cpio_phone = mysqli_real_escape_string($conn, $_POST['cpio_phone']);
        $faa_name = mysqli_real_escape_string($conn, $_POST['faa_name']);
        $faa_designation = mysqli_real_escape_string($conn, $_POST['faa_designation']);
        $faa_email = mysqli_real_escape_string($conn, $_POST['faa_email']);
        $faa_phone = mysqli_real_escape_string($conn, $_POST['faa_phone']);
        $display_order = intval($_POST['display_order']);
        
        $query = "UPDATE rti_officers SET 
                  subject_matter = '$subject_matter',
                  cpio_name = '$cpio_name',
                  cpio_designation = '$cpio_designation',
                  cpio_email = '$cpio_email',
                  cpio_phone = '$cpio_phone',
                  faa_name = '$faa_name',
                  faa_designation = '$faa_designation',
                  faa_email = '$faa_email',
                  faa_phone = '$faa_phone',
                  display_order = $display_order
                  WHERE id = $id";
        
        if (mysqli_query($conn, $query)) {
            $_SESSION['success'] = "RTI Officer updated successfully!";
        } else {
            $_SESSION['error'] = "Error: " . mysqli_error($conn);
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Delete RTI Officer
function deleteRtiOfficer($conn) {
    $id = intval($_GET['id']);
    $query = "DELETE FROM rti_officers WHERE id = $id";
    
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "RTI Officer deleted successfully!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Update Nodal Officer
function updateNodalOfficer($conn) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = mysqli_real_escape_string($conn, $_POST['nodal_name']);
        $designation = mysqli_real_escape_string($conn, $_POST['nodal_designation']);
        $email = mysqli_real_escape_string($conn, $_POST['nodal_email']);
        $phone = mysqli_real_escape_string($conn, $_POST['nodal_phone']);
        
        // Check if exists
        $check = mysqli_query($conn, "SELECT id FROM rti_nodal_officer LIMIT 1");
        if (mysqli_num_rows($check) > 0) {
            $query = "UPDATE rti_nodal_officer SET name='$name', designation='$designation', email='$email', phone='$phone'";
        } else {
            $query = "INSERT INTO rti_nodal_officer (name, designation, email, phone) VALUES ('$name', '$designation', '$email', '$phone')";
        }
        
        if (mysqli_query($conn, $query)) {
            $_SESSION['success'] = "Nodal Officer updated successfully!";
        } else {
            $_SESSION['error'] = "Error: " . mysqli_error($conn);
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Upload Document
function uploadDocument($conn, $upload_dir) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['document_file'])) {
        $document_type = $_POST['document_type'];
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        
        $file = $_FILES['document_file'];
        $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file['name']);
        $filepath = $upload_dir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            // Delete old file if exists
            $old = mysqli_query($conn, "SELECT file_path FROM rti_documents WHERE document_type = '$document_type'");
            if ($old_file = mysqli_fetch_assoc($old)) {
                if (file_exists($old_file['file_path'])) {
                    unlink($old_file['file_path']);
                }
                $query = "UPDATE rti_documents SET title='$title', file_path='$filepath', original_filename='{$file['name']}', file_size={$file['size']} WHERE document_type='$document_type'";
            } else {
                $query = "INSERT INTO rti_documents (title, document_type, file_path, original_filename, file_size) VALUES ('$title', '$document_type', '$filepath', '{$file['name']}', {$file['size']})";
            }
            
            if (mysqli_query($conn, $query)) {
                $_SESSION['success'] = "Document uploaded successfully!";
            } else {
                $_SESSION['error'] = "Database error: " . mysqli_error($conn);
            }
        } else {
            $_SESSION['error'] = "Failed to upload file.";
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Delete Document
function deleteDocument($conn, $upload_dir) {
    $id = intval($_GET['id']);
    $result = mysqli_query($conn, "SELECT file_path FROM rti_documents WHERE id = $id");
    $doc = mysqli_fetch_assoc($result);
    
    if ($doc && file_exists($doc['file_path'])) {
        unlink($doc['file_path']);
    }
    
    $query = "DELETE FROM rti_documents WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Document deleted successfully!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RTI Management - Admin Panel</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="vendor/datatables/dataTables.bootstrap4.css">
    <style>
        .card-header-custom {
            background: linear-gradient(135deg, #003679, #1a4d8c);
            color: white;
        }
        .btn-custom-primary {
            background: #003679;
            border-color: #003679;
        }
        .btn-custom-primary:hover {
            background: #002255;
            border-color: #002255;
        }
        .document-link {
            padding: 10px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .document-link:hover {
            background: #f8f9fc;
            transform: translateX(5px);
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
                        <h1 class="h3 mb-0 text-gray-800">
                            <i class="fas fa-gavel"></i> RTI Act Management
                        </h1>
                    </div>
                    
                    <?php if(isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>
                    
                    <!-- RTI Officers Management -->
                    <div class="card shadow mb-4">
                        <div class="card-header card-header-custom py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold">RTI Officers (CPIO & FAA)</h6>
                            <button class="btn btn-light btn-sm" data-toggle="modal" data-target="#addOfficerModal">
                                <i class="fas fa-plus"></i> Add New Officer
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="officersTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Subject Matter</th>
                                            <th>CPIO Name</th>
                                            <th>CPIO Designation</th>
                                            <th>CPIO Contact</th>
                                            <th>FAA Name</th>
                                            <th>FAA Designation</th>
                                            <th>FAA Contact</th>
                                            <th>Order</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $counter = 1; foreach($rti_officers as $officer): ?>
                                        <tr>
                                            <td><?php echo $counter++; ?></td>
                                            <td><strong><?php echo htmlspecialchars($officer['subject_matter']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($officer['cpio_name']); ?></td>
                                            <td><?php echo htmlspecialchars($officer['cpio_designation']); ?></td>
                                            <td>
                                                📞 <?php echo htmlspecialchars($officer['cpio_phone']); ?><br>
                                                ✉️ <?php echo htmlspecialchars($officer['cpio_email']); ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($officer['faa_name']); ?></td>
                                            <td><?php echo htmlspecialchars($officer['faa_designation']); ?></td>
                                            <td>
                                                📞 <?php echo htmlspecialchars($officer['faa_phone']); ?><br>
                                                ✉️ <?php echo htmlspecialchars($officer['faa_email']); ?>
                                            </td>
                                            <td><?php echo $officer['display_order']; ?></td>
                                            <td>
                                                <button class="btn btn-info btn-sm" onclick="editOfficer(<?php echo htmlspecialchars(json_encode($officer)); ?>)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <a href="?action=delete_officer&id=<?php echo $officer['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Nodal Officer Management -->
                    <div class="card shadow mb-4">
                        <div class="card-header card-header-custom py-3">
                            <h6 class="m-0 font-weight-bold">Nodal Officer (RTI)</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="?action=update_nodal">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Name</label>
                                        <input type="text" name="nodal_name" class="form-control" value="<?php echo htmlspecialchars($nodal_officer['name'] ?? ''); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Designation</label>
                                        <input type="text" name="nodal_designation" class="form-control" value="<?php echo htmlspecialchars($nodal_officer['designation'] ?? ''); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Email</label>
                                        <input type="email" name="nodal_email" class="form-control" value="<?php echo htmlspecialchars($nodal_officer['email'] ?? ''); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Phone</label>
                                        <input type="text" name="nodal_phone" class="form-control" value="<?php echo htmlspecialchars($nodal_officer['phone'] ?? ''); ?>" required>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">Update Nodal Officer</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Documents Management -->
                    <div class="card shadow mb-4">
                        <div class="card-header card-header-custom py-3">
                            <h6 class="m-0 font-weight-bold">RTI Documents</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php
                                $doc_types = [
                                    'english_act' => 'English RTI Act',
                                    'hindi_act' => 'Hindi RTI Act',
                                    'office_order' => 'Office Order'
                                ];
                                foreach($doc_types as $type => $label):
                                    $doc = $documents[$type] ?? null;
                                ?>
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6><?php echo $label; ?></h6>
                                            <?php if($doc): ?>
                                                <p class="small text-muted">
                                                    <i class="fas fa-file-pdf"></i> <?php echo $doc['original_filename']; ?><br>
                                                    <small>Uploaded: <?php echo date('d-m-Y', strtotime($doc['created_at'])); ?></small>
                                                </p>
                                                <a href="<?php echo $doc['file_path']; ?>" target="_blank" class="btn btn-info btn-sm">View</a>
                                                <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#uploadModal" onclick="setDocumentType('<?php echo $type; ?>', '<?php echo addslashes($label); ?>')">Update</button>
                                                <?php if($doc): ?>
                                                <a href="?action=delete_document&id=<?php echo $doc['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this document?')">Delete</a>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <p class="text-muted">No document uploaded</p>
                                                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#uploadModal" onclick="setDocumentType('<?php echo $type; ?>', '<?php echo addslashes($label); ?>')">Upload</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include('inc/footer.php'); ?>
        </div>
    </div>
    
    <!-- Add/Edit Officer Modal -->
    <div class="modal fade" id="addOfficerModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Add RTI Officer</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <form method="POST" action="?action=add_officer">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label>Subject Matter *</label>
                                <input type="text" name="subject_matter" class="form-control" required>
                            </div>
                            <div class="col-12"><h6 class="mt-2">CPIO Details</h6><hr></div>
                            <div class="col-md-6 mb-3"><label>Name *</label><input type="text" name="cpio_name" class="form-control" required></div>
                            <div class="col-md-6 mb-3"><label>Designation *</label><input type="text" name="cpio_designation" class="form-control" required></div>
                            <div class="col-md-6 mb-3"><label>Email *</label><input type="email" name="cpio_email" class="form-control" required></div>
                            <div class="col-md-6 mb-3"><label>Phone *</label><input type="text" name="cpio_phone" class="form-control" required></div>
                            <div class="col-12"><h6 class="mt-2">FAA Details</h6><hr></div>
                            <div class="col-md-6 mb-3"><label>Name *</label><input type="text" name="faa_name" class="form-control" required></div>
                            <div class="col-md-6 mb-3"><label>Designation *</label><input type="text" name="faa_designation" class="form-control" required></div>
                            <div class="col-md-6 mb-3"><label>Email *</label><input type="email" name="faa_email" class="form-control" required></div>
                            <div class="col-md-6 mb-3"><label>Phone *</label><input type="text" name="faa_phone" class="form-control" required></div>
                            <div class="col-md-6 mb-3"><label>Display Order</label><input type="number" name="display_order" class="form-control" value="0"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Officer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Upload Document Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Upload Document</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <form method="POST" action="?action=upload_document" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="document_type" id="document_type">
                        <div class="mb-3">
                            <label>Title</label>
                            <input type="text" name="title" id="doc_title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>PDF File</label>
                            <input type="file" name="document_file" class="form-control" accept=".pdf" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Edit Officer Modal -->
    <div class="modal fade" id="editOfficerModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">Edit RTI Officer</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <form method="POST" action="?action=update_officer" id="editForm">
                    <div class="modal-body" id="editFormContent"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Officer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#officersTable').DataTable();
        });
        
        function setDocumentType(type, title) {
            document.getElementById('document_type').value = type;
            document.getElementById('doc_title').value = title;
        }
        
        function editOfficer(officer) {
            var html = `
                <input type="hidden" name="id" value="${officer.id}">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label>Subject Matter *</label>
                        <input type="text" name="subject_matter" class="form-control" value="${escapeHtml(officer.subject_matter)}" required>
                    </div>
                    <div class="col-12"><h6 class="mt-2">CPIO Details</h6><hr></div>
                    <div class="col-md-6 mb-3"><label>Name *</label><input type="text" name="cpio_name" class="form-control" value="${escapeHtml(officer.cpio_name)}" required></div>
                    <div class="col-md-6 mb-3"><label>Designation *</label><input type="text" name="cpio_designation" class="form-control" value="${escapeHtml(officer.cpio_designation)}" required></div>
                    <div class="col-md-6 mb-3"><label>Email *</label><input type="email" name="cpio_email" class="form-control" value="${escapeHtml(officer.cpio_email)}" required></div>
                    <div class="col-md-6 mb-3"><label>Phone *</label><input type="text" name="cpio_phone" class="form-control" value="${escapeHtml(officer.cpio_phone)}" required></div>
                    <div class="col-12"><h6 class="mt-2">FAA Details</h6><hr></div>
                    <div class="col-md-6 mb-3"><label>Name *</label><input type="text" name="faa_name" class="form-control" value="${escapeHtml(officer.faa_name)}" required></div>
                    <div class="col-md-6 mb-3"><label>Designation *</label><input type="text" name="faa_designation" class="form-control" value="${escapeHtml(officer.faa_designation)}" required></div>
                    <div class="col-md-6 mb-3"><label>Email *</label><input type="email" name="faa_email" class="form-control" value="${escapeHtml(officer.faa_email)}" required></div>
                    <div class="col-md-6 mb-3"><label>Phone *</label><input type="text" name="faa_phone" class="form-control" value="${escapeHtml(officer.faa_phone)}" required></div>
                    <div class="col-md-6 mb-3"><label>Display Order</label><input type="number" name="display_order" class="form-control" value="${officer.display_order}"></div>
                </div>
            `;
            document.getElementById('editFormContent').innerHTML = html;
            $('#editOfficerModal').modal('show');
        }
        
        function escapeHtml(str) {
            if(!str) return '';
            return str.replace(/[&<>]/g, function(m) {
                if(m === '&') return '&amp;';
                if(m === '<') return '&lt;';
                if(m === '>') return '&gt;';
                return m;
            });
        }
    </script>
</body>
</html>