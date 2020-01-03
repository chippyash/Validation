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
    public const ERR_INVALID = 'Invalid ISO8601 datestring';
    public const ERR_ENFORCEZONE_NOTIME = 'format: Enforcing zone without time';
    public const ERR_LAXZONE_NOTIME = 'format: Lax zone without lax time';
    public const ERR_NOSIGNEDDIGITS = 'numSignedDigits';
    public const ERR_MINSIGNEDDIGITS = 'numSignedDigits: min == 4';
    public const ERR_REQ_DATE = 'Invalid ISO8601 datestring: Date segment required';
    public const ERR_REQ_TIME = 'Invalid ISO8601 datestring: Time segment required';
    public const ERR_REQ_ZONE = 'Invalid ISO8601 datestring: Zone segment required';
    public const ERR_TIME_NOTFOUND = 'Invalid ISO8601 datestring: Time segment specified but not found';
    public const ERR_ZONE_NOTFOUND = 'Invalid ISO8601 datestring: Zone segment specified but not found';
    public const ERR_FAILED_PHP_CHECK = 'Datestring is valid but failed PHP compatibility';
    /*#@-*/

    /**#@+
     * Allowable ISO8601 datestring formats
     */
    public const FORMAT_BASIC = 1;             //000000001
    public const FORMAT_BASIC_SIGNED = 2;      //000000010
    public const FORMAT_EXTENDED = 3;          //000000011
    public const FORMAT_EXTENDED_SIGNED = 4;   //000000100
    /*#@-*/

    /**
     * No format use FORMAT_EXTENDED
     */
    public const FORMAT_NONE = 0;              //000000000

    /**
     * Bitmask for valid formats
     */
    public const MASK_FORMAT = 7;              //000000111

    /**#@+
     * Enforce time and zone flags
     */
    public const ENFORCE_TIME = 16;            //000010000
    public const ENFORCE_ZONE = 32;            //000100000
    /*#@-*/

    /**
     * Bitmask for time/zone enforcements
     */
    public const MASK_ENFORCE = 48;            //000110000

    /**#@+
     * Allow laxness in time and zone separator flags
     */
    public const LAX_TIME = 64;                //001000000
    public const LAX_ZONE = 128;               //010000000
    /*#@-*/

    /**
     * Bitmask for allowing laxity in time/zone separators
     */
    public const MASK_LAX = 192;               //011000000

    /**
     * Do additional check to see if datestring is
     * understandable by PHPs \DateTime
     */
    public const CHECK_PHP_PARSEABLE = 256;    //100000000

    /**
     * Bitmask for checking PHP \DateTime compatibility
     */
    public const MASK_PHP = 256;               //100000000

    /**#@+
     * Format keys - Basic formats
     * Post validation you can test if $messenger->has() a format
     * to determine the date format
     */
    public const FMT_KEY_BYO = 'basicYearOnly';
    public const FMT_KEY_BYMD = 'basicYearMonthDay';
    public const FMT_KEY_BW = 'basicWeek';
    public const FMT_KEY_BWPD = 'basicWeekPlusDay';
    public const FMT_KEY_BO = 'basicOrdinal';
    public const FMT_KEY_BTHMS = 'basicTimeHourMinSec';
    public const FMT_KEY_BTHM = 'basicTimeHourMin';
    public const FMT_KEY_BTH = 'basicTimeHour';
    public const FMT_KEY_BDTHMS = 'basicDecimalTimeHourMinSec';
    public const FMT_KEY_BDTHM = 'basicDecimalTimeHourMin';
    public const FMT_KEY_BDTH = 'basicDecimalTimeHour';
    public const FMT_KEY_BZPH = 'basicZonePositiveHour';
    public const FMT_KEY_BZNH = 'basicZoneNegativeHour';
    public const FMT_KEY_BZPHM = 'basicZonePositiveHourMin';
    public const FMT_KEY_BZNHM = 'basicZoneNegativeHourMin';
    public const FMT_KEY_BZUTC = 'basicZoneUTC';
    /*#@-*/

    /**#@+
     * Format keys - Extended formats
     * Post validation you can test if $messenger->has() a format
     * to determine the date format
     */
    public const FMT_KEY_EYO = 'extendedYearOnly';
    public const FMT_KEY_EYM = 'extendedYearMonth';
    public const FMT_KEY_EYMD = 'extendedYearMonthDay';
    public const FMT_KEY_EW = 'extendedWeek';
    public const FMT_KEY_EWPD = 'extendedWeekPlusDay';
    public const FMT_KEY_EO = 'extendedOrdinal';
    public const FMT_KEY_ETHMS = 'extendedTimeHourMinSec';
    public const FMT_KEY_ETHM = 'extendedTimeHourMin';
    public const FMT_KEY_EDTHMS = 'extendedDecimalTimeHourMinSec';
    public const FMT_KEY_EDTHM = 'extendedDecimalTimeHourMin';
    public const FMT_KEY_EZPH = 'extendedZonePositiveHour';
    public const FMT_KEY_EZNH = 'extendedZoneNegativeHour';
    public const FMT_KEY_EZPHM = 'extendedZonePositiveHourMin';
    public const FMT_KEY_EZNHM = 'extendedZoneNegativeHourMin';
    public const FMT_KEY_EZUTC = 'extendedZoneUTC';

    /**#@+
     * Format keys - Signed date formats
     * Post validation you can test if $messenger->has() a format
     * to determine the date format
     */
    public const FMT_KEY_SBYO = 'signedBasicYearOnly';
    public const FMT_KEY_SEYO = 'signedExtendedYearOnly';
    public const FMT_KEY_SEYM = 'signedExtendedYearMonth';
    public const FMT_KEY_SEYMD = 'signedExtendedYearMonthDay';
}
