<?php
/**
 * Chippyash/validation
 *
 * Validation
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2015, UK
 *
 * @link http://php.net/manual/en/functions.anonymous.php
 */
namespace Chippyash\Validation;

use Chippyash\Validation\Exceptions\ValidationException;

/**
 * Create your validators from this
 *
 * Multi Validator capability using anonymous functions
 */
class ValidationProcessor
{
    /**
     * Validation functions
     * @var array
     */
    protected $func = [];

   /**
    * Construct with initial validation function
    *
    * @param \callable $func
    * @throws ValidationException
    */
   public function __construct($func)
   {
       $this->messenger = new Messenger();
       $this->add($func);
   }

   /**
    * Add a validator
    * @param \callable $func
    * @return $this Fluent Interface
    * @throws ValidationException
    */
   public function add($func)
   {
       ValidationException::assert(
            function() use ($func)
            {
                 return !is_callable($func);
            },
            'function is not callable'
       );
       $this->func[] = $func;
       return $this;
   }

   /**
    * Validate
    *
    * @param mixed $value
    * @return boolean
    */
   public function validate($value)
   {
       $messenger = $this->messenger->clear();
       return array_reduce(
           $this->func,
           function(&$result, $func) use($value, $messenger) {
               return $result && $func($value, $messenger);
           },
           true
       );
   }

   /**
     * Get messages resulting from the last validation
     *
     * @return Messenger
     */
    public function getMessenger()
    {
        return $this->messenger;
    }
}
