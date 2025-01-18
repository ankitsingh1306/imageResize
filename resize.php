<?php

// Check if a file was uploaded
if (isset($_FILES['image']) && isset($_POST['width']) && isset($_POST['height'])) {

    // Get the uploaded image file and the desired width and height
    $image = $_FILES['image'];
    $width = $_POST['width'];
    $height = $_POST['height'];

    // Check if the uploaded file is an image
    if (exif_imagetype($image['tmp_name'])) {
        
        // Get the image details
        list($original_width, $original_height) = getimagesize($image['tmp_name']);
        $image_type = exif_imagetype($image['tmp_name']);
        
        // Create a new image resource from the uploaded file
        switch ($image_type) {
            case IMAGETYPE_JPEG:
                $src_image = imagecreatefromjpeg($image['tmp_name']);
                break;
            case IMAGETYPE_PNG:
                $src_image = imagecreatefrompng($image['tmp_name']);
                break;
            case IMAGETYPE_GIF:
                $src_image = imagecreatefromgif($image['tmp_name']);
                break;
            default:
                die('Unsupported image type.');
        }

        // Create a blank image resource with the desired size
        $dst_image = imagecreatetruecolor($width, $height);

        // Resize the image
        imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $width, $height, $original_width, $original_height);

        // Set the content type for the image
        header('Content-Type: image/jpeg');

        // Output the resized image as JPEG
        imagejpeg($dst_image);

        // Free up memory
        imagedestroy($src_image);
        imagedestroy($dst_image);
    } else {
        die('Uploaded file is not a valid image.');
    }
} else {
    die('No image uploaded or invalid parameters.');
}
?>
