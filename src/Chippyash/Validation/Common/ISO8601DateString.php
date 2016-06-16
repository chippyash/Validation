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
 * @link http://php.net/manual/en/functions.anonymous.php
 */

namespace Chippyash\Validation\Common;

use Chippyash\Type\Number\IntType;
use Chippyash\Type\String\StringType;
use Chippyash\Validation\Common\ISO8601\Constants as C;
use Chippyash\Validation\Common\ISO8601\MatchDate;
use Chippyash\Validation\Common\ISO8601\SplitDate;
use Chippyash\Validation\Exceptions\InvalidParameterException;
use Monad\FTry;
use Monad\Match;
use Monad\Option;

/**
 * Validator for ISO 8601 Date string
 * This validator accepts ALL valid ISO8601 date strings.
 * NB, valid format ISO 8601 datestrings may not be compatible with the PHP
 * DateTime constructor.  You can add an additional check when constructing this
 * class to check for compatibility with DateTime.
 *
 * Constants used by this class are separated out into ISO8601/Constants for convenience
 */
class ISO8601DateString extends AbstractValidator
{
    /**
     * The format we are using
     * @var int
     */
    protected $format = C::FORMAT_EXTENDED;
    /**
     * Shall I enforce presence of time part?
     * @var boolean
     */
    protected $enforceTime = false;
    /**
     * Shall I enforce presence or zone part?
     * @var boolean
     */
    protected $enforceZone = false;
    /**
     * If I am using signed years, how many digits do I expect
     * @var int
     */
    protected $numSignedDigits = 0;

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
     * Are we checking \DateTime compatibility?
     * @var boolean
     */
    protected $phpCheck = false;

    /**
     * regex patterns
     * NB Remember that date value is lowercased, so patterns must match
     * lowercased alphas
     *
     * @var array
     */
    protected $formatRegex = array(
        C::FORMAT_BASIC => array(
            MatchDate::STRDATE => array(
                C::FMT_KEY_BYO => '/^\d{4}$/',
                C::FMT_KEY_BYMD => '/^\d{4}(0[1-9]|1[0-2])(0[1-9]|[12]\d|3[01])$/',
                C::FMT_KEY_BW => '/^\d{4}w(0[1-9]|[1-4]\d|5[0-3])$/',
                C::FMT_KEY_BWPD => '/^\d{4}w(0[1-9]|[1-4]\d|5[0-3])[1-7]$/',
                C::FMT_KEY_BO => '/^\d{4}(00[1-9]|[12]\d{2}|3[0-5]\d|36[0-6])$/'
            ),
            MatchDate::STRTIME => array(
                C::FMT_KEY_BTHMS => '/^([01]\d|2[0-4])([0-5]\d)([0-5]\d)$/',
                C::FMT_KEY_BTHM => '/^([01]\d|2[0-4])([0-5]\d)$/',
                C::FMT_KEY_BTH => '/^([01]\d|2[0-4])$/',
                C::FMT_KEY_BDTHMS => '/^([01]\d|2[0-4])([0-5]\d)([0-5]\d)[\.,]\d{1,}$/',
                C::FMT_KEY_BDTHM => '/^([01]\d|2[0-4])([0-5]\d)[\.,]\d{1,}$/',
                C::FMT_KEY_BDTH => '/^([01]\d|2[0-4])[\.,]\d{1,}$/',
            ),
            MatchDate::STRZONE => array(
                C::FMT_KEY_BZPH => '/^\+([01]\d|2[0-4])$/',
                C::FMT_KEY_BZNH => '/^\-(0[1-9]|1\d|2[0-4])$/',
                C::FMT_KEY_BZPHM => '/^\+([01]\d|2[0-4])([0-4]\d|5[1-9])$/',
                C::FMT_KEY_BZNHM => '/^\-([01]\d|2[0-4])(0[1-9]|[1-4]\d|5[1-9])$/',
                C::FMT_KEY_BZUTC => '/^UTC$/' //workaround for Z UTC designation
            )
        ),
        C::FORMAT_BASIC_SIGNED => array(
            //NB ## is replaced with real number of digits required for signed year
            //due to limitation of basic format, only an extended year is supported
            MatchDate::STRDATE => array(
                C::FMT_KEY_SBYO => '/^[\+\-]\d{##}$/',
            ),
            //time and zone are filled with basic regex patterns
            MatchDate::STRTIME => null,
            MatchDate::STRZONE => null
        ),
        C::FORMAT_EXTENDED => array(
            MatchDate::STRDATE => array(
                C::FMT_KEY_EYO => '/^\d{4}$/',
                C::FMT_KEY_EYM => '/^\d{4}\-(0[1-9]|1[0-2])$/',
                C::FMT_KEY_EYMD => '/^\d{4}\-(0[1-9]|1[0-2])\-(0[1-9]|[12]\d|3[01])$/',
                C::FMT_KEY_EW => '/^\d{4}\-w(0[1-9]|[1-4]\d|5[0-3])$/',
                C::FMT_KEY_EWPD => '/^\d{4}\-w(0[1-9]|[1-4]\d|5[0-3])[1-7]$/',
                C::FMT_KEY_EO => '/^\d{4}\-(00[1-9]|[12]\d{2}|3[0-5]\d|36[0-6])$/'
            ),
            MatchDate::STRTIME => array(
                C::FMT_KEY_ETHMS => '/^([01]\d|2[0-4]):([0-5]\d):([0-5]\d)$/',
                C::FMT_KEY_ETHM => '/^([01]\d|2[0-4]):([0-5]\d)$/',
                C::FMT_KEY_EDTHMS => '/^([01]\d|2[0-4]):([0-5]\d):([0-5]\d)[\.,]\d{1,}$/',
                C::FMT_KEY_EDTHM => '/^([01]\d|2[0-4]):([0-5]\d)[\.,]\d{1,}$/',
            ),
            MatchDate::STRZONE => array(
                C::FMT_KEY_EZPH => '/^\+([01]\d|2[0-4])$/',
                C::FMT_KEY_EZNH => '/^\-(0[1-9]|1\d|2[0-4])$/',
                C::FMT_KEY_EZPHM => '/^\+([01]\d|2[0-4]):([0-4]\d|5[1-9])$/',
                C::FMT_KEY_EZNHM => '/^\-([01]\d|2[0-4]):(0[1-9]|[1-4]\d|5[1-9])$/',
                C::FMT_KEY_EZUTC => '/^UTC$/' //workaround for Z UTC designation
            )
        ),
        C::FORMAT_EXTENDED_SIGNED => array(
            //NB ## is replaced with real number of digits required for signed year
            MatchDate::STRDATE => array(
                C::FMT_KEY_SEYO => '/^[\+\-]\d{##}$/',
                C::FMT_KEY_SEYM => '/^[\+\-]\d{##}\-(0[1-9]|1[0-2])$/',
                C::FMT_KEY_SEYMD => '/^[\+\-]\d{##}\-(0[1-9]|1[0-2])\-(0[1-9]|[12]\d|3[01])$/',

            ),
            //time and zone are filled with extended regex patterns
            MatchDate::STRTIME => null,
            MatchDate::STRZONE => null
        ),
    );

