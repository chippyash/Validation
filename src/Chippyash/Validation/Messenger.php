<?php
/**
 * Chippyash/validation
 *
 * Validation
 *
 * @author    Ashley Kitson
 * @copyright Ashley Kitson, 2015, UK
 *
 * @link http://php.net/manual/en/functions.anonymous.php
 */
namespace Chippyash\Validation;

use Chippyash\Type\String\StringType;

/**
 * A utility class for saving and retrieving messages
 */
class Messenger
{
    /**
     * Message store
     *
     * @var array of StringType
     */
    protected $messages = array();

    /**
     *
     * @param StringType $msg
     * @return $this Fluent Interface
     */
    public function add(StringType $msg)
    {
        $this->messages[] = $msg;

        return $this;
    }

    /**
     * Return message array
     *
     * @return array of StringType
     */
    public function get()
    {
        return $this->messages;
    }

    /**
     * Do we have a particular message?
     *
     * @param  StringType $msg
     * @return boolean
     */
    public function has(StringType $msg)
    {
        return in_array($msg(), $this->messages);
    }

    /**
     * Returns all messages separated by ' : '
     *
     * @return string
     */

    public function __toString()
    {
        return $this->implode();
    }

    /**
     * Return string containing imploded message array
     *
     * @param  string $separator
     * @return string
     */
    public function implode($separator = ' : ')
    {
        $m = array_map(
            function (StringType $msg) {
                return $msg();
            },
            $this->messages
        );

        return implode($separator, $m);
    }

    /**
     * Clear the message store
     *
     * @return $this Fluent Interface
     */
    public function clear()
    {
        unset($this->messages);
        $this->messages = array();

        return $this;
    }
}
