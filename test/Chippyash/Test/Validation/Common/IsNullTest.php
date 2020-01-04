<?php

namespace Chippyash\Test\Validation\Pattern;

use Chippyash\Validation\Messenger;
use Chippyash\Validation\Common\IsNull;
use PHPUnit\Framework\TestCase;

class IsNullTest extends TestCase {

    /**
     * @var messenger
     */
    protected $messenger;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void {
        $this->messenger = new Messenger();
    }

    public function testYouCanValidateANullValue() {
        $v = new IsNull();
        $this->assertTrue($v(null, $this->messenger));
        $this->assertFalse($v(0, $this->messenger));
        $this->assertFalse($v(1, $this->messenger));
        $this->assertFalse($v(true, $this->messenger));
        $this->assertFalse($v(false, $this->messenger));
    }
}
