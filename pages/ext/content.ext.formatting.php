<?php

function _formatPost ($post, $convBreak = false)
{

	global $_baseURI, $_extFormatPost;
	
	// Format the tags
	if (is_object($post)) {
		$cacheKey = 'formatted_content_'.$post->id;
		$cacheResult = DxCache::Get($cacheKey);
		$cacheResult = false;
		if ($cacheResult === false) {
			
			// Break new lines in the body up into paragraphs and then run it through any post formatting extensions
			$disableFormatting = isset($post->meta->formatting) ? $post->meta->formatting : false;
			if (!$disableFormatting) {
				$body = htmlentities(htmlentities($post->body));
				$body = implode ('</p><p>', explode (chr(13), $body));
				$body = '<p>'.str_replace(array(chr(10), chr(13)), '', $body).'</p>';
				$body = str_replace ('<p></p>', '', $body);
				$body = preg_replace ("@\<p\>(\<div(.*?)\>(.*?)\</div\>)\</p\>@is", '$1', $body);
				$post->body = $body;
				$post = $_extFormatPost ($post);
			}
			
			$post->day = date("j", $post->date);
			$post->month = date("M", $post->date);
			$post->year = date("Y", $post->date);
			$post->timestamp = $post->date;
			$post->rfcDate = date("Y-m-d\TH:i:s", $post->date);
			$post->date = date("F j, Y", $post->date);
			
			// Write to cache
			DxCache::Set($cacheKey, $post);
			
		} else {
			$post = $cacheResult;
		}
		
		// Cut off anything after the break tag
		if ($convBreak) {
			$t = explode ('[break]', $post->body);
			$post->body = $t[0];
			if (sizeof ($t) > 1) {
				$post->postBreak = "break";
			}
		} else {
			$post->body = str_replace ("[break]", "<a name=\"break\"> </a>", $post->body);
		}
		
		// Run some checks to make sure our paragraphs are properly closed
		$lastOpenPara = strrpos($post->body, '<p>');
		$lastClosePara = strrpos($post->body, '</p>');
		
		if ($lastOpenPara !== false && ($lastClosePara === false || $lastClosePara < $lastOpenPara)) {
			$post->body .= '</p>';
		}
	}
	
	return $post;

}

function _formatComment ($comment)
{

	$body = $comment->body;
	$body = htmlentities(htmlentities($body));
	$body = preg_replace('@http://([.\S]+)@is', '<a href="http://$1" target="_blank">http://$1</a>', $body);
	$body = implode ('</p><p>', explode (chr(13), $body));
	$body = '<p>'.str_replace(array(chr(10), chr(13)), '', $body).'</p>';
	$body = str_replace ('<p></p>', '', $body);
	$comment->body = $body;
	$comment->rfcTime = date ("Y-m-d\TH:i:s", (int)$comment->date);
	$comment->date = date("F j, Y", (int)$comment->date);
	return $comment;

}

function ext_formatPostMarkup ($post)
{

	// Markup to change
	$exp = array (	"@\[b\](.*?)\[/b\]@",
					"@\[i\](.*?)\[/i\]@",
					"@\[img=http:\/\/(.*?)\](.*?)\[/img\]@",
					"@\[url=http:\/\/(.*?)\](.*?)\[/url\]@",
					"@\|js=(.*?)=sj\|(.*?)\|@",
					"@\<p\>\[head\](.*?)\[/head\]\</p\>@",
					"@\[list\](.*?)\[/list\]@",
					"@\[item\](.*?)\[/item\]@",
					"@\[youtube=(.*?)\]@",
					"@\<p\>\[quote\](.*?)\[/quote\]\</p\>@",
					"@\[flash=(.*?)\]@");
	
	// What to change it to
	$rpl = array (	"<strong>\$1</strong>",
					"<em>\$1</em>",
					"<img src=\"http://\$1\" alt=\"\$2\" title=\"\$2\" />",
					"<a href=\"http://\$1\">\$2</a>",
					"<a href=\"javascript:\$1\">\$2</a>",
					"<h4>\$1</h4>",
					"<ul>\$1</ul>",
					"<li>\$1</li>",
					'<div id="ytd_$1"><script type="text/javascript">$(\'#ytd_$1\').youtube(\'$1\');</script></div>',
					"<blockquote><span><p>\$1</p></span></blockquote>",
					"<div id=\"flash_embed\"><script type=\"text/javascript\">\$(function(){\$(\"#flash_embed\").flash({swf:\"\$1\", width:630, height:375})});</script></div>");
					
	$post->body = preg_replace($exp, $rpl, $post->body);
	return $post;
	
}

?>