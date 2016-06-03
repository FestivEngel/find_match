<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

header('Content-Type: application/json');

require_once dirname(__FILE__) . '/../lib/autoload.php';
$result['status'] = false;

/*
 * Configuration
 * ATTN: chmod a+rwx for photos folder
 */
$img_info['image_max_size'] = Configuration::IMAGE_MAX_SIZE; // Maximum image size (height and width)
$img_info['thumbnail_size'] = Configuration::THUMB_MAX_SIZE; // Thumbnails will be cropped and resized to 150x150 pixels
$img_info['thumbnail_prefix'] = Configuration::THUMB_PREFIX; // thumb Prefix
$img_info['destination_folder'] = $_SERVER['DOCUMENT_ROOT'] . '/photos/'; // Image directory ends with / (slash)
$result['destination_folder'] = $img_info['destination_folder'];
$img_info['thumbnail_destination_folder'] = $_SERVER['DOCUMENT_ROOT'] . '/photos/'; // Thumbnail directory ends with / (slash)
$result['thumbnail_destination_folder'] = $img_info['thumbnail_destination_folder'];
$img_info['quality'] = 90; // jpeg quality
$img_info['random_file_name'] = true; // randomize each file name

// Accept HTTP POST comming as Ajax request
if (isset($_SESSION['userId']) &&
    isset($_POST) &&
    isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
) {
    // Check empty file field
    if (!isset($_FILES['image_file']) || !is_uploaded_file($_FILES['image_file']['tmp_name'])) {
        errorResultAndExit('Image file is missing!');
    }

    $img_info['image_data'] = $_FILES['image_file']; // file input
    $img_info['unique_id'] = uniqid(); // unique id for random filename

    // Check destination dir
    if (!file_exists($img_info['destination_folder'])) {
        errorResultAndExit('Error: ' . $img_info['destination_folder'] . ' folder doesn\'t exist!');
    }

    // Check thumbnail destination dir
    if (!file_exists($img_info['thumbnail_destination_folder'])) {
        errorResultAndExit('Error: ' . $img_info['thumbnail_destination_folder'] . ' folder doesn\'t exist!');
    }

    // Get image size info from a valid image file
    $im_info = getimagesize($img_info['image_data']['tmp_name']);
    if ($im_info) {
        $img_info['image_width'] = $im_info[0]; // image width
        $img_info['image_height'] = $im_info[1]; // image height
        $img_info['image_type'] = $im_info['mime']; // image type
    } else {
        errorResultAndExit('Error: Make sure image file is valid!');
    }

    // Check size
    //if ($img_info['image_width'] < Configuration::)

    $img_info['img_res'] = getImageResource($img_info);

    // Resize image file
    if ($img_info['img_res']) {
        $resizedImage = resizeImage($img_info); // call image resize function
        $generatedThumb = generateThumb($img_info); // call thumbnail generator function

        if ($resizedImage && $generatedThumb) {
            $result['status'] = true;
            $result['imageFileName'] = $resizedImage;
            $result['thumbFileName'] = $generatedThumb;
            Member::updatePhoto($_SESSION['userId'], $resizedImage);
        }
    } else {
        errorResultAndExit('Error creating image resource!');
    }
}

/**
 * Proportionally resize images
 *
 * @param $img_info
 * @return bool|string
 */
function resizeImage($img_info)
{
    if ($img_info['image_width'] <= 0 || $img_info['image_height'] <= 0) {
        return false; // return false if nothing to resize
    }

    // Path for saving resized images
    if ($img_info['random_file_name']) {
        $new_image_name = $img_info['unique_id'] . getExtention($img_info);
    } else {
        $new_image_name = $img_info['image_data']['name'];
    }

    $img_info['save_dir'] = $img_info['destination_folder'] . $new_image_name;

    // Do not resize if image is smaller than max size
    if ($img_info['image_width'] <= $img_info['image_max_size'] && $img_info['image_height'] <= $img_info['image_max_size']) {
        copy($img_info['image_data']['tmp_name'], $img_info['save_dir']);
        return $new_image_name;
    }

    // Construct a proportional size of new image
    $image_scale = min($img_info['image_max_size'] / $img_info['image_width'], $img_info['image_max_size'] / $img_info['image_height']);
    $new_width = ceil($image_scale * $img_info['image_width']);
    $new_height = ceil($image_scale * $img_info['image_height']);

    // Create a new true color image
    $img_info['canvas'] = imagecreatetruecolor($new_width, $new_height);

    // Copy and resize part of an image with resampling
    if (imagecopyresampled($img_info['canvas'],
        $img_info['img_res'],
        0,
        0,
        0,
        0,
        $new_width,
        $new_height,
        $img_info['image_width'],
        $img_info['image_height'])) {
        if (saveImage($img_info)) {
            return $new_image_name;
        }
    }

    return false;
}

