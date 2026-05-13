<?php
session_start();

// Include database configuration
include('inc/config.php');

// Check login status
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit;
}

// Handle Add/Edit/Delete operations
$error_msg = '';
$success_msg = '';

// Delete operation
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    try {
        // First get all documents to delete files
        $sql_get_docs = "SELECT doc_file FROM phd_documents WHERE phd_programme_id = :id";
        $query_get_docs = $dbh->prepare($sql_get_docs);
        $query_get_docs->bindParam(':id', $delete_id, PDO::PARAM_INT);
        $query_get_docs->execute();
        $docs = $query_get_docs->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($docs as $doc) {
            if (file_exists($doc['doc_file'])) {
                unlink($doc['doc_file']);
            }
        }
        
        $sql = "DELETE FROM phd_programmes WHERE id = :id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $delete_id, PDO::PARAM_INT);
        $query->execute();
        $success_msg = "PhD Programme deleted successfully!";
    } catch (PDOException $e) {
        $error_msg = "Delete failed: " . $e->getMessage();
    }
}

// Handle Add/Edit form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_form'])) {
    $programme_name = trim($_POST['programme_name']);
    $session = trim($_POST['session']);
    $status = $_POST['status'];
    $last_date = $_POST['last_date'];
    $phd_id = isset($_POST['phd_id']) ? intval($_POST['phd_id']) : 0;
    
    try {
        if ($phd_id > 0) {
            // Update existing programme
            $sql = "UPDATE phd_programmes SET programme_name = :programme_name, session = :session, 
                    status = :status, last_date = :last_date WHERE id = :id";
            $query = $dbh->prepare($sql);
            $query->bindParam(':id', $phd_id, PDO::PARAM_INT);
            $query->bindParam(':programme_name', $programme_name);
            $query->bindParam(':session', $session);
            $query->bindParam(':status', $status);
            $query->bindParam(':last_date', $last_date);
            $query->execute();
            $success_msg = "PhD Programme updated successfully!";
        } else {
            // Insert new programme
            $sql = "INSERT INTO phd_programmes (programme_name, session, status, last_date) 
                    VALUES (:programme_name, :session, :status, :last_date)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':programme_name', $programme_name);
            $query->bindParam(':session', $session);
            $query->bindParam(':status', $status);
            $query->bindParam(':last_date', $last_date);
            $query->execute();
            $phd_id = $dbh->lastInsertId();
            $success_msg = "PhD Programme added successfully!";
        }
        
        // Handle document uploads
        // Handle document uploads
if (isset($_FILES['documents']) && !empty($_FILES['documents']['name'])) {

    $upload_dir = 'uploads/phd_docs/';

    // Create folder if not exists
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    foreach ($_FILES['documents']['name'] as $i => $file_original_name) {

        // Skip empty uploads
        if (empty($file_original_name)) {
            continue;
        }

        // Check upload error
        if ($_FILES['documents']['error'][$i] != 0) {
            continue;
        }

        // Get title safely
        $doc_title = isset($_POST['doc_titles'][$i])
            ? trim($_POST['doc_titles'][$i])
            : 'Untitled Document';

        // Sanitize filename
        $clean_name = preg_replace('/[^a-zA-Z0-9._-]/', '', $file_original_name);

        // Unique filename
        $file_name = time() . '_' . uniqid() . '_' . $clean_name;

        $file_path = $upload_dir . $file_name;

        // Move uploaded file
        if (move_uploaded_file($_FILES['documents']['tmp_name'][$i], $file_path)) {

            // Insert into database
            $sql_doc = "INSERT INTO phd_documents 
                        (phd_programme_id, doc_title, doc_file)
                        VALUES 
                        (:phd_programme_id, :doc_title, :doc_file)";

            $query_doc = $dbh->prepare($sql_doc);

            $query_doc->bindParam(':phd_programme_id', $phd_id, PDO::PARAM_INT);
            $query_doc->bindParam(':doc_title', $doc_title, PDO::PARAM_STR);
            $query_doc->bindParam(':doc_file', $file_path, PDO::PARAM_STR);

            $query_doc->execute();
        }
    }
}
        
        // Delete document if requested
        if (isset($_POST['delete_doc_ids']) && !empty($_POST['delete_doc_ids'])) {
            $delete_ids = $_POST['delete_doc_ids'];
            foreach ($delete_ids as $doc_id) {
                // First get file path to delete actual file
                $sql_get = "SELECT doc_file FROM phd_documents WHERE id = :id";
                $query_get = $dbh->prepare($sql_get);
                $query_get->bindParam(':id', $doc_id);
                $query_get->execute();
                $doc = $query_get->fetch(PDO::FETCH_ASSOC);
                if ($doc && file_exists($doc['doc_file'])) {
                    unlink($doc['doc_file']);
                }
                
                $sql_del = "DELETE FROM phd_documents WHERE id = :id";
                $query_del = $dbh->prepare($sql_del);
                $query_del->bindParam(':id', $doc_id);
                $query_del->execute();
            }
        }
        
    } catch (PDOException $e) {
        $error_msg = "Operation failed: " . $e->getMessage();
    }
}

