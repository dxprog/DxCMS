<?php

/**
 * dxprog.com PHP library
 */

// Used to keep track of page generation time
$GLOBALS["_begin"] = microtime (true);
 
// Include the API libs
require_once("./lib/dx_aal.php");
require_once("./lib/dx_serialize.php");
require_once("./lib/dx_display.php");

// Include API stuff for class definitions on cached objects
require_once('./api/apis/api.content.php');
require_once('./api/apis/api.comic.php');
require_once('./api/apis/api.user.php');

// Set the time zone
date_default_timezone_set('America/Chicago');
 
// Define our globals
$GLOBALS["_content"] = null;
$GLOBALS["_sidebars"] = null;
$GLOBALS["_api"] = "http://api.dxprog.com/";
$GLOBALS["_title"] = "matt hackmann -&gt; web developer";

// Handle URL rewrites
Dx::urlRewrite();

// Get the URL where this script is being executed from
$localDir = str_replace ("index.php", "", $_SERVER["SCRIPT_NAME"]);
$GLOBALS["_baseURI"] = "http://".$_SERVER["HTTP_HOST"].substr ($localDir, 0, strlen ($localDir) -1);

// Set up templating stuff
DxDisplay::setTheme('dx2010');
DxDisplay::setTemplate('default');
DxDisplay::setVariable('baseuri', $GLOBALS['_baseURI']);

// Check to see which page must be included
if (!$_GET["page"] || !file_exists ("./pages/page.".$_GET["page"].".php"))
	$_page = "content";
else
	$_page = $_GET["page"];

// Include the config file if it exists
if (file_exists ("./config/config.$_page.php"))
	include ("./config/config.$_page.php");
	
// Search for any related extensions and include them
$exp = "/(\w+).$_page.ext.php/";
if ($dir = opendir ("./pages/ext")) {
	while (($file = readdir ($dir)) !== false) {
		if (preg_match ($exp, $file))
			include ("./pages/ext/$file");
	}
}

// Turn control over to the requested page
require_once ("./pages/page.$_page.php");
renderContent();

// Render the page to output
DxDisplay::render();

// Calculate the amount of time it took to generate the page
$genTime = microtime (true) - $GLOBALS["_begin"];
echo '<!-- Generated in ', $genTime, ' seconds. API hits - ', $_apiHits, ' -->';