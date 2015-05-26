<?php
# Owen Maule Test Driven Development walkthrough
# Copyright Owen Maule 2015

include 'tests.php';

# Comment out one of the following includes

# dataflow.php - stubs, will not pass testing!
include 'dataflow.php';

# implementation.php - implementation, will pass testing if complete
#include 'implementation.php';

$testFailures = testSystem( $systemTestData );

if( ! $testFailures )
{
	# Nothing failed
	echo 'All tests passed';
} else {
	echo 'Failures occurred: ', nl2br( $testFailures );
}