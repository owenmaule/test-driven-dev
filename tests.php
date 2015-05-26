<?php
# Owen Maule Test Driven Development walkthrough
# Copyright Owen Maule 2015

# Indexes into arrays - have used this to shorten the test data definition, in practice it is likely better to use keys for flexibility

# (int quantity, string name, bool exempt, bool imported, int price)
define( 'ITEM_QUANTITY', 0 );
define( 'ITEM_NAME', 1 );
define( 'ITEM_EXEMPT', 2 );
define( 'ITEM_IMPORTED', 3 );
define( 'ITEM_PRICE', 4 );

# (array items, int tax, int total)
define( 'RECEIPT_ITEMS', 0 );
define( 'RECEIPT_TAX', 1 );
define( 'RECEIPT_TOTAL', 2 );

# The test data

$testData_taxItem = array (
	array ( 'i' => array ( 1, 'Book', true, false, 1249 ),
		'o' => 0
		),
	array ( 'i' => array ( 1, 'Music CD', false, false, 1499 ),
		'o' => 150
	),
	array ( 'i' => array ( 1, 'Chocolate bar', true, false, 85 ),
		'o' => 0
	),
	array ( 'i' => array ( 1, 'Imported box of chocolates', true, true, 1000 ),
		'o' => 50
	),
	array ( 'i' => array ( 1, 'Imported bottle of perfume', false, true, 4750 ),
		'o' => 715
	),
	array ( 'i' => array ( 1, 'Imported bottle of perfume', false, true, 2799 ),
		'o' => 420
	),
	array ( 'i' => array ( 1, 'Bottle of perfume', false, false, 1899 ),
		'o' => 190
	),
	array ( 'i' => array ( 1, 'Packet of paracetamol', true, false, 975 ),
		'o' => 0
	),
	array ( 'i' => array ( 1, 'Box of imported chocolates', true, true, 1125 ),
		'o' => 60
	),
);

$testData_taxItems = array (
	array (
		'i' => array (
			array ( 1, 'Book', true, false, 1249 ),
			array ( 1, 'Music CD', false, false, 1499 ),
			array ( 1, 'Chocolate bar', true, false,  85 )
		),
		'o' => array (
			RECEIPT_ITEMS => array (
				array ( 1, 'Book', true, false, 1249 ),
				array ( 1, 'Music CD', false, false, 1649 ),
				array ( 1, 'Chocolate bar', true, false,  85 ),
			),
			RECEIPT_TAX => 150,
			RECEIPT_TOTAL => 2983,
		)
	),
	array (
		'i' => array (
			array ( 1, 'Imported box of chocolates', true, true, 1000 ),
			array ( 1, 'Imported bottle of perfume', false, true, 4750 ),
		),
		'o' => array (
			RECEIPT_ITEMS => array (
				array ( 1, 'Imported box of chocolates', true, true, 1050 ),
				array ( 1, 'Imported bottle of perfume', false, true, 5465 ),
			),
			RECEIPT_TAX => 765,
			RECEIPT_TOTAL => 6515,
		)
	),
	array (
		'i' => array (
			array ( 1, 'Imported bottle of perfume', false, true, 2799 ),
			array ( 1, 'Bottle of perfume', false, false, 1899 ),
			array ( 1, 'Packet of paracetamol', true, false, 975 ),
			array ( 1, 'Box of imported chocolates', true, true, 1125 ),
		),
		'o' => array (
			RECEIPT_ITEMS => array (
				array ( 1, 'Imported bottle of perfume', false, true, 3219 ),
				array ( 1, 'Bottle of perfume', false, false, 2089 ),
				array ( 1, 'Packet of paracetamol', true, false, 975 ),
				array ( 1, 'Box of imported chocolates', true, true, 1185 ),
			),
			RECEIPT_TAX => 670,
			RECEIPT_TOTAL => 7468,
		)
	),
);

$testData_parseItems = array (
	array ( 'i' => "1 Book at 12.49\n1 Music CD at 14.99\n1 Chocolate bar at 0.85",
		'o' => array (
			array ( 1, 'Book', true, false, 1249 ),
			array ( 1, 'Music CD', false, false, 1499 ),
			array ( 1, 'Chocolate bar', true, false,  85 ),
		)
	),

	array ( 'i' => "1 Imported box of chocolates at 10.00\n1 Imported bottle of perfume at 47.50",
		'o' => array (
			array ( 1, 'Imported box of chocolates', true, true, 1000 ),
			array ( 1, 'Imported bottle of perfume', false, true, 4750 ),
		)
	),

	array ( 'i' => "1 Imported bottle of perfume at 27.99\n1 Bottle of perfume at 18.99\n1 Packet of paracetamol at 9.75\n1 Box of imported chocolates at 11.25",
		'o' => array (
			array ( 1, 'Imported bottle of perfume', false, true, 2799 ),
			array ( 1, 'Bottle of perfume', false, false, 1899 ),
			array ( 1, 'Packet of paracetamol', true, false, 975 ),
			array ( 1, 'Box of imported chocolates', true, true, 1125 ),
		)
	),
);

