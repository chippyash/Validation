<?php
/**
 * Chippyash/validation
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

use Zend\Validator\EmailAddress;

/**
 * Validator for an email address.
 *
 * Does basic validation only.  If you want additional functionality use the
 * Chippyash\Validation\Common\ZFValidator validator, passing in your own
 * configured Zend\Validator\EmailAddress
 */
class Email extends ZFValidator
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(new EmailAddress());
    }
}

