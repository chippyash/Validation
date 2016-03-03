<?php
/**
 * Chippyash/validation
 *
 * Functional validation
 *
 * Common validations
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2015, UK
 *
 * @link http://php.net/manual/en/functions.anonymous.php
 */

namespace Chippyash\Validation\Pattern;

use Chippyash\Validation\Common\AbstractValidator;
use Chippyash\Type\String\StringType;

/**
 * Test that a value has a value type map matching
 * the one given during construction.
 *
 * The value must be one of:
 *  - an array
 *  - a class with public properties e.g. stdClass
 *  - a class implementing ArrayAccess
 *
 *
 * The map can be nested.
 * The map must define all publicly reachable attributes unless the type is
 *  a function($value, Messenger $messenger) that returns true or false.  The
 *  value passed is the actual value in the item under test.  You cannot recurse
 *  further down any nested item if using the function unless the function does it
 *  itself
 *
 * Allowable types are those
 *  returned by gettype()
 *  a function() ... declaration
 *  the name of a class
 *  a validator implementing the ValidationPatternInterface
 *
 * Usage:
 *  $valueMap = [
 *  'foo' => 'string',
 *  'bar' => [
 *      'baz' => 'int',
 *      'fred' => '\Chippyash\Type\String\StringType'
 *  ],
 *  'blogs' => 'real',
 *  'jimmy' => function ($value, Messenger $messenger){return is_array($value);},
 *  'email' => new Email()
 * ]
 *  ValidationProcessor->add(new HasTypeMap($valueMap));
 */
class HasTypeMap extends AbstractValidator {

    /**
     * User supplied types map
     *
     * @var array
     */
    protected $typeMap = array();

    /**
     * key map used to check value to be validated
     *
     * @var array
     */
    protected $checkMap = array();

    /**
     *
     * @param array $typeMap types map to be checked against
     */
    public function __construct(array $typeMap) {
        $this->typeMap = $typeMap;
    }

    /**
     * Do the validation
     * 
     * @param mixed $value
     * @return boolean
     */
    protected function validate($value)
    {
        $parsedValue = $this->parseValue($value);
        if ($parsedValue === false) {
            $this->messenger->add(new StringType('Value cannot be mapped'));
        }
        //$ret = ($parsedValue == $this->typeMap);
        $ret = $this->rValidate($parsedValue, $this->typeMap, $value);
        if (!$ret) {
            $this->messenger->add(new StringType('Value has invalid type map'));
        }

        return $ret;
    }

    /**
     * Parse the value into a type map
     *
     * @param mixed $value
     * @return array|false
     */
    protected function parsevalue($value)
    {
        //make sure the whole value can be mapped
        if (!is_array($value) && !$value instanceof \ArrayAccess && !is_object($value)) {
            return false;
        }

        return $this->rParseValue($value);
    }

    /**
     * Recursive value type parser
     *
     * @param mixed $parsableValue
     * @return array|string
     */
    protected function rParseValue($parsableValue)
    {
        $ret = array();
        foreach ($parsableValue as $key => $value) {
            if (is_array($value) || $value instanceof \ArrayAccess || is_object($value)) {
                $ret[$key] = $this->rParseValue($value);
            } else {
                $ret[$key] = $this->normalizeType($value);
            }
        }
        if (empty($ret)) {
            //value did not traverse so return the type
            $ret = $this->normalizeType($parsableValue);
        }

        return $ret;
    }

    /**
     * Normalize a value type
     *
     * @param mixed $value
     * @return string
     */
    protected function normalizeType($value) {
        $actType = gettype($value);
        switch ($actType) {
            case "object":
                $ret = get_class($value);
                break;
            default: //"integer" "double" "string", "boolean", "resource", "NULL", "unknown"
                $ret = $actType;
                break;
        }

        return $ret;
    }

    /**
     * Recursive validator
     *
     * @param mixed $valueMap - map of types for the value under test
     * @param mixed $typeMap - map of types required
     * @param mixed $actValue - the value under test
     * @return boolean
     */
    protected function rValidate($valueMap, $typeMap, $actValue)
    {
        $ret = true;
        foreach ($typeMap as $key => $type) {
            if (!isset($valueMap[$key])) {
                $this->messenger->add(new StringType("Value key:{$key} does not exist"));
                $ret = false;
            } elseif (is_callable($type)) {
                $testValue = $this->issetInObjectOrArray($actValue, $key);
                if($testValue === false){
                    $ret = false;
                } else {
                    $ret = $ret && $type($testValue, $this->messenger);
                    if (!$ret) {
                        $this->messenger->add(new StringType("Value key:{$key} did not return true from a function call"));
                    }
                }
            } elseif (is_array($type) || $type instanceof \ArrayAccess || is_object($type)) {
                $testValue = $this->issetInObjectOrArray($actValue, $key);
                if($testValue === false){
                    $ret = false;
                } else {
                    $ret = $ret && $this->rValidate($valueMap[$key], $type, $testValue);
                    if (!$ret) {
                        $implodedType = implode(':', array_keys($type));
                        $this->messenger->add(new StringType("Value key:{$key} is not of type:[{$implodedType}]"));
                    }
                }
            } else {
                $ret = $ret && ($valueMap[$key] == $type);
                if (!$ret) {
                    $this->messenger->add(new StringType("Value key:{$key} is not of type:{$type}"));
                }
            }

            if (!$ret) {
                break; //no point in continuing
            }
        }

        return $ret;
    }

    /**
     * Test if key isset in either Object or Array
     *
     * @param Object|array $actValue
     * @param string $key
     * @return boolean|string
     */
    protected function issetInObjectOrArray($actValue, $key)
    {
        $ret = false;
        if (is_object($actValue)) {
            if (property_exists($actValue, $key)) {
                $ret = $actValue->$key;
            }
        } else {
            if (isset($actValue[$key])) {
                $ret = $actValue[$key];
            }
        }

        return $ret;
    }
}
