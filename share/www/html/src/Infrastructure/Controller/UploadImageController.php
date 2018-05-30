<?php
declare(strict_types=1);

namespace Performance\ImageLoader\Infrastructure\Controller;

use Performance\ImageLoader\Application\Service\AddImage;
use Performance\ImageLoader\Application\Service\SendAMQP;
use Performance\ImageLoader\Domain\Model\Image;
use Performance\ImageLoader\Infrastructure\Queue\AMQPController;
use Performance\ImageLoader\Infrastructure\Repository\MySQLImageRepository;

final class UploadImageController
{
    private $info;
    private $addImageService;
    private $mysqlImageRepo;
    private $sendAMQP;
    private $amqpController;
    private $uploadDirectory  = "uploads/";

    public function __construct(array $info)
    {
        $this->info = $info;
        $this->mysqlImageRepo = new MySQLImageRepository();
        $this->addImageService = new AddImage($this->mysqlImageRepo);
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

        $message = [
            'name' => $newImage->getName(),
            'upload_directory' => $this->uploadDirectory,
            'file_name' => $newImage->getFileName()
        ];
        $this->sendAMQP->sendToQueue($message);
    }
}