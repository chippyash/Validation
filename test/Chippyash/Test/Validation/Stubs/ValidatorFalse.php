<?php
/**
 * chippyash/validation
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

namespace Chippyash\Test\Validation\Stubs;

use Chippyash\Validation\Pattern\ValidatorPatternInterface;
use Chippyash\Validation\Messenger;

/**
 * Stub validator that always returns FALSE
 */
class ValidatorFalse implements ValidatorPatternInterface{
    public function __invoke($value, Messenger $messenger) {return false;}
    public function isValid($value){return false;}
    public function getMessages(){}
}