// Get programme for editing
$edit_programme = null;
$existing_docs = [];
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $sql = "SELECT * FROM phd_programmes WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $edit_id, PDO::PARAM_INT);
    $query->execute();
    $edit_programme = $query->fetch(PDO::FETCH_ASSOC);
    
    // Get documents for this programme
    $sql_docs = "SELECT * FROM phd_documents WHERE phd_programme_id = :programme_id ORDER BY id DESC";
    $query_docs = $dbh->prepare($sql_docs);
    $query_docs->bindParam(':programme_id', $edit_id);
    $query_docs->execute();
    $existing_docs = $query_docs->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Admin-panel - PhD Programme Management</title>
    
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="vendor/datatables/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
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
                        <h1 class="h3 mb-0 text-gray-800">PhD Programmes Management</h1>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#phdModal" onclick="resetForm()">
                            <i class="fas fa-plus"></i> Add New PhD Programme
                        </button>
                    </div>
                    
                    <?php if ($error_msg): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $error_msg; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($success_msg): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo $success_msg; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Data Table -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">All PhD Programmes</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Programme Name</th>
                                            <th>Session</th>
                                            <th>Status</th>
                                            <th>Last Date</th>
                                            <th>Documents</th>
                                            <th>Created Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT * FROM phd_programmes ORDER BY id DESC";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_ASSOC);
                                        
                                        foreach ($results as $row) {
                                            // Get document count
                                            $sql_count = "SELECT COUNT(*) as count FROM phd_documents WHERE phd_programme_id = :id";
                                            $query_count = $dbh->prepare($sql_count);
                                            $query_count->bindParam(':id', $row['id']);
                                            $query_count->execute();
                                            $doc_count = $query_count->fetch(PDO::FETCH_ASSOC);
                                            
                                            $status_badge = ($row['status'] == 'open') ? 
                                                '<span class="badge badge-success">Open</span>' : 
                                                '<span class="badge badge-danger">Closed</span>';
                                            
                                            // Check if expired
                                            $is_expired = strtotime($row['last_date']) < time();
                                            if ($is_expired && $row['status'] == 'open') {
                                                $status_badge = '<span class="badge badge-warning">Expired</span>';
                                            }
                                            ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><strong><?php echo htmlspecialchars($row['programme_name']); ?></strong></td>
                                                <td><?php echo htmlspecialchars($row['session']); ?></td>
                                                <td><?php echo $status_badge; ?></td>
                                                <td><?php echo date('d M Y', strtotime($row['last_date'])); ?>
                                                    <?php if ($is_expired): ?>
                                                        <br><small class="text-danger">(Passed)</small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <button class="btn btn-info btn-sm" onclick="viewDocuments(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars(addslashes($row['programme_name'])); ?>')">
                                                        <i class="fas fa-file-alt"></i> <?php echo $doc_count['count']; ?> Documents
                                                    </button>
                                                </td>
                                                <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm" onclick="editPhd(<?php echo $row['id']; ?>)">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                    <button class="btn btn-danger btn-sm" onclick="deletePhd(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars(addslashes($row['programme_name'])); ?>')">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php } ?>
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
    
    <!-- Add/Edit Modal -->
    <div class="modal fade" id="phdModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add New PhD Programme</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="phdForm" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="phd_id" id="phd_id" value="0">
                        <input type="hidden" name="submit_form" value="1">
                        
                        <div class="form-group">
                            <label>Programme Name *</label>
                            <input type="text" class="form-control" name="programme_name" id="programme_name" required placeholder="e.g., PhD in Biotechnology">
                        </div>
                        
                        <div class="form-group">
                            <label>Session *</label>
                            <input type="text" class="form-control" name="session" id="session" required placeholder="e.g., July - December 2024">
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Status *</label>
                                <select class="form-control" name="status" id="status" required>
                                    <option value="open">Open</option>
                                    <option value="closed">Closed</option>
                                </select>
                            </div>
                            
                            <div class="form-group col-md-6">
                                <label>Last Date to Apply *</label>
                                <input type="date" class="form-control" name="last_date" id="last_date" required>
                            </div>
                        </div>
                        
                        <div class="form-group" id="documentsSection">
                            <label>Required Documents</label>
                            <div id="documentList">
                                <div class="document-item mb-2">
                                    <div class="form-row">
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="doc_titles[]" placeholder="Document Title e.g., Application Form">
                                        </div>
                                        <div class="col-md-5">
                                            <input type="file" class="form-control-file" name="documents[]" accept=".pdf,.doc,.docx,.jpg,.png">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger btn-sm" onclick="removeDocument(this)">Remove</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-secondary btn-sm mt-2" onclick="addDocument()">
                                <i class="fas fa-plus"></i> Add Another Document
                            </button>
                        </div>
                        
                        <div id="existingDocuments" style="display:none;">
                            <hr>
                            <h6>Existing Documents</h6>
                            <div id="existingDocsList"></div>
                            <small class="text-muted">Check the box to delete existing documents</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Programme</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- View Documents Modal -->
    <div class="modal fade" id="viewDocsModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Programme Documents</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="docsModalBody">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "pageLength": 10,
                "order": [[0, 'desc']]
            });
        });
        
        function addDocument() {
            const docHtml = `
                <div class="document-item mb-2">
                    <div class="form-row">
                        <div class="col-md-5">
                            <input type="text" class="form-control" name="doc_titles[]" placeholder="Document Title">
                        </div>
                        <div class="col-md-5">
                            <input type="file" class="form-control-file" name="documents[]" accept=".pdf,.doc,.docx,.jpg,.png">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeDocument(this)">Remove</button>
                        </div>
                    </div>
                </div>
            `;
            $('#documentList').append(docHtml);
        }
        
        function removeDocument(btn) {
            $(btn).closest('.document-item').remove();
        }
        
        function resetForm() {
            $('#phdForm')[0].reset();
            $('#phd_id').val(0);
            $('#modalTitle').text('Add New PhD Programme');
            $('#documentList').html(`
                <div class="document-item mb-2">
                    <div class="form-row">
                        <div class="col-md-5">
                            <input type="text" class="form-control" name="doc_titles[]" placeholder="Document Title">
                        </div>
                        <div class="col-md-5">
                            <input type="file" class="form-control-file" name="documents[]" accept=".pdf,.doc,.docx,.jpg,.png">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeDocument(this)">Remove</button>
                        </div>
                    </div>
                </div>
            `);
            $('#existingDocuments').hide();
        }
        
        function editPhd(id) {
            $.ajax({
                url: 'get_phd_data.php',
                type: 'GET',
                data: { id: id },
                dataType: 'json',
                success: function(data) {
                    $('#phd_id').val(data.id);
                    $('#programme_name').val(data.programme_name);
                    $('#session').val(data.session);
                    $('#status').val(data.status);
                    $('#last_date').val(data.last_date);
                    $('#modalTitle').text('Edit PhD Programme');
                    
                    // Show existing documents
                    if (data.documents && data.documents.length > 0) {
                        let docsHtml = '<div class="form-group">';
                        data.documents.forEach(function(doc) {
                            docsHtml += `
                                <div class="form-check mb-2" id="doc_${doc.id}">
                                    <input type="checkbox" class="form-check-input" name="delete_doc_ids[]" value="${doc.id}" id="del_doc_${doc.id}">
                                    <label class="form-check-label" for="del_doc_${doc.id}">
                                        <strong>${doc.doc_title}</strong><br>
                                        <a href="${doc.doc_file}" target="_blank" class="btn btn-sm btn-link">View Document</a>
                                        <span class="text-danger">(Check to delete)</span>
                                    </label>
                                </div>
                            `;
                        });
                        docsHtml += '</div>';
                        $('#existingDocsList').html(docsHtml);
                        $('#existingDocuments').show();
                    } else {
                        $('#existingDocuments').hide();
                    }
                    
                    $('#phdModal').modal('show');
                },
                error: function() {
                    Swal.fire('Error', 'Failed to load programme data', 'error');
                }
            });
        }
        
        function deletePhd(id, title) {
            Swal.fire({
                title: 'Are you sure?',
                text: `You want to delete "${title}"? This will also delete all associated documents!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `manage_phd.php?delete_id=${id}`;
                }
            });
        }
        
        function viewDocuments(id, title) {
            $.ajax({
                url: 'get_phd_data.php',
                type: 'GET',
                data: { id: id, get_docs_only: 1 },
                dataType: 'json',
                success: function(data) {
                    let html = `<h6>Documents for: ${title}</h6><hr>`;
                    if (data.documents && data.documents.length > 0) {
                        html += '<div class="list-group">';
                        data.documents.forEach(function(doc) {
                            html += `
                                <div class="list-group-item">
                                    <strong><i class="fas fa-file-alt"></i> ${doc.doc_title}</strong><br>
                                    <a href="${doc.doc_file}" target="_blank" class="btn btn-sm btn-info mt-2">
                                        <i class="fas fa-download"></i> Download/View Document
                                    </a>
                                </div>
                            `;
                        });
                        html += '</div>';
                    } else {
                        html += '<p class="text-muted text-center">No documents uploaded for this programme.</p>';
                    }
                    $('#docsModalBody').html(html);
                    $('#viewDocsModal').modal('show');
                },
                error: function() {
                    $('#docsModalBody').html('<p class="text-danger">Error loading documents. Please try again.</p>');
                    $('#viewDocsModal').modal('show');
                }
            });
        }
    </script>
</body>
</html>