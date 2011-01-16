<?php

/**
 * Gallery Functions
 */

function rpc_getCategories ()
{
	
	global $_type;
	
	/* Grab all the categories */
	db_Connect ();
	$result = db_Query ("SELECT * FROM albums ORDER BY album_title DESC");
	$out = array (); $xml = "";
	while ($row = db_Fetch ($result)) {
		$t = null;
		$t->id = $row->album_id;
		$t->name = $row->album_title;
		$out[] = $t;
		$xml .= "<album id=\"$t->id\">$t->name</album>";
	}
	
	/* Output to the correct format */
	switch ($_type) {
		case "xml":
			return "<albums>$xml</albums>";
		case "json":
		default:
			return json_encode ($out);
	}
	
}

function rpc_getItems ()
{
	
	global $_type, $_cat;
	
	/* Get items for a specific category if one was spcified */
	if (is_numeric ($_cat))
		$cat = "WHERE item_parent='$_cat'";
	
	/* Get the items sorted by date descending */
	db_Connect ();
	$result = db_Query ("SELECT * FROM album_items $cat ORDER BY item_date DESC");
	$out = array (); $xml = "";
	while ($row = db_Fetch ($result)) {
		$t = null;
		$t->id = $row->item_id;
		$t->title = $row->item_title;
		$t->date->raw = (int) $row->item_date;
		$t->date->formatted = date ("F j, Y", (int) $row->item_date);
		$t->description = $row->item_descripion;
		$t->category = $row->item_parent;
		$out[] = $t;
		$xml .= "<item id=\"$t->id\" category=\"$t->category\"><time raw=\"$row->item_date\">".$t->date->formatted."</time><title>$t->title</title><description>$t->description</description></item>";
	}
	
	/* Spit it all back */
	switch ($_type) {
		case "xml":
			return "<items>$xml</items>";
		case "json":
		default:
			return json_encode ($out);
	}
	
}

function rpc_getItem ()
{
	
	global $_type, $_id, $_baseURI;
	
	// Validate the ID before continuing
	if (!is_numeric ($_id))
		raiseError (300, "Invalid ID");
	
	// Get the item
	db_Connect ();
	$row = db_Fetch (db_Query ("SELECT * FROM album_items WHERE item_id='$_id'"));
	$t = null;
	$t->name = $row->item_title;
	$t->description = $row->item_description;
	$t->date = date ("F j, Y", $row->item_date);
	$t->file = "/gallery/$row->item_file";
	$t->aspect = $row->item_aspect;
	$t->category = $row->item_parent;
	
	// Return based on type
	switch ($_type) {
		case "xml":
			return "<item id=\"$t->id\" category=\"$t->category\"><name>$t->name</name><description><![CDATA[$t->description]]></description><date raw=\"$row->item_date\">$t->date</date><file>$t->file</file></item>";
		case "json":
		default:
			return json_encode ($t);
	}
	
}

?>