    /**
     * Constructor
     *
     * You can provide a format.  Default is FORMAT_EXTENDED
     * You can OR a format with the ENFORCE_.. bits e.g.
     * new IntType(FORMAT_EXTENDED | ENFORCE_TIME | ENFORCE_ZONE)
     *
     * You can also set laxness for the time and zone separators
     * a lax time means that instead of the T separator, a space can be used
     * ditto for lax zone (i.e. space between time part and zone part)
     * e.g. new IntType(FORMAT_EXTENDED | ENFORCE_TIME | LAX_TIME)
     *
     * To check for PHP DateTime compatibility OR the format with CHECK_PHP_PARSEABLE
     * e.g. new IntType(FORMAT_EXTENDED | CHECK_PHP_PARSEABLE)
     *
     * @param \Chippyash\Type\Number\IntType $format
     * @param \Chippyash\Type\Number\IntType $numSignedDigits required if Signed dates are to be validated
     *
     * @throws InvalidParameterException
     */
    public function __construct(IntType $format = null, IntType $numSignedDigits = null)
    {
        Match::on($format)
            ->null(
                function () {
                    $this->format = C::FORMAT_EXTENDED;
                }
            )
            ->any(
                function () use ($format) {
                    $this->setFormatAndFlags($format);
                }
            );

        InvalidParameterException::assert(
            function () {
                return !$this->enforceTime && $this->enforceZone;
            },
            C::ERR_ENFORCEZONE_NOTIME
        );

        InvalidParameterException::assert(
            function () {
                return !$this->laxTime && $this->laxZone;
            },
            C::ERR_LAXZONE_NOTIME
        );

        Match::on(
            Option::create(
                $this->format == C::FORMAT_BASIC_SIGNED || $this->format == C::FORMAT_EXTENDED_SIGNED,
                false
            )
        )
        ->Monad_Option_Some(
            function () use ($numSignedDigits) {
                InvalidParameterException::assert(
                    function () use ($numSignedDigits) {
                        return is_null($numSignedDigits);
                    },
                    C::ERR_NOSIGNEDDIGITS
                );

                InvalidParameterException::assert(
                    function () use ($numSignedDigits) {
                        return ($numSignedDigits() < 4);
                    },
                    C::ERR_MINSIGNEDDIGITS
                );

                $this->numSignedDigits = $numSignedDigits();
                $this->prepareSignedRegexes();
            }
        );
    }

