<?php
declare(strict_types=1);

namespace Performance\ImageLoader\Infrastructure\Controller;

use Performance\ImageLoader\Application\Service\GetAllImages;
use Performance\ImageLoader\Infrastructure\Repository\MySQLImageRepository;
use Performance\ImageLoader\Infrastructure\Repository\RedisImageRepository;

final class AllImagesController
{
    private $getAllImagesService;
    private $mysqlImageRepo;
    private $redisImageRepo;
    private $getAllImagesServiceRedis;

    public function __construct()
    {
        $this->redisImageRepo = new RedisImageRepository();
        $this->mysqlImageRepo = new MySQLImageRepository();
        $this->getAllImagesService = new GetAllImages($this->mysqlImageRepo);
        $this->getAllImagesServiceRedis = new GetAllImages($this->redisImageRepo);
    }

    public function __invoke()
    {
        $images = $this->getAllImagesServiceRedis->__invoke();

        if(sizeof($images) != 0) {
            $images = $this->getAllImagesService->__invoke();
            $this->redisImageRepo->addAllImages($images);
        }

        return $images;
    }
}