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
 */

namespace Chippyash\Validation\Util;

use Chippyash\Validation\Exceptions\InvalidParameterException;
use Laminas\Validator\Ip as ZendIp;

/**
 * IP utilities
 */
class IpUtil
{
    /**
     * Return user's ip address
     * NB - HTTP_X_FORWARDED_FOR can be spoofed - you have been warned
     *
     * @return string
     */
    public static function getUserIp()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }

        return 'noip';
    }

    /**
     * Checks if an ip is in a cidr address range
     * e.g. 10.0.0.0/8 Class A mask
     *
     * @param string $ipAddr   IP address
     * @param string $cidr CIDR notation ip range mask
     *
     * @return boolean True if matched else false
     *
     * @link   http://www.oav.net/mirrors/cidr.html
     * @throws InvalidParameterException
     */
    public static function cidrMatch(string $ipAddr, string $cidr)
    {
        if (!self::isValidIP($ipAddr)) {
            throw new InvalidParameterException('ip');
        }
        if (!self::isValidCidr($cidr)) {
            throw new InvalidParameterException('cidr');
        }
        if (PHP_INT_SIZE == 4) {
            // @codeCoverageIgnoreStart
            return self::cidrMatch32bit($ipAddr, $cidr);
            // @codeCoverageIgnoreEnd
        }
         
        return self::cidrMatch64bit($ipAddr, $cidr);
    }

    /**
     * Check if string is a valid ip address
     *
     * @param  string  $ipAddr
     * @param  boolean $allowipv6
     * @return boolean
     */
    public static function isValidIP($ipAddr, $allowipv6 = false)
    {
        $validator = new ZendIp(['allowipv6' => $allowipv6]);
        return $validator->isValid($ipAddr);
    }

    /**
     * Check if string is a valid cidr mask
     * example cidr 10.24.3.0/24
     *
     * @param  string $cidr
     * @return boolean
     */
    public static function isValidCidr($cidr)
    {
        $parts = explode('/', $cidr);
        $parts[0] = (isset($parts[0]) ? (string)$parts[0] : '');
        $parts[1] = (isset($parts[1]) ? intval($parts[1]) : '');
        $ipAddr = (!empty($parts[0]) ? $parts[0] : false);
        $bits = ($parts[1] === '' ? false : $parts[1]);
        if ($ipAddr === false || $bits === false) {
            return false;
        }

        return self::isValidIP($ipAddr) &&
        ($bits > -1 && $bits < 33 && $bits != 31);
    }

    /**
     * 32 bit processor CIDR match
     *
     * @codeCoverageIgnore
     *
     * @link http://stackoverflow.com/questions/594112/matching-an-ip-to-a-cidr-mask-in-php5
     *
     * @param  string $ipAddr
     * @param  string $cidr
     * @return boolean
     */
    protected static function cidrMatch32bit($ipAddr, $cidr)
    {
        [$subNet, $bits] = explode('/', $cidr);
        $ipLong = \ip2long($ipAddr);
        $subnetLong = \ip2long($subNet);
        $mask = -1 << (32 - $bits);
        $subnetLong &= $mask; // nb: in case the supplied subnet wasn't correctly aligned
        return ($ipLong & $mask) == $subnetLong;
    }

    /**
     * 64 bit processor CIDR match
     *
     * @link http://stackoverflow.com/questions/594112/matching-an-ip-to-a-cidr-mask-in-php5
     *
     * @param  string $ipAddr
     * @param  string $cidr
     * @return boolean
     */
    protected static function cidrMatch64bit($ipAddr, $cidr)
    {
        [$sn, $bits] = explode('/', $cidr);
        $ipLong = self::ip2long64($ipAddr, $bits);
        $subnet = self::ip2long64($sn, $bits);
        $mask = -1 << (32 - $bits);

        return ($ipLong & $mask) == $subnet;
    }

    /**
     * 64 bit ip2long function compacted to 32 bits
     *
     * @param  string $ipAddr
     * @param  int    $bits
     * @return int
     */
    protected static function ip2long64($ipAddr, $bits)
    {
        return -1 << (32 - $bits) & \ip2long($ipAddr);
    }
}
