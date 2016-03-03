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
 * @link http://en.wikipedia.org/wiki/ISO_8601
 * @link http://php.net/manual/en/class.datetime.php
 */

namespace Chippyash\Validation\Common\ISO8601;

use Chippyash\Validation\Common\ISO8601\Constants as C;

/**
 * Utility helper for ISODateString validator
 * Splits a datestring into component parts
 */
class SplitDate
{
    /**
     * Have we found a time part?
     * @var boolean
     */
    protected $timepartFound = false;
    /**
     * Have we found a zone part?
     * @var boolean
     */
    protected $zonepartFound = false;
    /**
     * Are we allowing a lax time sparator?
     * @var boolean
     */
    protected $laxTime = false;
    /**
     * Are we allowing a lax zone separator?
     * @var boolean
     */
    protected $laxZone = false;

    /**
     * The format we are using
     * @var int
     */
    protected $format = C::FORMAT_EXTENDED;

    public function __construct($laxTime, $laxZone, $format)
    {
        $this->laxTime = (boolean) $laxTime;
        $this->laxZone = (boolean) $laxZone;
        $this->format = $format;
    }

    /**
     * Split date by its parts
     *
     * @param string $value  ISO datetime string
     * @return array[date, time, zone, timePartFound]
     */
    public function splitDate($value)
    {
        if ($this->laxTime) {
            $parts = explode(' ', $value);
            //join back zone part if found
            if (count($parts) == 3) {
                $parts[1] .= ' ' . $parts[2];
                unset($parts[2]);
            }
        } else {
            $parts = explode('t', $value);
        }
        //guard
        if (count($parts) == 0 || count($parts) > 2 || (count($parts) == 1 && empty($parts[0]))) {
            return array(null, null, null, $this->timepartFound, $this->zonepartFound);
        }
        if (count($parts) == 1) {
            //we have a date only
            return array($parts[0], null, null, $this->timepartFound, $this->zonepartFound);
        }
        if (count($parts) == 2) {
            $this->timepartFound = true;
            //we have a date and something else
            list($time, $zone) = $this->splitTime($parts[1]);
            if (!is_null($zone)) {
                return array($parts[0], $time, $zone, $this->timepartFound, $this->zonepartFound);
            } elseif (!is_null($time)) {
                return array($parts[0], $time, null, $this->timepartFound, $this->zonepartFound);
            } else {
                return array($parts[0], null, null, $this->timepartFound, $this->zonepartFound);
            }
        } else {
            return array($parts[0], null, null, $this->timepartFound, $this->zonepartFound);
        }
    }

    /**
     * Split timezone by its parts
     *
     * @param string $value
     * @return array[time, zone]
     */
    protected function splitTime($value)
    {
        //guard
        if (empty($value)) {
            return array(null, null);
        }

        //try to find zone, z == UTC
        $partsZ = explode('z', $value);
        $partsM = explode('-', $value);
        $partsP = explode('+', $value);
        if ($this->laxZone) {
            //trim the time part cus there may be a space
            $partsZ[0] = rtrim($partsZ[0]);
            $partsM[0] = rtrim($partsM[0]);
            $partsP[0] = rtrim($partsP[0]);
        }
        //guard
        if (count($partsZ) == 0 && count($partsM) == 0 && count($partsP) == 0) {
            return array(null, null);
        }
        //UTC timezone stated
        if (count($partsZ) == 2 && empty($partsZ[1])) {
            $this->zonepartFound = true;
            return array($partsZ[0], $this->getUTCTimezone());
        }

        //minus timezone stated
        if (count($partsM) == 2) {
            $this->zonepartFound = true;
            return array($partsM[0], "-{$partsM[1]}");
        }

        //plus timezone stated
        if (count($partsP) == 2) {
            $this->zonepartFound = true;
            return array($partsP[0], "+{$partsP[1]}");
        }

        //no zone found
        return array((empty($value) ? null : $value), null);
    }

    /**
     * Get current UTC timezone offset
     *
     * @return string|null
     */
    protected function getUTCTimezone()
    {
        if (\date_default_timezone_get() == 'UTC') {
            return 'UTC'; //already in UTC. workaround as zone must be non empty
        } else {
            $timezone = new \DateTimeZone(\date_default_timezone_get()); // Get default system timezone to create a new DateTimeZone object
            $offset = $timezone->getOffset(new \DateTime()); // Offset in seconds to UTC
            $offsetHours = round(abs($offset)/3600);
            $offsetMinutes = round((abs($offset) - $offsetHours * 3600) / 60);
            $offsetString = ($offset < 0 ? '-' : '+')
                        . ($offsetHours < 10 ? '0' : '') . $offsetHours
                        . ($this->format == C::FORMAT_EXTENDED || $this->format == C::FORMAT_EXTENDED_SIGNED ? ':' : '')
                        . ($offsetMinutes < 10 ? '0' : '') . $offsetMinutes;

            return $offsetString;
        }
    }
}
