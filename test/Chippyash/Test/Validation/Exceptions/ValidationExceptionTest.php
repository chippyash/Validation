<?php
/**
 * Validation
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2015, UK
 * @license GPL V3+ See LICENSE.md
 */

namespace Chippyash\Test\Validation\Exceptions;

use Chippyash\Validation\Exceptions\ValidationException;
use PHPUnit\Framework\TestCase;

class ValidationExceptionTest extends TestCase
{
    public function testYouCanThrowAValidationException()
    {
        $this->expectException(\Chippyash\Validation\Exceptions\ValidationException::class);
        throw new ValidationException();
    }

    public function testValidationExceptionHasADefaultMessage()
    {
        $this->expectException(\Chippyash\Validation\Exceptions\ValidationException::class);
        $this->expectExceptionMessage('Exception occurred somewhere within Chippyash Validation library');
        throw new ValidationException();
    }

    public function testYouCanOverideTheDefaultMessage()
    {
        $this->expectException(\Chippyash\Validation\Exceptions\ValidationException::class);
        $this->expectExceptionMessage('Foo Bar');
        throw new ValidationException('Foo Bar');
    }

    public function testYouCanAssertAValidationException()
    {
        $this->expectException(\Chippyash\Validation\Exceptions\ValidationException::class);
        $this->expectExceptionMessage('est Assertion Failed');
        ValidationException::assert(function(){return true;}, 'Test Assertion Failed');
    }

    public function testTryingToAssertAValidationExceptionWithANonCallableFunctionWillThrowAValidationException()
    {
        $this->expectException(\Chippyash\Validation\Exceptions\ValidationException::class);
        $this->expectExceptionMessage('Test for assert is not callable');
        ValidationException::assert('foo', 'Test Assertion Failed');
    }
}