    /**
     * Set datestring formats and other flags based on format
     *
     * @param IntType $format
     */
    protected function setFormatAndFlags(IntType $format)
    {
        $fullMask = C::MASK_FORMAT | C::MASK_ENFORCE | C::MASK_LAX | C::MASK_PHP;
        $fmt = $format() & $fullMask;
        $enforcement = $fmt & C::MASK_ENFORCE;
        $laxness = $fmt & C::MASK_LAX;
        $phpCompatibility = $fmt & C::MASK_PHP;
        $this->format = ($fmt - ($enforcement + $laxness + $phpCompatibility)) & C::MASK_FORMAT;

        $this->format = ($this->format == C::FORMAT_NONE ? C::FORMAT_EXTENDED : $this->format);

        $this->enforceTime = (C::ENFORCE_TIME & $enforcement) == C::ENFORCE_TIME;
        $this->enforceZone = (C::ENFORCE_ZONE & $enforcement) == C::ENFORCE_ZONE;

        $this->laxTime = (C::LAX_TIME & $laxness) == C::LAX_TIME;
        $this->laxZone = (C::LAX_ZONE & $laxness) == C::LAX_ZONE;

        $this->phpCheck = (C::CHECK_PHP_PARSEABLE & $fmt) == C::CHECK_PHP_PARSEABLE;
    }

    /**
     * Prepare signed regex patterns with required number of digits
     */
    protected function prepareSignedRegexes()
    {
        Match::on(Option::create($this->format == C::FORMAT_BASIC_SIGNED, false))
            ->Monad_Option_Some(
                function () {
                    foreach ($this->formatRegex[C::FORMAT_BASIC_SIGNED][MatchDate::STRDATE] as &$regex) {
                        $regex = str_replace('##', $this->numSignedDigits, $regex);
                    }
                    //time and zone regex patterns are same as basic
                    $this->formatRegex[C::FORMAT_BASIC_SIGNED][MatchDate::STRTIME] =
                    $this->formatRegex[C::FORMAT_BASIC][MatchDate::STRTIME];
                    $this->formatRegex[C::FORMAT_BASIC_SIGNED][MatchDate::STRZONE] =
                    $this->formatRegex[C::FORMAT_BASIC][MatchDate::STRZONE];
                }
            );

        Match::on(Option::create($this->format == C::FORMAT_EXTENDED_SIGNED, false))
            ->Monad_Option_Some(
                function () {
                    foreach ($this->formatRegex[C::FORMAT_EXTENDED_SIGNED][MatchDate::STRDATE] as &$regex) {
                        $regex = str_replace('##', $this->numSignedDigits, $regex);
                    }
                    //time and zone regex patterns are same as extended
                    $this->formatRegex[C::FORMAT_EXTENDED_SIGNED][MatchDate::STRTIME] =
                    $this->formatRegex[C::FORMAT_EXTENDED][MatchDate::STRTIME];
                    $this->formatRegex[C::FORMAT_EXTENDED_SIGNED][MatchDate::STRZONE] =
                    $this->formatRegex[C::FORMAT_EXTENDED][MatchDate::STRZONE];

                }
            );
    }

    /**
     * Validation
     *
     * @param  mixed $value
     * @return boolean True if value is valid else false
     */
    protected function validate($value)
    {
        return Match::on(Option::create($this->validateISO($value), false))
            ->Monad_Option_Some(
                function ($opt) use ($value) {
                    return Match::on(Option::create($opt->value() && $this->phpCheck, false))
                    ->Monad_Option_Some(
                        function () use ($value) {
                            return Match::on(
                                FTry::with(
                                    function () use ($value) {
                                        new \DateTime($value);
                                    }
                                )
                        )
                        ->Monad_FTry_Success(true)
                        ->Monad_FTry_Failure(
                            function () {
                                $this->messenger->clear()->add(new StringType(C::ERR_FAILED_PHP_CHECK));
                                return false;
                            }
                        )
                        ->value();
                        }
                    )
                    ->Monad_Option_None(
                        function () use ($opt) {
                            return $opt->value();
                        }
                    )
                    ->value();
                }
            )
            ->Monad_Option_None(false)
            ->value();
    }

