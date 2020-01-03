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
 * Validator that accepts two validators and
 * returns value of v1 AND v2
 */
class LAnd extends AbstractValidator
{
    /**
     * First validator
     * @var ValidatorPatternInterface
     */
    protected $validator1;
    /**
     * Second validator
     * @var ValidatorPatternInterface
     */
    protected $validator2;

    public function __construct(
        ValidatorPatternInterface $validator1,
        ValidatorPatternInterface $validator2
    ) {
        $this->validator1 = $validator1;
        $this->validator2 = $validator2;
    }

    /**
     * Do the validation
     *
     * @param  mixed $value
     * @return boolean
     */
    protected function validate($value)
    {
        $v1 = $this->validator1;
        $v2 = $this->validator2;

        return $v1($value, $this->messenger) && $v2($value, $this->messenger);
    }
}
