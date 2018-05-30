<?php
declare(strict_types=1);

namespace Performance\ImageLoader\Infrastructure\Controller;

use Performance\ImageLoader\Application\Service\GetAllImages;
use Performance\ImageLoader\Infrastructure\Repository\MySQLImageRepository;

final class AllImagesController
{
    private $getAllImagesService;
    private $mysqlImageRepo;

    public function __construct()
    {
        $this->mysqlImageRepo = new MySQLImageRepository();
        $this->getAllImagesService = new GetAllImages($this->mysqlImageRepo);
    }

    public function __invoke()
    {
        return $this->getAllImagesService->__invoke();
    }
}