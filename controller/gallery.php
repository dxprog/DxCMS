<?php

namespace Controller {

	use Lib;

	// Include the content formatting functions
	include('./controller/ext/content.ext.markup.php');
	
	class Gallery implements Page {
		
		public static function registerExtension($class, $method, $type) {
			
		}
		
		private static function _initialize() {
		
			// Create a list of all the blog related functions that are defined
			$t = get_defined_functions ();
			$_methods = array ();
			foreach ($t["user"] as $func) {
				if (strpos ($func, "content_") !== false || strpos ($func, "ext_") !== false)
					$_methods[$func] = true;
			}

			// Get a list of all the post formatting functions and create a master formatting function from those
			$_extFormatPost = '';
			$formatPost = '';
			foreach ($_methods as $func=>$val) {
				if (strpos ($func, 'formatpost') !== false) {
					$formatPost .= "\$body = $func (\$body);";
				}
			}
			$_extFormatPost = create_function ('$body', $formatPost."return \$body;");
		
		}

		/**
		 * Content rendering logic
		 */
		public static function render() {

			$action = isset($_GET['action']) ? $_GET['action'] : null;

			switch ($action) {
				case 'item':
					self::_showGalleryItem($_GET['perma']);
					break;
				default:
					self::_showAllGalleryItems();
					break;
			}

		}

		/**
		 * Renders gallery content selecting out a particular content type
		 */
		private static function _showAllGalleryItems($type = null) {
			
			global $_title;
			
			// Change the template and set the title variables
			Lib\Display::setTemplate('gallery');
			Lib\Display::setVariable('title', ucfirst($_GET['type']) . ' - ' . $_title);
			Lib\Display::setVariable('section', ucfirst($_GET['type']));
			
			// Get the items, display them
			$items = Lib\Dx::call('content', 'getContent', array('contentType'=>$_GET['type'], 'max'=>0, 'noCount'=>true, 'noTags'=>true));
			$render = Lib\Display::compile($items, 'gallery');
			Lib\Display::setVariable('content', $render);
			
		}

		/**
		 * Gets a gallery item and displays it in a "chromeless" template
		 */
		private static function _showGalleryItem($perma) {
			
			global $_title;
			
			// Get the item from the database and log a view on it
			$item = Lib\Dx::call('content', 'getContent', array('perma'=>$perma));
			if (isset($item->body->content) && count($item->body->content) > 0) {
				$item = $item->body->content[0];
				
				$obj = null;
				$obj->post = _formatPost($item, true);
				
				// Check for flash content
				$file = strtolower($obj->post->meta->file);
				if (strlen($file) > 0) {
					$ext = explode('.', $file);
					$obj->post->meta->fileType = $ext[count($ext) - 1];
				}
				
				$render = Lib\Display::compile($obj, 'content_article');
				Lib\Display::setTemplate('gallery_item');
				Lib\Display::setVariable('title', $obj->post->title . ' - ' . $_title);
				Lib\Display::setVariable('content', $render);
			} else {
				Lib\Display::showError(404, 'We weren\'t able to find what you were looking for.');
			}
			
		}
		
	}
}