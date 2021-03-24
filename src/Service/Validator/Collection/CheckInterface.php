<?php declare(strict_types=1);

namespace App\Service\Validator\Collection;

interface CheckInterface
{
    public function isValid(array $data): bool;
}
