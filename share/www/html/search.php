<?php

use Performance\ImageLoader\Infrastructure\Controller\AllImagesController;

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';

$allImagesController = new AllImagesController();
$images = $allImagesController->__invoke();
?>
<html>

<head>
    <link href="css/base.css" type="text/css" rel="stylesheet" />
</head>

<body>
<h1>Search page</h1>
<main>
    <?php foreach($images as $image): ?>
        <a href="edit.php?id=<?=$image['image_id']?>">
            <image src="uploads/<?=$image['file_name']?>" />
            <span><?=$image['name']?></span>
        </a>
    <?php endforeach; ?>
</main>

<a href="index.php">Upload images</a>
</body>

</html>