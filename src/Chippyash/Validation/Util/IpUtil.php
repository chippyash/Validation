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
 */

namespace Chippyash\Validation\Util;


use Zend\Validator\Ip as ZendIp;
use Chippyash\Validation\Exceptions\InvalidParameterException;

/**
 * IP utilities
 */
class IpUtil {

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
        } elseif(isset ($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        } else {
            return 'noip';
        }
    }

    /**
     * Checks if an ip is in a cidr address range
     * e.g. 10.0.0.0/8 Class A mask
     *
     * @param string $ip IP address
     * @param string $cidr CIDR notation ip range mask
     *
     * @return boolean True if matched else false
     *
     * @link http://www.oav.net/mirrors/cidr.html
     * @throws InvalidParameterException
     */
    public static function cidrMatch($ip, $cidr)
    {
        if (!self::isValidIP($ip)) {
            throw new InvalidParameterException('ip');
        }
        if (!self::isValidCidr($cidr)) {
            throw new InvalidParameterException('cidr');
        }
        if (PHP_INT_SIZE == 4) {
            // @codeCoverageIgnoreStart
            return self::cidrMatch32bit($ip, $cidr);
            // @codeCoverageIgnoreEnd
        } else {
            return self::cidrMatch64bit($ip, $cidr);
        }
    }

    /**
     * Check if string is a valid ip address
     *
     * @param string $ip
     * @param boolean $allowipv6
     * @return boolean
     */
    public static function isValidIP($ip, $allowipv6 = false)
    {
        $validator = new ZendIp(array('allowipv6' => $allowipv6));
        return $validator->isValid($ip);
    }

    /**
     * Check if string is a valid cidr mask
     * example cidr 10.24.3.0/24
     *
     * @param string $cidr
     * @return boolean
     */
    public static function isValidCidr($cidr)
    {
        $parts = explode('/', $cidr);
        $parts[0] = (isset($parts[0]) ? (string) $parts[0] : '');
        $parts[1] = (isset($parts[1]) ? intval($parts[1]) : '');
        $ip = (!empty($parts[0]) ? $parts[0] : false);
        $bits = ($parts[1] === '' ? false : $parts[1]);
        if ($ip===false || $bits===false) {
            return false;
        }

        return self::isValidIP($ip) &&
                ($bits > -1 && $bits < 33 && $bits != 31);
    }

    /**
     * 32 bit processor CIDR match
     *
     * @codeCoverageIgnore
     *
     *
     * @link http://stackoverflow.com/questions/594112/matching-an-ip-to-a-cidr-mask-in-php5
     *
     * @param string $ip
     * @param string $cidr
     * @return boolean
     */
    protected static function cidrMatch32bit($ip, $cidr)
    {
        list ($sn, $bits) = explode('/', $cidr);
        $ip = \ip2long($ip);
        $subnet = \ip2long($sn);
        $mask = -1 << (32 - $bits);
        $subnet &= $mask; # nb: in case the supplied subnet wasn't correctly aligned
        return ($ip & $mask) == $subnet;
    }

    /**
     * 64 bit processor CIDR match
     *
     * @link http://stackoverflow.com/questions/594112/matching-an-ip-to-a-cidr-mask-in-php5
     *
     * @param string $ip
     * @param string $cidr
     * @return boolean
     */
    protected static function cidrMatch64bit($ip, $cidr)
    {
        list ($sn, $bits) = explode('/', $cidr);
        $ip = self::ip2long64($ip, $bits);
        $subnet = self::ip2long64($sn, $bits);
        $mask = -1 << (32 - $bits);

        return ($ip & $mask) == $subnet;
    }

    /**
     * 64 bit ip2long function compacted to 32 bits
     *
     * @param string $ip
     * @param int $bits
     * @return int
     */
    protected static function ip2long64($ip, $bits) {
        return (-1 << (32 - $bits)) & \ip2long($ip);
    }
}
