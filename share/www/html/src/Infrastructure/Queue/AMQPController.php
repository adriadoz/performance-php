<?php
declare(strict_types=1);

namespace Performance\ImageLoader\Infrastructure\Queue;

use \PhpAmqpLib\Connection\AMQPStreamConnection;
use \PhpAmqpLib\Message\AMQPMessage;

final class AMQPController
{
    private $connection;
    private $channel;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            'localhost',
            5672,
            'guest',
            'guest'
        );
        $this->channel = $this->connection->channel();
        $this->channel->basic_qos(null, 1, null);
        $this->channel->queue_declare('resize.xl', false, true, false, false);
    }

    public function sendMessage(string $messageContent)
    {
        $message = new AMQPMessage(
            json_encode($messageContent),
            ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
        );
        var_dump($message);

        $this->channel->basic_publish($message, '', 'resize.xl');
        $this->channel->close();
    }

    public function closeConnection()
    {
        $this->connection->close();
    }
}