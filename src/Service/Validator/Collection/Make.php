<?php declare(strict_types=1);

namespace App\Service\Validator\Collection;

class Make implements CheckInterface
{
    public function isValid(array $data): bool
    {
        return isset($data['make']) && is_string($data['make']) && trim($data['make']) !== '';
    }
}
