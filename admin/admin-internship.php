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
        // Delete will cascade to documents due to foreign key
        $sql = "DELETE FROM internship_programmes WHERE id = :id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $delete_id, PDO::PARAM_INT);
        $query->execute();
        $success_msg = "Internship programme deleted successfully!";
    } catch (PDOException $e) {
        $error_msg = "Delete failed: " . $e->getMessage();
    }
}

// Handle Add/Edit form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $status = $_POST['status'];
    $last_date = $_POST['last_date'];
    $duration = $_POST['duration'];
    $internship_id = isset($_POST['internship_id']) ? intval($_POST['internship_id']) : 0;
    
    try {
        if ($internship_id > 0) {
            // Update existing programme
            $sql = "UPDATE internship_programmes SET title = :title, status = :status, 
                    last_date = :last_date, duration = :duration WHERE id = :id";
            $query = $dbh->prepare($sql);
            $query->bindParam(':id', $internship_id, PDO::PARAM_INT);
            $query->bindParam(':title', $title);
            $query->bindParam(':status', $status);
            $query->bindParam(':last_date', $last_date);
            $query->bindParam(':duration', $duration);
            $query->execute();
            $success_msg = "Internship programme updated successfully!";
        } else {
            // Insert new programme
            $sql = "INSERT INTO internship_programmes (title, status, last_date, duration) 
                    VALUES (:title, :status, :last_date, :duration)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':title', $title);
            $query->bindParam(':status', $status);
            $query->bindParam(':last_date', $last_date);
            $query->bindParam(':duration', $duration);
            $query->execute();
            $internship_id = $dbh->lastInsertId();
            $success_msg = "Internship programme added successfully!";
        }
        
        // Handle document uploads
        if (isset($_FILES['documents']) && !empty($_FILES['documents']['name'][0])) {
            $upload_dir = 'uploads/internship_docs/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            for ($i = 0; $i < count($_FILES['documents']['name']); $i++) {
                if ($_FILES['documents']['error'][$i] == 0) {
                    $doc_title = $_POST['doc_titles'][$i];
                    $file_name = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['documents']['name'][$i]);
                    $file_path = $upload_dir . $file_name;
                    
                    if (move_uploaded_file($_FILES['documents']['tmp_name'][$i], $file_path)) {
                        $sql_doc = "INSERT INTO internship_documents (internship_id, doc_title, doc_file) 
                                   VALUES (:internship_id, :doc_title, :doc_file)";
                        $query_doc = $dbh->prepare($sql_doc);
                        $query_doc->bindParam(':internship_id', $internship_id);
                        $query_doc->bindParam(':doc_title', $doc_title);
                        $query_doc->bindParam(':doc_file', $file_path);
                        $query_doc->execute();
                    }
                }
            }
        }
        
        // Delete document if requested
        if (isset($_POST['delete_doc_ids']) && !empty($_POST['delete_doc_ids'])) {
            $delete_ids = $_POST['delete_doc_ids'];
            foreach ($delete_ids as $doc_id) {
                // First get file path to delete actual file
                $sql_get = "SELECT doc_file FROM internship_documents WHERE id = :id";
                $query_get = $dbh->prepare($sql_get);
                $query_get->bindParam(':id', $doc_id);
                $query_get->execute();
                $doc = $query_get->fetch(PDO::FETCH_ASSOC);
                if ($doc && file_exists($doc['doc_file'])) {
                    unlink($doc['doc_file']);
                }
                
                $sql_del = "DELETE FROM internship_documents WHERE id = :id";
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
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $sql = "SELECT * FROM internship_programmes WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $edit_id, PDO::PARAM_INT);
    $query->execute();
    $edit_programme = $query->fetch(PDO::FETCH_ASSOC);
    
    // Get documents for this programme
    $sql_docs = "SELECT * FROM internship_documents WHERE internship_id = :internship_id ORDER BY id DESC";
    $query_docs = $dbh->prepare($sql_docs);
    $query_docs->bindParam(':internship_id', $edit_id);
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
    <title>Admin-panel - Internship Management</title>
    
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
                        <h1 class="h3 mb-0 text-gray-800">Internship Programmes Management</h1>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#internshipModal" onclick="resetForm()">
                            <i class="fas fa-plus"></i> Add New Programme
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
                            <h6 class="m-0 font-weight-bold text-primary">All Internship Programmes</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Status</th>
                                            <th>Last Date</th>
                                            <th>Duration</th>
                                            <th>Documents</th>
                                            <th>Created Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT * FROM internship_programmes ORDER BY id DESC";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_ASSOC);
                                        
                                        foreach ($results as $row) {
                                            // Get document count
                                            $sql_count = "SELECT COUNT(*) as count FROM internship_documents WHERE internship_id = :id";
                                            $query_count = $dbh->prepare($sql_count);
                                            $query_count->bindParam(':id', $row['id']);
                                            $query_count->execute();
                                            $doc_count = $query_count->fetch(PDO::FETCH_ASSOC);
                                            
                                            $status_badge = ($row['status'] == 'open') ? 
                                                '<span class="badge badge-success">Open</span>' : 
                                                '<span class="badge badge-danger">Closed</span>';
                                            
                                            $duration_text = str_replace('_', ' ', ucfirst($row['duration']));
                                            ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                                <td><?php echo $status_badge; ?></td>
                                                <td><?php echo date('d M Y', strtotime($row['last_date'])); ?></td>
                                                <td><?php echo $duration_text; ?></td>
                                                <td>
                                                    <button class="btn btn-info btn-sm" onclick="viewDocuments(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars(addslashes($row['title'])); ?>')">
                                                        <i class="fas fa-file-alt"></i> <?php echo $doc_count['count']; ?> Documents
                                                    </button>
                                                </td>
                                                <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm" onclick="editInternship(<?php echo $row['id']; ?>)">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                    <button class="btn btn-danger btn-sm" onclick="deleteInternship(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars(addslashes($row['title'])); ?>')">
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
    <div class="modal fade" id="internshipModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add New Internship Programme</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="internshipForm" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="internship_id" id="internship_id" value="0">
                        
                        <div class="form-group">
                            <label>Programme Title *</label>
                            <input type="text" class="form-control" name="title" id="title" required>
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
                                <label>Duration *</label>
                                <select class="form-control" name="duration" id="duration" required>
                                    <option value="two_months">2 Months</option>
                                    <option value="three_months">3 Months</option>
                                    <option value="six_months">6 Months</option>
                                    <option value="one_year">1 Year</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Last Date to Apply *</label>
                            <input type="date" class="form-control" name="last_date" id="last_date" required>
                        </div>
                        
                        <div class="form-group" id="documentsSection">
                            <label>Required Documents</label>
                            <div id="documentList">
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
                            </div>
                            <button type="button" class="btn btn-secondary btn-sm mt-2" onclick="addDocument()">
                                <i class="fas fa-plus"></i> Add Another Document
                            </button>
                        </div>
                        
                        <div id="existingDocuments" style="display:none;">
                            <hr>
                            <h6>Existing Documents</h6>
                            <div id="existingDocsList"></div>
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
            $('#dataTable').DataTable();
        });
        
        let documentCounter = 1;
        
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
            $('#internshipForm')[0].reset();
            $('#internship_id').val(0);
            $('#modalTitle').text('Add New Internship Programme');
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
        
        function editInternship(id) {
            $.ajax({
                url: 'get_internship_data.php',
                type: 'GET',
                data: { id: id },
                dataType: 'json',
                success: function(data) {
                    $('#internship_id').val(data.id);
                    $('#title').val(data.title);
                    $('#status').val(data.status);
                    $('#duration').val(data.duration);
                    $('#last_date').val(data.last_date);
                    $('#modalTitle').text('Edit Internship Programme');
                    
                    // Show existing documents
                    if (data.documents && data.documents.length > 0) {
                        let docsHtml = '<div class="form-group">';
                        data.documents.forEach(function(doc) {
                            docsHtml += `
                                <div class="form-check mb-2" id="doc_${doc.id}">
                                    <input type="checkbox" class="form-check-input" name="delete_doc_ids[]" value="${doc.id}" id="del_doc_${doc.id}">
                                    <label class="form-check-label" for="del_doc_${doc.id}">
                                        ${doc.doc_title} - 
                                        <a href="${doc.doc_file}" target="_blank">View Document</a>
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
                    
                    $('#internshipModal').modal('show');
                }
            });
        }
        
        function deleteInternship(id, title) {
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
                    window.location.href = `manage_internship.php?delete_id=${id}`;
                }
            });
        }
        
        function viewDocuments(id, title) {
            $.ajax({
                url: 'get_internship_data.php',
                type: 'GET',
                data: { id: id, get_docs_only: 1 },
                dataType: 'json',
                success: function(data) {
                    let html = `<h6>Documents for: ${title}</h6><hr>`;
                    if (data.documents && data.documents.length > 0) {
                        html += '<ul class="list-group">';
                        data.documents.forEach(function(doc) {
                            html += `
                                <li class="list-group-item">
                                    <strong>${doc.doc_title}</strong><br>
                                    <a href="${doc.doc_file}" target="_blank" class="btn btn-sm btn-info mt-2">
                                        <i class="fas fa-download"></i> Download/View
                                    </a>
                                </li>
                            `;
                        });
                        html += '</ul>';
                    } else {
                        html += '<p class="text-muted">No documents uploaded for this programme.</p>';
                    }
                    $('#docsModalBody').html(html);
                    $('#viewDocsModal').modal('show');
                }
            });
        }
    </script>
</body>
</html>