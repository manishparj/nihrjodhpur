<?php
// newspaper_photos.php
session_start();
error_reporting(0);
include('inc/config.php');

// Check if admin is logged in
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}

// Handle Delete Operation
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Get photo path to delete file
    $sql = "SELECT photo_path FROM newspaper_photos WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $photo = $query->fetch(PDO::FETCH_ASSOC);
    
    if ($photo) {
        // Delete the physical file
        $file_path = $photo['photo_path'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        
        // Delete database record
        $sql = "DELETE FROM newspaper_photos WHERE id = :id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        if ($query->execute()) {
            $success = "Photo deleted successfully!";
        } else {
            $error = "Failed to delete photo!";
        }
    }
}

// Handle Add/Edit Operation
if (isset($_POST['submit'])) {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $newspaper_date = !empty($_POST['newspaper_date']) ? $_POST['newspaper_date'] : NULL;
    
    $upload_dir = "uploads/newspaper/";
    
    // Create directory if not exists
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Handle file upload
    $photo_path = '';
    $photo_name = '';
    
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $filename = $_FILES['photo']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $photo_name = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);
            $photo_path = $upload_dir . $photo_name;
            
            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path)) {
                $error = "Failed to upload file!";
            }
        } else {
            $error = "Invalid file type! Only JPG, PNG, GIF, WEBP allowed.";
        }
    }
    
    // If editing and no new photo, keep existing
    if ($id > 0 && empty($photo_path)) {
        $sql = "SELECT photo_path, photo_name FROM newspaper_photos WHERE id = :id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $existing = $query->fetch(PDO::FETCH_ASSOC);
        if ($existing) {
            $photo_path = $existing['photo_path'];
            $photo_name = $existing['photo_name'];
        }
    }
    
    if (!isset($error)) {
        if ($photo_path != '') {
            if ($id > 0) {
                // Update existing record
                $sql = "UPDATE newspaper_photos SET 
                        photo_path = :photo_path, 
                        photo_name = :photo_name, 
                        newspaper_date = :newspaper_date 
                        WHERE id = :id";
                $query = $dbh->prepare($sql);
                $query->bindParam(':id', $id, PDO::PARAM_INT);
            } else {
                // Insert new record
                $sql = "INSERT INTO newspaper_photos (photo_path, photo_name, newspaper_date) 
                        VALUES (:photo_path, :photo_name, :newspaper_date)";
                $query = $dbh->prepare($sql);
            }
            
            $query->bindParam(':photo_path', $photo_path, PDO::PARAM_STR);
            $query->bindParam(':photo_name', $photo_name, PDO::PARAM_STR);
            $query->bindParam(':newspaper_date', $newspaper_date, PDO::PARAM_STR);
            
            if ($query->execute()) {
                $success = $id > 0 ? "Photo updated successfully!" : "Photo added successfully!";
            } else {
                $error = "Database operation failed!";
            }
        } else {
            $error = "Please select a photo to upload!";
        }
    }
}

// Get record for editing
$edit_data = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM newspaper_photos WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $edit_data = $query->fetch(PDO::FETCH_ASSOC);
}

