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

/**
 * Validate input is an array with the specified key
 */
class ArrayKeyExists extends AbstractValidator
{
    /**@+
     * Error messages
     */
    public const ERR1 = 'value is not an array';
    public const ERR2 = 'key %s does not exist in array';
    /**@-*/

    /**
     * @var string
     */
    protected $keyName;

    /**
     * ArrayKeyExists constructor.
     * @param string $keyName
     */
    public function __construct($keyName)
    {
        $this->keyName = $keyName;
    }

    /**
     * @inheritDoc
     */
    protected function validate($value)
    {
        if (!is_array($value)) {
            $this->messenger->add(self::ERR1);
            return false;
        }
        if (!array_key_exists($this->keyName, $value)) {
            $this->messenger->add(sprintf(self::ERR2, $this->keyName));
            return false;
        }

        return true;
    }
}