    /**
     * Do the ISO8601 validation
     *
     * @param  mixed $value
     * @return boolean
     */
    protected function validateISO($value)
    {
        //clear flags
        $this->timepartFound = false;
        $this->zonepartFound = false;

        //clear message store
        $this->messenger->clear();

        //prep value
        $value = strtolower($value);
        $splitter = new SplitDate($this->laxTime, $this->laxZone, $this->format);
        //ooh - how pythonesq!
        list($date, $time, $zone, $this->timepartFound, $this->zonepartFound) = $splitter->splitDate($value);

        if ($this->checkForNoDate($date)
            || $this->checkForEnforcement($time, $zone)
            || $this->checkForMissingParts($time, $zone)
        ) {
            return false;
        }

        return $this->matchOnAvailableParts($date, $time, $zone);
    }

    /**
     * @param $date
     * @return boolean
     */
    private function checkForNoDate($date)
    {
        return Match::on(Option::create($date))
            ->Monad_Option_None(
                function () {
                    $this->messenger->add(new StringType(C::ERR_REQ_DATE));
                    return true;
                }
            )
            ->Monad_Option_Some(false)
            ->value();
    }

    /**
     * @param $time
     * @param $zone
     * @return bool
     */
    private function checkForEnforcement($time, $zone)
    {
        return Match::on(Option::create($this->enforceTime && is_null($time), false))
            ->Monad_Option_Some(
                function () {
                    $this->messenger->add(new StringType(C::ERR_REQ_TIME));
                    return true;
                }
            )
            ->Monad_Option_None(
                function () use ($zone) {
                    return Match::on(Option::create($this->enforceZone && is_null($zone), false))
                    ->Monad_Option_Some(
                        function () {
                            $this->messenger->add(new StringType(C::ERR_REQ_ZONE));
                            return true;
                        }
                    )
                    ->Monad_Option_None(false)
                    ->value();
                }
            )
            ->value();
    }

    /**
     * @param $time
     * @param $zone
     * @return bool
     */
    private function checkForMissingParts($time, $zone)
    {
        return Match::on(Option::create($this->timepartFound && is_null($time), false))
            ->Monad_Option_Some(
                function () {
                    $this->messenger->add(new StringType(C::ERR_TIME_NOTFOUND));
                    return true;
                }
            )
            ->Monad_Option_None(
                function () use ($zone) {
                    return Match::on(Option::create($this->zonepartFound && is_null($zone), false))
                    ->Monad_Option_Some(
                        function () {
                            $this->messenger->add(new StringType(C::ERR_ZONE_NOTFOUND));
                            return true;
                        }
                    )
                    ->Monad_Option_None(false)
                    ->value();
                }
            )
            ->value();
    }

    /**
     * @param $date
     * @param $time
     * @param $zone
     * @return bool
     */
    private function matchOnAvailableParts($date, $time, $zone)
    {
        $matcher = new MatchDate($this->format, $this->formatRegex, $this->messenger);

        return Match::on(Option::create($this->timepartFound, false))
            //has time part
            ->Monad_Option_Some(
                function () use ($matcher, $date, $time, $zone) {
                    return Match::on(Option::create($this->zonepartFound, false))
                    //has zone part
                    ->Monad_Option_Some(
                        function () use ($matcher, $date, $time, $zone) {
                            //date, time and zone
                            return Match::on(Option::create($matcher->matchDateAndTimeAndZone($date, $time, $zone), false))
                            ->Monad_Option_Some(true)
                            ->Monad_Option_None(
                                function () {
                                    $this->messenger->clear()->add(new StringType(C::ERR_INVALID));
                                    return false;
                                }
                            )
                            ->value();
                        }
                    )
                    //no zone part
                    ->Monad_Option_None(
                        function () use ($matcher, $date, $time) {
                            //date and time
                            return Match::on(Option::create($matcher->matchDateAndTime($date, $time), false))
                            ->Monad_Option_Some(true)
                            ->Monad_Option_None(
                                function () {
                                    $this->messenger->clear()->add(new StringType(C::ERR_INVALID));
                                    return false;
                                }
                            )
                            ->value();
                        }
                    )
                    ->value();
                }
            )
            //no timepart
            ->Monad_Option_None(
                function () use ($matcher, $date) {
                    //date only
                    return Match::on(Option::create($matcher->matchDate($date), false))
                    ->Monad_Option_Some(true)
                    ->Monad_Option_None(
                        function () {
                            $this->messenger->clear()->add(new StringType(C::ERR_INVALID));
                            return false;
                        }
                    )
                    ->value();
                }
            )
            ->value();
    }
}
