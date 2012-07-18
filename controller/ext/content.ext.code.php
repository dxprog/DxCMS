<?php

namespace Controller {

	/**
	 * Control structures for ECMA based languages
	 */

	class Code implements Extension {
		
		private static $_ECMAkeywords = '#[\w]+|var|static|private|public|new|using|import|class|namespace|void|function|int|char|float|double|if|else|elseif|for|foreach|each|try|catch|do|while|switch|return|break|goto|continue';
		private static $_phpFunctions = 'print_r|include|include_once|require|require_once|exit|fgets|fsockopen|fopen|fputs|fwrite|fread|echo|strtolower|strtoupper|define|print|die';
		
		public static function init() {
			Content::registerExtension('Code', 'formatPostCode', 'formatter');
		}
		
		public static function formatPostCode ($post)
		{
			
			// Match all code tags
			if (preg_match_all ("@\[code=(.*?)\](.*?)\[/code\]@is", $post->body, $matches)) {
				for ($i = 0; $i < sizeof ($matches[0]); $i++) {
					
					// Add the PHP tags in if that is the set language as well as additional keyword highlighting
					$code = $matches[2][$i];
					$lang = strtolower ($matches[1][$i]);
					
					// Remove all paragraph tags from the code bits
					$code = str_replace (array ('<p>', '</p>', "\n\n"), "\n", $code);
					
					// Do syntax highlighting
					$code = self::_highlightSyntax ($code, $lang);
					
					// Break each new line up and stuff it into a list with line numbers
					if ($code{0} == "\n") {
						$code = substr ($code, 1);
					}
					$t = explode ("\n", $code);
					$n = '';
					for ($j = 0; $j < sizeof ($t); $j++) {
						$n .= '<li>' . $t[$j] . '</li>';
					}
					if ($lang == 'php') {
						$n = '<li><span class="D">&#60;?php</span></li><li></li>' . $n . '<li><span class="D">?&#62;</span></li>';
					}
					
					$post->body = str_replace ($matches[0][$i], '<div class="code"><span>Code: ' . $lang . '</span><ol>' . $n . '</ol></div>', $post->body);
					
				}
			}
			
			return $post;
		}

		private function _highlightSyntax ($s, $type)
		{
			
			// Highlight based on the language
			$keywords = "";
			switch ($type) {
				case 'php':
					$keywords = self::$_phpFunctions . '|';
				case 'actionscript':
				case 'javascript':
				case 'json':
				case 'c#':
				case 'c':
				case 'c++':
					$keywords .= self::$_ECMAkeywords;
					break;
			}
			
			// Replacement expressions
			$exp = array (	'/&quot;(.*?)&quot;/',
							'/(\/\*.*?\*\/)/',
							'/(' . $keywords . ')/');
			$rpl = array (	'<S>"$1"</S>',
							'<C>$1</C>',
							'<K>$1</K>');

			$s = preg_replace ($exp, $rpl, $s);
			
			// Highlight single line comments
			$end = 0;
			while (($start = @strpos ($s, '//', $end)) !== false) {
				
				// Find the end of the line
				if (($end = @strpos ($s, "\n", $start)) === false) {
					$end = strlen ($s);
				}
				
				// Replace the text with a comment tag (be sure to strip out other tags as well
				$old = substr ($s, $start, $end - $start);
				$new = preg_replace ('/\<(S|C|K)\>(.*?)\<\/(S|C|K)\>/', '$2', $old);
				$s = str_replace ($old, '<C>' . $new . '</C>', $s);
				
			}
			
			// Replace the pseudo tags with HTML formatting
			$s = preg_replace ('/\<(S|C|K)\>(.*?)/', '<span class="$1">$2', $s);
			$s = preg_replace('/\<\/\w\>/', '</span>', $s);
			
			return $s;

		}

	}

	Code::init();
		
}