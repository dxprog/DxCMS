<?php

namespace Controller {

	use Api;
	use Lib;
	use stdClass;

	// Include the content formatting functions
	include('./controller/ext/content.ext.markup.php');
	
	class Gallery extends Content implements Page {

		/**
		 * Content rendering logic
		 */
		public static function render() {
			
			self::_init();
			
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
			
			$type = Lib\Url::Get('type', 'art');
			
			// Change the template and set the title variables
			Lib\Display::setTemplate('gallery');
			Lib\Display::setVariable('title', ucfirst($_GET['type']) . ' - ' . $_title);
			Lib\Display::setVariable('section', ucfirst($_GET['type']));
			
			$cacheKey = 'Gallery_' . $type;
			$render = Lib\Cache::Get($cacheKey);
			if (false === $render) {
				// Get the items, display them
				$items = Api\Content::getContent(array( 'contentType'=>$type, 'max'=>0, 'noCount'=>true, 'noTags'=>true ));
				foreach ($items->content as $item) {
					$item = Markup::formatPostMarkup($item);
					$item->body = '<p>' . str_replace("\n", '</p><p>', $item->body) . '</p>';
					$item->body = str_replace('<p></p>', '', $item->body);
				}
				$render = Lib\Display::compile($items, 'gallery_' . $type, $cacheKey);
			}
			
			Lib\Display::setVariable('content', $render);
			
		}

		/**
		 * Gets a gallery item and displays it in a "chromeless" template
		 */
		private static function _showGalleryItem($perma) {
			
			global $_title;
			
			$target = Lib\Url::Get('target', false);
			$id = Lib\Url::GetInt('id', false);
			
			// Get the item from the database and log a view on it
			switch ($target) {
				case 'json':
					
					$out = false;
					
					if ($id) {
					
						Api\Content::logContentView(array( 'id'=>$id ));
					
						$cacheKey = 'Gallery_' . $id . '_Json';
						$out = Lib\Cache::Get($cacheKey);
						if (false === $out) {
							$obj = Api\Content::getContent(array( 'id'=>$id ));
							if (is_object($obj) && $obj->count > 0) {
								
								$item = $obj->content[0];
								$item = self::_formatPost($item);
								
								// Load up the image and get the size
								if (isset($item->meta->file)) {
									$item->dimensions = self::_getImageDimensions($item->meta->file);
									if (null != $item->dimensions) {
										$out = json_encode($item);
										Lib\Cache::Set($cacheKey, $out);
									}
								}

							}
						}
					}
					
					header('Content-Type: text/javascript');
					echo $out;
					exit;
					
				default:
					$obj = Api\Content::getContent(array( 'perma'=>$perma ));
					if (is_array($obj->content) && count($obj->content) > 0) {
						$obj = $obj->content[0];
						
						if (is_callable(self::$_funcFormatter)) {
							$obj = call_user_func(self::$_funcFormatter, $obj);
						}
						
						// Check for flash content
						$file = strtolower($obj->meta->file);
						if (strlen($file) > 0) {
							$ext = explode('.', $file);
							$obj->meta->fileType = $ext[count($ext) - 1];
						}
						print_r($obj);
						$render = Lib\Display::compile($obj, 'content_article');
						Lib\Display::setTemplate('gallery_item');
						Lib\Display::setVariable('title', $obj->title . ' - ' . $_title);
						Lib\Display::setVariable('content', $render);
					} else {
						Lib\Display::showError(404, 'We weren\'t able to find what you were looking for.');
					}
					break;
			}
			
		}
		
		private static function _getImageDimensions($fileName) {
			
			$retVal = null;
			
			if ($fileName{0} === '/') {
				$fileName = '.' . $fileName;
			}
			
			if (file_exists($fileName)) {
				
				$ext = end(explode('.', $fileName));
				$img = null;
				switch (strtolower($ext)) {
					case 'jpg':
					case 'jpeg':
						$img = @imagecreatefromjpeg($fileName);
						break;
					case 'png':
						$img = @imagecreatefrompng($fileName);
						break;
					case 'gif':
						$img = @imagecreatefromgif($fileName);
						break;
				}
				
				if (null !== $img) {
					$retVal = new stdClass;
					$retVal->width = imagesx($img);
					$retVal->height = imagesy($img);
					imagedestroy($img);
				}
				
			}
			
			return $retVal;
			
		}
		
	}
}