$testData_renderReceipt = array (
	array (
		'i' => array (
			RECEIPT_ITEMS => array (
				array ( 1, 'Book', true, false, 1249 ),
				array ( 1, 'Music CD', false, false, 1649 ),
				array ( 1, 'Chocolate bar', true, false,  85 ),
			),
			RECEIPT_TAX => 150,
			RECEIPT_TOTAL => 2983,
		),
		'o' => "1 Book: 12.49\n1 Music CD: 16.49\n1 Chocolate bar: 0.85\nSales Taxes: 1.50\nTotal: 29.83"
	),
	array (
		'i' => array (
			RECEIPT_ITEMS => array (
				array ( 1, 'Imported box of chocolates', true, true, 1050 ),
				array ( 1, 'Imported bottle of perfume', false, true, 5465 ),
			),
			RECEIPT_TAX => 765,
			RECEIPT_TOTAL => 6515,
		),
		'o' => "1 Imported box of chocolates: 10.50\n1 Imported bottle of perfume: 54.65\nSales Taxes: 7.65\nTotal: 65.15"
	),
	array (
		'i' => array (
			RECEIPT_ITEMS => array (
				array ( 1, 'Imported bottle of perfume', false, true, 3219 ),
				array ( 1, 'Bottle of perfume', false, false, 2089 ),
				array ( 1, 'Packet of paracetamol', true, false, 975 ),
				array ( 1, 'Box of imported chocolates', true, true, 1185 ),
			),
			RECEIPT_TAX => 670,
			RECEIPT_TOTAL => 7468,
		),
		'o' => "1 Imported bottle of perfume: 32.19\n1 Bottle of perfume: 20.89\n1 Packet of paracetamol: 9.75\n1 Box of imported chocolates: 11.85\nSales Taxes: 6.70\nTotal: 74.68"
	),
);

$testData_taxReceipt = array (
	array ( 'i' => "1 Book at 12.49\n1 Music CD at 14.99\n1 Chocolate bar at 0.85",
		'o' => "1 Book: 12.49\n1 Music CD: 16.49\n1 Chocolate bar: 0.85\nSales Taxes: 1.50\nTotal: 29.83"
	),
	array ( 'i' => "1 Imported box of chocolates at 10.00\n1 Imported bottle of perfume at 47.50",
		'o' => "1 Imported box of chocolates: 10.50\n1 Imported bottle of perfume: 54.65\nSales Taxes: 7.65\nTotal: 65.15"
	),
	array ( 'i' => "1 Imported bottle of perfume at 27.99\n1 Bottle of perfume at 18.99\n1 Packet of paracetamol at 9.75\n1 Box of imported chocolates at 11.25",
		'o' => "1 Imported bottle of perfume: 32.19\n1 Bottle of perfume: 20.89\n1 Packet of paracetamol: 9.75\n1 Box of imported chocolates: 11.85\nSales Taxes: 6.70\nTotal: 74.68"
	),
);

# Full set of test data

$systemTestData = array (
	'taxItem' => $testData_taxItem,
	'taxItems' => $testData_taxItems,
	'parseItems' => $testData_parseItems,
	'renderReceipt' => $testData_renderReceipt,
	'taxReceipt' => $testData_taxReceipt,
);

/**
 * A generic function tester
 */
function functionTester( $function, $testData )
{
	$testResults = array ( 0, '' );
#	echo 'function: ', $function, ' ', nl2br( var_export( $testData, true ) ), '<br /><br />';
	foreach( $testData as $inputOutput )
	{
		$testInput = $inputOutput[ 'i' ];
		$expectedOutput = $inputOutput[ 'o' ];
		try
		{
			$actualOutput = $function( $testInput );
			if( $expectedOutput != $actualOutput )
			{
				# Failed a test
				++$testResults[ 0 ];
				$testResults[ 1 ] .= $function . '() failed on input: ' . str_replace( "\n", ' ', var_export( $testInput, true ) )
					. ' result: ' . str_replace( "\n", ' ', var_export( $actualOutput, true ) )
					. ' expected: ' . str_replace( "\n", ' ', var_export( $expectedOutput, true ) ) . PHP_EOL;
			}
		}
		catch ( Exception $e )
		{
			# An exception was thrown
			++$testResults[ 0 ];		
			$testResults[ 1 ] .= $function . '() exception: ' . $e->getMessage() . PHP_EOL;
		}
	}
	return $testResults;
}

/**
 * "Testing framework"
 * This top level test function can be called automatically for Continuous Integration
 * returns the output in a suitable form for shell scripting environment, i.e. nothing for success
 * if tests fail, the first line shows the fail count, followed by the fail log lines, a blank line separates modules
 */
function testSystem( $systemTestData )
{
	$failCount = 0;
	$failLog = '';
	
	foreach( $systemTestData as $function => $testData )
	{
		$testResults = functionTester( $function, $testData );
		$failCount += $testResults[ 0 ];
		$failLog .= $testResults[ 1 ] . PHP_EOL;
	}

	if( 0 == $failCount && '' == trim( $failLog ) )
	{
		# All tests were passed!
		# If a flag is used in the filesystem to indicate failing tests, it would be cleared at this point
		return '';
	}
	
	# Could send an email report at this point
	# and/or set a flag within the filesystem
	return $failCount . PHP_EOL . $failLog;
}