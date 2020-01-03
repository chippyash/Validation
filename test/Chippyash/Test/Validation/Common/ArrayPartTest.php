<?php
namespace Chippyash\Test\Validation\Common;

use Chippyash\Validation\Common\ArrayPart;
use PHPUnit\Framework\TestCase;

class ArrayPartTest extends TestCase
{
    /**
     * System Under Test
     * @var ArrayPart
     */
    protected $sut;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject Chippyash\Validation\Common\AbstractValidator
     */
    protected $keyValidator;

    public function setUp(): void
    {
        $this->keyValidator = $this->getMockBuilder('\Chippyash\Validation\Common\AbstractValidator')
            ->setMethods(['validate', 'getMessages'])
            ->getMockForAbstractClass();
        $this->sut = new ArrayPart('foo', $this->keyValidator);
    }

    public function testValidationWillFailIfInputIsNotAnArray()
    {
        $this->assertFalse($this->sut->isValid('foo'));
        $this->assertEquals(['value is not an array'], $this->sut->getMessages());
    }

    public function testValidationWillFailIfInputIsAnArrayAndDoesNotContainTheRequiredKey()
    {
        $this->assertFalse($this->sut->isValid(['bar' => 'foo']));
        $this->assertEquals(['key foo does not exist in array'], $this->sut->getMessages());
    }

    public function testValidationWillSucceedIfKeyExistsAndPassesAdditionalValidation()
    {
        $this->keyValidator
            ->method('validate')
            ->willReturn(true);
        $this->assertTrue($this->sut->isValid(['foo' => 'bar']));
    }

    public function testValidationWillFailIfKeyExistsAndFailsAdditionalValidation()
    {
        $this->keyValidator
            ->method('validate')
            ->willReturn(false);
        $this->keyValidator
            ->method('getMessages')
            ->willReturn(['foo is invalid']);
        $this->assertFalse($this->sut->isValid(['foo' => 'bar']));
        $this->assertEquals(
            [
                'key foo does not pass validation',
                'foo is invalid'
            ],
            $this->sut->getMessages()
        );
    }
}
