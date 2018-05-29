<?php
declare(strict_types=1);

namespace Performance\ImageLoader\Infrastructure\Controller;

use Performance\ImageLoader\Application\Service\AddImage;
use Performance\ImageLoader\Domain\Model\Image;
use Performance\ImageLoader\Infrastructure\Repository\MySQLImageRepository;

final class UploadImageController
{
    private $info;
    private $addImageService;
    private $mysqlImageRepo;

    public function __construct(array $info)
    {
        $this->info = $info;
        $this->mysqlImageRepo = new MySQLImageRepository();
        $this->addImageService = new AddImage($this->mysqlImageRepo);
    }

    public function __invoke()
    {
        $newId = uniqid('', true);
        $path_parts = pathinfo($_FILES['file']['name']);
        $name = $path_parts['filename'];
        echo($name);
        $newImage = new Image($newId, $name, $_FILES['file']['name'], '', []);
        $this->addImageService->__invoke($newImage);
    }
}