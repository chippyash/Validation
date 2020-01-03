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
 * @link http://php.net/manual/en/class.datetime.php
 */
namespace Chippyash\Validation\Common\ISO8601;

use Chippyash\Validation\Common\ISO8601\Constants as C;
use Chippyash\Validation\Messenger;

/**
 * Utility helper for ISO8601DateString
 * matches various date parts
 */
class MatchDate
{
    /**#@+
     * Date format array parts
     */
    public const STRDATE = 'date';
    public const STRTIME = 'time';
    public const STRZONE = 'zone';
    /*#@-*/

    /**
     * The format we are using
     * @var int
     */
    protected $format = C::FORMAT_EXTENDED;

    /**
     * regex paterns
     * NB Remember that date value is lowercased, so patterns must match
     * lowercased alphas
     *
     * @var array
     */
    protected $formatRegex = [];

    /**
     *
     * @var Messenger
     */
    protected $messenger;

    /**
     * @param int       $format      One of Constants::FORMAT_...
     * @param array     $formatRegex Regex patterns for formats
     * @param Messenger $messenger
     */
    public function __construct($format, array $formatRegex, Messenger $messenger)
    {
        $this->format = $format;
        $this->formatRegex = $formatRegex;
        $this->messenger = $messenger;
    }

    /**
     * Match a date part
     *
     * @param  string $date
     * @return boolean
     */
    public function matchDate($date)
    {
        return $this->matchPart(
            $date,
            $this->formatRegex[$this->format][self::STRDATE]
        );
    }

    /**
     * Match a time part
     *
     * @param  string $time
     * @return boolean
     */
    public function matchTime($time)
    {
        return $this->matchPart(
            $time,
            $this->formatRegex[$this->format][self::STRTIME]
        );
    }

    /**
     * Match a zone part
     *
     * @param  string $zone
     * @return boolean
     */
    public function matchZone($zone)
    {
        return $this->matchPart(
            $zone,
            $this->formatRegex[$this->format][self::STRZONE]
        );
    }

    /**
     * Match a date and time datestring part
     *
     * @param  string $date
     * @param  string $time
     * @return boolean
     */
    public function matchDateAndTime($date, $time)
    {
        return $this->matchDate($date)
        && $this->matchTime($time);
    }

    /**
     * Match a date, time and zone datestring part
     *
     * @param  string $date
     * @param  string $time
     * @param  string $zone
     * @return boolean
     */
    public function matchDateAndTimeAndZone($date, $time, $zone)
    {
        return $this->matchDate($date)
        && $this->matchTime($time)
        && $this->matchZone($zone);
    }

    /**
     * Match a datestring part
     *
     * @param  string $value
     * @param  array  $patterns
     * @return boolean
     */
    protected function matchPart($value, array $patterns)
    {
        foreach ($patterns as $key => $pattern) {
            if (preg_match($pattern, $value) === 1) {
                $this->messenger->add($key);
                return true;
            }
        }

        return false;
    }
}
