<?php

namespace Controller {

	class Markup implements Extension {

		public static function init() {
			Content::registerExtension('Markup', 'formatPostMarkup', 'formatter');
		}

		public static function formatPostMarkup($post)
		{

			// Markup to change
			$exp = array (	"@\[b\](.*?)\[/b\]@",
							"@\[i\](.*?)\[/i\]@",
							"@\[img=http:\/\/(.*?)\](.*?)\[/img\]@",
							"@\[url=http:\/\/(.*?)\](.*?)\[/url\]@",
							"@\|js=(.*?)=sj\|(.*?)\|@",
							"@\<p\>\[head\](.*?)\[/head\]\</p\>@",
							"@\<p\>\[header\](.*?)\[/header\]\</p\>@",
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
							"<h4>\$1</h4>",
							"<ul>\$1</ul>",
							"<li>\$1</li>",
							'<div id="ytd_$1"><script type="text/javascript">$(\'#ytd_$1\').youtube(\'$1\');</script></div>',
							"<blockquote><span><p>\$1</p></span></blockquote>",
							"<div id=\"flash_embed\"><script type=\"text/javascript\">\$(function(){\$(\"#flash_embed\").flash({swf:\"\$1\", width:630, height:375})});</script></div>");
							
			$post->body = preg_replace($exp, $rpl, $post->body);
			return $post;
			
		}
		
	}

	Markup::init();

}