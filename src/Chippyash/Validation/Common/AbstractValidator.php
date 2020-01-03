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

use Chippyash\Validation\Messenger;
use Chippyash\Validation\Pattern\ValidatorPatternInterface;

abstract class AbstractValidator implements ValidatorPatternInterface
{
    /**
     * Message store
     * @var Messenger
     */
    protected $messenger;

    /**
     * Invokable interface for ValidatorPatternInterface
     *
     * @param  mixed     $value
     * @param  Messenger $messenger
     * @return boolean True if value is valid else false
     */
    public function __invoke($value, Messenger $messenger)
    {
        $this->messenger = $messenger;
        return $this->validate($value);
    }

    /**
     * Do the validation
     *
     * @param  mixed $value
     * @return boolean
     */
    abstract protected function validate($value);

    /**
     * isValid interface for Laminas\Validator\ValidatorInterface
     *
     * @param  string $value
     * @return boolean
     */
    public function isValid($value)
    {
        $this->messenger = new Messenger();
        return $this->validate($value);
    }

    /**
     * getMessages interface for Laminas\Validator\ValidatorInterface
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->messenger->get();
    }
}
