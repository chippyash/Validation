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
 */

namespace Chippyash\Validation\Common;

use Chippyash\Type\String\StringType;
use Chippyash\Validation\Util\IpUtil;
use Monad\FTry;
use Monad\Match;
use Monad\Option;

/**
 * Validator to check current ip against a netmask
 */
class Netmask extends AbstractValidator
{

    const ERR_MSG1 = 'Value is not a valid ip in an allowed range';
    const ERR_MSG2 = 'Netmask is not a valid CIDR netmask';

    /**
     * CIDR format netmasks
     *
     * @var array
     */
    protected $netmasks = array();

    /**
     * Constructor
     *
     * @param string|array $netmask single or multiple netmasks in CIDR format
     */
    public function __construct($netmask)
    {
        $this->netmasks = is_array($netmask) ? $netmask : array($netmask);
    }

    /**
     * Do the validation
     *
     * @param mixed $ip IP Address to check - if null, then use current IP of requestor
     *
     * @return boolean
     */
    protected function validate($ip = null)
    {
        $ip = (empty($ip) ? IpUtil::getUserIp() : $ip);

        return Match::on(
            FTry::with(
                function () use ($ip) {
                    return array_reduce(
                        $this->netmasks,
                        function (&$result, $cidr) use ($ip) {
                            return $result || IpUtil::cidrMatch($ip, $cidr);
                        },
                        false
                    );
                }
            )
        )
            ->Monad_FTry_Success(
                function ($value) {
                    return Match::on(Option::create($value->flatten(), false))
                    ->Monad_Option_Some(true)
                    ->Monad_Option_None(
                        function () {
                            $this->messenger->add(new StringType(self::ERR_MSG1));
                            return false;
                        }
                    )
                    ->value();
                }
            )
            ->Monad_FTry_Failure(
                function ($e) {
                    return Match::on(Option::create(strpos($e->value()->getMessage(), 'cidr'), false))
                    ->Monad_Option_Some(
                        function () {
                            $this->messenger->add(new StringType(self::ERR_MSG2));
                            return false;
                        }
                    )
                    ->Monad_Option_None(
                        function () {
                            $this->messenger->add(new StringType(self::ERR_MSG1));
                            return false;
                        }
                    )
                    ->value();
                }
            )
            ->value();
    }
}
