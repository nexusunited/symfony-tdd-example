<?php declare(strict_types=1);

namespace App\Service;

class Import
{
    private CarImport $carImport;

    private ImportReader $importReader;

    public function __construct(
        CarImport $carImport,
        ImportReader $importReader
    )
    {
        $this->carImport = $carImport;
        $this->importReader = $importReader;
    }

    public function start()
    {
        $finder = $this->importReader->read();
        foreach ($finder as $item) {
            $pathToFile = $item->getRealPath();
            $this->carImport->import($pathToFile);
            unlink($pathToFile);
        }
    }
}
