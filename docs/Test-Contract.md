# Chippyash Validation

## 
      Chippyash\Test\Validation\Common\Double
    

*  Will return correct response for test data set via magic invoke method
*  Will return correct response for test data set via is valid method

## 
      Chippyash\Test\Validation\Common\Email
    

*  Will return correct response for test data set via magic invoke method
*  Will return correct response for test data set via is valid method

## 
      Chippyash\Test\Validation\Common\Enum
    

*  Will return correct response for test data set via magic invoke method
*  Will return correct response for test data set via is valid method

## 
      Chippyash\Test\Validation\Common\ISO8601DateString
    

*  Constructing with a null format will default to extended format
*  Constructing with a none format will default to extended format
*  Constructing basic date signed with no second param will throw an exception
*  Constructing extended date signed with no second param will throw an exception
*  Constructing date signed with second param out of bounds will throw an exception
*  Constructing enforcing zone with no time enforcement will throw an exception
*  Constructing lax zone with no lax time will throw an exception
*  You can validate an extended date
*  You can validate an u t c extended date
*  You can validate an extended date with lax time
*  You can validate a basic date
*  You can validate an u t c basic date
*  You can validate a basic date with lax time
*  You can validate a signed basic date
*  You can validate a signed good extended date
*  You can enforce time and zone
*  The validator supports the zend interface
*  You can validate p h p compatibility on a basic date
*  You can validate p h p compatibility on an extended date

## 
      Chippyash\Test\Test\Validation\Common\IsArray
    

*  Will return correct response for test data set via magic invoke method
*  Will return correct response for test data set via is valid method
*  You can optionally test for an empty array

## 
      Chippyash\Test\Validation\Pattern\IsTraversable
    

*  You can validate with an array
*  You can validate with a traversable
*  You can validate with a std class
*  Validating with a non traversable will return false

## 
      Chippyash\Test\Validation\Common\Lambda
    

*  If the lambda returns true then the validator will return true
*  If lamda returns false then default message will be used if none set
*  If lamda returns false then custom message will be used if set
*  You can access the messenger from within a callable function

## 
      Chippyash\Test\Validation\Common\Netmask
    

*  Will return correct response for test data set via magic invoke method
*  Will return correct response for test data set via is valid method

## 
      Chippyash\Test\Validation\Common\UKPostCode
    

*  You can invoke the validator
*  You can validate using is valid method

## 
      Chippyash\Test\Validation\Common\UKTelnum
    

*  You can validate using is valid method
*  You can invoke the validator

## 
      Chippyash\Test\Validation\Common\Zend
    

*  Will return correct response for test data set via magic invoke method
*  Will return correct response for test data set via is valid method
*  You can get the underlying zend error messages

## 
      Chippyash\Test\Validation\Exceptions\ValidationException
    

*  You can throw a validation exception
*  Validation exception has a default message
*  You can overide the default message
*  You can assert a validation exception
*  Trying to assert a validation exception with a non callable function will throw a validation exception

## 
      Chippyash\Test\Validation\Logical\Combination
    

*  Combinations of the logical validators work correctly

## 
      Chippyash\Test\Validation\Logical\LAnd
    

*  The and logical validator returns expected response

## 
      Chippyash\Test\Validation\Logical\LNot
    

*  The not logical validator returns expected response

## 
      Chippyash\Test\Validation\Logical\LOr
    

*  The or logical validator returns expected response

## 
      Chippyash\Test\Validation\Logical\LXor
    

*  The xor logical validator returns expected response

## 
      Chippyash\Test\Validation\Messenger
    

*  You can add string type messages
*  Calling get will return an array of string type messages
*  Calling implode will return a string
*  You can clear the messages
*  You can test if the messenger has a message

## 
      Chippyash\Test\Validation\Pattern\HasTypeMap
    

*  You can construct the pattern
*  The validation value may be an array
*  The validation value may be a traversable object
*  The pattern will fail if given an invalid value type
*  The pattern will succeed with a valid associative array to validate
*  The pattern will succeed with a valid object to validate
*  The pattern will fail with an invalid associative array
*  The pattern will fail with an invalid object
*  The pattern will fail if required key does not exist
*  The pattern will fail if value for a callable value is false
*  You can use a callable function for a validation type
*  The pattern will fail if value for a traversable value is false
*  You can use a traversable function for a validation type

## 
      Chippyash\Test\Test\Validation\Pattern\Repeater
    

*  A repeater expects value to be traversable
*  A default repeater will succeed for zero or more repetions
*  You can set a minimum number of items to be in the traversable
*  You can set a maximum number of items to be in the traversable
*  Maximum must not be less than minimum
*  A repeater will apply the validation to all value items until it finds an invalid one

## 
      Chippyash\Test\Validation\Util\IpUtil
    

*  You can get the client user ip for http request if available
*  Is valid i p returns boolean
*  Is valid cidr returns boolean
*  Cidr match will return boolean for valid inputs
*  Cidr match will throw exception for invalid ip
*  Cidr match will throw exception for invalid cidr

## 
      Chippyash\Test\Validation\ValidationProcessor
    

*  You can construct a validation processor with the correct parameters
*  Constructing with bad parameters will throw an exception
*  You can add additional validations to the processor
*  Adding an inlaid validator to the processor will throw an exception
*  You can validate using the processor
*  You can get the messenger from the processor


Generated by [chippyash/testdox-converter](https://github.com/chippyash/Testdox-Converter)