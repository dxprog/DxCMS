<?php

namespace Controller {

	use Api;
	use Lib;
	use stdClass;

	class Featured implements Extension {
		
		/**
		 * Registers functionality with the main class
		 */
		public static function init() {
			Lib\Display::registerExtension('Featured', 'sidebarFeatured', 'featured');
		}

		/**
		 * Renders the featured block
		 */
		public static function sidebarFeatured () {
			
			$cacheKey = 'sidebar_featured';
			$retVal = Lib\Cache::Get($cacheKey);
			if (false === $retVal) {
				$t = new stdClass();
				$t->featured = true;
				$obj = Api\Content::getContent(array( 'meta'=>$t, 'select'=>'title,perma,meta', 'max'=>4 ));
				$retVal = Lib\Display::compile($obj, 'content_featured', $cacheKey);
			}
			
			return $retVal;
			
		}

	}

	Featured::init();
	
}