<?php
declare(strict_types=1);

namespace Performance\ImageLoader\Application\Service;

use Performance\ImageLoader\Domain\Model\Image;
use Performance\ImageLoader\Infrastructure\Queue\AMQPController;

final class SendAMQP
{
    private $amqpController;
    public function __construct(AMQPController $amqpController)
    {
        $this->amqpController = $amqpController;
    }

    public function sendToQueue(Image $image){
        $this->amqpController->sendMessage($image->getFileName());
    }
}