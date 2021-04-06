<?php declare(strict_types=1);

namespace App\Service\Validator\Collection;

class Desc implements CheckInterface
{
    public function isValid(array $data): bool
    {
        return isset($data['desc']) && is_string($data['desc']);
    }
}
