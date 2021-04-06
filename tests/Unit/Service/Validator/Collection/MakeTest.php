<?php declare(strict_types=1);

namespace App\Tests\Unit\Service\Validator\Collection;

use App\Service\Validator\Collection\Make;
use PHPUnit\Framework\TestCase;

class MakeTest extends TestCase
{
    /**
     * @var \App\Service\Validator\Collection\Make
     */
    private Make $make;

    protected function setUp(): void
    {
        parent::setUp();

        $this->make = new Make();
    }

    public function testValidWhenMakeIsNotEmpty()
    {
        $data['make'] = 'UnitMake';
        self::assertTrue($this->make->isValid($data));
    }

    public function testNotValidWhenMakeIsNotSet()
    {
        $data = [];
        self::assertFalse($this->make->isValid($data));
    }

    public function testNotValidWhenMakeIsEmpty()
    {
        $data['make'] = '';
        self::assertFalse($this->make->isValid($data));
    }

    public function testNotValidWhenMakeIsNotString()
    {
        $data['make'] = 123;
        self::assertFalse($this->make->isValid($data));
    }

    public function testNotValidWhenMakeHasOnlySpace()
    {
        $data['make'] = '    ';
        self::assertFalse($this->make->isValid($data));
    }
}
