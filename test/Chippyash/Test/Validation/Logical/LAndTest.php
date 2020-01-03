<?php

namespace Chippyash\Test\Validation\Logical;

use Chippyash\Test\Validation\Stubs\ValidatorTrue;
use Chippyash\Test\Validation\Stubs\ValidatorFalse;
use Chippyash\Validation\Logical\LAnd;
use PHPUnit\Framework\TestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-12-20 at 16:38:44.
 */
class LAndTest extends TestCase {

    protected $true;
    protected $false;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void {
        $this->true = new ValidatorTrue();
        $this->false = new ValidatorFalse();
    }

    public function testTheAndLogicalValidatorReturnsExpectedResponse()
    {
        $test = new LAnd($this->true, $this->true);
        $this->assertTrue($test->isValid('foo'));
        $test = new LAnd($this->true, $this->false);
        $this->assertFalse($test->isValid('foo'));
        $test = new LAnd($this->false, $this->true);
        $this->assertFalse($test->isValid('foo'));
        $test = new LAnd($this->false, $this->false);
        $this->assertFalse($test->isValid('foo'));
    }
}
