<?php
namespace Chippyash\Test\Validation\Util;

use Chippyash\Validation\Util\IpUtil;
use PHPUnit\Framework\TestCase;

class IpUtilTest extends TestCase
{
    public function testYouCanGetTheClientUserIpForHttpRequestIfAvailable()
    {
        if (isset($_SERVER['REMOTE_ADDR'])) {
            unset($_SERVER['REMOTE_ADDR']);
        }
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            unset($_SERVER['HTTP_X_FORWARDED_FOR']);
        }
        $this->assertEquals('noip', IpUtil::getUserIp());
        
        $_SERVER['REMOTE_ADDR'] = '192.168.10.1';
        $this->assertEquals('192.168.10.1', IpUtil::getUserIp());
        
        $_SERVER['HTTP_X_FORWARDED_FOR'] = null;
        $this->assertEquals('192.168.10.1', IpUtil::getUserIp());
        $_SERVER['HTTP_X_FORWARDED_FOR'] = '192.168.10.56';
        $this->assertEquals('192.168.10.56', IpUtil::getUserIp());
}

    /**
     * @dataProvider ipNumbers
     */
    public function testIsValidIPReturnsBoolean($ip, $result)
    {
        $this->assertEquals($result, IpUtil::isValidIP($ip));
    }

    /**
     * @dataProvider cidrMasks
     */
    public function testIsValidCidrReturnsBoolean($cidr, $result)
    {
        $this->assertEquals($result, IpUtil::isValidCidr($cidr));
    }

    /**
     * @dataProvider cidrMatches
     */
    public function testCidrMatchWillReturnBooleanForValidInputs($ip, $cidr, $result)
    {
        $this->assertEquals($result, IpUtil::cidrMatch($ip, $cidr));
    }

    public function testCidrMatchWillThrowExceptionForInvalidIp()
    {
        $this->expectException(\Chippyash\Validation\Exceptions\ValidationException::class);
        IpUtil::cidrMatch('foo', '255.255.255.255/32');
    }

    public function testCidrMatchWillThrowExceptionForInvalidCidr()
    {
        $this->expectException(\Chippyash\Validation\Exceptions\ValidationException::class);
        IpUtil::cidrMatch('192.168.10.1', 'foo');
    }

    /**
     * Data provider
     * @return array
     */
    public function ipNumbers()
    {
        return array(
            //decimal
            array('192.168.52.0', true),
            //octal
            array('a3.b0.c9.00', true),
            //binary
            array('11011111.11011111.11101111.11111011', true),
            //out of range
            array('600.168.52.0', false),
            //blank
            array('', false),
        );
    }

    /**
     * Data provider
     * @return array
     */
    public function cidrMasks()
    {
        return array(
            array('255.255.255.255/32', true),
            array('255.255.255.255/30', true),
            array('255.255.255.255/29', true),
            array('255.255.255.255/28', true),
            array('255.255.255.255/27', true),
            array('255.255.255.255/26', true),
            array('255.255.255.255/25', true),
            array('255.255.255.255/24', true),
            array('255.255.255.255/23', true),
            array('255.255.255.255/22', true),
            array('255.255.255.255/21', true),
            array('255.255.255.255/20', true),
            array('255.255.255.255/19', true),
            array('255.255.255.255/18', true),
            array('255.255.255.255/17', true),
            array('255.255.255.255/16', true),
            array('255.255.255.255/15', true),
            array('255.255.255.255/14', true),
            array('255.255.255.255/13', true),
            array('255.255.255.255/12', true),
            array('255.255.255.255/11', true),
            array('255.255.255.255/10', true),
            array('255.255.255.255/9', true),
            array('255.255.255.255/8', true),
            array('255.255.255.255/7', true),
            array('255.255.255.255/6', true),
            array('255.255.255.255/5', true),
            array('255.255.255.255/4', true),
            array('255.255.255.255/3', true),
            array('255.255.255.255/2', true),
            array('255.255.255.255/1', true),
            array('255.255.255.255/0', true),
            array('255.255.255.255/', true), //same as /0
            array('255.255.255.255/31', false), //ineligible mask
            array('255.255.255.255/33', false), //out of range
            array('255.255.255.255/-1', false), //out of range
            array('255.255.255.255', false), //no bits
            array('600.255.255.255/24', false), //invalid ip
        );
    }

    public function cidrMatches()
    {
        return array(
            array('192.168.10.1', '192.168.10.1/32', true),
            array('193.168.10.1', '192.168.10.1/32', false),
            array('192.168.10.0', '192.168.10.1/32', false),
            array('255.255.254.0', '255.255.254.0/23', true),
            array('255.255.255.0', '255.255.254.0/23', true),
            array('255.255.255.128', '255.255.254.0/23', true),
            array('255.255.253.128', '255.255.254.0/23', false),
            array('10.0.0.0', '10.0.0.0/8', true),
            array('10.190.0.0', '10.0.0.0/8', true),
            array('10.190.52.0', '10.0.0.0/8', true),
            array('10.190.52.96', '10.0.0.0/8', true),
            array('11.0.52.96', '10.0.0.0/8', false),
        );
    }
}
