<?php

declare(strict_types=1);

/**
 * Chippyash/validation
 *
 * Functional validation
 *
 * @author    Ashley Kitson
 * @copyright Ashley Kitson, 2015, UK
 */
namespace Chippyash\Validation\Pattern;

use Chippyash\Validation\Common\IsTraversable;
use Chippyash\Validation\Exceptions\ValidationException;

/**
 * Test for a repeatable object.
 * Apply the supplied validator to each item in the traversable value.
 * Test is applied and fails at the first invalid item
 */
class Repeater extends IsTraversable
{
    public const ERR_MIN = "value has less than minimum number of entries";
    public const ERR_MAX = "value has more than maximum number of entries";
    public const ERR_ITEM = "value item#%s failed repeatable validation";

    /**
     * Minimum number of elements required in the repeatable value to be tested
     *
     * @var int
     */
    protected $min = 0;

    /**
     * Maximum number of elements required in the repeatable value to be tested
     * Value == -1 means no maximum
     *
     * @var int
     */
    protected $max = -1;

    /**
     * @var ValidatorPatternInterface
     */
    protected $validator;

    /**
     * Constructor
     *
     * @param ValidatorPatternInterface $validator
     * @param int|null              $min
     * @param int|null              $max
     *
     * @throws ValidationException
     */
    public function __construct(ValidatorPatternInterface $validator, ?int $min = null, ?int $max = null)
    {
        if (!is_null($min)) {
            $this->min = $min;
        }

        if (!is_null($max)) {
            $this->max = $max;
        }

        if ($this->max !== -1 && ($this->max < $this->min)) {
            throw new ValidationException('max|min');
        }

        $this->validator = $validator;
    }

    /**
     * Do the validation
     *
     * @param  \Traversable|array $value
     * @return boolean
     */
    protected function validate($value)
    {
        if (!parent::validate($value)) {
            return false;
        }

        if (count($value) < $this->min) {
            $this->messenger->add(self::ERR_MIN);
            return false;
        }

        if ($this->max !== -1 && (count($value) > $this->max)) {
            $this->messenger->add(self::ERR_MAX);
            return false;
        }

        $validator = $this->validator;
        foreach ($value as $k => $v) {
            if (!$validator($v, $this->messenger)) {
                $this->messenger->add(sprintf(self::ERR_ITEM, (string)$k));
                return false;
            }
        }

        return true;
    }
}
