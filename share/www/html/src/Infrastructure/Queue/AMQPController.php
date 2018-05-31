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
        $this->channel->queue_declare('resize.l', false, true, false, false);
        $this->channel->queue_declare('resize.m', false, true, false, false);
        $this->channel->queue_declare('resize.s', false, true, false, false);
        $this->channel->queue_declare('resize.xs', false, true, false, false);
        $this->channel->queue_declare('black.white', false, true, false, false);
        $this->channel->queue_declare('blur', false, true, false, false);
    }

    public function sendMessage(array $messageContent)
    {
        $message = new AMQPMessage(
            json_encode($messageContent),
            ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
        );
        $this->channel->basic_publish($message, '', 'resize.xl');
        $this->channel->basic_publish($message, '', 'resize.l');
        $this->channel->basic_publish($message, '', 'resize.m');
        $this->channel->basic_publish($message, '', 'resize.s');
        $this->channel->basic_publish($message, '', 'resize.xs');
        $this->channel->basic_publish($message, '', 'black.white');
        $this->channel->basic_publish($message, '', 'blur');
        $this->channel->close();
    }

    public function closeConnection()
    {
        $this->connection->close();
    }
}