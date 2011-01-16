<?php

// Include the TwitterOAuth framework
require_once ('twitteroauth/twitteroauth.php');
 
/**
 * Keys to be used with Twitter's OAuth stuff
 */
$GLOBALS["_twitterKey"] = "TWITTER_OATH_KEY";
$GLOBALS["_twitterSecret"] = "TWITTER_OATH_SECRET";

/**
 * Returns the URL to allow the user to login via Twitter
 */
function auth_getTwitterUrl ()
{

	global $_twitterKey, $_twitterSecret, $_baseURI;

	// Create our twitter object
	$to = new TwitterOAuth ($_twitterKey, $_twitterSecret);
	$token = '';
	
	// Get or generate the OAuth token as need. Twitter tokens do not expire, so set a cookie for a damn long time
	if (isset($_COOKIE['authToken'])) {
		$token = $_COOKIE['authToken'];
	} else {
		$token = $to->getRequestToken();
		setcookie('authToken', $token['oauth_token'], time() + 31536000, '/');
		setcookie('authSecret', $token['oauth_token_secret'], time() + 31536000, '/');
	}

	$url = $to->getAuthorizeUrl($token);
	
	return $url;

}

/**
 * Returns the user's information
 */
function auth_getTwitterUser($token)
{
	
	global $_twitterKey, $_twitterSecret;
	
	$retVal = '';
		
	$authToken = $token['oauth_token'];
	$authSecret = $token['oauth_token_secret'];
	$to = new TwitterOAuth($_twitterKey, $_twitterSecret, $authToken, $authSecret);
	$retVal = $to->OAuthRequest("https://twitter.com/account/verify_credentials.json", "GET");
	
	return json_decode($retVal);
	
}

/**
 * Retrieves the Twitter access token
 */
function auth_getTwitterAccessToken()
{
	
	global $_twitterKey, $_twitterSecret;
	
	$retVal = '';

	if (isset($_COOKIE['authToken']) && isset($_COOKIE['authSecret'])) {
		$authToken = $_COOKIE['authToken'];
		$authSecret = $_COOKIE['authSecret'];
		$to = new TwitterOAuth($_twitterKey, $_twitterSecret, $authToken, $authSecret);
		$token = $to->getAccessToken();
		setcookie('authToken', $token['oauth_token'], time() + 31536000, '/');
		setcookie('authSecret', $token['oauth_token_secret'], time() + 31536000, '/');
	}
	
	return $token;
	
}