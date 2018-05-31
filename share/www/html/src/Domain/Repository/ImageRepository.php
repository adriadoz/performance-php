<?php
declare(strict_types=1);

namespace Performance\ImageLoader\Domain\Repository;


use Performance\ImageLoader\Domain\Model\Image;

interface ImageRepository
{
    public function addImage(Image $image);
    public function editImage(Image $image);
    public function getImageById($id);
}