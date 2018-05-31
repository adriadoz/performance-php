<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

use Performance\ImageLoader\Infrastructure\Controller\UploadImageController;

require_once 'vendor/autoload.php';

$ds= DIRECTORY_SEPARATOR;

$storeFolder = 'uploads';


if (!empty($_FILES)) {
    $tempFile = $_FILES['file']['tmp_name'];

    $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;

    $targetFile =  $targetPath. $_FILES['file']['name'];

    move_uploaded_file($tempFile,$targetFile);

    $uploadController = new UploadImageController($_FILES);
    $uploadController->__invoke();
}