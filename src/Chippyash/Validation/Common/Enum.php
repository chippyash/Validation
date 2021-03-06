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

use Monad\Match;
use Monad\Option;

/**
 * Validator for a value being one of set of values (enumeration)
 */
class Enum extends AbstractValidator
{
    public const ERR_MSG = 'Value is not a valid enumeration';

    /**
     * Enumeration values
     * @var array
     */
    protected $enum = [];

    /**
     * Constructor
     *
     * @param array $enum of enum values to test against
     */
    public function __construct(array $enum)
    {
        $this->enum = $enum;
    }

    /**
     * Do the validation
     *
     * @param  mixed $value
     * @return boolean
     */
    protected function validate($value)
    {
        return Match::on(Option::create(in_array($value, $this->enum), false))
            ->Monad_Option_Some(true)
            ->Monad_Option_None(
                function () {
                    $this->messenger->add(self::ERR_MSG);
                    return false;
                }
            )
            ->value();
    }
}
