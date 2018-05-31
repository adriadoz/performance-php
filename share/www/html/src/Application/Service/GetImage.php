<?php
declare(strict_types=1);

namespace Performance\ImageLoader\Application\Service;

use Performance\ImageLoader\Domain\Repository\ImageRepository;

final class GetImage
{
    private $repo;

    public function __construct(ImageRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke($id)
    {
       return $this->repo->getImageById($id);
    }
}