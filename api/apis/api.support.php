<?php
/**
 * SMP Support Functions
 * @author Matt Hackmann <matt@dxprog.com>
 * @package SMP
 */
 

/**
 * Returns the error along with the appropriate HTTP error code and ends code execution.
 * @param integer $code The code of the error to be raised.
 * @param string $msg The error message to be sent along with the code. Will try to pull from $_err if not defined.
 */
function raiseError ($code, $msg)
{

	global $_type, $_method;

	// Generate the output based on the type. Default to XML
	switch (strtolower ($_type)) {
		case "json":
			$out = "{"._constructJSON ($code, $msg)."}";
			$content = "javascript";
			break;
		default:
			$out = "<?xml version=\"1.0\"?><response>"._constructXML ($code, $msg)."</response>";
			$content = "xml";
			break;
	}
	
	// Send along the output and halt script execution
	header ("Content-Type: application/$content; charset=utf-8");
	echo ($out);
	exit ();
	
}

/**
 * Replaces invalid XML characters with entities
 * @param string string The string to parse
 * @return string
 */
function makeXMLSafe ($string)
{
	$find = array ("&amp;", "&", "<", ">", "'"< "\"");
	$replace = array ("&", "&amp;", "&lt;", "&gt;", "&apos;", "&quot;");
	return str_replace ($find, $replace, $string);
}

/**
 * RPC function for authenicating a user and creating a session
 */
function rpc_createSession ()
{

	global $_userHash, $_hash, $_err, $_sesskey;
	
	// If the provided hash and the stored hash are not identical, fail out
	if ($_hash != $_userHash)
		raiseError (ERR_BAD_LOGIN, $_err[ERR_BAD_LOGIN]);

	// Generate a session key
	$_sesskey = md5 ($_SERVER["REMOTE_ADDR"].":".$_SERVER["HTTP_USER_AGENT"]);
	$_sesskey = md5 (time ().":".rand ().$key);
	
	// Store the session and return the key
	db_Connect ();
	db_Query ("INSERT INTO sessions VALUES ('$_sesskey', '".(time () + 86400)."')");
	setcookie ("_sesskey", $_sesskey, time () + 86400);
	return $_sesskey;
		
}

/**
 * Validates the current session
 */
function rpc_validateSession ()
{
	
	global $_err, $_sesskey;
	
	// Get the session key from the cookie if it's there
	if ($_COOKIE["_sesskey"])
		$_sesskey = $_COOKIE["_sesskey"];
	
	// Raise an error if no session has been created
	if (!isset ($_sesskey))
		raiseError (ERR_NEED_SESSION, $_err[ERR_NEED_SESSION]);
	
	// Look up the key in the database
	db_Connect ();
	$result = db_Query ("SELECT * FROM sessions WHERE sess_key='$_sesskey' AND sess_expire > '".time ()."'");
	if (!$result->count)
		raiseError (ERR_NEED_SESSION, $_err[ERR_NEED_SESSION]);
	
	// Reset the expiry of the session to one day later
	db_Query ("UPDATE sessions SET sess_expire='".(time () + 86400)."' WHERE sess_key='$_sesskey'");
	setcookie ("_sesskey", $_sesskey, time () + 86400);
	
}

/**
 * Constructs the response based upon the return type
 */
function _constructResponse ($type, $response)
{
	
	global $_begin;
	
	// Separate the method output into something usable
	if (is_array ($response)) {
		$ret = $response["code"];
		$msg = $response["message"];
		$out = $response["body"];
	}
	else {
		$ret = 0;
		$msg = "OK";
		$out = $response;
	}
	
	// If the response is blank, set it to null
	if (!$out) {
		$out = "null";
	}
	
	// Structure of output object:
	// Head
	// - Timestamp: current unix timestamp)
	// - Gen_time: amount of time it took to generate the page
	// Status
	// - Method: the method called by the user
	// - Code: The return code of the called function
	// - Message: A message to go along with the return code
	// Body
	// - The output generated by the called method
	$obj = $response;
	
	switch (strtolower ($type)) {
		
		case "json":
			$write = json_encode($obj);
			
			// Include the JSONP callback if it was specified
			if (isset($_GET['callback'])) {
				$write = $_GET['callback']."($write)";
			}
			
			// Set the content type
			$content = "javascript";
			break;
			
		case "xml":
			
			// Serialize the output object to XML
			$ser = new SerializeXML();
			$write = $ser->serialize($obj, 'response');
			unset($ser);
			
			// Set the content type
			$content = "xml";
			break;
			
		case 'php':
		default:
			$write = serialize($obj);
			$content = 'plain';
			break;
		
	}
	
	// Write the contents
	header ("Content-Type: text/$content; charset=utf-8");
	echo ($write);
	
}

function _constructXML ($code, $msg)
{

	global $_begin, $_method;

	// Calculate the generation time
	$genTime = microtime (true) - $_begin;

	// Construct the headers and return
	$out = "<metrics timestamp=\"".gmdate ("U")."\" gen_time=\"$genTime}\" />\n";
	$out .= "<status method=\"$_method\" ret_code=\"$code\" message=\"$msg\" />";
	return $out;
	
}

?>