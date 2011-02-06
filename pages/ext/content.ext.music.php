<?php

define('DXMP_LOCATION', 'http://dev.dxprog.com/dxmp/');

function content_sidebarMusic()
{

	$cacheKey = 'LastPlayed';
	/* $render = DxCache::Get($cacheKey);
	if ($render === false) {
		$obj = Dx::call('logs', 'getLogs', array('max'=>1), 300, DXMP_LOCATION.'api/');
		$obj->body[0]->rel_time = _makeRelativeTime($obj->body[0]->log_date);
		$obj->body[0]->album_art = urlencode(DXMP_LOCATION.'images/'.basename(urldecode($obj->body[0]->album_art)));
		$render = DxDisplay::compile($obj->body, 'sidebar_music');
		DxCache::Set($cacheKey, $render);
	} */
	DxDisplay::setVariable('music', '');

}

function _makeRelativeTime ($ts)
{
	$return = "";
	
	// Get the amount of time elapsed
	$elapsed = time () - $ts;
	
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