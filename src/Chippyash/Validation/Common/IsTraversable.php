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

/**
 * Test for a traversable object
 */
class IsTraversable extends AbstractValidator
{
    /**
     * Do the validation
     *
     * @param  mixed $value
     * @return boolean
     */
    protected function validate($value)
    {
        $ret = ($value instanceof \Traversable ||
            $value instanceof \stdClass ||
            is_array($value));
        if (!$ret) {
            $this->messenger->add('value is not traversable');
        }

        return $ret;
    }
}
