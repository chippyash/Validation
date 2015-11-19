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

class ValidationExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Chippyash\Validation\Exceptions\ValidationException
     */
    public function testYouCanThrowAValidationException()
    {
        throw new ValidationException();
    }

    /**
     * @expectedException \Chippyash\Validation\Exceptions\ValidationException
     * @expectedExceptionMessage Exception occurred somewhere within Chippyash Validation library
     */
    public function testValidationExceptionHasADefaultMessage()
    {
        throw new ValidationException();
    }

    /**
     * @expectedException \Chippyash\Validation\Exceptions\ValidationException
     * @expectedExceptionMessage Foo Bar
     */
    public function testYouCanOverideTheDefaultMessage()
    {
        throw new ValidationException('Foo Bar');
    }

    /**
     * @expectedException \Chippyash\Validation\Exceptions\ValidationException
     * @expectedExceptionMessage Test Assertion Failed
     */
    public function testYouCanAssertAValidationException()
    {
        ValidationException::assert(function(){return true;}, 'Test Assertion Failed');
    }

    /**
     * @expectedException \Chippyash\Validation\Exceptions\ValidationException
     * @expectedExceptionMessage Test for assert is not callable
     */
    public function testTryingToAssertAValidationExceptionWithANonCallableFunctionWillThrowAValidationException()
    {
        ValidationException::assert('foo', 'Test Assertion Failed');
    }
}
