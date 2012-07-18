<?php

namespace Controller {
	
	class Gallery implements Extension {
	
		public static function init() {
			Content::registerExtension('Gallery', 'formatPostGallery', 'formatter');
		}
	
		public static function formatPostGallery($post)
		{

			global $_baseURI;
			
			// Find the gallery tags and replace them with the appropriate markup
			if (preg_match_all ("@\[gallery=(.*?)\](.*?)\[/gallery\]@", $post->body, $matches)) {
				// Grab all the images and stuff them into list items
				for ($i = 0; $i < sizeof ($matches[0]); $i++) {
					
					// Extract the images
					preg_match_all ("@(\<img(.*?)/\>|\[img=(.*?)\](.*?)\[/img\])@", $matches[2][$i], $m);
					
					$pics = "";
					for ($j = 0; $j < sizeof ($m[0]); $j++) {
						// Get the URL to the image
						preg_match ("/http:\/\/(.*?)\.(png|gif|jpg|jpeg)/i", $m[0][$j], $p);
						
						// Get the caption
						if (!$m[4][$j]) {
							preg_match ("/title=\"(.*?)\"/i", $m[0][$j], $t);
							$caption = $t[1];
						}
						else
							$caption = $m[4][$j];
						
						// Create the list item
						$pics .= "<li><a href=\"{$p[0]}\" title=\"$caption\"><img src=\"/thumb.php?file={$p[0]}&width=100&height=100\" title=\"$caption\" alt=\"$caption\" /></a></li>";
					}
					$post->body = str_replace ($matches[0][$i], "<div class=\"gallery\"><span>Gallery: {$matches[1][$i]}</span><ul>$pics</ul></div>", $post->body);
					
				}
				
			}

			return $post;

		}
	
	}
	
	Gallery::init();
	
}

?>