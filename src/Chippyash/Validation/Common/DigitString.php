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
 * @copyright Ashley Kitson, 2017, UK
 * @license GPL V3+
 */
namespace Chippyash\Validation\Common;

use Laminas\Validator\Digits;

/**
 * Validate input is a DigitString
 */
class DigitString extends ZFValidator
{
    public function __construct()
    {
        parent::__construct(new Digits());
    }
}
