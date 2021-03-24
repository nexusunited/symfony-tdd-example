<?php declare(strict_types=1);

namespace App\Service\Validator;

use App\Service\Validator\Collection\Desc;
use App\Service\Validator\Collection\Make;
use App\Service\Validator\Collection\Model;

class Validator
{
    /**
     * @var \App\Service\Validator\Collection\CheckInterface[]
     */
    private array $collection;

    public function __construct()
    {
        $this->collection = [
            new Desc(),
            new Make(),
            new Model(),
        ];
    }

    public function isValid(array $data): bool
    {
        foreach ($this->collection as $validator) {
            if ($validator->isValid($data) === false) {
                return false;
            }
        }

        return true;
    }
}
