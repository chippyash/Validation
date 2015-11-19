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
namespace Chippyash\Validation\Common;

use Chippyash\Validation\Exceptions\ValidationException;
use chippyash\Type\String\StringType;
use Monad\Match;
use Monad\Option;

class Lambda extends AbstractValidator
{

    const ERR_LAMBDA = 'Validation for lambda function failed';

    /**
     * Constructor
     * @param callable $function function accepting value and optional Messenger, returning boolean
     * @param StringType $msg Error message in event of failure
     *
     * @throws ValidationException
     */
    public function __construct($func, StringType $msg = null)
    {
        ValidationException::assert(
            function () use ($func) {
                return !is_callable($func);
            }, 'func'
        );

        $this->function = $func;
        $this->msg = (is_null($msg) ? new StringType(self::ERR_LAMBDA) : $msg);
    }

    /**
     * Validate
     *
     * @param mixed $value
     * @return boolean
     */
    protected function validate($value)
    {
        $f = $this->function;

        return Match::on(Option::create((boolean) $f($value, $this->messenger), false))
            ->Monad_Option_Some(true)
            ->Monad_Option_None(function(){
                $this->messenger->add($this->msg);
                return false;
            })
            ->value();
    }
}