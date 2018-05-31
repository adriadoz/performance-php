<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'vendor/autoload.php';

use Performance\ImageLoader\Domain\Model\Image;
use Performance\ImageLoader\Infrastructure\Controller\ResizeL;
use Performance\ImageLoader\Infrastructure\Controller\ResizeM;
use Performance\ImageLoader\Infrastructure\Controller\ResizeS;
use Performance\ImageLoader\Infrastructure\Controller\ResizeXS;
use Performance\ImageLoader\Infrastructure\Repository\ElasticImageRepository;
use Performance\ImageLoader\Infrastructure\Repository\MySQLImageRepository;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Performance\ImageLoader\Infrastructure\Controller\ResizeXL;
use Performance\ImageLoader\Application\Service\AddImage;
use Performance\ImageLoader\Infrastructure\Controller\GrayScale;
use Performance\ImageLoader\Infrastructure\Controller\Blur;

$connection = new AMQPStreamConnection(
    'localhost',
    5672,
    'guest',
    'guest'
);

$channel = $connection->channel();

$channel->queue_declare('resize.xl', false, true, false, false);
$channel->queue_declare('resize.l', false, true, false, false);
$channel->queue_declare('resize.m', false, true, false, false);
$channel->queue_declare('resize.s', false, true, false, false);
$channel->queue_declare('resize.xs', false, true, false, false);
$channel->queue_declare('black.white', false, true, false, false);
$channel->queue_declare('blur', false, true, false, false);

$callbackResizeXL = function($msg){
    $elasticImageRepo = new ElasticImageRepository();
    $addImageServiceElastic = new AddImage($elasticImageRepo);
    $MysqlRepo = new MySQLImageRepository();
    $imageService = new AddImage($MysqlRepo);
    $data = json_decode($msg->body, true);

    $name = $data['name'];
    $fileName = $data['file_name'];

    $resizeXL = new ResizeXL($fileName, $data['upload_directory'], $MysqlRepo);
    $resizeXL->__invoke();
    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);

    $newId = uniqid('', true);
    $newImage = new Image($newId, 'XL_'.$name, 'XL_'.$fileName, '', ['XL']);
    $imageService->__invoke($newImage);
    $addImageServiceElastic->__invoke($newImage);
};

$callbackResizeL = function($msg){

    $elasticImageRepo = new ElasticImageRepository();
    $addImageServiceElastic = new AddImage($elasticImageRepo);
    $MysqlRepo = new MySQLImageRepository();
    $imageService = new AddImage($MysqlRepo);
    $data = json_decode($msg->body, true);

    $name = $data['name'];
    $fileName = $data['file_name'];

    $resizeL = new ResizeL($fileName, $data['upload_directory'], $MysqlRepo);
    $resizeL->__invoke();
    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);

    $newId = uniqid('', true);
    $newImage = new Image($newId, 'L_'.$name, 'L_'.$fileName, '', ['L']);
    $imageService->__invoke($newImage);
    $addImageServiceElastic->__invoke($newImage);
};

$callbackResizeM = function($msg){

    $elasticImageRepo = new ElasticImageRepository();
    $addImageServiceElastic = new AddImage($elasticImageRepo);
    $MysqlRepo = new MySQLImageRepository();
    $imageService = new AddImage($MysqlRepo);
    $data = json_decode($msg->body, true);

    $name = $data['name'];
    $fileName = $data['file_name'];

    $resizeM = new ResizeM($fileName, $data['upload_directory'], $MysqlRepo);
    $resizeM->__invoke();
    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);

    $newId = uniqid('', true);
    $newImage = new Image($newId, 'M_'.$name, 'M_'.$fileName, '', ['M']);
    $imageService->__invoke($newImage);
    $addImageServiceElastic->__invoke($newImage);
};
$callbackResizeS = function($msg){

    $elasticImageRepo = new ElasticImageRepository();
    $addImageServiceElastic = new AddImage($elasticImageRepo);
    $MysqlRepo = new MySQLImageRepository();
    $imageService = new AddImage($MysqlRepo);
    $data = json_decode($msg->body, true);

    $name = $data['name'];
    $fileName = $data['file_name'];

    $resizeS = new ResizeS($fileName, $data['upload_directory'], $MysqlRepo);
    $resizeS->__invoke();
    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);

    $newId = uniqid('', true);
    $newImage = new Image($newId, 'S_'.$name, 'S_'.$fileName, '', ['S']);
    $imageService->__invoke($newImage);
    $addImageServiceElastic->__invoke($newImage);
};
$callbackResizeXS = function($msg){

    $elasticImageRepo = new ElasticImageRepository();
    $addImageServiceElastic = new AddImage($elasticImageRepo);
    $MysqlRepo = new MySQLImageRepository();
    $imageService = new AddImage($MysqlRepo);
    $data = json_decode($msg->body, true);

    $name = $data['name'];
    $fileName = $data['file_name'];

    $resizeXS = new ResizeXS($fileName, $data['upload_directory'], $MysqlRepo);
    $resizeXS->__invoke();
    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);

    $newId = uniqid('', true);
    $newImage = new Image($newId, 'XS_'.$name, 'XS_'.$fileName, '', ['XS']);
    $imageService->__invoke($newImage);
    $addImageServiceElastic->__invoke($newImage);
};

$callbackGrayScale = function($msg){

    $elasticImageRepo = new ElasticImageRepository();
    $addImageServiceElastic = new AddImage($elasticImageRepo);
    $MysqlRepo = new MySQLImageRepository();
    $imageService = new AddImage($MysqlRepo);
    $data = json_decode($msg->body, true);

    $name = $data['name'];
    $fileName = $data['file_name'];

    $resizeXS = new GrayScale($fileName, $data['upload_directory'], $MysqlRepo);
    $resizeXS->__invoke();
    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);

    $newId = uniqid('', true);
    $newImage = new Image($newId, 'GRAYSCALE_'.$name, 'GRAYSCALE_'.$fileName, '', ['GRAYSCALE']);
    $imageService->__invoke($newImage);
    $addImageServiceElastic->__invoke($newImage);
};

$callbackBlur = function($msg){

    $elasticImageRepo = new ElasticImageRepository();
    $addImageServiceElastic = new AddImage($elasticImageRepo);
    $MysqlRepo = new MySQLImageRepository();
    $imageService = new AddImage($MysqlRepo);
    $data = json_decode($msg->body, true);

    $name = $data['name'];
    $fileName = $data['file_name'];

    $resizeXS = new Blur($fileName, $data['upload_directory'], $MysqlRepo);
    $resizeXS->__invoke();
    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);

    $newId = uniqid('', true);
    $newImage = new Image($newId, 'BLUR_'.$name, 'BLUR_'.$fileName, '', ['BLUR']);
    $imageService->__invoke($newImage);
    $addImageServiceElastic->__invoke($newImage);
};


$channel->basic_qos(null, 1, null);
$channel->basic_consume('resize.xl', '', false, false, false, false, $callbackResizeXL);
$channel->basic_consume('resize.l', '', false, false, false, false, $callbackResizeL);
$channel->basic_consume('resize.m', '', false, false, false, false, $callbackResizeM);
$channel->basic_consume('resize.s', '', false, false, false, false, $callbackResizeS);
$channel->basic_consume('resize.xs', '', false, false, false, false, $callbackResizeXS);
$channel->basic_consume('black.white', '', false, false, false, false, $callbackGrayScale);
$channel->basic_consume('blur', '', false, false, false, false, $callbackBlur);

while(count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();