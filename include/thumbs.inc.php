<?php
declare(strict_types=1);

/**
 * Creates a thumbnail from an image file
 *
 * @param string $img_src      Filename of the source image
 * @param int    $img_width    Maximum width of the thumbnail
 * @param int    $img_height   Maximum height of the thumbnail
 * @param string $folder_src   Directory of the source images
 * @param string $des_src      Directory for the thumbnails
 * 
 * @return bool True if thumbnail was created successfully, false otherwise
 */
function mkthumb(
    string $img_src,
    int $img_width = 100,
    int $img_height = 100,
    string $folder_src = "include/files",
    string $des_src = "include/files"
): bool {
    $src_path = $folder_src . "/" . $img_src;
    $dest_path = $des_src . "/" . $img_src . "_thumb";

    // Get size and type of the image
    $image_info = getimagesize($src_path);
    if ($image_info === false) {
        return false;
    }

    [$src_width, $src_height, $src_type] = $image_info;

    // Calculate new size
    $ratio = min($img_width / $src_width, $img_height / $src_height);
    $new_width = (int)round($src_width * $ratio);
    $new_height = (int)round($src_height * $ratio);

    // Create new image based on source type
    $create_funcs = [
        IMAGETYPE_GIF => 'imagecreatefromgif',
        IMAGETYPE_JPEG => 'imagecreatefromjpeg',
        IMAGETYPE_PNG => 'imagecreatefrompng'
    ];

    $output_funcs = [
        IMAGETYPE_GIF => 'imagegif',
        IMAGETYPE_JPEG => 'imagejpeg',
        IMAGETYPE_PNG => 'imagepng'
    ];

    if (!isset($create_funcs[$src_type]) || !isset($output_funcs[$src_type])) {
        return false;
    }

    $src_image = $create_funcs[$src_type]($src_path);
    $new_image = imagecreatetruecolor($new_width, $new_height);

    if ($src_image === false || $new_image === false) {
        return false;
    }

    // Preserve transparency for PNG images
    if ($src_type === IMAGETYPE_PNG) {
        imagealphablending($new_image, false);
        imagesavealpha($new_image, true);
    }

    if (!imagecopyresampled($new_image, $src_image, 0, 0, 0, 0, $new_width, $new_height, $src_width, $src_height)) {
        return false;
    }

    $result = $output_funcs[$src_type]($new_image, $dest_path);

    imagedestroy($src_image);
    imagedestroy($new_image);

    return $result;
}
?>