<?php
session_start();
include("inc/config.php");
if(isset($_POST["gallery_id"])){
    $id = mysqli_real_escape_string($conn, $_POST["gallery_id"]);
    $gallery = mysqli_query($conn, "SELECT * FROM carousel_galleries WHERE id='$id'");
    $g = mysqli_fetch_assoc($gallery);
    $photos = mysqli_query($conn, "SELECT * FROM carousel_items WHERE gallery_id='$id' ORDER BY display_order");
    $photo_arr = [];
    while($p = mysqli_fetch_assoc($photos)){
        $photo_arr[] = $p;
    }
    echo json_encode(["success"=>true, "title"=>$g["title"], "subtitle"=>$g["subtitle"], "cover_photo"=>$g["cover_photo"], "photos"=>$photo_arr]);
    exit;
}
echo json_encode(["success"=>false]);
?>