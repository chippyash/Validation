<?php

namespace Chippyash\Test\Validation;

use Chippyash\Validation\Messenger;
use chippyash\Type\String\StringType;

class MessengerTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Messenger
     */
    protected $sut;

    protected function setUp() {
        $this->sut = new Messenger();
    }

    public function testYouCanAddStringTypeMessages() {
        $this->sut->add(new StringType('foo'));
        $this->assertEquals('foo', (string) $this->sut);
        $this->sut->add(new StringType('foo'));
        $this->assertEquals('foo : foo', (string) $this->sut);
    }

    public function testCallingGetWillReturnAnArrayOfStringTypeMessages() {
        $this->sut->add(new StringType('foo'));
        $ret = $this->sut->get();
        $this->assertInternalType('array', $ret);
        $this->assertEquals(1, count($ret));
        $this->assertInstanceOf('\chippyash\Type\String\StringType', $ret[0]);
    }

    public function testCallingImplodeWillReturnAString() {
        $this->assertEquals('foo|foo', $this->sut->add(new StringType('foo'))
                                                    ->add(new StringType('foo'))
                                                    ->implode('|'));
    }

    public function testYouCanClearTheMessages() {
        $this->sut->add(new StringType('foo'));
        $this->assertEquals('foo', (string) $this->sut);
        $this->assertEquals('', (string) $this->sut->clear());
    }

    public function testYouCanTestIfTheMessengerHasAMessage()
    {
        $this->sut->add(new StringType('foo'));
        $this->assertTrue($this->sut->has(new StringType('foo')));
        $this->assertFalse($this->sut->has(new StringType('bar')));
    }
}
