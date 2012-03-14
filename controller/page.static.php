<?php

function renderContent() {

	// Create a list of all the related functions that are defined
	$t = get_defined_functions ();
	$_methods = array ();
	foreach ($t["user"] as $func) {
		if (strpos ($func, "static_") !== false || strpos ($func, "ext_") !== false)
			$_methods[$func] = true;
	}

	// Check to see if the page the user requested exists
	$pageFunc = "static_page".strtolower ($_GET["static"]);
	if (isset ($pageFunc))
		$GLOBALS["_content"] = call_user_func ($pageFunc);
	else
		$GLOBALS["_content"] = "Page ".$_GET["static"]." not found.";
	Display::setTemplate('simple');
	Display::setVariable('content', $GLOBALS['_content']);

}