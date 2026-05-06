<?php
session_start();

// Include database configuration (already has $dbh)
include('inc/config.php'); // <- this replaces the manual $dbh setup
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit;
}

// Create uploads directory if not exists
$upload_dir = 'uploads/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Handle different actions
$action = $_POST['action'] ?? $_GET['action'] ?? 'list';

switch($action) {
    case 'create':
        handleCreate($dbh, $upload_dir);
        break;
    case 'edit':
        handleEdit($dbh);
        break;
    case 'update':
        handleUpdate($dbh, $upload_dir);
        break;
    case 'delete':
        handleDelete($dbh, $upload_dir);
        break;
    default:
        // Store directors data for display
        $directors = listDirectors($dbh);
        break;
}

function uploadPhoto($file, $upload_dir) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $max_size = 5 * 1024 * 1024; // 5MB
    
    if (!in_array($file['type'], $allowed_types)) {
        return ['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, GIF, WEBP allowed.'];
    }
    
    if ($file['size'] > $max_size) {
        return ['success' => false, 'message' => 'File too large. Max 5MB allowed.'];
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = time() . '_' . uniqid() . '.' . $extension;
    $filepath = $upload_dir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'path' => $filepath];
    }
    
    return ['success' => false, 'message' => 'Failed to upload file.'];
}

function handleCreate($dbh, $upload_dir) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name']);
        $service_from = $_POST['service_from'];
        $service_to = $_POST['service_to'] ?: NULL;
        
        if (empty($name) || empty($service_from)) {
            $_SESSION['error'] = "Name and Service From date are required";
            header("Location: " . $_SERVER['PHP_SELF'] . "?action=create");
            exit;
        }
        
        // Handle photo upload
        $photo_path = '';
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $upload_result = uploadPhoto($_FILES['photo'], $upload_dir);
            if ($upload_result['success']) {
                $photo_path = $upload_result['path'];
            } else {
                $_SESSION['error'] = $upload_result['message'];
                header("Location: " . $_SERVER['PHP_SELF'] . "?action=create");
                exit;
            }
        }
        
        try {
            $stmt = $dbh->prepare("INSERT INTO directors (name, photo, service_from, service_to) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $photo_path, $service_from, $service_to]);
            $_SESSION['success'] = "Director added successfully";
        } catch(PDOException $e) {
            $_SESSION['error'] = "Error adding director: " . $e->getMessage();
        }
        
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
    
    // Store flag to show create form
    $GLOBALS['show_create_form'] = true;
}

function handleEdit($dbh) {
    $id = $_GET['id'] ?? 0;
    $stmt = $dbh->prepare("SELECT * FROM directors WHERE id = ?");
    $stmt->execute([$id]);
    $director = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$director) {
        $_SESSION['error'] = "Director not found";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
    
    // Store director data for edit form
    $GLOBALS['edit_director'] = $director;
}

function handleUpdate($dbh, $upload_dir) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['director_id'];
        $name = trim($_POST['name']);
        $service_from = $_POST['service_from'];
        $service_to = $_POST['service_to'] ?: NULL;
        
        if (empty($name) || empty($service_from)) {
            $_SESSION['error'] = "Name and Service From date are required";
            header("Location: " . $_SERVER['PHP_SELF'] . "?action=edit&id=" . $id);
            exit;
        }
        
        // Get current photo
        $stmt = $dbh->prepare("SELECT photo FROM directors WHERE id = ?");
        $stmt->execute([$id]);
        $current = $stmt->fetch(PDO::FETCH_ASSOC);
        $photo_path = $current['photo'];
        
        // Handle new photo upload
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            // Delete old photo if exists
            if ($photo_path && file_exists($photo_path)) {
                unlink($photo_path);
            }
            
            $upload_result = uploadPhoto($_FILES['photo'], $upload_dir);
            if ($upload_result['success']) {
                $photo_path = $upload_result['path'];
            } else {
                $_SESSION['error'] = $upload_result['message'];
                header("Location: " . $_SERVER['PHP_SELF'] . "?action=edit&id=" . $id);
                exit;
            }
        }
        
        try {
            $stmt = $dbh->prepare("UPDATE directors SET name = ?, photo = ?, service_from = ?, service_to = ? WHERE id = ?");
            $stmt->execute([$name, $photo_path, $service_from, $service_to, $id]);
            $_SESSION['success'] = "Director updated successfully";
        } catch(PDOException $e) {
            $_SESSION['error'] = "Error updating director: " . $e->getMessage();
        }
        
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

