<?php

namespace Controller {
	
	use Lib;
	
	class Comic implements Page {
	
		public static function render() {
			
			Lib\Display::setTemplate('comic');
			
			$perma = isset($_GET['perma']) ? $_GET['perma'] : '';
			$comic = Lib\Dx::call('comic', 'getComic', array('perma'=>$perma, 'contentType'=>'comic'));
			if (null != $comic && $comic->status->ret_code == 0 && null != $comic->body) {
				$comic->body->date = date('F j, Y', $comic->body->date);
				Lib\Display::setVariable('title', $comic->body->title);
				$comic = Lib\Display::compile($comic->body, 'comic');
				Lib\Display::setVariable('content', $comic);
			} else {
				Lib\Display::showError($comic->status->ret_code, 'Something went boom!');
			}

		}
		
		public static function registerExtension($class, $method, $type) {
		
		}
		
	}
	
}