<?php declare(strict_types=1);

namespace App\Service\Validator\Collection;

class Model implements CheckInterface
{
    public function isValid(array $data): bool
    {
        return isset($data['model']) && is_string($data['model']) && trim($data['model']) !== '';
    }
}
