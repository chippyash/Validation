<?php
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
 * @link http://en.wikipedia.org/wiki/ISO_8601
 */
namespace Chippyash\Validation\Common\ISO8601;

/**
 * ISO 8601 Datestring constants
 */
abstract class Constants
{
    /**#@+
     * Error strings returned from the class ISO8601DateString validator
     */
    const ERR_INVALID = 'Invalid ISO8601 datestring';
    const ERR_ENFORCEZONE_NOTIME = 'format: Enforcing zone without time';
    const ERR_LAXZONE_NOTIME = 'format: Lax zone without lax time';
    const ERR_NOSIGNEDDIGITS = 'numSignedDigits';
    const ERR_MINSIGNEDDIGITS = 'numSignedDigits: min == 4';
    const ERR_REQ_DATE = 'Invalid ISO8601 datestring: Date segment required';
    const ERR_REQ_TIME = 'Invalid ISO8601 datestring: Time segment required';
    const ERR_REQ_ZONE = 'Invalid ISO8601 datestring: Zone segment required';
    const ERR_TIME_NOTFOUND = 'Invalid ISO8601 datestring: Time segment specified but not found';
    const ERR_ZONE_NOTFOUND = 'Invalid ISO8601 datestring: Zone segment specified but not found';
    const ERR_FAILED_PHP_CHECK = 'Datestring is valid but failed PHP compatibility';
    /*#@-*/

    /**#@+
     * Allowable ISO8601 datestring formats
     */
    const FORMAT_BASIC = 1;             //000000001
    const FORMAT_BASIC_SIGNED = 2;      //000000010
    const FORMAT_EXTENDED = 3;          //000000011
    const FORMAT_EXTENDED_SIGNED = 4;   //000000100
    /*#@-*/

    /**
     * No format use FORMAT_EXTENDED
     */
    const FORMAT_NONE = 0;              //000000000

    /**
     * Bitmask for valid formats
     */
    const MASK_FORMAT = 7;              //000000111

    /**#@+
     * Enforce time and zone flags
     */
    const ENFORCE_TIME = 16;            //000010000
    const ENFORCE_ZONE = 32;            //000100000
    /*#@-*/

    /**
     * Bitmask for time/zone enforcements
     */
    const MASK_ENFORCE = 48;            //000110000

    /**#@+
     * Allow laxness in time and zone separator flags
     */
    const LAX_TIME = 64;                //001000000
    const LAX_ZONE = 128;               //010000000
    /*#@-*/

    /**
     * Bitmask for allowing laxity in time/zone separators
     */
    const MASK_LAX = 192;               //011000000

    /**
     * Do additional check to see if datestring is
     * understandable by PHPs \DateTime
     */
    const CHECK_PHP_PARSEABLE = 256;    //100000000

    /**
     * Bitmask for checking PHP \DateTime compatibility
     */
    const MASK_PHP = 256;               //100000000

    /**#@+
     * Format keys - Basic formats
     * Post validation you can test if $messenger->has() a format
     * to determine the date format
     */
    const FMT_KEY_BYO = 'basicYearOnly';
    const FMT_KEY_BYMD = 'basicYearMonthDay';
    const FMT_KEY_BW = 'basicWeek';
    const FMT_KEY_BWPD = 'basicWeekPlusDay';
    const FMT_KEY_BO = 'basicOrdinal';
    const FMT_KEY_BTHMS = 'basicTimeHourMinSec';
    const FMT_KEY_BTHM = 'basicTimeHourMin';
    const FMT_KEY_BTH = 'basicTimeHour';
    const FMT_KEY_BDTHMS = 'basicDecimalTimeHourMinSec';
    const FMT_KEY_BDTHM = 'basicDecimalTimeHourMin';
    const FMT_KEY_BDTH = 'basicDecimalTimeHour';
    const FMT_KEY_BZPH = 'basicZonePositiveHour';
    const FMT_KEY_BZNH = 'basicZoneNegativeHour';
    const FMT_KEY_BZPHM = 'basicZonePositiveHourMin';
    const FMT_KEY_BZNHM = 'basicZoneNegativeHourMin';
    const FMT_KEY_BZUTC = 'basicZoneUTC';
    /*#@-*/

    /**#@+
     * Format keys - Extended formats
     * Post validation you can test if $messenger->has() a format
     * to determine the date format
     */
    const FMT_KEY_EYO = 'extendedYearOnly';
    const FMT_KEY_EYM = 'extendedYearMonth';
    const FMT_KEY_EYMD = 'extendedYearMonthDay';
    const FMT_KEY_EW = 'extendedWeek';
    const FMT_KEY_EWPD = 'extendedWeekPlusDay';
    const FMT_KEY_EO = 'extendedOrdinal';
    const FMT_KEY_ETHMS = 'extendedTimeHourMinSec';
    const FMT_KEY_ETHM = 'extendedTimeHourMin';
    const FMT_KEY_EDTHMS = 'extendedDecimalTimeHourMinSec';
    const FMT_KEY_EDTHM = 'extendedDecimalTimeHourMin';
    const FMT_KEY_EZPH = 'extendedZonePositiveHour';
    const FMT_KEY_EZNH = 'extendedZoneNegativeHour';
    const FMT_KEY_EZPHM = 'extendedZonePositiveHourMin';
    const FMT_KEY_EZNHM = 'extendedZoneNegativeHourMin';
    const FMT_KEY_EZUTC = 'extendedZoneUTC';

    /**#@+
     * Format keys - Signed date formats
     * Post validation you can test if $messenger->has() a format
     * to determine the date format
     */
    const FMT_KEY_SBYO = 'signedBasicYearOnly';
    const FMT_KEY_SEYO = 'signedExtendedYearOnly';
    const FMT_KEY_SEYM = 'signedExtendedYearMonth';
    const FMT_KEY_SEYMD = 'signedExtendedYearMonthDay';
}
