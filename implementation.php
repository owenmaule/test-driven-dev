<?php
# Owen Maule Test Driven Development walkthrough
# Copyright Owen Maule 2015

define( 'BASIC_TAX', 0.1 );
define( 'IMPORT_DUTY', 0.05 );

$EXEMPT_ITEMS = array(
	'book',
	'chocolate',
	'paracetamol',
	'headache pills',
);

# Just to be aware of the (very primitive) matching rule, although could be used for internationalisation
define( 'IMPORTED', 'imported' );

/**
 * Takes a purchase (int quantity, string name, bool exempt, bool imported, int price)
 * Returns the sales tax applicable
 */
function taxItem( $itemDetails )
{
	# Type sanity
	if( ! isset( $itemDetails ) || ! is_array( $itemDetails )
		|| ! isset( $itemDetails[ ITEM_EXEMPT ] ) || ! is_bool( $itemDetails[ ITEM_EXEMPT ] )
		|| ! isset( $itemDetails[ ITEM_IMPORTED ] ) || ! is_bool( $itemDetails[ ITEM_IMPORTED ] )
		|| ! isset( $itemDetails[ ITEM_PRICE ] ) || ! is_int( $itemDetails[ ITEM_PRICE ] )
	)
	{
		throw new Exception ( 'taxItem() data missing or invalid type' ); 
	}

	$price = $itemDetails[ ITEM_PRICE ];
	$tax = 0;
	if( $price )
	{
		if( ! $itemDetails[ ITEM_EXEMPT ] )
		{
			$tax += $price * BASIC_TAX;
		}
		if( $itemDetails[ ITEM_IMPORTED ] )
		{
			$tax += $price * IMPORT_DUTY;
		}

		# round up to nearest 5 pence/cent
		$tax = (int)( ceil( $tax / 5.0 ) * 5.0 );
	}
	return $tax;
}

/**
 * Takes a list of purchases (int quantity, string name, bool exempt, bool imported, int price)
 * Returns the list of purchases with tax rules applied (int quantity, string name, int price). Also provides totals for tax and overall cost. The output of this is termed the receipt.
 */
function taxItems( $items )
{
	# Type sanity
	if( ! isset( $items ) || ! is_array( $items ) )
	{
		throw new Exception ( 'taxItems() data missing or invalid type' ); 
	}

	$totalTax = 0;
	$total = 0;
	foreach( $items as &$item )
	{
		$itemTax = taxItem( $item );
		$item[ ITEM_PRICE ] += $itemTax;
		$total += $item[ ITEM_PRICE ];
		$totalTax += $itemTax;
	}

	return array (
		RECEIPT_ITEMS => $items,
		RECEIPT_TAX => $totalTax,
		RECEIPT_TOTAL => $total,
	);
}

/**
 * Takes a text input e.g.
 * 1 Book at 12.49
 * 1 Music CD at 14.99
 * 1 Chocolate bar at 0.85
 * Returns item details for each, analysed for tax exception and sales
 * (int quantity, string name, bool exempt, bool imported, int price)
 */
function parseItems( $inputBuffer )
{
	# Type sanity
	if( ! isset( $inputBuffer ) || ! is_string( $inputBuffer ) )
	{
		throw new Exception ( 'parseItems() data missing or invalid type' );
	}
	global $EXEMPT_ITEMS;

	$items = array ();
	$itemLines = explode( "\n", trim( $inputBuffer ) );
	foreach( $itemLines as &$itemLine )
	{
		$itemLine = trim( $itemLine );
		$firstSpace = strpos( $itemLine, ' ' );
		$atBlock = strpos( $itemLine, ' at ', $firstSpace + 1 );
		
		$quantity = substr( $itemLine, 0, $firstSpace );
		$name = substr( $itemLine, $firstSpace + 1, $atBlock - $firstSpace - 1 );
		$price = substr( $itemLine, $atBlock + 4 );		
#		echo "qty=($quantity) name=($name) price=($price)<br />";

		$exempt = false;
		$lcItemLine = strtolower( $itemLine );
		foreach( $EXEMPT_ITEMS as &$exemptItem )
		{
			# Simple example so simple matching - could be upgraded to preg_match() for regex support
			if( false !== strpos( $lcItemLine, $exemptItem ) )
			{
				$exempt = true;
				break;
			}
		}
		$imported = ( false !== strpos( $lcItemLine, IMPORTED ) );

		$items[] = array( (int)$quantity, $name, $exempt, $imported, (int)ceil( $price * 100.0 ) );
	}

	return $items;
}

/**
 * Takes receipt details (array items, int tax, int total)
 * Returns a textual representation of the data
 * items array values are of the form (int quantity, string name, int price)
 */
function renderReceipt( $receiptDetails )
{
	# Type sanity
	if( ! isset( $receiptDetails ) || ! is_array( $receiptDetails )
		|| ! isset( $receiptDetails[ RECEIPT_ITEMS ] ) || ! is_array( $receiptDetails[ RECEIPT_ITEMS ] )
		|| ! isset( $receiptDetails[ RECEIPT_TAX ] ) || ! is_int( $receiptDetails[ RECEIPT_TAX ] )
		|| ! isset( $receiptDetails[ RECEIPT_TOTAL ] ) || ! is_int( $receiptDetails[ RECEIPT_TOTAL ] )
	)
	{
		throw new Exception ( 'renderReceipt() data missing or invalid type of receipt' ); 
	}

	$output = '';
	foreach( $receiptDetails[ RECEIPT_ITEMS ] as $itemDetails )
	{
		# Type check of item details
		if( ! isset( $itemDetails[ ITEM_QUANTITY ] ) || ! is_int( $itemDetails[ ITEM_QUANTITY ] )
			|| ! isset( $itemDetails[ ITEM_NAME ] ) || ! is_string( $itemDetails[ ITEM_NAME ] )
			|| ! isset( $itemDetails[ ITEM_PRICE ] ) || ! is_int( $itemDetails[ ITEM_PRICE ] )
		)
		{
			throw new Exception ( 'renderReceipt() data missing or invalid type of item details' ); 
		}

		$output .= $itemDetails[ ITEM_QUANTITY ] . ' ' . $itemDetails[ ITEM_NAME ] . ': '
			. number_format( $itemDetails[ ITEM_PRICE ] / 100.0, 2 ) . PHP_EOL;
	}
	$output .= 'Sales Taxes: ' . number_format( $receiptDetails[ RECEIPT_TAX ] / 100.0, 2 ) . PHP_EOL
		. 'Total: ' . number_format( $receiptDetails[ RECEIPT_TOTAL ] / 100.0, 2 );
	return $output;
}

/**
 * Takes a text input of a list of items bought e.g.
 * 1 Book at 12.49
 * 1 Music CD at 14.99
 * 1 Chocolate bar at 0.85
 * returns a text output of the resultant receipt including tax and totals e.g.
 * 1 Book: 12.49
 * 1 Music CD: 16.49
 * 1 Chocolate bar: 0.85
 * Sales Taxes: 1.50
 * Total: 29.83
 */
function taxReceipt( $itemsText )
{
	return renderReceipt( taxItems( parseItems( $itemsText ) ) );
}