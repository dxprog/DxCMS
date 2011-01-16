<?php

/**
 * Twitter integration for dxprog.com
 */

// Include the TwitterOAuth framework
require_once ("./config/config.twitter.php");

function content_sidebarTwitter ()
{
	
	global $twitter_user;
	
	$retVal = "";
	$out = null;
	
	// Only try to load if the user is set
	if (isset ($twitter_user)) {
		// Check for a cached version of the sidebar
		$cacheKey = 'sidebar_twitter';
		$data = DxCache::Get($cacheKey);
		if ($data === false) {
			$data = file_get_contents('http://twitter.com/statuses/user_timeline.json?screen_name='.$twitter_user.'&count=2');
			$data = json_decode($data);
			$data = $data[0];
			DxCache::Set($cacheKey, $data);
		}
		$data->text = preg_replace('@http://([.\S]+)@is', '<a href="http://$1" target="_blank">http://$1</a>', $data->text);
		$data->text = preg_replace('/@([.\S]+)/is', '@<a href="http://twitter.com/$1" title="Visit $1\'s twitter page" target="_blank">$1</a>', $data->text);
		$data->created_at = _makeTwitterRelativeTime(strtotime($data->created_at));
	}
	$retVal = DxDisplay::compile($data, 'sidebar_twitter');
	DxDisplay::setVariable('twitter', $retVal);
		
}

function _makeTwitterRelativeTime ($ts)
{
	$return = '';
	
	// Get the amount of time elapsed
	$elapsed = gmdate('U') - $ts;
	
	// Find the days
	$days = floor ($elapsed / 86400);
	if ($days > 0)
		return $days." day".($days == 1 ? "" : "s");
	
	// Hours
	$hours = floor ($elapsed / 3600);
	if ($hours > 0)
		return $hours." hour".($hours == 1 ? "" : "s");

	// Minutes
	$minutes = floor ($elapsed / 60);
	if ($minutes > 0)
		return $minutes." minute".($minutes == 1 ? "" : "s");

	// Seconds
	return $elapsed." second".($elapsed == 1 ? "" : "s");
}

?>