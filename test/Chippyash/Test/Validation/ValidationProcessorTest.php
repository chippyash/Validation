<?php

namespace Chippyash\Test\Validation;

use Chippyash\Validation\Messenger;
use Chippyash\Validation\ValidationProcessor;
use chippyash\Type\String\StringType;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-05-12 at 10:26:14.
 */
class ValidationProcessorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ValidationProcessor
     */
    protected $object;

    public function testYouCanConstructAValidationProcessorWithTheCorrectParameters()
    {
        $obj = new ValidationProcessor(function($value) {
            return true;
        });
        $this->assertInstanceOf('Chippyash\Validation\ValidationProcessor',
                $obj);
    }

    /**
     * @expectedException \Chippyash\Validation\Exceptions\ValidationException
     */
    public function testConstructingWithBadParametersWillThrowAnException()
    {
        $obj = new ValidationProcessor('foo');
    }

    public function testYouCanAddAdditionalValidationsToTheProcessor()
    {
        $obj = new ValidationProcessor(function($value, Messenger $messenger) {
            return true;
        });
        $obj->add(function($value, Messenger $messenger) {
            return true;
        });
        $this->assertTrue($obj->validate('foo'));
    }

    /**
     * @expectedException \Chippyash\Validation\Exceptions\ValidationException
     */
    public function testAddingAnInlaidValidatorToTheProcessorWillThrowAnException()
    {
        $obj = new ValidationProcessor(function() {
            return true;
        });
        $obj->add('foo');
    }

    public function testYouCanValidateUsingTheProcessor()
    {
        $obj = new ValidationProcessor(function($value, Messenger $messenger) {
            return true;
        });
        $obj->add(function($value, Messenger $messenger) {
            return true;
        });
        $this->assertTrue($obj->validate('foo'));
        $obj->add(function($value) {
            return false;
        });
        $this->assertFalse($obj->validate('foo'));
    }

    public function testYouCanGetTheMessengerFromTheProcessor()
    {
        $obj = new ValidationProcessor(function($value, Messenger $messenger) {
            $messenger->add(new StringType('foo bar'));
            return false;
        });
        $this->assertFalse($obj->validate('foo'));
        $messenger = $obj->getMessenger();
        $this->assertEquals('foo bar', $messenger->implode());
    }
}
