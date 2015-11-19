<?php
/**
 * ISO 8601 DateString validator test
 */
namespace Chippyash\Test\Validation\Common;

use Chippyash\Validation\Messenger;
use chippyash\Type\Number\IntType;
use Chippyash\Validation\Common\ISO8601\Constants as C;
use Chippyash\Validation\Common\ISO8601DateString;

/**
 * Component test for ISO8601DateString which comprises:
 *  - Chippyash\Validation\CommonISO8601DateString
 *  - Chippyash\Validation\Common\ISO8601\Constants
 *  - Chippyash\Validation\Common\ISO8601\MatchDate
 *  - Chippyash\Validation\Common\ISO8601\SplitDate
 */
class ISO8601DateStringTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructingWithANullFormatWillDefaultToExtendedFormat()
    {
        $sut = new ISO8601DateString();
        $refl = new \ReflectionObject($sut);
        $param = $refl->getProperty('format');
        $param->setAccessible(true);
        $this->assertEquals(C::FORMAT_EXTENDED, $param->getValue($sut));
    }

    public function testConstructingWithANoneFormatWillDefaultToExtendedFormat()
    {
        $sut = new ISO8601DateString(new IntType(C::FORMAT_NONE));
        $refl = new \ReflectionObject($sut);
        $param = $refl->getProperty('format');
        $param->setAccessible(true);
        $this->assertEquals(C::FORMAT_EXTENDED, $param->getValue($sut));
    }

    /**
     * @expectedException \Chippyash\Validation\Exceptions\ValidationException
     * @expectedExceptionMessage Invalid or missing parameter: numSignedDigits
     */
    public function testConstructingBasicDateSignedWithNoSecondParamWillThrowAnException()
    {
        $object = new ISO8601DateString(
                new IntType(C::FORMAT_BASIC_SIGNED)
                );
    }

    /**
     * @expectedException \Chippyash\Validation\Exceptions\ValidationException
     * @expectedExceptionMessage Invalid or missing parameter: numSignedDigits
     */
    public function testConstructingExtendedDateSignedWithNoSecondParamWillThrowAnException()
    {
        $object = new ISO8601DateString(
                new IntType(C::FORMAT_EXTENDED_SIGNED)
                );
    }

    /**
     * @expectedException \Chippyash\Validation\Exceptions\ValidationException
     * @expectedExceptionMessage Invalid or missing parameter: numSignedDigits: min == 4
     */
    public function testConstructingDateSignedWithSecondParamOutOfBoundsWillThrowAnException()
    {
        $object = new ISO8601DateString(
                new IntType(C::FORMAT_EXTENDED_SIGNED),
                new IntType(3)
                );
    }

    /**
     * @expectedException \Chippyash\Validation\Exceptions\ValidationException
     * @expectedExceptionMessage Invalid or missing parameter: format: Enforcing zone without time
     */
    public function testConstructingEnforcingZoneWithNoTimeEnforcementWillThrowAnException()
    {
        $object = new ISO8601DateString(
                new IntType(C::FORMAT_BASIC | C::ENFORCE_ZONE)
                );
    }

    /**
     * @expectedException \Chippyash\Validation\Exceptions\ValidationException
     * @expectedExceptionMessage Invalid or missing parameter: format: Lax zone without lax time
     */
    public function testConstructingLaxZoneWithNoLaxTimeWillThrowAnException()
    {
        $object = new ISO8601DateString(
                new IntType(C::FORMAT_BASIC | C::LAX_ZONE)
                );
    }

    /**
     * @dataProvider extendedDate
     */
    public function testYouCanValidateAnExtendedDate($test, $result, $msg)
    {
        $object = new ISO8601DateString(new IntType(C::FORMAT_EXTENDED));
        $messenger = new Messenger();
        $this->assertEquals($result, $object($test, $messenger));
        $this->assertEquals($msg, $messenger->implode());
    }

    public function testYouCanValidateAnUTCExtendedDate()
    {
        $test = '2013T12:03:01Z';
        $result = true;
        $msg = 'extendedYearOnly : extendedTimeHourMinSec : extendedZoneUTC';

        // Ensure the timezone is set to UTC
        $zone = \date_default_timezone_get();
        \date_default_timezone_set( 'UTC' );

        $object = new ISO8601DateString(new IntType(C::FORMAT_EXTENDED));
        $messenger = new Messenger();
        $this->assertEquals($result, $object($test, $messenger));
        $this->assertEquals($msg, $messenger->implode());

        // Reset timezone
        \date_default_timezone_set( $zone );
    }

    /**
     * @dataProvider extendedDate
     */
    public function testYouCanValidateAnExtendedDateWithLaxTime($test, $result, $msg)
    {
        $object = new ISO8601DateString(new IntType(C::FORMAT_EXTENDED | C::LAX_TIME));
        $messenger = new Messenger();
        //replace time separator with space
        $test = str_replace(array('t','T'), array(' ',' '), $test);
        $this->assertEquals($result, $object($test, $messenger));
        $this->assertEquals($msg, $messenger->implode());
    }

    /**
     * @dataProvider basicDate
     */
    public function testYouCanValidateABasicDate($test, $result, $msg)
    {
        $object = new ISO8601DateString(new IntType(C::FORMAT_BASIC));
        $messenger = new Messenger();
        $this->assertEquals($result, $object($test, $messenger));
        $this->assertEquals($msg, $messenger->implode());
    }

    public function testYouCanValidateAnUTCBasicDate()
    {
        $test = '2013T120301Z';
        $result = true;
        $msg = 'basicYearOnly : basicTimeHourMinSec : basicZoneUTC';

        // Ensure the timezone is set to UTC
        $zone = \date_default_timezone_get();
        \date_default_timezone_set( 'UTC' );

        $object = new ISO8601DateString(new IntType(C::FORMAT_BASIC));
        $messenger = new Messenger();
        $this->assertEquals($result, $object($test, $messenger));
        $this->assertEquals($msg, $messenger->implode());

        // Reset timezone
        \date_default_timezone_set( $zone );
    }

    /**
     * @dataProvider basicDate
     */
    public function testYouCanValidateABasicDateWithLaxTime($test, $result, $msg)
    {
        $object = new ISO8601DateString(new IntType(C::FORMAT_BASIC | C::LAX_TIME));
        $messenger = new Messenger();
        //replace time separator with space
        $test = str_replace(array('t','T'), array(' ',' '), $test);
        $this->assertEquals($result, $object($test, $messenger));
        $this->assertEquals($msg, $messenger->implode());
    }

    /**
     * @dataProvider signedBasicDate
     */
    public function testYouCanValidateASignedBasicDate($sign, $restOfTest, $result, $msg)
    {
        //The number of digits for a signed date is random
        //I've chosen an upper limit but it could be changed with no
        //effect on the test.  According to the ISO spec, if you use signed
        //years then it is contracted between sender and receiver
        $digits = rand(4, 24);
        $object = new ISO8601DateString(
                new IntType(C::FORMAT_BASIC_SIGNED),
                new IntType($digits)
                );
        $messenger = new Messenger();
        $test = $sign . str_pad('', $digits, '0') . $restOfTest;
        $this->assertEquals($result, $object($test, $messenger));
        $this->assertEquals($msg, $messenger->implode());

    }

    /**
     * @dataProvider signedExtendedDate
     */
    public function testYouCanValidateASignedGoodExtendedDate($sign, $restOfTest, $result, $msg)
    {
        //The number of digits for a signed date is random
        //I've chosen an upper limit but it could be changed with no
        //effect on the test.  According to the ISO spec, if you use signed
        //years then it is contracted between sender and receiver
        $digits = rand(4, 24);
        $object = new ISO8601DateString(
                new IntType(C::FORMAT_EXTENDED_SIGNED),
                new IntType($digits)
                );
        $messenger = new Messenger();
        $test = $sign . str_pad('', $digits, '0') . $restOfTest;
        $this->assertEquals($result, $object($test, $messenger));
        $this->assertEquals($msg, $messenger->implode());
    }


    public function testYouCanEnforceTimeAndZone()
    {
        $messenger = new Messenger();
        //basic - enforce time
        $format = C::FORMAT_BASIC | C::ENFORCE_TIME;
        $object = new ISO8601DateString(
                new IntType($format)
                );
        $this->assertTrue($object('1999T030415', $messenger));
        $this->assertFalse($object('1999T', $messenger)); //no time
        //extended - enforce time
        $format = C::FORMAT_EXTENDED | C::ENFORCE_TIME;
        $object = new ISO8601DateString(
                new IntType($format)
                );
        $this->assertTrue($object('1999T03:04:15', $messenger));
        $this->assertFalse($object('1999T', $messenger)); //no time

        //basic - enforce time and zone
        $format = C::FORMAT_BASIC | C::ENFORCE_TIME | C::ENFORCE_ZONE;
        $object = new ISO8601DateString(
                new IntType($format)
                );
        $this->assertTrue($object('1999T030415Z', $messenger));
        $this->assertTrue($object('1999T030415-0001', $messenger));
        $this->assertFalse($object('1999T030415-0000', $messenger)); //zero is invalid negative offset
        $this->assertTrue($object('1999T030415+0000', $messenger));
        $this->assertFalse($object('1999T030415', $messenger)); //no zone

        //extended - enforce time and zone
        $format = C::FORMAT_EXTENDED | C::ENFORCE_TIME | C::ENFORCE_ZONE;
        $object = new ISO8601DateString(
                new IntType($format)
                );
        $this->assertTrue($object('1999T03:04:15Z', $messenger));
        $this->assertTrue($object('1999T03:04:15-00:01', $messenger));
        $this->assertFalse($object('1999T03:04:15-00:00', $messenger)); //zero is invalid negative offset
        $this->assertFalse($object('1999T03:04:15', $messenger)); //no zone
    }

    /**
     * @dataProvider basicDate
     */
    public function testTheValidatorSupportsTheZendInterface($test, $result, $msg)
    {
        $object = new ISO8601DateString(new IntType(C::FORMAT_BASIC));
        $this->assertEquals($result, $object->isValid($test));
        $this->assertEquals($msg, implode(' : ', $object->getMessages()));
    }

    /**
     * @dataProvider phpBasicDate
     */
    public function testYouCanValidatePHPCompatibilityOnABasicDate($test, $result)
    {
        $object = new ISO8601DateString(new IntType(C::FORMAT_BASIC | C::CHECK_PHP_PARSEABLE));
        $this->assertEquals($result, $object->isValid($test));
        if (!$result) {
            $this->assertEquals(
                    'Datestring is valid but failed PHP compatibility',
                    implode('',$object->getMessages())
                    );
        }
    }

    /**
     * @dataProvider phpExtendedDate
     */
    public function testYouCanValidatePHPCompatibilityOnAnExtendedDate($test, $result)
    {
        $object = new ISO8601DateString(new IntType(C::FORMAT_EXTENDED | C::CHECK_PHP_PARSEABLE));
        $this->assertEquals($result, $object->isValid($test));
        if (!$result) {
            $this->assertEquals(
                    'Datestring is valid but failed PHP compatibility',
                    implode('',$object->getMessages())
                    );
        }
    }

