<?php
declare(strict_types=1);

namespace Performance\ImageLoader\Domain\Model;

final class Image
{
    private $id;
    private $name;
    private $file_name;
    private $description;
    private $tags;

    public function __construct(string $id, string $name, string $file_name, string $description, array $tags)
    {
        $this->id = $id;
        $this->name = $name;
        $this->file_name = $file_name;
        $this->description = $description;
        $this->tags = $tags;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getFileName()
    {
        return $this->file_name;
    }
}