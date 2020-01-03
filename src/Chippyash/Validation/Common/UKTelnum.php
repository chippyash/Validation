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

if (!class_exists('Laminas\I18n\Validator\PhoneNumber')) {
    throw new \Exception('Please install zendframework/zend-i18n to use UKTelNum');
}

use Laminas\I18n\Validator\PhoneNumber;

/**
 * Validator for UK mobile and landline telephone numbers
 *
 * Can be float or string
 */
class UKTelnum extends ZFValidator
{
    public function __construct()
    {
        parent::__construct(
            new PhoneNumber(
                [
                    'country' => 'GB',
                    'allowed_types' => ['general', 'fixed', 'personal', 'mobile']
                ]
            )
        );
    }

    /**
     * Strip leading zeros, plus signs and spaces from telnum string
     * as Laminas validator wants the stripped down string
     *
     * @param  mixed $value
     * @return boolean
     */
    protected function validate($value)
    {
        return parent::validate(str_replace(' ', '', ltrim((string)$value, '0+')));
    }
}
