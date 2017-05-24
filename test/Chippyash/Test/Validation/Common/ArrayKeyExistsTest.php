<?php
namespace Chippyash\Test\Validation\Common;

use Chippyash\Validation\Common\ArrayKeyExists;

class ArrayKeyExistsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * System Under Test
     * @var ArrayKeyExists
     */
    protected $sut;

    public function SetUp()
    {
        $this->sut = new ArrayKeyExists('foo');
    }

    public function testValidationWillFailIfInputIsNotAnArray()
    {
        $this->assertFalse($this->sut->isValid('foo'));
        $this->assertEquals(['value is not an array'], $this->sut->getMessages());
    }

    public function testValidationWillSucceedIfInputIsAnArrayContainingRequiredKey()
    {
        $this->assertTrue($this->sut->isValid(['foo' => 'bar']));
    }

    public function testValidationWillFailIfInputIsAnArrayAndDoesNotContainTheRequiredKey()
    {
        $this->assertFalse($this->sut->isValid(['bar' => 'foo']));
        $this->assertEquals(['key foo does not exist in array'], $this->sut->getMessages());
    }
}
