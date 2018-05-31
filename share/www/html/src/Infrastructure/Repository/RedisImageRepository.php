<?php
declare(strict_types=1);

namespace Performance\ImageLoader\Infrastructure\Repository;

use Performance\ImageLoader\Domain\Model\Image;
use Performance\ImageLoader\Domain\Repository\ImageRepository;
use Predis\Client;

final class RedisImageRepository implements ImageRepository
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getImageById($id)
    {
    }

    public function addImage(Image $image)
    {
        $id  = $image->getId();
        $name = $image->getName();
        $fileName = $image->getFileName();
        $description = $image->getDescription();
        $tags = implode(', ',$image->getTags());

        $this->client->hset($id, "name", $name);
        $this->client->hset($id, "file_name", $fileName);
        $this->client->hset($id, "description", $description);
        $this->client->hset($id, "tags", $tags);
    }

    public function editImage(Image $image)
    {
        $this->addImage($image);
    }

    public function getAllImages()
    {
        $images = [];
        $keys = $this->client->keys('*');

        foreach ($keys as $key) {
            $redisImage = $this->client->hgetall($key);
          
            if(!isset($redisImage['tags'])){
                $tagsArray = [];
            }
            elseif(is_string($redisImage['tags'])){
                $tagsArray = explode(', ',$redisImage['tags']);
            }
            $image = new Image(
                $key,
                $redisImage['name'],
                $redisImage['file_name'],
                $redisImage['description'],
                $tagsArray
            );
            array_push($images, $image);
        }

        return $images;
    }

    public function addAllImages($images)
    {
        foreach ($images as $image) {

        $id  = $image['image_id'];
        $name = $image['name'];
        $fileName = $image['file_name'];
        $description = $image['description'];
        $tags = $image['tags'];

        $this->client->hset($id, "name", $name);
        $this->client->hset($id, "file_name", $fileName);
        $this->client->hset($id, "description", $description);
        $this->client->hset($id, "tags", $tags);
        }
    }
}