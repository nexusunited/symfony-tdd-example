<?php declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Finder\Finder;

class ImportReader
{
    public function read(): Finder
    {
        return new Finder();
    }
}
