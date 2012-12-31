<?php

namespace Controller {
	
	use Api;
	use Lib;
	
	class Comic implements Page {
	
		public static function render() {
			
			global $_title;
			
			Lib\Display::setTemplate('comic');
			$perma = Lib\Url::Get('perma');
			
			// Grab from cache
			$cacheKey = 'Comic_' . $perma;
			$comic = Lib\Cache::Get($cacheKey);
			if (false === $comic) {
				$comic = Api\Comic::getComic(array( 'perma'=>$perma ));
			}
			
			// Render
			if (null != $comic) {
				Api\Content::logContentView(array( 'id'=>$comic->id ));
				Lib\Display::setVariable('title', $comic->title . ' - ' . $_title);
				$comic = Lib\Display::compile($comic, 'comic');
				Lib\Display::setVariable('content', $comic);
			} else {
				Lib\Display::showError($comic->status->ret_code, 'Something went boom!');
			}

		}
		
		public static function registerExtension($class, $method, $type) {
		
		}
		
	}
	
}