// Get all photos for display
$sql = "SELECT * FROM newspaper_photos ORDER BY upload_date DESC";
$query = $dbh->prepare($sql);
$query->execute();
$photos = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="en" class="no-js">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Newspaper Photo Management - Admin Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTables -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <style>
        .errorWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #dd3d36;
            color: #fff;
            border-radius: 5px;
        }
        .succWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #5cb85c;
            color: #fff;
            border-radius: 5px;
        }
        .photo-preview {
            max-width: 80px;
            max-height: 60px;
            border-radius: 5px;
            object-fit: cover;
        }
        .photo-modal-img {
            max-width: 100%;
            max-height: 500px;
        }
        .image-preview {
            margin-top: 15px;
            max-width: 200px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .btn-action {
            margin: 0 2px;
        }
        .table img {
            cursor: pointer;
            transition: transform 0.2s;
        }
        .table img:hover {
            transform: scale(1.05);
        }
        .photo-card {
            transition: all 0.3s;
        }
        .photo-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
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
                        <h1 class="h3 mb-0 text-gray-800">Newspaper Photo Management</h1>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#photoModal" onclick="resetForm()">
                            <i class="fas fa-plus"></i> Add New Photo
                        </button>
                    </div>
                    
                    <!-- Alert Messages -->
                    <?php if(isset($error)) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php } ?>
                    
                    <?php if(isset($success)) { ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php } ?>
                    
                    <!-- Photos Grid View -->
                    <div class="row">
                        <?php if(count($photos) > 0): ?>
                            <?php foreach($photos as $photo): ?>
                                <div class="col-md-3 col-sm-6 mb-4">
                                    <div class="card photo-card h-100">
                                        <img src="<?php echo htmlspecialchars($photo['photo_path']); ?>" 
                                             class="card-img-top" 
                                             alt="Newspaper Photo"
                                             onclick="showPhotoModal('<?php echo htmlspecialchars($photo['photo_path']); ?>', '<?php echo date('d M Y', strtotime($photo['newspaper_date'])); ?>')"
                                             style="cursor: pointer;">
                                        <div class="card-body">
                                            <p class="card-text text-muted small">
                                                <i class="far fa-calendar-alt"></i> 
                                                <?php echo $photo['newspaper_date'] ? date('d M Y', strtotime($photo['newspaper_date'])) : 'No date'; ?>
                                            </p>
                                            <p class="card-text text-muted small">
                                                <i class="far fa-clock"></i> 
                                                Uploaded: <?php echo date('d M Y', strtotime($photo['upload_date'])); ?>
                                            </p>
                                        </div>
                                        <div class="card-footer bg-transparent text-center">
                                            <button class="btn btn-sm btn-info" onclick='editPhoto(<?php echo json_encode($photo); ?>)'>
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <a href="?action=delete&id=<?php echo $photo['id']; ?>" 
                                               class="btn btn-sm btn-danger" 
                                               onclick="return confirm('Are you sure you want to delete this photo?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12 text-center py-5">
                                <i class="fas fa-image fa-4x text-gray-300 mb-3"></i>
                                <h5 class="text-gray-500">No photos uploaded yet</h5>
                                <p class="text-muted">Click "Add New Photo" to upload your first newspaper photo.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <?php include('inc/footer.php'); ?>
        </div>
    </div>
    
    <!-- Add/Edit Photo Modal -->
    <div class="modal fade" id="photoModal" tabindex="-1" role="dialog" aria-labelledby="photoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="photoModalLabel">Add New Newspaper Photo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" enctype="multipart/form-data" id="photoForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="photo_id" value="0">
                        
                        <div class="form-group">
                            <label for="photo">Select Photo <span class="text-danger">*</span></label>
                            <input type="file" class="form-control-file" id="photo" name="photo" accept="image/jpeg,image/png,image/gif,image/webp" required>
                            <small class="form-text text-muted">Allowed: JPG, PNG, GIF, WEBP. Max size: 5MB</small>
                            <div id="imagePreviewContainer"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="newspaper_date">Newspaper Date (Optional)</label>
                            <input type="date" class="form-control" id="newspaper_date" name="newspaper_date">
                            <small class="form-text text-muted">Select the date this newspaper was published</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit" class="btn btn-primary">Save Photo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Photo View Modal -->
    <div class="modal fade" id="viewPhotoModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewPhotoTitle">Newspaper Photo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img src="" id="viewPhotoImg" class="photo-modal-img" alt="">
                    <p class="mt-3 text-muted" id="viewPhotoDate"></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Image preview on file select
            $('#photo').on('change', function(e) {
                var container = $('#imagePreviewContainer');
                container.empty();
                
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        container.html('<img src="' + e.target.result + '" class="image-preview">');
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });
        
        function resetForm() {
            $('#photoForm')[0].reset();
            $('#photo_id').val(0);
            $('#photoModalLabel').text('Add New Newspaper Photo');
            $('#photo').prop('required', true);
            $('#imagePreviewContainer').empty();
        }
        
        function editPhoto(photo) {
            $('#photo_id').val(photo.id);
            $('#newspaper_date').val(photo.newspaper_date);
            $('#photoModalLabel').text('Edit Newspaper Photo');
            $('#photo').prop('required', false);
            $('#imagePreviewContainer').empty();
            
            // Show current photo preview
            if (photo.photo_path) {
                $('#imagePreviewContainer').html(
                    '<img src="' + photo.photo_path + '" class="image-preview"><br>' +
                    '<small class="text-muted">Current photo (upload new to replace)</small>'
                );
            }
            
            $('#photoModal').modal('show');
        }
        
        function showPhotoModal(photoPath, date) {
            $('#viewPhotoImg').attr('src', photoPath);
            $('#viewPhotoDate').text(date ? 'Newspaper Date: ' + date : 'No date specified');
            $('#viewPhotoModal').modal('show');
        }
    </script>
</body>

</html>