function handleDelete($dbh, $upload_dir) {
    $id = $_GET['id'] ?? 0;
    
    // Get photo path to delete file
    $stmt = $dbh->prepare("SELECT photo FROM directors WHERE id = ?");
    $stmt->execute([$id]);
    $director = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($director && $director['photo'] && file_exists($director['photo'])) {
        unlink($director['photo']);
    }
    
    try {
        $stmt = $dbh->prepare("DELETE FROM directors WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['success'] = "Director deleted successfully";
    } catch(PDOException $e) {
        $_SESSION['error'] = "Error deleting director: " . $e->getMessage();
    }
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

function listDirectors($dbh) {
    // Get directors ordered by service_from (oldest first)
    $stmt = $dbh->query("SELECT * FROM directors ORDER BY service_from ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    <title>Admin-panel - Directors Management</title>

    <!-- Font awesome -->
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="vendor/datatables/dataTables.bootstrap4.css">
    
    <style>
        /* Custom styles for directors timeline */
        .timeline {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .timeline-item {
            display: flex;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
        }
        .timeline-item:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        .timeline-year {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            min-width: 120px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            text-align: center;
        }
        .year-start, .year-end {
            font-size: 1.2em;
            font-weight: bold;
        }
        .year-separator {
            margin: 5px 0;
        }
        .timeline-photo {
            width: 150px;
            height: 150px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f7f9fc;
            padding: 15px;
        }
        .timeline-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #667eea;
        }
        .timeline-photo .no-photo {
            width: 100%;
            height: 100%;
            background: #e0e0e0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: #999;
        }
        .timeline-info {
            flex: 1;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .director-name {
            font-size: 1.4em;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .service-period {
            color: #666;
            font-size: 0.9em;
        }
        .service-period i {
            font-style: normal;
            background: #f0f0f0;
            padding: 3px 8px;
            border-radius: 15px;
        }
        .current-badge {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 0.75em;
            margin-left: 10px;
        }
        .actions {
            display: flex;
            gap: 10px;
            margin-left: 20px;
        }
        .btn-edit, .btn-delete {
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.85em;
            transition: opacity 0.2s;
        }
        .btn-edit {
            background: #ffc107;
            color: #333;
        }
        .btn-delete {
            background: #dc3545;
            color: white;
        }
        .btn-edit:hover, .btn-delete:hover {
            opacity: 0.8;
        }
        .empty-state {
            text-align: center;
            padding: 60px;
            color: #666;
        }
        .empty-state p {
            margin-bottom: 20px;
            font-size: 1.2em;
        }
        @media (max-width: 768px) {
            .timeline-item {
                flex-direction: column;
            }
            .timeline-year {
                flex-direction: row;
                justify-content: space-around;
                padding: 10px;
            }
            .timeline-photo {
                width: 100%;
                height: auto;
                padding: 20px;
            }
            .timeline-info {
                flex-direction: column;
                text-align: center;
            }
            .actions {
                margin-top: 15px;
                margin-left: 0;
            }
        }
        
        /* Form styles */
        .form-container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
        }
        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .current-photo {
            margin: 10px 0;
            padding: 10px;
            background: #f7f9fc;
            border-radius: 4px;
        }
        .current-photo img {
            max-width: 100px;
            border-radius: 50%;
        }
        .helper-text {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #667eea;
            text-decoration: none;
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

                    <?php if(isset($show_create_form)): ?>
                        <!-- Create Form -->
                        <div class="form-container">
                            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                                <h1 class="h3 mb-0 text-gray-800">Add New Director</h1>
                                <a href="?action=list" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">← Back to Timeline</a>
                            </div>
                            
                            <form method="POST" action="?action=create" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="name">Director Name *</label>
                                    <input type="text" id="name" name="name" class="form-control" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="photo">Photo</label>
                                    <input type="file" id="photo" name="photo" class="form-control" accept="image/*">
                                    <div class="helper-text">Accepted formats: JPG, PNG, GIF, WEBP. Max size: 5MB</div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="service_from">Service From (Start Date) *</label>
                                    <input type="date" id="service_from" name="service_from" class="form-control" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="service_to">Service To (End Date)</label>
                                    <input type="date" id="service_to" name="service_to" class="form-control">
                                    <div class="helper-text">Leave empty if currently serving</div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Add Director</button>
                            </form>
                        </div>

                    <?php elseif(isset($edit_director)): ?>
                        <!-- Edit Form -->
                        <div class="form-container">
                            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                                <h1 class="h3 mb-0 text-gray-800">Edit Director</h1>
                                <a href="?action=list" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">← Back to Timeline</a>
                            </div>
                            
                            <form method="POST" action="?action=update" enctype="multipart/form-data">
                                <input type="hidden" name="director_id" value="<?php echo $edit_director['id']; ?>">
                                
                                <div class="form-group">
                                    <label for="name">Director Name *</label>
                                    <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($edit_director['name']); ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="photo">Photo</label>
                                    <?php if($edit_director['photo'] && file_exists($edit_director['photo'])): ?>
                                        <div class="current-photo">
                                            <img src="<?php echo $edit_director['photo']; ?>" alt="Current Photo">
                                            <span>Current photo</span>
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" id="photo" name="photo" class="form-control" accept="image/*">
                                    <div class="helper-text">Leave empty to keep current photo. Accepted formats: JPG, PNG, GIF, WEBP. Max size: 5MB</div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="service_from">Service From (Start Date) *</label>
                                    <input type="date" id="service_from" name="service_from" class="form-control" value="<?php echo $edit_director['service_from']; ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="service_to">Service To (End Date)</label>
                                    <input type="date" id="service_to" name="service_to" class="form-control" value="<?php echo $edit_director['service_to']; ?>">
                                    <div class="helper-text">Leave empty if currently serving</div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Update Director</button>
                            </form>
                        </div>

                    <?php else: ?>
                        <!-- List View -->
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3 mb-0 text-gray-800">📅 Directors Timeline</h1>
                            <a href="?action=create" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                                <i class="fas fa-plus fa-sm text-white-50"></i> Add New Director
                            </a>
                        </div>

                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Chronological order of directors (Oldest to Newest)</h6>
                            </div>
                            <div class="card-body">
                                <?php if(empty($directors)): ?>
                                    <div class="empty-state">
                                        <p>No directors found.</p>
                                        <a href="?action=create" class="btn btn-primary">Add First Director</a>
                                    </div>
                                <?php else: ?>
                                    <div class="timeline">
                                        <?php foreach($directors as $index => $director): ?>
                                            <div class="timeline-item">
                                                <div class="timeline-year">
                                                    <div class="year-start"><?php echo date('d-m-Y', strtotime($director['service_from'])); ?></div>
                                                    <div class="year-separator">→</div>
                                                    <div class="year-end">
                                                        <?php 
                                                        if($director['service_to']) {
                                                            echo date('d-m-Y', strtotime($director['service_to']));
                                                        } else {
                                                            echo '<span style="color: #ffd700;">Present</span>';
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                                
                                                <div class="timeline-photo">
                                                    <?php if($director['photo'] && file_exists($director['photo'])): ?>
                                                        <img src="<?php echo $director['photo']; ?>" alt="<?php echo htmlspecialchars($director['name']); ?>">
                                                    <?php else: ?>
                                                        <div class="no-photo">👤</div>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <div class="timeline-info">
                                                    <div>
                                                        <div class="director-name">
                                                            <?php echo htmlspecialchars($director['name']); ?>
                                                            <?php if(!$director['service_to']): ?>
                                                                <span class="current-badge">Current Director</span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="service-period">
                                                            <i>📅 Service Period: <?php echo date('d-m-Y', strtotime($director['service_from'])); ?> - <?php echo $director['service_to'] ? date('d-m-Y', strtotime($director['service_to'])) : 'Present'; ?></i>
                                                        </div>
                                                    </div>
                                                    <div class="actions">
                                                        <a href="?action=edit&id=<?php echo $director['id']; ?>" class="btn-edit">✏️ Edit</a>
                                                        <a href="?action=delete&id=<?php echo $director['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this director?')">🗑️ Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <?php include('inc/footer.php'); ?>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

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