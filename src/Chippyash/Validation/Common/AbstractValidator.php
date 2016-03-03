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

use Chippyash\Type\String\StringType;
use Chippyash\Validation\Pattern\ValidatorPatternInterface;
use Chippyash\Validation\Messenger;

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
     * @param mixed $value
     * @param Messenger $messenger
     * @return boolean True if value is valid else false
     */
    public function __invoke($value, Messenger $messenger)
    {
        $this->messenger = $messenger;
        return $this->validate($value);
    }

    /**
     * isValid interface for Zend\Validator\ValidatorInterface
     *
     * @param string $value
     * @return boolean
     */
    public function isValid($value)
    {
        $this->messenger = new Messenger();
        return $this->validate($value);
    }

    /**
     * getMessages interface for Zend\Validator\ValidatorInterface
     *
     * @return array
     */
    public function getMessages()
    {
        return array_map(
                function(StringType $msg) {return $msg();},
                $this->messenger->get()
        );
    }

    /**
     * Do the validation
     *
     * @param mixed $value
     * @return boolean
     */
    abstract protected function validate($value);
}


