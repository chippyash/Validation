<?php

namespace Chippyash\Test\Test\Validation\Common;

use Chippyash\Validation\Common\IsArray;
use Chippyash\Validation\Messenger;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-11-19 at 09:52:37.
 */
class IsArrayTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider arrays
     */
    public function testWillReturnCorrectResponseForTestDataSetViaMagicInvokeMethod($test, $result, $message)
    {
        $sut = new IsArray();
        $messenger = new Messenger();
        $this->assertEquals($result, $sut($test, $messenger));
        if (!$result) {
            $this->assertEquals($message, $messenger->implode());
        }
    }

    /**
     * @dataProvider arrays
     */
    public function testWillReturnCorrectResponseForTestDataSetViaIsValidMethod($test, $result, $message)
    {
        $sut = new IsArray();
        $this->assertEquals($result, $sut->isValid($test));
        if (!$result) {
            $this->assertEquals($message, implode(':',$sut->getMessages()));
        }
    }

    public function arrays()
    {
        return array(
            array(array(), true, null),
            array(array(1,2), true, null),
            array(array('three','four'), true, null),
            array(array(array(), array()), true, null),
            array(1, false, IsArray::ERR_INVALID),
            array('a', false, IsArray::ERR_INVALID),
            array(null, false, IsArray::ERR_INVALID)
        );
    }
    
    public function testYouCanOptionallyTestForAnEmptyArray()
    {
        $sut = new IsArray(true);
        $this->assertFalse($sut->isValid(array()));
        $this->assertEquals(IsArray::ERR_ARRAY_EMPTY, implode(':',$sut->getMessages()));
        $this->assertTrue($sut->isValid(array('foo')));
    }
}
