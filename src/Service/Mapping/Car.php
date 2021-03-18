<?php declare(strict_types=1);

namespace App\Service\Mapping;

use App\Service\Dto\CarImportDto;

class Car
{
    public function mapJsonToCarImportDto(array $car): CarImportDto
    {
        return new CarImportDto();
    }
}
