<?php declare(strict_types=1);

namespace App\Tests\Unit\Service\Validator\Collection;

use App\Service\Validator\Collection\Desc;
use PHPUnit\Framework\TestCase;

class DescTest extends TestCase
{

    /**
     * @var \App\Service\Validator\Collection\Desc
     */
    private Desc $desc;

    protected function setUp(): void
    {
        parent::setUp();

        $this->desc = new Desc();
    }

    public function testValidWhenDescriptionIsEmpty()
    {
        $data['desc'] = '';
        self::assertTrue($this->desc->isValid($data));
    }

    public function testValidWhenDescriptionIsNotEmpty()
    {
        $data['desc'] = 'unit';
        self::assertTrue($this->desc->isValid($data));
    }

    public function testNotValidWhenDescriptionIsNotSet()
    {
        $data = [];
        self::assertFalse($this->desc->isValid($data));
    }

    public function testNotValidWhenDescriptionIsNotString()
    {
        $data['desc'] = 123;
        self::assertFalse($this->desc->isValid($data));
    }
}
