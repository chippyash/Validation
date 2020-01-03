<?php

namespace Chippyash\Test\Validation;

use Chippyash\Validation\Messenger;
use PHPUnit\Framework\TestCase;

class MessengerTest extends TestCase {

    /**
     * @var Messenger
     */
    protected $sut;

    protected function setUp(): void {
        $this->sut = new Messenger();
    }

    public function testYouCanAddStringMessages() {
        $this->sut->add('foo');
        $this->assertEquals('foo', (string) $this->sut);
        $this->sut->add('foo');
        $this->assertEquals('foo : foo', (string) $this->sut);
    }

    public function testCallingGetWillReturnAnArrayOfStringMessages() {
        $this->sut->add('foo');
        $ret = $this->sut->get();
        $this->assertIsArray($ret);
        $this->assertEquals(1, count($ret));
    }

    public function testCallingImplodeWillReturnAString() {
        $this->assertEquals('foo|foo', $this->sut->add('foo')
                                                    ->add('foo')
                                                    ->implode('|'));
    }

    public function testYouCanClearTheMessages() {
        $this->sut->add('foo');
        $this->assertEquals('foo', (string) $this->sut);
        $this->assertEquals('', (string) $this->sut->clear());
    }

    public function testYouCanTestIfTheMessengerHasAMessage()
    {
        $this->sut->add('foo');
        $this->assertTrue($this->sut->has('foo'));
        $this->assertFalse($this->sut->has('bar'));
    }
}
