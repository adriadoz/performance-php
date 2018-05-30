<?php
declare(strict_types=1);

namespace Performance\ImageLoader\Application\Service;


use Performance\ImageLoader\Domain\Repository\ImageRepository;

final class GetAllImages
{
    private $repo;

    public function __construct(ImageRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke()
    {
        return $this->repo->getAllImages();
    }
}