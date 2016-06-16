<?php
/**
 * Chippyash/validation
 *
 * Validation
 *
 * @author    Ashley Kitson
 * @copyright Ashley Kitson, 2015, UK
 *
 * @link http://php.net/manual/en/functions.anonymous.php
 */
namespace Chippyash\Validation\Exceptions;

/**
 * A generic Validation exception
 */
class ValidationException extends \Exception
{
    /**
     * Standard message
     * @var string
     */
    protected $msg = 'Exception occurred somewhere within Chippyash Validation library';

    public function __construct($message = null, $code = null, $previous = null)
    {
        $message = (is_null($message) ? $this->msg : $message);
        parent::__construct($message, $code, $previous);
    }

    /**
     * Assert a test and throw this exception if it returns true
     *
     * @param callable $test    Function returning true if error condition exists
     * @param string   $message Message for exception
     * @param int      $code    Code for exception
     *
     * @throws ValidationException
     * @throws static
     */
    public static function assert($test, $message, $code = null)
    {
        if (!is_callable($test)) {
            throw new self('Test for assert is not callable', 500);
        }
        if ($test() === true) {
            throw new static($message, $code);
        }
    }
}
