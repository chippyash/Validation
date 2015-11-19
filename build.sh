#!/bin/bash
cd ~/Projects/chippyash/source/Validation
vendor/phpunit/phpunit/phpunit -c test/phpunit.xml --testdox-html contract.html test/
tdconv -t "Chippyash Validation" contract.html docs/Test-Contract.md
rm contract.html

