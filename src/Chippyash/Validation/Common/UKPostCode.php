<?php
/**
 * chippyash/validation
 *
 * Functional validation
 *
 * Common validations
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2015, UK
 *
 * @link http://php.net/manual/en/functions.anonymous.php
 */

namespace Chippyash\Validation\Common;

if (!class_exists('Zend\I18n\Validator\PostCode')) {
    throw new \Exception('Please install zendframework/zend-i18n to use UKPostCode');
}

use Zend\I18n\Validator\PostCode;

/**
 * Validator for a UK Post Code
 * This will validate post codes with or without spaces in the code
 */
class UKPostCode extends ZFValidator
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(new PostCode(array('locale'=>'en-GB')));
    }
}

