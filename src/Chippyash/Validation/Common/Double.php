<?php
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

use Chippyash\Type\String\StringType;
use Monad\Match;
use Monad\Option;

/**
 * Validator to check for a double
 */
class Double extends AbstractValidator
{
    const ERR_MSG = 'Value is not a Double format';

    const REGEX_DOUBLE = '/^[+-]?(([0-9]+)|([0-9]*\.[0-9]+|[0-9]+\.[0-9]*)|(([0-9]+|([0-9]*\.[0-9]+|[0-9]+\.[0-9]*))[eE][+-]?[0-9]+))$/';

    /**
     * Do the validation
     *
     * @param  mixed $value
     * @return boolean
     */
    protected function validate($value)
    {
        return Match::on(Option::create(preg_match(self::REGEX_DOUBLE, $value), 0))
            ->Monad_Option_Some(true)
            ->Monad_Option_None(
                function () {
                    $this->messenger->add(new StringType(self::ERR_MSG));
                    return false;
                }
            )
            ->value();
    }
}
