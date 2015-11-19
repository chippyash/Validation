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

namespace Chippyash\Validation\Pattern;

use Zend\Validator\ValidatorInterface;
use Chippyash\Validation\Messenger;

/**
 * Interface for a validation pattern
 */
interface ValidatorPatternInterface extends ValidatorInterface 
{
    /**
     * Invokable interface for validation patterns
     *
     * @param mixed $value
     * @param Messenger $messenger
     * @return boolean True if value is valid else false
     */
    public function __invoke($value, Messenger $messenger);
}