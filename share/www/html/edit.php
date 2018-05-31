<?php

require_once 'vendor/autoload.php';

use Performance\ImageLoader\Application\Service\GetImage;
use Performance\ImageLoader\Application\Service\EditImage;
use Performance\ImageLoader\Infrastructure\Repository\MySQLImageRepository;
use Performance\ImageLoader\Domain\Model\Image;
use Performance\ImageLoader\Infrastructure\Repository\ElasticImageRepository;
use Performance\ImageLoader\Infrastructure\Repository\RedisImageRepository;

$mysqlRepo = new MySQLImageRepository();
$elasticRepo = new ElasticImageRepository();
$redisRepo = new RedisImageRepository();

if (isset($_POST['description']) && ($_POST['description'] !== '' || $_POST['tags'] !== '')) {

    $imageId = $_POST['image_id'];
    $description = $_POST['description'];
    $tags = $_POST['tags'];
    $name = $_POST['name'];
    $fileName = $_POST['file_name'];
    $tagsArray = explode (', ',$tags);
    $editedImage = new Image($imageId, $name, $fileName, $description, $tagsArray);
    $editImageService = new EditImage($mysqlRepo);
    $editImageServiceElastic = new EditImage($elasticRepo);
    $editImageServiceRedis = new EditImage($redisRepo);
    $editImageService->__invoke($editedImage);
    $editImageServiceElastic->__invoke($editedImage);
    $editImageServiceRedis->__invoke($editedImage);
}else{
    $imageId= $_GET['id'];
}
$getImageService = new GetImage($mysqlRepo);
$image = $getImageService->__invoke($imageId);

?>
<html>

<head>
    <link href="css/base.css" type="text/css" rel="stylesheet" />
</head>
<h1>Editing '<?=$image['name']?>' image</h1>
<img src="uploads/<?=$image['file_name']?>"/>
<form method="POST" action="">
    <label for="description">Description</label>
    <input type="text" id="description" name="description" value="<?=$image['description']?>" placeholder="add a description">
    <label for="tags">Tags</label>
    <input type="text" name="tags" id="tags" value="<?=$image['tags']?>" placeholder="landscape, person, dog ...">
    <input type="hidden" name="image_id" value="<?=$imageId?>" />
    <input type="hidden" name="name" value="<?=$image['name']?>" />
    <input type="hidden" name="file_name" value="<?=$image['file_name']?>" />
    <button type="submit" value="update" >Save edition</button>
</form>

<a href="search.php">Back to Search images</a>

</body>
</html>