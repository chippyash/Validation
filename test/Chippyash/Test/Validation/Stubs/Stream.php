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

namespace Chippyash\Test\Validation\Stubs;

/**
 * A stream stub (don't use vfsStream when all you need is stream of some description)
 *
 * @link http://stackoverflow.com/questions/11700397/trying-to-test-filesystem-operations-with-vfsstream
 * @link http://php.net/manual/en/function.stream-wrapper-register.php
 * Usage
 * @see /Chippyash/Test/Validation/Pattern/hasTypeMapTest.php
 */
class Stream {

    public $context;
    public static $position = 0;
    public static $body = '';

    public function stream_open($path, $mode, $options, &$opened_path) {
        return true;
    }

    public function stream_read($bytes) {
        $chunk = substr(static::$body, static::$position, $bytes);
        static::$position += strlen($chunk);
        return $chunk;
    }

    public function stream_write($data) {
        return strlen($data);
    }

    public function stream_eof() {
        return static::$position >= strlen(static::$body);
    }

    public function stream_tell() {
        return static::$position;
    }

    public function stream_close() {
        return null;
    }
}

