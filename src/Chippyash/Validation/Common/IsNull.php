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
 * @copyright Ashley Kitson, 2020, UK
 */

namespace Chippyash\Validation\Common;

/**
 * Test for a null value
 */
class IsNull extends AbstractValidator
{
    /**
     * Do the validation
     *
     * @param  mixed $value
     * @return boolean
     */
    protected function validate($value)
    {
        $ret = is_null($value);
        if (!$ret) {
            $this->messenger->add('value is not null');
        }

        return $ret;
    }
}
