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
 * Validator for an array value
 *
 * Can be float or string
 */
class IsArray extends AbstractValidator
{
    public const ERR_INVALID = 'value is not an array';
    public const ERR_ARRAY_EMPTY = 'array is empty';

    protected $checkForEmpty = false;

    /**
     * If checkForEmpty == true, validation will return false if array is empty
     *
     * @param boolean $checkForEmpty
     */
    public function __construct($checkForEmpty = false)
    {
        $this->checkForEmpty = $checkForEmpty;
    }

    /**
     * Do the validation
     *
     * @param  mixed $value
     * @return boolean
     */
    protected function validate($value)
    {
        return Match::on(Option::create(is_array($value), false))
            ->Monad_Option_Some(
                function () use ($value) {
                    return Match::on(Option::create($this->checkForEmpty && empty($value), false))
                    ->Monad_Option_Some(
                        function () {
                            $this->messenger->add(self::ERR_ARRAY_EMPTY);
                            return false;
                        }
                    )
                    ->Monad_Option_None(true)
                    ->value();
                }
            )
            ->Monad_Option_None(
                function () {
                    $this->messenger->add(self::ERR_INVALID);
                    return false;
                }
            )
            ->value();
    }
}
