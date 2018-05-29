<?php
declare(strict_types=1);

namespace Performance\ImageLoader\Infrastructure\Repository;

use PDO;
use PDOException;
use Performance\ImageLoader\Domain\Model\Image;
use Performance\ImageLoader\Domain\Repository\ImageRepository;

final class MySQLImageRepository implements ImageRepository
{
    private $database;

    public function __construct()
    {
        $host = 'localhost';
        $dbName= 'images_db';
        $user = 'root';
        $pass = 'root';
        try {
            $this->database = new PDO("mysql:host={$host};dbname={$dbName}", $user, $pass);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function addImage(Image $image)
    {
        $id = $image->getId();
        $name = $image->getName();
        $fileName = $image->getFileName();

        $statement = $this->database->prepare('INSERT INTO images SET image_id =:id, name =:name, file_name =:filename');
        $statement->bindParam(':id', $id, \PDO::PARAM_STR);
        $statement->bindParam(':name', $name, \PDO::PARAM_STR);
        $statement->bindParam(':filename', $fileName, \PDO::PARAM_STR);
        $statement->execute();
    }

    public function updateImage(Image $image)
    {
    }
}