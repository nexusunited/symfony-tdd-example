<?php declare(strict_types=1);

namespace App\Service;

class Import
{
    private CarImport $carImport;

    /**
     * @param \App\Service\CarImport $carImport
     */
    public function __construct(CarImport $carImport)
    {
        $this->carImport = $carImport;
    }

    public function start()
    {

    }
}
