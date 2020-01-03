<?php

namespace Chippyash\Test\Test\Validation\Pattern;

use Chippyash\Validation\Messenger;
use Chippyash\Validation\Common\Lambda;
use Chippyash\Validation\Pattern\Repeater;
use PHPUnit\Framework\TestCase;

class RepeaterTest extends TestCase
{

    /**
     * @var messenger
     */
    protected $messenger;

    protected function setUp(): void {
        $this->messenger = new Messenger();
    }

    public function testARepeaterExpectsValueToBeTraversable()
    {
        $validator = new Lambda(function($value){return true;});
        $sut = new Repeater($validator);
        $this->assertFalse($sut->isValid('foo'));
        $this->assertTrue($sut->isValid(array()));
        $this->assertTrue($sut->isValid(new \ArrayObject()));
    }

    public function testADefaultRepeaterWillSucceedForZeroOrMoreRepetions()
    {
        $validator = new Lambda(function($value){return true;});
        $sut = new Repeater($validator);
        $this->assertTrue($sut->isValid(array()));
        $this->assertTrue($sut->isValid(array(1)));
        $this->assertTrue($sut->isValid(array(1,2,3,4,5)));
    }

    public function testYouCanSetAMinimumNumberOfItemsToBeInTheTraversable()
    {
        $validator = new Lambda(function($value){return true;});
        $sut = new Repeater($validator, 2);
        $this->assertFalse($sut->isValid(array()));
        $this->assertFalse($sut->isValid(array(1)));
        $this->assertTrue($sut->isValid(array(1,2)));
    }

    public function testYouCanSetAMaximumNumberOfItemsToBeInTheTraversable()
    {
        $validator = new Lambda(function($value){return true;});
        $sut = new Repeater($validator, null, 2);
        $this->assertTrue($sut->isValid(array()));
        $this->assertTrue($sut->isValid(array(1)));
        $this->assertTrue($sut->isValid(array(1,2)));
        $this->assertFalse($sut->isValid(array(1,2,3)));
    }

    public function testMaximumMustNotBeLessThanMinimum()
    {
        $this->expectException(\Chippyash\Validation\Exceptions\ValidationException::class);
        $validator = new Lambda(function($value){return true;});
        $sut = new Repeater($validator, 3, 2);
    }

    public function testARepeaterWillApplyTheValidationToAllValueItemsUntilItFindsAnInvalidOne()
    {
        $validator = new Lambda(function($value){return is_int($value);});
        $sut = new Repeater($validator);
        $this->assertTrue($sut->isValid(array()));
        $this->assertTrue($sut->isValid(array(1)));

        $this->assertFalse($sut->isValid(array(1, 'foo')));
        $this->assertEquals(
            'Validation for lambda function failed:value item#1 failed repeatable validation',
            implode(':',$sut->getMessages())
        );

        $this->assertFalse($sut->isValid(array(1, 2, 'foo')));
        $this->assertEquals(
            'Validation for lambda function failed:value item#2 failed repeatable validation',
            implode(':',$sut->getMessages())
        );

        $this->assertFalse($sut->isValid(array(1, 2, 'foo', 3)));
        $this->assertEquals(
            'Validation for lambda function failed:value item#2 failed repeatable validation',
            implode(':',$sut->getMessages())
        );
    }
}
