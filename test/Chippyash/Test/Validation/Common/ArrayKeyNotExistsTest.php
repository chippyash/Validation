<?php
namespace Chippyash\Test\Validation\Common;

use Chippyash\Validation\Common\ArrayKeyNotExists;

class ArrayKeyNotExistsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * System Under Test
     * @var ArrayKeyNotExists
     */
    protected $sut;

    public function SetUp()
    {
        $this->sut = new ArrayKeyNotExists('foo');
    }

    public function testValidationWillFailIfInputIsNotAnArray()
    {
        $this->assertFalse($this->sut->isValid('foo'));
        $this->assertEquals(['value is not an array'], $this->sut->getMessages());
    }

    public function testValidationWillSucceedIfInputIsAnArrayAndDoesNotContainTheRequiredKey()
    {
        $this->assertTrue($this->sut->isValid(['bar' => 'foo']));
    }

    public function testValidationWillFailIfInputIsAnArrayContainingRequiredKey()
    {
        $this->assertFalse($this->sut->isValid(['foo' => 'bar']));
        $this->assertEquals(['key foo exists in array'], $this->sut->getMessages());
    }
}
