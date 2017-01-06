<?php
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

use Chippyash\Type\String\StringType;

/**
 * Validate a specific key in an array with another validator
 */
class ArrayPart extends AbstractValidator
{
    /**@+
     * Error messages
     */
    const ERR1 = 'value is not an array';
    const ERR2 = 'key %s does not exist in array';
    const ERR3 = 'key %s does not pass validation';
    /**@-*/

    /**
     * @var string
     */
    protected $keyName;
    /**
     * @var AbstractValidator
     */
    protected $keyValidator;

    /**
     * ArrayPart constructor.
     * @param string $keyName
     * @param AbstractValidator $keyValidator
     */
    public function __construct($keyName, AbstractValidator $keyValidator)
    {
        $this->keyName = $keyName;
        $this->keyValidator = $keyValidator;
    }

    /**
     * @inheritDoc
     */
    protected function validate($value)
    {
        if (!is_array($value)) {
            $this->messenger->add(new StringType(self::ERR1));
            return false;
        }
        if (!array_key_exists($this->keyName, $value)) {
            $this->messenger->add(new StringType(sprintf(self::ERR2, $this->keyName)));
            return false;
        }

        if (!$this->keyValidator->isValid($value[$this->keyName])) {
            $this->messenger->add(new StringType(sprintf(self::ERR3, $this->keyName)));
            $msgs = $this->keyValidator->getMessages();
            array_walk($msgs, function($msg) {
                $this->messenger->add(new StringType($msg));
            });
            return false;
        }

        return true;
    }
}