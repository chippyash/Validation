<?php

namespace Chippyash\Test\Validation\Logical;

use Chippyash\Test\Validation\Stubs\ValidatorTrue;
use Chippyash\Test\Validation\Stubs\ValidatorFalse;
use Chippyash\Validation\Logical\LXor;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-12-20 at 16:38:44.
 */
class LXorTest extends \PHPUnit_Framework_TestCase {

    protected $true;
    protected $false;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->true = new ValidatorTrue();
        $this->false = new ValidatorFalse();
    }

    public function testTheXorLogicalValidatorReturnsExpectedResponse()
    {
        $test = new LXor($this->true, $this->true);
        $this->assertFalse($test->isValid('foo'));
        $test = new LXor($this->true, $this->false);
        $this->assertTrue($test->isValid('foo'));
        $test = new LXor($this->false, $this->true);
        $this->assertTrue($test->isValid('foo'));
        $test = new LXor($this->false, $this->false);
        $this->assertFalse($test->isValid('foo'));
    }

}