<?php
declare(strict_types=1);

namespace Performance\ImageLoader\Infrastructure\Repository;

use Elasticsearch\ClientBuilder;
use Performance\ImageLoader\Domain\Model\Image;
use Performance\ImageLoader\Domain\Repository\ImageRepository;

final class ElasticImageRepository implements ImageRepository
{
    private $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()->build();
    }

    public function addImage(Image $image)
    {
        $params = [
            'index' => 'images',
            'type' => 'img',
            'id' => $image->getId(),
            'body' => [
                'image_name' => $image->getName(),
                'file_name' => $image->getFileName(),
                'description' => $image->getDescription(),
                'tags' => $image->getTagsString()
            ]
        ];

        $this->client->index($params);
    }

    public function updateImage(Image $image)
    {

    }

    public function searchImages(string $tagsToSearch)
    {
        $foundImages = [];

        $params = [
            'index' => 'images',
            'type' => 'img',
            'body' => [
                'query' => [
                    'match' => [
                        'tags' => $tagsToSearch
                    ]
                ]
            ]
        ];

        $results = $this->client->search($params);
        $images   = $results['hits']['hits'];

        foreach ($images as $image) {
            $id = $image['_id'];
            $name = $image['_source']['image_name'];
            $fileName = $image['_source']['file_name'];
            $description = $image['_source']['description'];
            $tags = explode (', ',$image['_source']['tags']);
            $foundImage = new Image($id, $name, $fileName, $description, $tags);
            $arrayImage = [
                'name' => $foundImage->getName(),
                'image_id' => $id,
                'file_name' => $foundImage->getFileName()
            ];
            array_push($foundImages, $arrayImage);
        }

        return $foundImages;
    }
}