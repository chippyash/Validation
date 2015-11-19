<?php

namespace Chippyash\Test\Validation\Pattern;

use Chippyash\Validation\Messenger;
use Chippyash\Validation\Pattern\HasTypeMap;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-09-26 at 09:19:34.
 */
class HasTypeMapTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var messenger
     */
    protected $messenger;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->messenger = new Messenger();
    }

    public function testYouCanConstructThePattern()
    {
        $this->assertInstanceOf(
            'Chippyash\Validation\Pattern\HasTypeMap',
            new HasTypeMap([])
        );
    }

    public function testTheValidationValueMayBeAnArray()
    {
        $sut = new HasTypeMap([]);
        $this->assertTrue($sut->isValid([]));
    }

    public function testTheValidationValueMayBeATraversableObject()
    {
        $sut = new HasTypeMap([]);
        $value = new\stdClass();
        $value->foo = 'bar';
        $this->assertTrue($sut->isValid($value));
    }

    public function testThePatternWillFailIfGivenAnInvalidValueType()
    {
        $sut = new HasTypeMap(['a'=>'b']);
        $value = 'bar';
        $this->assertFalse($sut->isValid($value));
        $this->assertEquals('Value cannot be mapped: Value key:a does not exist: Value has invalid type map',
            implode(': ',$sut->getMessages())
        );
    }

    public function testThePatternWillSucceedWithAValidAssociativeArrayToValidate()
    {
        stream_wrapper_register('foo', 'Chippyash\Test\Validation\Stubs\Stream');
        $streamStub = fopen('foo://bar', 'r');

        $test = array(
            'a' => new \stdClass(),
            'b' => array(
                'b1' => 2,
                'b2' => 'foo'
            ),
            'c' => true,
            'd' => 23.67,
            'e' => $this->messenger,
            'f' => null,
            'g' => $streamStub,
            'h' => 'foo'
        );
        $typeMap = array(
            'a' => 'stdClass',
            'b' => array(
                'b1' => 'integer',
                'b2' => 'string'
            ),
            'c' => 'boolean',
            'd' => 'double',
            'e' => 'Chippyash\Validation\Messenger',
            'f' => 'NULL',
            'g' => 'resource',
            'h' => function($value, Messenger $msg){return $value == 'foo';}
        );
        $sut = new HasTypeMap($typeMap);

        $this->assertTrue($sut($test, $this->messenger));

        fclose($streamStub);
        stream_wrapper_unregister('foo');
    }

    public function testThePatternWillSucceedWithAValidObjectToValidate()
    {
        stream_wrapper_register('foofoo', 'Chippyash\Test\Validation\Stubs\Stream');
        $streamStub = fopen('foofoo://bar', 'r');

        $test = new \stdClass();
        $test->a = new \stdClass();
        $test->b = array(
                'b1' => 2,
                'b2' => 'foo'
            );
        $test->c = true;
        $test->d = 23.67;
        $test->e = $this->messenger;
        $test->f = null;
        $test->g = $streamStub;
        $test->h = 123;

        $typeMap = array(
            'a' => 'stdClass',
            'b' => array(
                'b1' => 'integer',
                'b2' => 'string'
            ),
            'c' => 'boolean',
            'd' => 'double',
            'e' => 'Chippyash\Validation\Messenger',
            'f' => 'NULL',
            'g' => 'resource',
            'h' => function($value, Messenger $msg){return $value == 123;}
        );
        $sut = new HasTypeMap($typeMap);

        $this->assertTrue($sut($test, $this->messenger));

        fclose($streamStub);
        stream_wrapper_unregister('foofoo');
    }

    public function testThePatternWillFailWithAnInvalidAssociativeArray()
    {
        $test = array(
            'a' => 12
        );
        $typeMap = array(
            'a' => 'stdClass',
            'b' => array(
                'b1' => 'integer',
                'b2' => 'string'
            ),
            'c' => 'boolean',
            'd' => 'double',
            'e' => 'Chippyash\Validation\Messenger',
            'f' => 'NULL',
            'g' => 'resource'
        );
        $sut = new HasTypeMap($typeMap);

        $this->assertFalse($sut($test, $this->messenger));
        $this->assertEquals("Value key:a is not of type:stdClass : Value has invalid type map",
            $this->messenger->implode()
        );
    }

    public function testThePatternWillFailWithAnInvalidObject()
    {
        $test = new \stdClass();
        $test->a = 12;
        $typeMap = array(
            'a' => 'stdClass',
            'b' => array(
                'b1' => 'integer',
                'b2' => 'string'
            ),
            'c' => 'boolean',
            'd' => 'double',
            'e' => 'Chippyash\Validation\Messenger',
            'f' => 'NULL',
            'g' => 'resource'
        );
        $sut = new HasTypeMap($typeMap);

        $this->assertFalse($sut($test, $this->messenger));
        $this->assertEquals("Value key:a is not of type:stdClass : Value has invalid type map",
            $this->messenger->implode()
        );
    }

    public function testThePatternWillFailIfRequiredKeyDoesNotExist()
    {
        $sut = new HasTypeMap(['foo'=>'string']);
        $this->assertFalse($sut->isValid([]));
        $this->assertEquals('Value key:foo does not exist: Value has invalid type map',
            implode(': ',$sut->getMessages())
        );
    }

    /**
     * @todo - this may be unwanted behaviour
     */
    public function testThePatternWillFailIfValueForACallableValueIsFalse()
    {
        $map = ['a' => function($value){return true;}];
        $test =['a'=>false];
        $sut = new HasTypeMap($map);
        $this->assertFalse($sut($test, $this->messenger));
        $this->assertEquals("Value has invalid type map",
            $this->messenger->implode()
        );
    }

    public function testYouCanUseACallableFunctionForAValidationType()
    {
        $map = ['a' => function($value){return true;}];
        $test =['a'=>'foo'];
        $sut = new HasTypeMap($map);
        $this->assertTrue($sut($test, $this->messenger));

        $map = ['a' => function($value){return false;}];
        $sut = new HasTypeMap($map);
        $this->assertFalse($sut($test, $this->messenger));
    }

    /**
     * @todo - this may be unwanted behaviour
     */
    public function testThePatternWillFailIfValueForATraversableValueIsFalse()
    {
        $map = ['a' => ['b' => 'string']];
        $test =['a'=>false];
        $sut = new HasTypeMap($map);
        $this->assertFalse($sut($test, $this->messenger));
        $this->assertEquals("Value has invalid type map",
            $this->messenger->implode()
        );
    }

    public function testYouCanUseATraversableFunctionForAValidationType()
    {
        $map = ['a' => ['b' => 'string']];
        $test =['a' => ['b' => 'foo']];
        $sut = new HasTypeMap($map);
        $this->assertTrue($sut($test, $this->messenger));

        $map = ['a' => ['b' => ['c' => 'string']]];
        $sut = new HasTypeMap($map);
        $this->assertFalse($sut($test, $this->messenger));
    }


}
