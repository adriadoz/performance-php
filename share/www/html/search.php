<?php

require_once 'vendor/autoload.php';

use Performance\ImageLoader\Infrastructure\Controller\AllImagesController;
use Performance\ImageLoader\Infrastructure\Repository\ElasticImageRepository;

if (isset($_POST['search']) && $_POST['searchInput'] !== '') {
    $searchInput = $_POST['searchInput'];
    $elasticImageRepo = new ElasticImageRepository();
    $images = $elasticImageRepo->searchImages($searchInput);
} else {
    $allImagesController = new AllImagesController();
    $images = $allImagesController->__invoke();
}
?>
<html>

<head>
    <link href="css/base.css" type="text/css" rel="stylesheet" />
</head>

<body>
<h1>Search page</h1>
<form method="POST" action="">
    <input type="text" name="searchInput" id="searchInput" placeholder="Write a tag to search">
    <button type="submit" name="search" value="search">Search</button>
</form>
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