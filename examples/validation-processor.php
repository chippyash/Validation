#!/usr/bin/php
<?php
/**
 * Validation
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2015, UK
 * @license GPL V3+ See LICENSE.md
 */

require_once '../vendor/autoload.php';

use Chippyash\Validation\Pattern\HasTypeMap;
use Chippyash\Validation\Pattern\Repeater;
use Chippyash\Validation\Common\ISO8601DateString;
use Chippyash\Validation\Common\Email;
use Chippyash\Validation\Common\UKPostCode;
use Chippyash\Validation\Common\Lambda;
use Chippyash\Validation\Messenger;
use Chippyash\Validation\ValidationProcessor;
use Monad\Match;
use Monad\Option;
use Chippyash\Type\Number\IntType;

$requiredValidator = new HasTypeMap([
    'a' => new ISO8601DateString(),
    'b' => 'boolean',
    'c' => new Repeater(
        new HasTypeMap([
            'd' => 'string',
            'e' => new UKPostCode()
        ]),
        null,
        new IntType(4)
    ),
    'f' => new Repeater(new Email())
]);

$optionalValidator = new Lambda(function($value, Messenger $messenger) {
    return Match::on(\Monad\Option::create(isset($value->g), false))
        ->Monad_Option_Some(function() use ($value) {
            return ($value->g === 'foobar');
        })
        ->Monad_Option_None(true) //true because we don't fail if item does not exist
        ->value();
});

$json1 = <<<EOT
{
    "a": "2015-12-01",
    "b": false,
    "c": [
        {
            "d": "fred",
            "e": "NN10 6HB"
        },
        {
            "d": "jim",
            "e": "EC1V 7DA"
        },
        {
            "d": "maggie",
            "e": "LE4 4HB"
        },
        {
            "d": "sue",
            "e": "SW17 9JR"
        }
    ],
    "f": [
        "a@b.com",
        "c@d.co.uk"
    ],
    "g": "foobar"
}
EOT;

$json2 = <<<EOT
{
    "a": "2015-12-01",
    "b": false,
    "c": [
        {
            "d": "fred",
            "e": "NN10 6HB"
        },
        {
            "d": "jim",
            "e": "EC1V 7DA"
        },
        {
            "d": "maggie",
            "e": "LE4 4HB"
        },
        {
            "d": "sue",
            "e": "SW17 9JR"
        }
    ],
    "f": [
        "a@b.com",
        "c@d.co.uk"
    ]
}
EOT;

$validator = new ValidationProcessor($requiredValidator);
$validator->add($optionalValidator);

$test1 = $validator->validate(json_decode($json1));

echo 'Test 1: ' . ($test1 ? 'Value is valid' : 'Value is invalid') . PHP_EOL;

if (!$test1) {
    var_dump($validator->getMessenger()->implode());
}

$test2 = $validator->validate(json_decode($json2));

echo 'Test 2: ' . ($test2 ? 'Value is valid' : 'Value is invalid') . PHP_EOL;

if (!$test2) {
    var_dump($validator->getMessenger()->implode());
}
