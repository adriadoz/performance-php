<?php

declare(strict_types=1);

namespace Performance\ImageLoader\Infrastructure\Controller;


ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

use Performance\ImageLoader\Application\Service\AddImage;
use Performance\ImageLoader\Application\Service\SendAMQP;
use Performance\ImageLoader\Domain\Model\Image;
use Performance\ImageLoader\Infrastructure\Queue\AMQPController;
use Performance\ImageLoader\Infrastructure\Repository\ElasticImageRepository;
use Performance\ImageLoader\Infrastructure\Repository\MySQLImageRepository;
use Performance\ImageLoader\Infrastructure\Repository\RedisImageRepository;

final class UploadImageController
{
    private $info;
    private $addImageService;
    private $mysqlImageRepo;
    private $sendAMQP;
    private $amqpController;
    private $addImageServiceElastic;
    private $addImageServiceRedis;
    private $redisImageRepo;
    private $elasticImageRepo;
    private $uploadDirectory  = "uploads/";

    public function __construct(array $info)
    {
        $this->info = $info;
        $this->mysqlImageRepo = new MySQLImageRepository();
        $this->elasticImageRepo = new ElasticImageRepository();
        $this->redisImageRepo = new RedisImageRepository();
        $this->addImageService = new AddImage($this->mysqlImageRepo);
        $this->addImageServiceElastic = new AddImage($this->elasticImageRepo);
        $this->addImageServiceRedis = new AddImage($this->redisImageRepo);
        $this->amqpController = new AMQPController();
        $this->sendAMQP = new SendAMQP($this->amqpController);
    }

    public function __invoke()
    {
        $newId = uniqid('', true);
        $path_parts = pathinfo($_FILES['file']['name']);
        $name = $path_parts['filename'];
        $newImage = new Image($newId, $name, $_FILES['file']['name'], '', []);
        $this->addImageService->__invoke($newImage);
        $this->addImageServiceElastic->__invoke($newImage);
        $this->addImageServiceRedis->__invoke($newImage);
        $message = [
            'name' => $newImage->getName(),
            'upload_directory' => $this->uploadDirectory,
            'file_name' => $newImage->getFileName()
        ];
        $this->sendAMQP->sendToQueue($message);
    }
}