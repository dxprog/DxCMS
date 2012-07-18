<?php

namespace Controller {
	
	use Api;
	use Lib;
	
	class Comic implements Page {
	
		public static function render() {
			
			Lib\Display::setTemplate('comic');
			
			$perma = isset($_GET['perma']) ? $_GET['perma'] : '';
			$comic = Api\Comic::getComic(array( 'perma'=>$perma ));
			if (null != $comic) {
				Api\Content::logContentView(array( 'id'=>$comic->id ));
				Lib\Display::setVariable('title', $comic->title);
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