<?php
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

use Chippyash\Type\String\StringType;
use Monad\Match;
use Monad\Option;
use Zend\Validator\ValidatorInterface;

/**
 * Chippyash Validator that wraps a Zend Validator
 */
class ZFValidator extends AbstractValidator
{
    /**
     *
     * @var \Zend\Validator\ValidatorInterface
     */
    protected $validator;

    /**
     * Constructor
     *
     * @param \Zend\Validator\ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Do the validation
     *
     * @param  mixed $value
     * @return boolean
     */
    protected function validate($value)
    {
        return Match::on(Option::create($this->validator->isValid($value), false))
            ->Monad_Option_Some(true)
            ->Monad_Option_None(
                function () {
                    $msgs = $this->validator->getMessages();
                    array_walk(
                        $msgs, 
                        function ($msg) {
                            $this->messenger->add(new StringType($msg));
                        }
                    );
                    return false;
                }
            )
            ->value();
    }
}
