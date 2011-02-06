<?php

/**
 * Determines the comic's image path and attaches it as a new property to the content object
 */
function ext_formatPostComic($content) {
	
	if ($content->type == 'comic') {
		$date = date("Ymd", $content->date);
		$content->comic_image = 'cm_' . $date . '_' . substr(md5($date), 0, 1) . '.png';
	}
	
	return $content;

}