<?php

// App info
define('FB_APP_ID', 'FACEBOOK_APP_ID');
define('FB_API_KEY', 'FACEBOOK_API_KEY');
define('FB_API_SECRET', 'FACEBOOK_API_SECRET');

require_once('lib/facebook.php');

function auth_getLoginUrl() {

	$fb = new Facebook(array('appId'=>FB_APP_ID, 'secret'=>FB_API_SECRET, 'cookie'=>true));
	$url = $fb->getLoginUrl();
	return $url;

}

function auth_getUserDetails() {

	$retVal = null;
	$fb = new Facebook(array('appId'=>FB_APP_ID, 'secret'=>FB_API_SECRET, 'cookie'=>true));
	$session = $fb->getSession();
	if ($session) {
		$retVal = $fb->api('/me');
		$retVal['avatar'] = 'http://graph.facebook.com/'.$retVal['id'].'/picture';
	}
	return $retVal;

}