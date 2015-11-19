<?php

namespace Chippyash\Test\Validation\Common;

use Chippyash\Validation\Common\Netmask;
use Chippyash\Validation\Messenger;

class NetmaskTest extends \PHPUnit_Framework_TestCase
{
    /**
     * This overide of the run method is required to run the test in isolation.
     * I have no idea why, and whilst the test runs absolutely fine when run
     * on local vm, by itself, when run as part of suite it borks
     * if not in isolation!
     */
    public function run(\PHPUnit_Framework_TestResult $result = NULL)
    {
        //mock the current incoming ip address
        $_SERVER['REMOTE_ADDR'] = '192.168.10.167';
        $this->setPreserveGlobalState(false);
        return parent::run($result);
    }


    /**
     * @dataProvider ipAndCidr
     */
    public function testWillReturnCorrectResponseForTestDataSetViaMagicInvokeMethod($ip, $cidr, $result, $message = null)
    {
        $sut = new Netmask($cidr);
        $messenger = new Messenger();
        $this->assertEquals($result, $sut($ip, $messenger));
        if (!$result) {
            $this->assertEquals($message, $messenger->implode());
        }
    }

    /**
     * @dataProvider ipAndCidr
     */
    public function testWillReturnCorrectResponseForTestDataSetViaIsValidMethod($ip, $cidr, $result, $message = null)
    {
        $sut = new Netmask($cidr);
        $this->assertEquals($result, $sut->isValid($ip));
        if (!$result) {
            $this->assertEquals($message, implode($sut->getMessages()));
        }
    }

    /**
     * Test data
     * NB - The Netmask validator makes use of IpUtil::cidrMatch, therefore
     * these tests only concentrate on ensuring that we can pass in a single or
     * multiple cidrs to the validator constructor.
     *
     * @return array
     */
    public function ipAndCidr()
    {
        return array(
            array('127.0.0.0', array('0.0.0.0/0'), true),
            array(null, array('0.0.0.0/0'), true),
            array('128.0.0.0', array('0.0.0.0/1'), false, Netmask::ERR_MSG1),
            array('127.0.0.0', '0.0.0.0/0', true),
            array('1.1.1.1', array('0.0.0.0/1','0.0.0.0/2'), true),
            array('128.0.0.0', array('0.0.0.0/1','0.0.0.0/2'), false, Netmask::ERR_MSG1),
            array('noip', array('0.0.0.0/0'), false, Netmask::ERR_MSG1),
            array('127.0.0.1', 'foobar', false, Netmask::ERR_MSG2),
        );
    }

}
