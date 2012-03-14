<?php

namespace Controller {
	
	use stdClass;
	use Lib;
	
	/**
	 * Twitter integration for dxprog.com
	 */
	class Twitter implements Extension {
		
		public static function init() {
			Lib\Display::registerExtension('Twitter', 'sidebarTwitter', 'twitter');
		}
		
		public static function sidebarTwitter() {
			
			$twitter_user = Lib\Dx::getOption('twitter_user');
			
			$retVal = '';
			
			// Only try to load if the user is set
			if (isset ($twitter_user)) {
				// Check for a cached version of the sidebar
				$cacheKey = 'sidebar_twitter';
				$retVal = Lib\Cache::Get($cacheKey);
				if (false === $retVal) {
					$data = @file_get_contents('http://twitter.com/statuses/user_timeline.json?screen_name='.$twitter_user.'&count=2');
					if ($data) {
						$data = json_decode($data);
						$data = $data[0];
					}
					$data->text = preg_replace('@http://([.\S]+)@is', '<a href="http://$1" target="_blank">http://$1</a>', $data->text);
					$data->text = preg_replace('/@([.\S]+)/is', '@<a href="http://twitter.com/$1" title="Visit $1\'s twitter page" target="_blank">$1</a>', $data->text);
					$data->created_at = self::_makeTwitterRelativeTime(strtotime($data->created_at));
					$retVal = Lib\Display::compile($data, 'sidebar_twitter', $cacheKey);
				}
			}
			
			return $retVal;			
		}

		private static function _makeTwitterRelativeTime($ts) {
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
	}

	Twitter::init();
	
}