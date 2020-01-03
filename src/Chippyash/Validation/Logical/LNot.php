<?php

declare(strict_types=1);

/**
 * Chippyash/validation
 *
 * Functional validation
 *
 * Logic validations
 *
 * @author    Ashley Kitson
 * @copyright Ashley Kitson, 2015, UK
 */

namespace Chippyash\Validation\Logical;

use Chippyash\Validation\Common\AbstractValidator;
use Chippyash\Validation\Pattern\ValidatorPatternInterface;

/**
 * Validator that accepts one validator
 * returns !validator
 */
class LNot extends AbstractValidator
{
    /**
     * Validator
     * @var ValidatorPatternInterface
     */
    protected $validator;

    public function __construct(ValidatorPatternInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Do the validation
     *
     * @param  mixed $value
     * @return boolean
     */
    protected function validate($value)
    {
        $v1 = $this->validator;

        return !$v1($value, $this->messenger);
    }
}
