<?php

include('lib/auth_twitter.php');

// Bop the user over to twitter for authentication
if (isset($_GET['redirect'])) {

	if (isset($_SERVER['HTTP_REFERER'])) {
		setcookie('lastPage', $_SERVER['HTTP_REFERER']);
	}
	$url = auth_getTwitterUrl();
	header('Location: '.$url);

} else {
	
	// Get and store the user's credentials
	$token = auth_getTwitterAccessToken();
	$user = auth_getTwitterUser($token);

	setcookie('authUser', $user->screen_name, time() + 31536000, '/');
	setcookie('authAvatar', $user->profile_image_url, time() + 31536000, '/');
	setcookie('authType', 'twitter', time() + 31536000, '/');
	header('Location: '.$_COOKIE['lastPage']);
	
}