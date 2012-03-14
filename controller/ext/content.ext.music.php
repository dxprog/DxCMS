<?php

namespace Controller {
	
	use Lib;
	
	class DXMP implements Extension {

		private static $_dxmpLocation = 'http://music.dxprog.com:8080/';

		public static function init() {
			Lib\Display::registerExtension('DXMP', 'sidebarMusic', 'music');
		}
		
		function sidebarMusic()
		{

			$cacheKey = 'LastPlayed';
			$retVal = Lib\Cache::Get($cacheKey);
			if ($retVal === false) {
				$retVal = '';
				$obj = Lib\Dx::call('dxmp', 'getLastSongPlayed', array('max' => 1), 300, self::$_dxmpLocation . 'api');
				if (null != $obj && is_object($obj->body) > 0) {
					$obj->body->rel_time = self::_makeRelativeTime($obj->body->date_played);
					$retVal = Lib\Display::compile($obj->body, 'sidebar_music', $cacheKey);
				}
			} 
			
			return $retVal;

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
	}

	DXMP::init();
	
}