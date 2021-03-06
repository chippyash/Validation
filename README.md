# chippyash/validation

## Quality Assurance

![PHP 7.2](https://img.shields.io/badge/PHP-7.2-blue.svg)
[![Build Status](https://travis-ci.org/chippyash/Validation.svg?branch=master)](https://travis-ci.org/chippyash/Validation)
[![Test Coverage](https://codeclimate.com/github/chippyash/Validation/badges/coverage.svg)](https://codeclimate.com/github/chippyash/Validation/coverage)
[![Code Climate](https://codeclimate.com/github/chippyash/Validation/badges/gpa.svg)](https://codeclimate.com/github/chippyash/Validation)

The above badges represent the current development branch.  As a rule, I don't push
 to GitHub unless tests, coverage and usability are acceptable.  This may not be
 true for short periods of time; on holiday, need code for some other downstream
 project etc.  If you need stable code, use a tagged version. Read 'Further Documentation'
 and 'Installation'.
 
Please note that developer support for PHP5.4 & 5.5 was withdrawn at version 2.0.0 of this library.
If you need support for PHP 5.4 or 5.5, please use a version `>=1,<2`
 
Support for PHP <7.2 was withdrawn at version 3.0.0 of this library.
If you need support for PHP 5.6 - 7.1, use a version `~2.0`

## What?

Provides extensive and complex validation of nested structures.  Primary use case is
validating incoming Json data, where, unlike XML, there is no defined validation 
pattern in common usage. (XML has XSD.)

## Why?

Validating incoming data is important because you can't trust the data provider.  Early
warning in your program makes it robust.  In large and/or corporate systems, your 
upstream data providers can change without you knowing. Being able to validate that the
data conforms to an expected pattern prevents your application from failing unnecessarily.

A robust system, first filters, then validates, then optionally filters again (usually
referred to as mapping,) any incoming data.  This library provides a Zend Validator 
compatible extension that allows you to create complex validations of nested data.

## How

Validation just wouldn't be the same if you didn't know why something failed. All
Chippyash Validators use the Chippyash/Validation/Messenger class to store validation
errors, and record reasons why a validation passed.  Sometimes, knowing why it succeeded
is just as important.
 
All Chippyash Validators support invoking on the validation class, in which case
you need to supply the messenger:

<pre>
    use Chippyash\Validation\Messenger;
    use Chippyash\Validation\Common\Double as Validator;
        
    $messenger = new Messenger();
    $validator = new Validator();
    $result = $validator($someValue, $messenger);
    $msg = $messenger->implode();
    if (!$result) {
        echo $msg;
    } else {
        //parse the messages and switch dependent on why it succeeded
    }
    
</pre>

Alternatively, You can call the isValid() method, in which case, you do not need to
supply the Messenger:

<pre>
    use Chippyash\Validation\Common\Double as Validator;
        
    $validator = new Validator();
    $result = $validator->isValid($someValue);
    if (!$result) {
        $errMsg = implode(' : ', $validator->getMessages());
    }

</pre>

### Simple Validators

- Chippyash\Validation\Common\DigitString: does the value contain only numeric characters? 
- Chippyash\Validation\Common\Double: Is the supplied string equivalent to a double (float) value;
- Chippyash\Validation\Common\Email: Is the supplied string a simple email address
- Chippyash\Validation\Common\Enum: Is supplied string one of a known set of strings


<pre>
    use Chippyash\Validation\Common\Enum;
      
    $validator = new Enum(['foo','bar']);
    $ret = $validator->isValid('bop'); //returns false
</pre>

- Chippyash\Validation\Common\IsArray: Is the supplied value an array?
- Chippyash\Validation\Common\ArrayKeyExists: Is value and array and has the required key?
- Chippyash\Validation\Common\ArrayKeyNotExists: Is value and array and does not have the required key?
- Chippyash\Validation\Common\IsTraversable: Is the supplied value traversable?
- Chippyash\Validation\Common\Netmask: Does the supplied IP address belong to the
constructed Net Mask (CIDR)

<pre>
    use Chippyash\Validation\Common\Netmask;
    
    $validator = new Netmask('0.0.0.0/1');
    return $validator->isValid('127.0.0.1);  //return true
    return $validator->isValid('128.0.0.1);  //return false
</pre>

You can construct a Netmask Validator with a single CIDR address mask or an array
of them.  If you call the Netmask isValid (or invoke it) with a null IP, It will 
try to get the IP from $_SERVER['REMOTE_ADDR'] or $_SERVER['HTTP_X_FORWARDED_FOR'] thus
making it ideal for its' primary use case, that of protecting your web app against
 requests from unauthorised IP addresses.

For more uses of the Netmask validator, see the test cases.
 
- Chippyash\Validation\Common\UKPostCode: Simple extension of Zend PostCode to check
 for UK Post Codes.  Should be straightforward to create your own country specific
 validator;
- Chippyash\Validation\Common\UKTelNum. Again, a simple extension of the Zend 
TelNum Validator
- Chippyash\Validation\Common\ZFValidator: A Simple class allowing you to extend it
to create any validator using the Zend Validators.

### Complex Validators

Here is where we start to depart from the Zend validators. 

- Chippyash\Validation\Common\ArrayPart. Is the value an array, does the required key exist, and does it validate
according to the passed in function parameter?

<pre>
    use Chippyash\Validation\Common\ArrayPart;
    use Chippyash\Validation\Common\Enum;
    
    $validator = new ArrayPart('idx', new Enum(['foo','bar'])); 
    $ret = $validator->isValid(['idx' => 'bop']); //false  
    $ret = $validator->isValid(['foo' => 'bop']); //false  
    $ret = $validator->isValid(['idx' => 'bar']); //true  
</pre>

- Chippyash\Validation\Common\Lambda.  The Lambda validator expects a function on construction that will accept
a value and return true or false:

<pre>
    use Chippyash\Validation\Common\Lambda;
    
    $validator = new Lambda(function($value) {
        return $value === 'foo';
    });
    
    $ret = $validator->isValid('bar'); //false    
</pre>

You can pass in an optional second StringType parameter with the failure message

<pre>
    use Chippyash\Validation\Common\Lambda;
    use Chippyash\Type\String\StringType;
        
    $validator = new Lambda(function($value) {
        return $value === 'foo';
    },
        new StringType('Oops, not a Foo');
    
    if (!$validator->isValid('bar')) { //false
        $errMsg = implode(' : ', $validator->getMessages());
    }
</pre>

You can specify a Messenger parameter as the second parameter to your function declaration if
you want to handle adding error messages manually

<pre>
    use Chippyash\Validation\Messenger;
    use Chippyash\Validation\Common\Lambda;
    
    $validator = new Lambda(function($value, Messenger $messenger) {
            if ($value != 'foo') {
                $messenger->add('error message');
                return false;
            }
            return true;
        }
    );
</pre>

- Chippyash\Validation\Common\ISO8601DateString: Does the supplied string conform to 
an ISO8601 datestring pattern.  *docs tbc*.  This validator is so complex, that it probably deserves it's own library.
So be warned, it may be removed from this one!

### Pattern Validators

Pattern validators allow you to validate complex data structures.  These data structures
will normally be a traversable (array, object with public parameters, object implementing
a traversable interface etc.)  They are central to the usefulness of this library.

For example, lets say we have some incoming Json:

<pre>
$json = '
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
';

</pre>

The first thing we'll do is convert this into something PHP can understand, i.e.

<pre>
    $value = json_decode($json);  //or use the Zend\Json class for solid support
</pre>

#### HasTypeMap

The HasTypeMap validator allows us to validate both the keys and the values of our
incoming data and thus forms the heart of any complex validation requirement.

<pre>
use Chippyash\Validation\Pattern\HasTypeMap;
use Chippyash\Validation\Common\ISO8601DateString;
use Chippyash\Validation\Common\IsArray;
use Chippyash\Validation\Common\Email;

$validator = new HasTypeMap([
    'a' => new ISO8601DateString(), 
    'b' => 'boolean', 
    'c' => new IsArray(), 
    'f' => new IsArray()
]);

$ret = $validator->isValid($value);
</pre>

Note, again, the best we can do for the 'c' and 'f' element is determine if it is an array.
See the 'Repeater' below for how to solve this problem.

The values supplied in the TypeMap can be one of the following:

- Any returned by PHP gettype(), i.e. "integer" "double" "string", "boolean", "resource", "NULL", "unknown"
- The name of a class, e.g. '\Chippyash\Type\String\StringType'
- A function conforming to the signature 'function($value, Messenger $messenger)' and returning
  true or false
- An object implementing the ValidationPatternInterface

#### Repeater

The Repeater pattern allows us to validate a non associative array of values. Its
 constructor is:
 
<pre>
__construct(ValidatorPatternInterface $validator, IntType $min = null, IntType $max = null)
</pre>

If $min === null, then it will default to 1. If $max === null, then it will default to
  -1, i.e. no max.  
  
We can now rewrite our validator to validate the entire input data:

<pre>
use Chippyash\Validation\Pattern\HasTypeMap;
use Chippyash\Validation\Pattern\Repeater;
use Chippyash\Validation\Common\ISO8601DateString;
use Chippyash\Validation\Common\IsArray;
use Chippyash\Validation\Common\Email;
use Chippyash\Validation\Common\UKPostCode;

$validator = new HasTypeMap([
    'a' => new ISO8601DateString(),
    'b' => 'boolean',
    'c' => new Repeater(
        new HasTypeMap([
            'd' => 'string',
            'e' => new UKPostCode()
        ]),
        null,
        4
    ),
    'f' => new Repeater(new Email())
]);

$ret = $validator->isValid($value);
</pre>

This says that the 'c' element must contain 1-4 items conforming to the given TypeMap.
You can see this in action in the examples/has-type-map.php script.

### Logical Validators
 
These validators allow you carry out boolean logic. LAnd, LOr, LNot and LXor do as expected.

Each require ValidatorPatternInterface constructor parameters. Here is superficial example:

<pre>
    use Chippyash\Validation\Logical;
    use Chippyash\Validation\Common\Lambda;
    
    $true = new Lambda(function($value){return true;});
    $false = new Lambda(function($value){return false;});

    $and = new Logical\LAnd($true, $false);
    $or = new Logical\LOr($true, $false);
    $not = new Logical\LNot($true);
    $xor = new Logical\LXor($true, $false);
</pre>

And of course, you can combined them:

<pre>
    $validator = new Logical\LNot( new Logical\LAnd($true, Logical\LXor($false, $true)))
    $ret = $validator->isValid('foo');
    
    //the above is equivelent to
    $ret = !( true && (false xor true)) 
</pre>

The real power of this is that it allows you to create alternate validation:

<pre>
    $nullOrDate = new LOr(
        new Lambda(function($value) {
            return is_null($value);
        },
        new Lambda(function($value) {
            try {new \DateTime($value); return true;} catch (\Exception $e) { return false;}
        })
    );
</pre>

### Validation Processor

All the above assumes you are running a single validation on the data and that all of
the items specified by the validator pattern exist in the incoming data.  What happens
when you have optional items?  This is where the ValidationProcessor comes in.

ValidationProcessor allows you to run a number of validation passes over the data.
Typically, you'd run a validation for all required data items first, and then run one
or more subsequent validations checking for optional items.

To use, construct the processor with your first (usually required item) validator, 
then simply add additional ones to it.

<pre>
$validator = new ValidationProcessor($requiredValidator);
$validator->add($optionalValidator);
</pre>

Run your validation and gather any error messages if required:

<pre>
if (!$validator->validate($value)) {
    var_dump($validator->getMessenger()->implode());
}
</pre>

The processor will run each validation in turn and return the combined result.  See
 examples/validation-processor.php for more illustration.
 
## Further documentation

Please note that what you are seeing of this documentation displayed on Github is
always the latest dev-master. The features it describes may not be in a released version
 yet. Please check the documentation of the version you Compose in, or download.

[Test Contract](https://github.com/chippyash/Validation/blob/master/docs/Test-Contract.md) in the docs directory.

Check out [ZF4 Packages](http://zf4.biz/packages?utm_source=github&utm_medium=web&utm_campaign=blinks&utm_content=validation) for more packages

### UML


## Changing the library

1.  fork it
2.  write the test
3.  amend it
4.  do a pull request

Found a bug you can't figure out?

1.  fork it
2.  write the test
3.  do a pull request

NB. Make sure you rebase to HEAD before your pull request

Or - raise an issue ticket.

## Where?

The library is hosted at [Github](https://github.com/chippyash/Validation). It is
available at [Packagist.org](https://packagist.org/packages/chippyash/validation)

### Installation

Install [Composer](https://getcomposer.org/)

#### For production

<pre>
    "chippyash/validation": ">=3,<4"
</pre>

Or to use the latest, possibly unstable version:

<pre>
    "chippyash/validation": "dev-master"
</pre>


#### For development

Clone this repo, and then run Composer in local repo root to pull in dependencies

<pre>
    git clone git@github.com:chippyash/Validation.git Validation
    cd Validation
    composer install
</pre>

To run the tests:

<pre>
    cd Validation
    vendor/bin/phpunit -c test/phpunit.xml test/
</pre>

## License

This software library is released under the [BSD 3 Clause license](https://opensource.org/licenses/BSD-3-Clause)

This software library is Copyright (c) 2015-2020, Ashley Kitson, UK

This software library contains code items that are derived from other works: 

None of the contained code items breaks the overriding license, or vice versa,  as far as I can tell. 
So as long as you stick to GPL V3+ then you are safe. If at all unsure, please seek appropriate advice.

If the original copyright owners of the derived code items object to this inclusion, please contact the author.

## Thanks

I didn't do this by myself. I'm deeply indebted to those that trod the path before me.
 
The following have done work that this library uses:

[Zend Validator](http://framework.zend.com/manual/current/en/modules/zend.validator.html): This library requires the Zend Validator Library. Zend Validator provides
  a comprehensive set of use case specific validators.  Whilst this library provides
  some specific examples of how to use them, it builds on it. Nevertheless the
  Zend Validator library is a robust tool, and this dev wouldn't do without it.
  
[Zend I18n](http://framework.zend.com/manual/current/en/modules/zend.i18n.translating.html): Additional validations are available from the Zend I18n lib.

## History

V1.0.0 Initial Release

V1.1.0 Update dependencies

V1.1.1 Move code coverage to codeclimate

V1.1.2 Add link to packages

V1.1.3 Verify PHP 7 compatibility

V1.1.4 Remove @internal flag on Lambda validator

V1.1.5 Allow wider range of zend dependencies

V1.2.0 Add additional common validators

V1.2.1 update dependencies

V1.2.2 update build scripts

V1.2.3 update composer - forced by packagist composer.json format change

V2.0.0 BC Break. Withdraw old php version support

V2.1.0 Change of license from GPL V3 to BSD 3 Clause 

V3.0.0 BC Break. Remove PHP support < 7.2. Switch Zend dependency to Laminas