/* DATA */
    public function extendedDate()
    {
        return array(
            //date. NB Week separator can be upper or lower case 'W'
            array('2013', true, 'extendedYearOnly'),      //year only
            array('2013-12', true, 'extendedYearMonth'),      //year month
            array('2013-12-01', true, 'extendedYearMonthDay'),      //year month day
            array('2013-W02', true, 'extendedWeek'),       //year week
            array('2013-w021', true, 'extendedWeekPlusDay'),//year week weekday
            array('2013-001', true, 'extendedOrdinal'),    //year ordinal-day
            array('2013-000', false, 'Invalid ISO8601 datestring'),  //ordinal-day < 001
            array('2013-367', false, 'Invalid ISO8601 datestring'),  //ordinal-day > 366
            array('201', false, 'Invalid ISO8601 datestring'),      //year too short
            array('2013-13-01', false, 'Invalid ISO8601 datestring'), //month > 12
            array('2013-00-01', false, 'Invalid ISO8601 datestring'), //month < 01
            array('2013-01-00', false, 'Invalid ISO8601 datestring'), //day < 01
            array('2013-01-32', false, 'Invalid ISO8601 datestring'), //day > 31
            array('2013-W00', false, 'Invalid ISO8601 datestring'), //week <01
            array('2013-W54', false, 'Invalid ISO8601 datestring'), //week >53
            array('2013-W050', false, 'Invalid ISO8601 datestring'), //weekday <1
            array('2013-w058', false, 'Invalid ISO8601 datestring'), //weekday >7
            array('', false, 'Invalid ISO8601 datestring: Date segment required'), //no date
            //datetime. NB Time separator can be upper or lower case 'T'
            array('2013T12:03:01', true, 'extendedYearOnly : extendedTimeHourMinSec'),
            array('2013t12:03', true, 'extendedYearOnly : extendedTimeHourMin'),
            array('2013T', false, 'Invalid ISO8601 datestring: Time segment specified but not found'), //no time part
            array('2013t25:03:01', false, 'Invalid ISO8601 datestring'), //hour > 24
            array('2013T24:60:03', false, 'Invalid ISO8601 datestring'), //min > 59
            array('2013t00:59:62', false, 'Invalid ISO8601 datestring'), //sec > 59
            array('2013t25:03', false, 'Invalid ISO8601 datestring'), //hour > 24
            array('2013T24:60', false, 'Invalid ISO8601 datestring'), //min > 59
            array('2013t00', false, 'Invalid ISO8601 datestring'), //hour only is not extended format
            //decimal datetime. NB decimal point can be dot or comma. Arbitrary decimal precision
            array('2013-09-15T12:03:01.0', true, 'extendedYearMonthDay : extendedDecimalTimeHourMinSec'),
            array('2013-09T12:03:01.0', true, 'extendedYearMonth : extendedDecimalTimeHourMinSec'),
            array('2013T12:03:01.0', true, 'extendedYearOnly : extendedDecimalTimeHourMinSec'),
            array('2013t12:03,123', true, 'extendedYearOnly : extendedDecimalTimeHourMin'),
            array('2013T12:03:01.', false, 'Invalid ISO8601 datestring'),   //no digits after decimal point
            array('2013t12:03.', false, 'Invalid ISO8601 datestring'),      //no digits after decimal point
            array('2013T12,034', false, 'Invalid ISO8601 datestring'),      //hour decimal is not extended format
            //datetime + zone
            array('2013T12:03:01+00', true, 'extendedYearOnly : extendedTimeHourMinSec : extendedZonePositiveHour'),
            array('2013-03-19T12:03:01+00', true, 'extendedYearMonthDay : extendedTimeHourMinSec : extendedZonePositiveHour'),
            array('2013-03T12:03:01+00', true, 'extendedYearMonth : extendedTimeHourMinSec : extendedZonePositiveHour'),
            array('2013T12:03:01+01', true, 'extendedYearOnly : extendedTimeHourMinSec : extendedZonePositiveHour'),
            array('2013T12:03:01+01:06', true, 'extendedYearOnly : extendedTimeHourMinSec : extendedZonePositiveHourMin'),
            array('2013T12:03:01+24', true, 'extendedYearOnly : extendedTimeHourMinSec : extendedZonePositiveHour'),
            array('2013T12:03:01+25', false, 'Invalid ISO8601 datestring'), //zone hour > 24
            array('2013T12:03:01-01', true, 'extendedYearOnly : extendedTimeHourMinSec : extendedZoneNegativeHour'),
            array('2013T12:03:01-24', true, 'extendedYearOnly : extendedTimeHourMinSec : extendedZoneNegativeHour'),
            array('2013T12:03:01-25', false, 'Invalid ISO8601 datestring'), //zone hour > 24
            array('2013T12:03:01-00', false, 'Invalid ISO8601 datestring'), //negative zero zone hour
        );
    }

    public function basicDate()
    {
        return array(
            //date. NB Week separator can be upper or lower case 'W'
            //there is no yearmonth for basic format
            array('2013', true, 'basicYearOnly'),      //year only
            array('20131201', true, 'basicYearMonthDay'),      //year month day
            array('2013W02', true, 'basicWeek'),       //year week
            array('2013w021', true, 'basicWeekPlusDay'),//year week weekday
            array('2013001', true, 'basicOrdinal'),    //year ordinal-day
            array('2013000', false, 'Invalid ISO8601 datestring'),  //ordinal-day < 001
            array('2013367', false, 'Invalid ISO8601 datestring'),  //ordinal-day > 366
            array('201', false, 'Invalid ISO8601 datestring'),      //year too short
            array('20131301', false, 'Invalid ISO8601 datestring'), //month > 12
            array('20130001', false, 'Invalid ISO8601 datestring'), //month < 01
            array('20130100', false, 'Invalid ISO8601 datestring'), //day < 01
            array('20130132', false, 'Invalid ISO8601 datestring'), //day > 31
            array('201312', false, 'Invalid ISO8601 datestring'), //YYYYMM is invalid for basic date
            array('2013W00', false, 'Invalid ISO8601 datestring'), //week <01
            array('2013W54', false, 'Invalid ISO8601 datestring'), //week >53
            array('2013W050', false, 'Invalid ISO8601 datestring'), //weekday <1
            array('2013W058', false, 'Invalid ISO8601 datestring'), //weekday >7
            array('', false, 'Invalid ISO8601 datestring: Date segment required'), //no date
            //datetime. NB Time separator can be upper or lower case 'T'
            array('2013T120301', true, 'basicYearOnly : basicTimeHourMinSec'),
            array('20131201T120301', true, 'basicYearMonthDay : basicTimeHourMinSec'),
            array('2013t1203', true, 'basicYearOnly : basicTimeHourMin'),
            array('2013T12', true, 'basicYearOnly : basicTimeHour'),
            array('201304T12', false, 'Invalid ISO8601 datestring'),  //no datemonth for basic format
            array('2013T', false, 'Invalid ISO8601 datestring: Time segment specified but not found'), //no time part
            array('2013t250301', false, 'Invalid ISO8601 datestring'), //hour > 24
            array('2013T246003', false, 'Invalid ISO8601 datestring'), //min > 59
            array('2013t005962', false, 'Invalid ISO8601 datestring'), //sec > 59
            array('2013t2503', false, 'Invalid ISO8601 datestring'), //hour > 24
            array('2013T2460', false, 'Invalid ISO8601 datestring'), //min > 59
            array('2013t25', false, 'Invalid ISO8601 datestring'), //hour > 24
            //decimal datetime. NB decimal point can be dot or comma. Arbitrary decimal precision
            array('2013T120301.0', true, 'basicYearOnly : basicDecimalTimeHourMinSec'),
            array('2013t1203,123', true, 'basicYearOnly : basicDecimalTimeHourMin'),
            array('2013T12.54', true, 'basicYearOnly : basicDecimalTimeHour'),
            array('2013T120301.', false, 'Invalid ISO8601 datestring'),   //no digits after decimal point
            array('2013t1203.', false, 'Invalid ISO8601 datestring'),     //no digits after decimal point
            array('2013T12,', false, 'Invalid ISO8601 datestring'),       //no digits after decimal point
            //datetime + zone
            array('2013T120301+00', true, 'basicYearOnly : basicTimeHourMinSec : basicZonePositiveHour'),
            array('2013T120301+01', true, 'basicYearOnly : basicTimeHourMinSec : basicZonePositiveHour'),
            array('2013T120301+24', true, 'basicYearOnly : basicTimeHourMinSec : basicZonePositiveHour'),
            array('2013T120301+0106', true, 'basicYearOnly : basicTimeHourMinSec : basicZonePositiveHourMin'),
            array('2013T120301+25', false, 'Invalid ISO8601 datestring'), //hour > 24
            array('2013T120301-01', true, 'basicYearOnly : basicTimeHourMinSec : basicZoneNegativeHour'),
            array('2013T120301-24', true, 'basicYearOnly : basicTimeHourMinSec : basicZoneNegativeHour'),
            array('2013T120301-25', false, 'Invalid ISO8601 datestring'), //hour > 24
            array('2013T120301-00', false, 'Invalid ISO8601 datestring'), //negative zero
        );
    }

    /**
     * The only required tests here are the first two, as for signed datestrings
     * only the year is important, all other segments remain bound to the rules
     * for basic dates.
     * @return array
     */
    public function signedBasicDate()
    {
        return array(
            array('+', '', true, 'signedBasicYearOnly'),
            array('-', '', true, 'signedBasicYearOnly'),
            //not really required
            array('+', 'T00', true, 'signedBasicYearOnly : basicTimeHour'),
            array('+', 'T0000', true, 'signedBasicYearOnly : basicTimeHourMin'),
            array('-', 'T000000', true, 'signedBasicYearOnly : basicTimeHourMinSec'),
        );
    }

    /**
     * The only required tests here are the first two, as for signed datestrings
     * only the year is important, all other segments remain bound to the rules
     * for extended dates.
     * @return array
     */
    public function signedExtendedDate()
    {
        return array(
            array('+', '', true, 'signedExtendedYearOnly'),
            array('-', '', true, 'signedExtendedYearOnly'),
            //not really required
            array('+', 'T00:00', true, 'signedExtendedYearOnly : extendedTimeHourMin'),
            array('-', 'T00:00:00', true, 'signedExtendedYearOnly : extendedTimeHourMinSec'),
        );
    }

    public function phpBasicDate()
    {
        return array(
            //date. NB Week separator must be upper case 'W'
            //there is no yearmonth for basic format
            array('2013',true),
            array('20131201',true),
            array('2013W02',true),
            array('2013W021',true),
            array('2013001',true),
            array('20131201T120301',true),
            array('2013t1203',true),
            array('2013T120301',false),
            array('2013T12',false),
            array('2013T120301.0',false),
            array('2013t1203,123',false),
            array('2013T12.54',false),
            array('2013T120301+00',false),
            array('2013T120301+01',false),
            array('2013T120301+24',false),
            array('2013T120301+0106',false),
            array('2013T120301-01',false),
            array('2013T120301-24',false),
            array('2013T120301Z',false),
        );
    }

    public function phpExtendedDate()
    {
        return array(
            array('2013',true),
            array('2013-12',true),
            array('2013-12-01',true),
            array('2013-W02',true),
            array('2013-W021',true),
            array('2013-001',false),
            array('2013T12:03:01',false),
            array('2013T12:03',false),
            array('2013-09-15T12:03:01.0',true),
            array('2013-09T12:03:01.0',true),
            array('2013T12:03:01.0',false),
            array('2013T12:03,123',false),
            array('2013T12:03:01+00',false),
            array('2013-03-19T12:03:01+00',true),
            array('2013-03T12:03:01+00',true),
            array('2013T12:03:01+01',false),
            array('2013T12:03:01+01:06',false),
            array('2013T12:03:01+24',false),
            array('2013T12:03:01-01',false),
            array('2013T12:03:01-24',false),
            array('2013T12:03:01Z',false),
        );
    }
}
