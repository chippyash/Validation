<?php

namespace Chippyash\Test\Validation\Common;

use Chippyash\Validation\Common\UKTelnum;
use Chippyash\Validation\Messenger;
use PHPUnit\Framework\TestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-12-17 at 10:55:41.
 */
class UKTelnumTest extends TestCase
{

    /**
     * @var UKTelnum
     */
    protected $sut;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->sut = new UKTelnum;
    }

    /**
     * @dataProvider ukTelNums
     */
    public function testYouCanValidateUsingIsValidMethod($number, $result)
    {
        $this->assertEquals($result, $this->sut->isValid($number));
    }

    /**
     * @dataProvider ukTelNums
     */
    public function testYouCanInvokeTheValidator($number, $result)
    {
        $messenger = new Messenger();
        $sut = $this->sut;
        $this->assertEquals($result, $sut($number, $messenger));
    }

    public function ukTelNums()
    {
        return array(
            array('02074351245', true),
            array('07885 376269', true),
            array('+447885 376269', true),
            array('+447885 376269', true),
            array('-447885 376269', false),
            array('557885 376269', false),
        );
    }
}
