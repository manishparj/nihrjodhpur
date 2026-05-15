<?php
session_start();

// Include database configuration
include('inc/config.php');

// Check login status
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit;
}

// Create tables if not exist
$create_galleries = "CREATE TABLE IF NOT EXISTS carousel_galleries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    subtitle TEXT,
    cover_photo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($conn, $create_galleries);

$create_items = "CREATE TABLE IF NOT EXISTS carousel_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gallery_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    caption TEXT,
    display_order INT DEFAULT 0,
    FOREIGN KEY (gallery_id) REFERENCES carousel_galleries(id) ON DELETE CASCADE
)";
mysqli_query($conn, $create_items);

// Create uploads directory if not exists
$upload_dir = 'uploads/carousel/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// ========== CREATE / UPDATE / DELETE HANDLERS ==========

// Handle Add Carousel
if (isset($_POST['add_carousel'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $subtitle = mysqli_real_escape_string($conn, $_POST['subtitle']);
    
    // Handle cover photo upload
    $cover_photo = '';
    if (isset($_FILES['cover_photo']) && $_FILES['cover_photo']['error'] == 0) {
        $ext = pathinfo($_FILES['cover_photo']['name'], PATHINFO_EXTENSION);
        $cover_photo = 'cover_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
        move_uploaded_file($_FILES['cover_photo']['tmp_name'], $upload_dir . $cover_photo);
    }
    
    $query = "INSERT INTO carousel_galleries (title, subtitle, cover_photo, created_at) VALUES ('$title', '$subtitle', '$cover_photo', NOW())";
    if (mysqli_query($conn, $query)) {
        $gallery_id = mysqli_insert_id($conn);
        
        // Handle multiple photos upload
        if (isset($_FILES['gallery_photos']) && !empty($_FILES['gallery_photos']['name'][0])) {
            $files = $_FILES['gallery_photos'];
            for ($i = 0; $i < count($files['name']); $i++) {
                if ($files['error'][$i] == 0) {
                    $ext = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
                    $photo_name = 'photo_' . time() . '_' . rand(1000, 9999) . '_' . $i . '.' . $ext;
                    move_uploaded_file($files['tmp_name'][$i], $upload_dir . $photo_name);
                    $caption = mysqli_real_escape_string($conn, $_POST['photo_captions'][$i] ?? '');
                    mysqli_query($conn, "INSERT INTO carousel_items (gallery_id, image_url, caption, display_order) VALUES ('$gallery_id', '$photo_name', '$caption', '$i')");
                }
            }
        }
        
        $_SESSION['success'] = "Carousel added successfully!";
        echo "<script>window.location.href='admin-slider.php';</script>";
        exit;
    }
}

// Handle Update Carousel
if (isset($_POST['update_carousel'])) {
    $id = mysqli_real_escape_string($conn, $_POST['carousel_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $subtitle = mysqli_real_escape_string($conn, $_POST['subtitle']);
    
    $update_query = "UPDATE carousel_galleries SET title='$title', subtitle='$subtitle' WHERE id='$id'";
    
    // Handle cover photo update
    if (isset($_FILES['cover_photo']) && $_FILES['cover_photo']['error'] == 0) {
        // Delete old cover photo
        $old_cover = mysqli_fetch_assoc(mysqli_query($conn, "SELECT cover_photo FROM carousel_galleries WHERE id='$id'"));
        if ($old_cover && $old_cover['cover_photo'] && file_exists($upload_dir . $old_cover['cover_photo'])) {
            unlink($upload_dir . $old_cover['cover_photo']);
        }
        
        $ext = pathinfo($_FILES['cover_photo']['name'], PATHINFO_EXTENSION);
        $cover_photo = 'cover_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
        move_uploaded_file($_FILES['cover_photo']['tmp_name'], $upload_dir . $cover_photo);
        $update_query = "UPDATE carousel_galleries SET title='$title', subtitle='$subtitle', cover_photo='$cover_photo' WHERE id='$id'";
    }
    
    if (mysqli_query($conn, $update_query)) {
        // Handle new gallery photos upload
        if (isset($_FILES['gallery_photos']) && !empty($_FILES['gallery_photos']['name'][0])) {
            $files = $_FILES['gallery_photos'];
            // Get current max display order
            $max_order_result = mysqli_query($conn, "SELECT MAX(display_order) as max FROM carousel_items WHERE gallery_id='$id'");
            $max_order = mysqli_fetch_assoc($max_order_result)['max'] ?? -1;
            
            for ($i = 0; $i < count($files['name']); $i++) {
                if ($files['error'][$i] == 0) {
                    $ext = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
                    $photo_name = 'photo_' . time() . '_' . rand(1000, 9999) . '_' . $i . '.' . $ext;
                    move_uploaded_file($files['tmp_name'][$i], $upload_dir . $photo_name);
                    $caption = mysqli_real_escape_string($conn, $_POST['photo_captions'][$i] ?? '');
                    $order = $max_order + $i + 1;
                    mysqli_query($conn, "INSERT INTO carousel_items (gallery_id, image_url, caption, display_order) VALUES ('$id', '$photo_name', '$caption', '$order')");
                }
            }
        }
        
        $_SESSION['success'] = "Carousel updated successfully!";
        echo "<script>window.location.href='admin-slider.php';</script>";
        exit;
    }
}

// Handle Delete Carousel
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Get cover photo to delete
    $cover_query = mysqli_query($conn, "SELECT cover_photo FROM carousel_galleries WHERE id='$id'");
    $cover = mysqli_fetch_assoc($cover_query);
    if ($cover && $cover['cover_photo'] && file_exists($upload_dir . $cover['cover_photo'])) {
        unlink($upload_dir . $cover['cover_photo']);
    }
    
    // Get and delete all gallery photos
    $items_query = mysqli_query($conn, "SELECT image_url FROM carousel_items WHERE gallery_id='$id'");
    while ($item = mysqli_fetch_assoc($items_query)) {
        if ($item['image_url'] && file_exists($upload_dir . $item['image_url'])) {
            unlink($upload_dir . $item['image_url']);
        }
    }
    
    mysqli_query($conn, "DELETE FROM carousel_items WHERE gallery_id='$id'");
    mysqli_query($conn, "DELETE FROM carousel_galleries WHERE id='$id'");
    
    $_SESSION['success'] = "Carousel deleted successfully!";
    echo "<script>window.location.href='admin-slider.php';</script>";
    exit;
}

// Handle Delete Single Gallery Photo (AJAX)
if (isset($_POST['delete_photo']) && isset($_POST['photo_id'])) {
    $photo_id = mysqli_real_escape_string($conn, $_POST['photo_id']);
    $photo_query = mysqli_query($conn, "SELECT image_url FROM carousel_items WHERE id='$photo_id'");
    $photo = mysqli_fetch_assoc($photo_query);
    if ($photo && $photo['image_url'] && file_exists($upload_dir . $photo['image_url'])) {
        unlink($upload_dir . $photo['image_url']);
    }
    mysqli_query($conn, "DELETE FROM carousel_items WHERE id='$photo_id'");
    echo json_encode(['success' => true]);
    exit;
}

// Get Edit Data if needed
$edit_data = null;
$edit_items = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $edit_id = mysqli_real_escape_string($conn, $_GET['id']);
    $edit_query = mysqli_query($conn, "SELECT * FROM carousel_galleries WHERE id='$edit_id'");
    $edit_data = mysqli_fetch_assoc($edit_query);
    
    if ($edit_data) {
        $edit_items = mysqli_query($conn, "SELECT * FROM carousel_items WHERE gallery_id='$edit_id' ORDER BY display_order");
    }
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
    <title>Admin - Manage Carousel Gallery</title>
    
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="vendor/datatables/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <style>
        .gallery-photo-item {
            position: relative;
            display: inline-block;
            margin: 8px;
        }
        .gallery-photo-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #ddd;
        }
        .delete-photo-btn {
            position: absolute;
            top: -8px;
            right: -8px;
            background: red;
            color: white;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            cursor: pointer;
            border: none;
        }
        .delete-photo-btn:hover {
            background: darkred;
        }
        .modal-gallery-img {
            max-width: 100%;
            max-height: 60vh;
            object-fit: contain;
        }
        .photo-upload-area {
            border: 2px dashed #ccc;
            padding: 20px;
            border-radius: 10px;
            background: #f8f9fc;
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
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Manage Carousel Gallery</h1>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#addCarouselModal">
                            <i class="fas fa-plus"></i> Add New Carousel
                        </button>
                    </div>
                    
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Carousel List Table -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">All Carousel Galleries</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Cover</th>
                                            <th>Title</th>
                                            <th>Subtitle</th>
                                            <th>Photos Count</th>
                                            <th>Created Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = mysqli_query($conn, "SELECT g.*, (SELECT COUNT(*) FROM carousel_items WHERE gallery_id=g.id) as photo_count FROM carousel_galleries g ORDER BY g.id DESC");
                                        while ($row = mysqli_fetch_assoc($query)):
                                        ?>
                                        <tr>
                                            <td><?php echo $row['id']; ?></td>
                                            <td>
                                                <?php if ($row['cover_photo'] && file_exists($upload_dir . $row['cover_photo'])): ?>
                                                    <img src="<?php echo $upload_dir . $row['cover_photo']; ?>" 
                                                         style="width: 60px; height: 45px; object-fit: cover; border-radius: 5px; cursor: pointer;"
                                                         onclick="viewModalGallery(<?php echo $row['id']; ?>)"
                                                         alt="cover">
                                                <?php else: ?>
                                                    <span class="text-muted">No image</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                                            <td><?php echo htmlspecialchars($row['subtitle']); ?></td>
                                            <td><?php echo $row['photo_count']; ?></td>
                                            <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-info" onclick="viewModalGallery(<?php echo $row['id']; ?>)">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                                <a href="admin-slider.php?action=edit&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <a href="admin-slider.php?action=delete&id=<?php echo $row['id']; ?>" 
                                                   class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('Are you sure you want to delete this carousel and all its photos?')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- ========== ADD CAROUSEL MODAL ========== -->
    <div class="modal fade" id="addCarouselModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Carousel Gallery</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Title *</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Subtitle</label>
                            <input type="text" name="subtitle" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Cover Photo *</label>
                            <input type="file" name="cover_photo" class="form-control-file" accept="image/*" required>
                        </div>
                        <div class="form-group photo-upload-area">
                            <label>Gallery Photos (Multiple)</label>
                            <input type="file" name="gallery_photos[]" class="form-control-file" accept="image/*" multiple>
                            <small class="text-muted">You can select multiple images at once</small>
                            <div id="photoCaptionsContainer"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="add_carousel" class="btn btn-primary">Save Gallery</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- ========== EDIT CAROUSEL MODAL ========== -->
    <?php if ($edit_data && $edit_data): ?>
    <div class="modal fade show" id="editCarouselModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" style="display: block;">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="carousel_id" value="<?php echo $edit_data['id']; ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Carousel: <?php echo htmlspecialchars($edit_data['title']); ?></h5>
                        <a href="admin-slider.php" class="btn btn-secondary btn-sm">Back to List</a>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Title *</label>
                                    <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($edit_data['title']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Subtitle</label>
                                    <input type="text" name="subtitle" class="form-control" value="<?php echo htmlspecialchars($edit_data['subtitle']); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Cover Photo</label>
                                    <div class="mb-2">
                                        <?php if ($edit_data['cover_photo'] && file_exists($upload_dir . $edit_data['cover_photo'])): ?>
                                            <img src="<?php echo $upload_dir . $edit_data['cover_photo']; ?>" style="width: 150px; height: 100px; object-fit: cover; border-radius: 8px;">
                                        <?php endif; ?>
                                    </div>
                                    <input type="file" name="cover_photo" class="form-control-file" accept="image/*">
                                    <small>Leave empty to keep current cover</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group photo-upload-area">
                                    <label>Add More Photos</label>
                                    <input type="file" name="gallery_photos[]" class="form-control-file" accept="image/*" multiple>
                                    <small>Add new images to this gallery</small>
                                    <div id="editPhotoCaptionsContainer"></div>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        <h6>Current Gallery Photos</h6>
                        <div id="existingPhotos" class="d-flex flex-wrap">
                            <?php if ($edit_items && mysqli_num_rows($edit_items) > 0): ?>
                                <?php while ($item = mysqli_fetch_assoc($edit_items)): ?>
                                <div class="gallery-photo-item" data-photo-id="<?php echo $item['id']; ?>">
                                    <img src="<?php echo $upload_dir . $item['image_url']; ?>" alt="gallery photo">
                                    <button type="button" class="delete-photo-btn" onclick="deleteGalleryPhoto(<?php echo $item['id']; ?>, this)">×</button>
                                    <small class="d-block text-center"><?php echo htmlspecialchars($item['caption'] ?? ''); ?></small>
                                </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p class="text-muted">No photos added yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="admin-slider.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" name="update_carousel" class="btn btn-primary">Update Gallery</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- ========== VIEW MODAL GALLERY (Popup Carousel) ========== -->
    <div class="modal fade" id="viewGalleryModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-0">
                    <div>
                        <h4 id="modalGalleryTitle" class="mb-0"></h4>
                        <p id="modalGallerySubtitle" class="mb-0 text-muted"></p>
                    </div>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body text-center">
                    <div id="modalCarousel" class="carousel slide" data-ride="carousel" data-interval="false">
                        <div class="carousel-inner" id="modalCarouselInner"></div>
                        <a class="carousel-control-prev" href="#modalCarousel" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#modalCarousel" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
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
            
            // Handle multiple photo captions input in add mode
            $('input[name="gallery_photos[]"]').on('change', function() {
                var container = $('#photoCaptionsContainer');
                container.empty();
                var files = this.files;
                for (var i = 0; i < files.length; i++) {
                    container.append(`
                        <div class="form-group mt-2">
                            <label>Caption for ${files[i].name}</label>
                            <input type="text" name="photo_captions[]" class="form-control" placeholder="Enter caption">
                        </div>
                    `);
                }
            });
            
            // Handle multiple photo captions input in edit mode
            $('input[name="gallery_photos[]"]').on('change', function() {
                var container = $('#editPhotoCaptionsContainer');
                container.empty();
                var files = this.files;
                for (var i = 0; i < files.length; i++) {
                    container.append(`
                        <div class="form-group mt-2">
                            <label>Caption for ${files[i].name}</label>
                            <input type="text" name="photo_captions[]" class="form-control" placeholder="Enter caption">
                        </div>
                    `);
                }
            });
        });
        
        // View Modal Gallery - opens carousel with all photos
        function viewModalGallery(galleryId) {
            $.ajax({
                url: 'ajax_get_carousel.php',
                type: 'POST',
                data: { gallery_id: galleryId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#modalGalleryTitle').text(response.title);
                        $('#modalGallerySubtitle').text(response.subtitle);
                        var carouselInner = $('#modalCarouselInner');
                        carouselInner.empty();
                        
                        // Add cover photo as first slide
                        if (response.cover_photo) {
                            carouselInner.append(`
                                <div class="carousel-item active">
                                    <img src="uploads/carousel/${response.cover_photo}" class="d-block m-auto modal-gallery-img" alt="Cover">
                                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
                                        <h5>Cover: ${escapeHtml(response.title)}</h5>
                                        <p>${escapeHtml(response.subtitle)}</p>
                                    </div>
                                </div>
                            `);
                        }
                        
                        // Add all gallery photos
                        if (response.photos && response.photos.length > 0) {
                            $.each(response.photos, function(index, photo) {
                                carouselInner.append(`
                                    <div class="carousel-item">
                                        <img src="uploads/carousel/${photo.image_url}" class="d-block m-auto modal-gallery-img" alt="Gallery image">
                                        <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
                                            <p>${escapeHtml(photo.caption || '')}</p>
                                        </div>
                                    </div>
                                `);
                            });
                        }
                        
                        $('#viewGalleryModal').modal('show');
                    } else {
                        Swal.fire('Error', 'Failed to load gallery', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Something went wrong', 'error');
                }
            });
        }
        
        // Escape HTML to prevent XSS
        function escapeHtml(text) {
            if (!text) return '';
            return text.replace(/[&<>]/g, function(m) {
                if (m === '&') return '&amp;';
                if (m === '<') return '&lt;';
                if (m === '>') return '&gt;';
                return m;
            });
        }
        
        // Delete single gallery photo (AJAX)
        function deleteGalleryPhoto(photoId, element) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This photo will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'admin-slider.php',
                        type: 'POST',
                        data: { delete_photo: true, photo_id: photoId },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                $(element).closest('.gallery-photo-item').remove();
                                Swal.fire('Deleted!', 'Photo has been deleted.', 'success');
                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            } else {
                                Swal.fire('Error', 'Failed to delete photo', 'error');
                            }
                        }
                    });
                }
            });
        }
    </script>
</body>
</html>