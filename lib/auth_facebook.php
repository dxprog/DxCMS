<?php

// App info
$GLOBALS['FB_APP_ID'] = Dx::getOption('facebook_app_id');
$GLOBALS['FB_API_KEY'] = Dx::getOption('facebook_api_key');
$GLOBALS['FB_API_SECRET'] = Dx::getOption('facebook_api_secret');

require_once('lib/facebook.php');

function auth_getLoginUrl() {

	global $FB_APP_ID, $FB_API_KEY, $FB_API_SECRET;
	$fb = new Facebook(array('appId'=>$FB_APP_ID, 'secret'=>$FB_API_SECRET, 'cookie'=>true));
	$url = $fb->getLoginUrl();
	return $url;

}

function auth_getUserDetails() {

	global $FB_APP_ID, $FB_API_KEY, $FB_API_SECRET;
	$retVal = null;
	$fb = new Facebook(array('appId'=>$FB_APP_ID, 'secret'=>$FB_API_SECRET, 'cookie'=>true));
	$session = $fb->getSession();
	if ($session) {
		$retVal = $fb->api('/me');
		$retVal['avatar'] = 'http://graph.facebook.com/'.$retVal['id'].'/picture';
	}
	return $retVal;

}

function auth_signout() {
	setcookie('authType', '', time() - 86400);
}