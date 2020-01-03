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

use Laminas\Validator\ValidatorInterface;
use Monad\Match;
use Monad\Option;

/**
 * Chippyash Validator that wraps a Laminas Validator
 */
class ZFValidator extends AbstractValidator
{
    /**
     *
     * @var \Laminas\Validator\ValidatorInterface
     */
    protected $validator;

    /**
     * Constructor
     *
     * @param \Laminas\Validator\ValidatorInterface $validator
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
                        function ($msg): void {
                            $this->messenger->add($msg);
                        }
                    );
                    return false;
                }
            )
            ->value();
    }
}
