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
use chippyash\Type\Number\IntType;

$validator = new HasTypeMap([
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

$json = <<<EOT
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

$value = json_decode($json);

$test = $validator->isValid($value);

echo ($test ? 'Value is valid' : 'Value is invalid');

if (!$test) {
    var_dump($validator->getMessages());
}
