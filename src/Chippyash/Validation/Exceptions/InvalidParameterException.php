<?php
/**
 * chippyash/validation
 *
 * Validation
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2015, UK
 *
 * @link http://php.net/manual/en/functions.anonymous.php
 */
namespace Chippyash\Validation\Exceptions;


/**
 * Invalid parameter exception
 */
class InvalidParameterException extends ValidationException {

    protected $msg = 'Invalid or missing parameter: %s';

    public function __construct($paramName, $code = null, $previous = null)
    {
        parent::__construct(sprintf($this->msg, $paramName), $code, $previous);
    }
}
