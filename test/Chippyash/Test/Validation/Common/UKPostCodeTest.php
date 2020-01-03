<?php

namespace Chippyash\Test\Validation\Common;

use Chippyash\Validation\Common\UKPostCode;
use Chippyash\Validation\Messenger;
use PHPUnit\Framework\TestCase;

class UKPostCodeTest extends TestCase
{
    protected $sut;

    protected $messenger;

    protected function setUp(): void
    {
        $this->sut = new UKPostCode();
        $this->messenger = new Messenger();
    }

    /**
     * @dataProvider postcodes
     */
    public function testYouCanInvokeTheValidator($test, $result)
    {
        $sut = $this->sut;
        $this->assertEquals($result, $sut($test, $this->messenger));
        if (!$result) {
            $this->assertEquals('The input does not appear to be a postal code', $this->messenger->implode());
        }
    }

    /**
     * @dataProvider postcodes
     */
    public function testYouCanValidateUsingIsValidMethod($test, $result)
    {
        $this->assertEquals($result, $this->sut->isValid($test));
        if (!$result) {
            $this->assertEquals(
                'The input does not appear to be a postal code',
                implode(': ', $this->sut->getMessages()));
        }
    }


    public function postcodes()
    {
        return array(
            array("CO1 1HQ", true),
            array("CO11HR", true),
            array("CO1 1HS", true),
            array("CO1 1HT", true),
            array("XX1 1HT", false),
            array("CO1 1VV", false),
        );
    }
}
