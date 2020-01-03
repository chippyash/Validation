<?php
namespace Chippyash\Test\Validation\Common;

use Chippyash\Validation\Common\DigitString;
use PHPUnit\Framework\TestCase;

class DigitStringTest extends TestCase
{
    /**
     * System Under Test
     * @var DigitString
     */
    protected $sut;

    public function setUp(): void
    {
        $this->sut = new DigitString();
    }

    public function testOnlyDigitStringsWillPassValidation()
    {
        $this->assertTrue($this->sut->isValid(10));
        $this->assertTrue($this->sut->isValid('01234'));

        $this->assertFalse($this->sut->isValid(-1023));
        $this->assertFalse($this->sut->isValid('a'));
        $this->assertFalse($this->sut->isValid('1a02b3'));

    }

}
