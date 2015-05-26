<?php
# Owen Maule Test Driven Development walkthrough
# Copyright Owen Maule 2015

# I suggest the lowest level functionality be:

/**
 * Takes a purchase (int quantity, string name, bool exempt, bool imported, int price)
 * Returns the sales tax applicable
 */
function taxItem( $itemDetails )
{
	# To do
	throw new Exception ( 'taxItem() not implemented' );
	return 0;
}

# The next highest layer would be:

/**
 * Takes a list of purchases (int quantity, string name, bool exempt, bool imported, int price)
 * Returns the list of purchases with tax rules applied (int quantity, string name, int price). Also provides totals for tax and overall cost. The output of this is termed the receipt.
 */
function taxItems( $items )
{
	# To do
	throw new Exception ( 'taxItems() not implemented' );
	return array ( array (), 0, 0 );
}

# The input to the above function would be generated using text parsing and analysis routine as follows:

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
	# To do
	throw new Exception ( 'parseItems() not implemented' );
	return array ();
}

# Output rendering of the resultant receipt would be as follows:

/**
 * Takes receipt details (array items, int tax, int total)
 * Returns a textual representation of the data
 * items array values are of the form (int quantity, string name, int price)
 */
function renderReceipt( $receiptDetails )
{
	# To do
	throw new Exception ( 'renderReceipt() not implemented' );
	return '';
}

# The outermost layer logic is simple enough to be specified at this stage and this would be the API that
# would be tested against if the system was a black box, without being modularised before tests are written

/**
 * Takes a text input of a list of items bought e.g.
 * 1 Book at 12.49
 * 1 Music CD at 14.99
 * 1 Chocolate bar at 0.85
 * returns a text output of the resultant receipt including tax and total e.g.
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