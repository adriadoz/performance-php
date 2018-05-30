<?php
declare(strict_types=1);

namespace Performance\ImageLoader\Infrastructure\Controller;

use Gumlet\ImageResize;
use Performance\ImageLoader\Infrastructure\Repository\MySQLImageRepository;

final class ResizeL
{
    private $fileName;
    private $directory;
    private $repo;

    public function __construct(string $fileName, string $directory, MySQLImageRepository $repo)
    {
        $this->repo = $repo;
        $this->fileName = $fileName;
        $this->directory = $directory;
    }

    public function __invoke()
    {
        $image = new ImageResize($this->directory . $this->fileName);
        $image->resizeToBestFit(800, 500);
        $image->save($this->directory ."L_" . $this->fileName);
    }
}