/**
 * Function to create thumbnails! images will be cropped and exact size
 *
 * @param $img_info
 * @return bool|string
 */
function generateThumb($img_info)
{
    if ($img_info['image_width'] <= 0 || $img_info['image_height'] <= 0) {
        return false; // return false if nothing to resize
    }

    // Path for saving resized images
    if ($img_info['random_file_name']) {
        $new_image_name = $img_info['thumbnail_prefix'] . $img_info['unique_id'] . getExtention($img_info);
    } else {
        $new_image_name = $img_info['thumbnail_prefix'] . $img_info['image_data']['name'];
    }

    $img_info['save_dir'] = $img_info['thumbnail_destination_folder'] . $new_image_name;

    // Offsets
    if ($img_info['image_width'] > $img_info['image_height']) {
        $y_offset = 0;
        $x_offset = ($img_info['image_width'] - $img_info['image_height']) / 2;
        $s_size = $img_info['image_width'] - ($x_offset * 2);
    } else {
        $x_offset = 0;
        $y_offset = ($img_info['image_height'] - $img_info['image_width']) / 2;
        $s_size = $img_info['image_height'] - ($y_offset * 2);
    }

    // Create a new true color image
    $img_info['canvas'] = imagecreatetruecolor($img_info['thumbnail_size'], $img_info['thumbnail_size']);

    // Copy and resize part of an image with resampling
    if (imagecopyresampled($img_info['canvas'],
        $img_info['img_res'],
        0,
        0,
        $x_offset,
        $y_offset,
        $img_info['thumbnail_size'],
        $img_info['thumbnail_size'],
        $s_size, $s_size)) {
        if (saveImage($img_info)) {
            return $new_image_name;
        }
    }

    return false;
}

/**
 * Saves images to destination directory
 *
 * @param $img_info
 * @return bool
 */
function saveImage($img_info)
{
    switch (strtolower($img_info['image_type'])) {
        case 'image/png':
            imagepng($img_info['canvas'], $img_info['save_dir']); //save png file
            break;

        case 'image/gif':
            imagegif($img_info['canvas'], $img_info['save_dir']); //save gif file
            break;

        case 'image/jpeg':
        case 'image/pjpeg':
            imagejpeg($img_info['canvas'], $img_info['save_dir'], $img_info['quality']);  //save jpeg file
            break;

        default:
            return false;
    }

    imagedestroy($img_info['canvas']); //free-up memory
    return true;
}

/**
 * Create a new image resource from file
 *
 * @param $img_info
 * @return bool|resource
 */
function getImageResource($img_info)
{
    switch ($img_info['image_type']) {
        case 'image/png':
            return imagecreatefrompng($img_info['image_data']['tmp_name']);
        case 'image/gif':
            return imagecreatefromgif($img_info['image_data']['tmp_name']);
        case 'image/jpeg':
        case 'image/pjpeg':
            return imagecreatefromjpeg($img_info['image_data']['tmp_name']);
        default:
            return false;
    }
}

/**
 * Returns file extension from given image type
 *
 * @param $img_info
 * @return null|string
 */
function getExtention($img_info)
{
    switch ($img_info['image_type']) {
        case 'image/gif':
            return '.gif';
        case 'image/jpeg':
        case 'image/pjpeg':
            return '.jpg';
        case 'image/png':
            return '.png';
    }

    return null;
}

/**
 * @param string $errorDescription
 */
function errorResultAndExit($errorDescription)
{
    $result['error'] = $errorDescription;
    echo json_encode($result);
    exit;
}

echo json_encode($result);