<?php
// Enable error reporting for debugging (remove after testing)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database configuration
include('config/config.php');

// Set JSON header
header('Content-Type: application/json');

// Check if gallery_id is provided
if (!isset($_POST['gallery_id']) && !isset($_GET['gallery_id'])) {
    echo json_encode([
        'success' => false, 
        'message' => 'No gallery ID provided'
    ]);
    exit;
}

// Get gallery ID from POST or GET
$gallery_id = isset($_POST['gallery_id']) ? $_POST['gallery_id'] : $_GET['gallery_id'];
$gallery_id = mysqli_real_escape_string($conn, $gallery_id);

// Fetch gallery details
$gallery_query = "SELECT * FROM carousel_galleries WHERE id = '$gallery_id'";
$gallery_result = mysqli_query($conn, $gallery_query);

if (!$gallery_result) {
    echo json_encode([
        'success' => false, 
        'message' => 'Database error: ' . mysqli_error($conn)
    ]);
    exit;
}

if (mysqli_num_rows($gallery_result) == 0) {
    echo json_encode([
        'success' => false, 
        'message' => 'Gallery not found'
    ]);
    exit;
}

$gallery = mysqli_fetch_assoc($gallery_result);

// Fetch all photos for this gallery
$photos_query = "SELECT * FROM carousel_items WHERE gallery_id = '$gallery_id' ORDER BY display_order ASC";
$photos_result = mysqli_query($conn, $photos_query);

$photos = [];
if ($photos_result && mysqli_num_rows($photos_result) > 0) {
    while ($photo = mysqli_fetch_assoc($photos_result)) {
        $photos[] = [
            'id' => $photo['id'],
            'image_url' => $photo['image_url'],
            'caption' => $photo['caption'],
            'display_order' => $photo['display_order']
        ];
    }
}

// Return JSON response
echo json_encode([
    'success' => true,
    'id' => $gallery['id'],
    'title' => $gallery['title'],
    'subtitle' => $gallery['subtitle'],
    'cover_photo' => $gallery['cover_photo'],
    'photos' => $photos,
    'total_photos' => count($photos)
]);

exit;
?>