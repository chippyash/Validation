<?php

declare(strict_types=1);

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

/**
 * A utility class for saving and retrieving messages
 */
class Messenger
{
    /**
     * Message store
     *
     * @var array of String
     */
    protected $messages = [];

    /**
     *
     * @param String $msg
     * @return $this Fluent Interface
     */
    public function add(string $msg)
    {
        $this->messages[] = $msg;

        return $this;
    }

    /**
     * Return message array
     *
     * @return array of String
     */
    public function get()
    {
        return $this->messages;
    }

    /**
     * Do we have a particular message?
     *
     * @param  String $msg
     * @return boolean
     */
    public function has(string $msg)
    {
        return in_array($msg, $this->messages);
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
        return implode($separator, $this->messages);
    }

    /**
     * Clear the message store
     *
     * @return $this Fluent Interface
     */
    public function clear()
    {
        unset($this->messages);
        $this->messages = [];

        return $this;
    }
}
