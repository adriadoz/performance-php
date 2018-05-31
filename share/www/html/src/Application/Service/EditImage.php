<?php
declare(strict_types=1);

namespace Performance\ImageLoader\Application\Service;

use Performance\ImageLoader\Domain\Model\Image;
use Performance\ImageLoader\Domain\Repository\ImageRepository;

final class EditImage
{
    private $repo;

    public function __construct(ImageRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(Image $image)
    {
        $this->repo->editImage($image);
    }
}