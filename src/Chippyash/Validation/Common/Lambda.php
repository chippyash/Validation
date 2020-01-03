<?php

declare(strict_types=1);

/**
 * Chippyash/validation
 *
 * Functional validation
 *
 * Common validations
 *
 * @author    Ashley Kitson
 * @copyright Ashley Kitson, 2015, UK
 *
 * @link http://php.net/manual/en/functions.anonymous.php
 */
namespace Chippyash\Validation\Common;

use Chippyash\Validation\Exceptions\ValidationException;
use Monad\Match;
use Monad\Option;

class Lambda extends AbstractValidator
{
    public const ERR_LAMBDA = 'Validation for lambda function failed';

    /**
     * @var callable
     */
    protected $function;

    /**
     * @var String
     */
    protected $msg;

    /**
     * Constructor
     *
     * @param callable   $func
     * @param String $msg  Error message in event of failure
     *
     * @throws ValidationException
     * @throws static
     */
    public function __construct(callable $func, ?string $msg = null)
    {
        ValidationException::assert(
            function () use ($func) {
                return !is_callable($func);
            },
            'func'
        );

        $this->function = $func;
        $this->msg = (is_null($msg) ? self::ERR_LAMBDA : $msg);
    }

    /**
     * Validate
     *
     * @param  mixed $value
     * @return boolean
     */
    protected function validate($value)
    {
        $f = $this->function;

        return Match::on(Option::create((bool)$f($value, $this->messenger), false))
            ->Monad_Option_Some(true)
            ->Monad_Option_None(
                function () {
                    $this->messenger->add($this->msg);
                    return false;
                }
            )
            ->value();
    }
}
