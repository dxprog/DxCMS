<?php

include('lib/auth_facebook.php');

// Bop the user over to Facebook for authentication
if (!isset($_GET['session'])) {

	// Save the referrer to a cookie so we know where to go back to and then head off to Facebook
	setcookie('lastPage', $_SERVER['HTTP_REFERER']);
	$url = auth_getLoginUrl();
	header('Location: '.$url);

} else {
	
	$user = auth_getUserDetails();
	setcookie('authUser', $user['name'], time() + 31536000, '/');
	setcookie('authAvatar', $user['avatar'], time() + 31536000, '/');
	setcookie('authType', 'facebook', time() + 31536000, '/');
	header('Location: '.$_COOKIE['lastPage']);
	
}