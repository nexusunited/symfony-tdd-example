<?php declare(strict_types=1);

namespace App\Service\Mapping;

use App\Service\Dto\CarImportDto;

class Car
{
    /**
     * @param array $car
     *
     * @return \App\Service\Dto\CarImportDto
     */
    public function mapJsonToCarImportDto(array $car): CarImportDto
    {
        $carImportDto = new CarImportDto();
        $carImportDto->setMake($car['make']);
        $carImportDto->setDescription($car['desc']);
        $carImportDto->setModel($car['model']);

        return $carImportDto;
    }
}
