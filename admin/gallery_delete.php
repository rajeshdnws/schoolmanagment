<?php
include('session.php');
requireAccess('gallery', 'delete');

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Get image path to delete file
    $image = getRow("SELECT image_path FROM gallery WHERE id = $id");
    if ($image) {
        $filePath = '../' . $image['image_path'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
    
    $query = "DELETE FROM gallery WHERE id = $id";
    executeQuery($query);
}

header('Location: gallery.php');
exit;
?>
