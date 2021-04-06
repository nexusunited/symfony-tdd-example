<?php declare(strict_types=1);

namespace App\Tests\Unit\Service\Validator\Collection;

use App\Service\Validator\Collection\Model;
use PHPUnit\Framework\TestCase;

class ModelTest extends TestCase
{
    /**
     * @var \App\Service\Validator\Collection\Model
     */
    private Model $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = new Model();
    }

    public function testValidWhenModelIsNotEmpty()
    {
        $data['model'] = 'UnitModel';
        self::assertTrue($this->model->isValid($data));
    }

    public function testNotValidWhenModelIsNotSet()
    {
        $data = [];
        self::assertFalse($this->model->isValid($data));
    }

    public function testNotValidWhenModelIsEmpty()
    {
        $data['model'] = '';
        self::assertFalse($this->model->isValid($data));
    }

    public function testNotValidWhenModelIsNotString()
    {
        $data['model'] = 123;
        self::assertFalse($this->model->isValid($data));
    }

    public function testNotValidWhenModelHasOnlySpace()
    {
        $data['model'] = '    ';
        self::assertFalse($this->model->isValid($data));
    }
}
