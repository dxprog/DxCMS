<?php

namespace Controller {

	use Api;
	use Lib;
	use stdClass;

	define('POST_CACHE', 600); // Length of time to cache posts
	define('ENTRIES_PER_PAGE', 5);
	define('SEARCH_RESULTS_PER_PAGE', 15);

	class Content implements Page {
		
		// Array of registered extensions
		private static $_extensions = array();
		
		// Dynamic function for formatting a post with all the included extensions
		public static $_funcFormatter = null;
		
		/**
		 * Renders page depending on incoming parameters
		 */
		public static function render() {

			// Get a list of all the post formatting static functions and create a master formatting static function from those
			$formatPost = '';
			foreach (self::$_extensions as $extension) {
				if ($extension->type === 'formatter') {
					$formatPost .= '$body = Controller\\' . $extension->class . '::' . $extension->method . '($body);';
				}
			}
			$formatPost .= 'return $body;';
			self::$_funcFormatter = create_function('$body', $formatPost);
			
			// Register the generic display extensions
			Lib\Display::registerExtension('Content', 'sidebarTagcloud', 'tagcloud');
			Lib\Display::registerExtension('Content', 'sidebarPopular', 'popular');
			
			// If no action, default to main page
			if (!isset($_GET['action']) || !$_GET['action']) {
				$_GET['action'] = 'posts';
			}
			
			// Generate the main content
			$method = '_' . strtolower($_SERVER['REQUEST_METHOD']) . $_GET['action'];
			if (!method_exists ('Controller\\Content', $method)) {
				Lib\Display::setVariable('content', 'Page doesn\'t exist');
			} else {
				$GLOBALS['_content'] = call_user_func(array('Controller\\Content', $method));
			}

		}
		
		/**
		 * Allows extensions to register their functionality with the main class
		 */
		public static function registerExtension($class, $method, $type)
		{
			$obj = new stdClass();
			$obj->class = $class;
			$obj->method = $method;
			$obj->type = $type;
			self::$_extensions[] = $obj;
		}
		
		/**
		 * Executes all registered sidebar extensions
		 */
		private static function renderSidebars() {
		
		}
		
		private static function _getEntry ($error = "", $body = "")
		{

			global $_baseURI, $_title;

			$retVal = new stdClass();
			$templateData = new stdClass();
			
			// Switch to the entry template
			Lib\Display::setTemplate('content_entry');
			
			// Get the post and log a page view
			$entry = Lib\Dx::call('content', 'getContent', array('perma'=>$_GET['perma']));
			if (null != $entry->body && isset($entry->body->content) && $entry->status->ret_code == 0) {
				$post = $entry->body->content[0];
				Lib\Dx::call('content', 'logContentView', array('id'=>$post->id), 0);
				$templateData->post = self::_formatPost($post);

				// Set the page title
				Lib\Display::setVariable('title', $templateData->post->title.' - '.$_title);
				
				// Check for flash content
				if (isset($post->meta->file)) {
					$file = strtolower($post->meta->file);
					if (strlen($file) > 0) {
						$ext = explode('.', $file);
						$templateData->post->meta->fileType = $ext[count($ext) - 1];
					}
				}
				
				// Get user comments if there are any to get
				if ($post->children > 0) {
					$comments = Lib\Dx::call('content', 'getContent', array('parent'=>$post->id, 'max'=>0, 'order'=>'asc', 'noCount'=>true, 'noTags'=>true), 0);
					for ($i = 0, $count = sizeof($comments->body->content); $i < $count; $i++) {
						$comments->body->content[$i] = self::_formatComment($comments->body->content[$i]);
					}
					$templateData->comments = $comments->body->content;
				}
				
				// Get user info should there be some
				$templateData->user = self::_getUser();

				// Run the post and comments through the template
				$retVal = Lib\Display::compile($templateData, "content_article");
				Lib\Display::setVariable('content', $retVal);
				self::_getRelated($post->id);
			} else {
				Lib\Display::showError($entry->status->ret_code, 'Something broke along the way!');
			}

		}

		private static function _getRss() {
			
			Lib\Display::setTemplate('rss');
			$obj = Lib\Dx::call('content', 'getContent', array('max'=>15, 'parent'=>0, 'noCount'=>true))->body->content;
			foreach ($obj as &$item) {
				$item = self::_formatPost($item, true);
				$item->body = $item->body;
				$item->rfcDate = date('r', $item->timestamp);
			}
			header('Content-type: text/xml');
			$render = Lib\Display::compile($obj, 'content_rss', 'RssFeed');
			Lib\Display::setVariable('rss', $render);
			
		}

		private static function _getUser()
		{

			$retVal = new stdClass();
			
			if (isset($_COOKIE['authType']) && isset($_COOKIE['authUser'])) {
				
				$authType = $_COOKIE['authType'];
				switch ($authType) {
					case 'twitter':
					case 'facebook':
						$retVal->auth_type = $authType;
						$retVal->user_name = $_COOKIE['authUser'];
						$retVal->avatar = $_COOKIE['authAvatar'];
						$retVal->showSignIn = 'false';
						break;
					default:
						$retVal->avatar = 'http://images.dxprog.com/anonymous_coward.png';
						$retVal->user_name = $_COOKIE['userName'];
						$retVal->email = $_COOKIE['email'];
						$retVal->showSignIn = 'true';
				}
				
			} else {
				
				$retVal->avatar = 'http://images.dxprog.com/anonymous_coward.png';
				$retVal->user_name = '';
				$retVal->email = '';
				$retVal->showSignIn = 'true';
				
			}
			
			return $retVal;

		}

		private static function _postComment ()
		{
			
			// Get the user's status
			$user = self::_getUser();
			$parent = Api\Content::getIdFromPerma($_GET['perma']);
			
			if ($parent) {
			
				// Store all the values we'll need coming in from the form
				$sync = new stdClass();
				$sync->meta = new stdClass();
				$sync->body = $_POST['comment'];
				$sync->type = 'cmmnt';
				$sync->parent = $parent;
				$sync->meta->user_ip = $_SERVER['REMOTE_ADDR'];
				if (isset($_POST['comment_parent']) && $_POST['comment_parent'] > 0) {
					$sync->meta->comment_parent = intVal($_POST['comment_parent']);
				}
				
				// Check to see if the user is anonymous or authenticated and set values accordingly
				if ($user->showSignIn == 'true') {
					$sync->meta->user_email = $_POST['email'];
					$sync->meta->user_name = $_POST['name'];
					$sync->meta->user_avatar = $user->avatar;
					if ($_POST['botProof'] != md5($sync->meta->user_email)) {
						echo 'We don\'t serve your kind';
						exit;
					}
				} else {
					$sync->meta->user_name = $user->user_name;
					$sync->meta->user_avatar = $user->avatar;
					$sync->meta->user_auth = $user->auth_type;
				}
				
				// If all is good
				if ($sync->body) {
					$ret = Api\Content::syncContent(null, $sync);
					if (null !== $ret) {
						// Clear the cache for this post
						Lib\Dx::call('content', 'getContent', array('perma'=>$_GET['perma']), 0);
						header('Location: /entry/' . $_GET['perma']);
					} else {
						throw new Exception('Unable to post comment');
					}
				}
				
			}
			
		}

		/**
		 * Retrieves blog posts based on criteria from the query string
		 */
		private static function _getPosts ()
		{
			
			global $_baseURI, $_title, $_api;
			
			// Declarations
			$title = 'Blog - '.$_title;
			$retVal = new stdClass();
			$minDate = null;
			$maxDate = null;
			$tag = '';
			$page = '';
			
			// Get the page
			if (isset($_GET['p']) && is_numeric ($_GET['p'])) {
				$page = $_GET['p'];
			} else {
				$page = 1;
			}
			
			// Figure up tags
			if (isset($_GET['tag']) && $_GET['tag']) {
				$tag = urldecode($_GET['tag']);
				$title = 'Blog - Posts tagged with ' . $tag . ' - ' . $_title;
			}
			
			// Check the dates
			if (isset($_GET['month']) && isset($_GET['year']) && is_numeric ($_GET['month']) && is_numeric ($_GET['year'])) {
				$minDate = mktime (0, 0, 0, $_GET['month'], 1, $_GET['year']);
				$maxDate = mktime (0, 0, 0, $_GET['month'] + 1, 1, $_GET['year']);
				$title = "Blog - Posts from ".date("F Y", $minDate)." - $_title";
			}
			
			// If we're not on the home page, use the blog template
			$homePage = true;
			if ($page > 1 || $tag || $minDate || $maxDate) {
				Lib\Display::setTemplate('content_plain');
				$homePage = false;
			}
			
			$type = '';
			if (isset($_GET['type'])) {
				$type = $_GET['type'];
			}
			
			// Generate the cache key
			$cacheKey = 'BlogHome_' . $page . '_' . $minDate . '_' . $maxDate . '_' . $tag . '_' . $type;
			
			$retVal = Lib\Cache::Get($cacheKey);
			if (!$retVal) {
			
				// Grab the fifteen latest posts
				$retVal = new stdClass();
				$obj = Lib\Dx::call('content', 'getContent', array('offset'=>($page - 1) * ENTRIES_PER_PAGE, 'tag'=>$tag, 'mindate'=>$minDate, 'maxdate'=>$maxDate, 'max'=>ENTRIES_PER_PAGE, 'parent'=>0, 'contentType'=>$type));
				if (null != $obj && $obj->status->ret_code == 0 && isset($obj->body->content)) {
					
					// Format each entry
					$arr = array();
					
					foreach ($obj->body->content as $post) {
						$arr[] = self::_formatPost ($post, true);
					}
				
					// Figure up the paging buttons
					$numPages = $obj->body->count / ENTRIES_PER_PAGE;
					$localDir = str_replace ('index.php', '', $_SERVER['SCRIPT_NAME']);
					$rawPage = preg_replace ('@/page/(\d+)/@', '/', str_replace ($localDir, '/', $_SERVER['REQUEST_URI']));

					$t->contentType = $type ? '/' . $type . '/' : '/';
					if ($numPages > $page) {
						$t->prev = $_baseURI . '?p='.($page + 1);
					}
					if ($page > 1) {
						$t->next = $_baseURI . '?p='.($page - 1);
					}
				
					$t->articles = $arr;
					$retVal = Lib\Display::compile($t, 'content_articles', $cacheKey);
				
				} else {
					Lib\Display::showError($obj->status->ret_code, 'There was an error siplaying that page!');
				}
			}

			Lib\Display::setVariable('title', $_title);
			Lib\Display::setVariable('content', $retVal);
			
		}

		private static function _getSearch() {

			global $_title;

			if ($_GET['q']) {
				
				// Switch to a basic template
				Lib\Display::setTemplate('content_plain');
				Lib\Display::setVariable('title', 'Search results - ' . $_title);
				
				// Do the search and start populating our outgoing object with related data
				$obj = new stdClass();
				$page = intVal($_GET['p']);
				$obj->query = $_GET['q'];
				$obj->results = Lib\Dx::call('content', 'search', array('q'=>$_GET['q'], 'noTags'=>true, 'page'=>$page, 'max'=>SEARCH_RESULTS_PER_PAGE));
				$obj->speed = substr((string)$obj->results->metrics->gen_time, 0, 7);
				$obj->count = $obj->results->body->count;
				
				// Figure up the paging buttons
				$numPages = ceil($obj->results->body->count / SEARCH_RESULTS_PER_PAGE);
				$localDir = str_replace ('index.php', '', $_SERVER['SCRIPT_NAME']);
				$rawPage = preg_replace ('@/page/(\d+)/@', '/', str_replace ($localDir, '/', $_SERVER['REQUEST_URI']));
				$obj->page = $page;
				$obj->firstResult = $page * SEARCH_RESULTS_PER_PAGE - SEARCH_RESULTS_PER_PAGE + 1;

				if ($numPages > $page) {
					$obj->next = '/search/' . $obj->query . '/page/'.($page + 1).'/';
				}
				if ($page > 1) {
					$obj->prev = '/search/' . $obj->query . '/page/'.($page - 1).'/';
				}
				
				// Do formatting on the posts, strip out the HTML and truncate the body
				$obj->results = $obj->results->body->results;
				foreach ($obj->results as &$item) {
					$item = self::_formatPost($item, true);
					$item->date = date('F j, Y', $item->timestamp);
					$item->body = preg_replace('/<[^>]*>/', '', $item->body);
					$item->body = _truncateText($item->body, 140);
				}
				
				// Display
				$content = html_entity_decode(Lib\Display::compile($obj, 'content_search', 0, 0));
				Lib\Display::setVariable('content', $content);
			}

		}

		private static function _getRelated($id) {
			
			global $_baseURI;
			$cacheKey = 'RelatedPosts_' . $id;
			$obj = Lib\Cache::Get($cacheKey);
			if ($obj === false) {
				$obj = Lib\Dx::call('content', 'getRelated', array('id'=>$id));
			}
			
			$retVal = Lib\Display::compile($obj->body, 'content_related');
			Lib\Display::setVariable('related', $retVal);
			
		}

		private static function _sidebarArchives ()
		{

			global $_baseURI;

			$obj = new stdClass();
			
			// First, check to see if an archive cache file exists and it's not too old
			$cacheKey = "sidebar_archives";
			$obj = Lib\Cache::Get("sidebar_archives");
			if ($obj === false) {
			
				$t = Lib\Dx::call('content', 'getArchives');
				
				// Format the archives
				$out = new stdClass();
				$out->archives = array();
				foreach ($t->body as $d) {
					$t = new stdClass();
					$t->url = $_baseURI.'/archives/'.date ("m/Y", $d->timestamp).'/';
					$t->title = $d->text;
					$out->archives[] = $t;
				}
				
				// Cache the bitch
				$xs = new SerializeXML();
				$obj->xml = $xs->serialize($out, "content_archives");
				Lib\Cache::Set($cacheKey, $obj);
				
			}
			
			return Lib\Display::compile((string)$obj->xml, "content_archives");
			
		}

		public static function sidebarPopular ()
		{
			$cacheKey = 'sidebar_mostpopular';
			$retVal = Lib\Cache::Get($cacheKey);
			
			if ($retVal === false) {
				$obj = Lib\Dx::Call('content', 'getPopular', array('max'=>5), 0);
				$obj = $obj->body;
				$retVal = Lib\Display::compile($obj, 'content_mostpopular', $cacheKey);
			}
			
			return $retVal;
		}

		public static function sidebarTagCloud ()
		{
			// Check for a cached version before continuing
			$cacheKey = 'sidebar_tagcloud';
			$retVal = Lib\Cache::Get($cacheKey);
			if ($retVal === false) {
				// Retrieve the top 25 tags
				$obj = Lib\Dx::call('content', 'getTagsByPopularity', array('max'=>25, 'type'=>'blog'), 0);
				$obj = $obj->body;
				$retVal = Lib\Display::compile($obj, 'content_tagcloud');
			}
			
			return $retVal;
		}

		static function revertXMLSafe ($string)
		{
			$replace = array ("\"", "'", ">", "<", "&");
			$find = array ("&quot;", "&apos;", "&gt;", "&lt;", "&amp;");
			return str_replace ($find, $replace, $string);
		}
		 
		static function _truncateText ($text, $length)
		{

			$retVal = $text;
			if (strlen ($text) > $length) {
				$pos = $length;
				while (isset($text{$pos}) && $text{$pos} != " " && $text{$pos} != "." && $text{$pos} != "\n") {
					$pos++;
				}
				return substr ($text, 0, $pos)."...";
			}
			return $retVal;

		}
		
		private static function _formatPost ($post, $convBreak = false) {

			global $_baseURI, $_extFormatPost;
			
			// Format the tags
			if (is_object($post)) {
				$cacheKey = 'formatted_content_'.$post->id;
				$cacheResult = Lib\Cache::Get($cacheKey);
				if ($cacheResult === false) {
					
					// Break new lines in the body up into paragraphs and then run it through any post formatting extensions
					$disableFormatting = isset($post->meta->formatting) ? $post->meta->formatting : false;
					if (!$disableFormatting) {
						$body = htmlentities($post->body);
						$body = '<p>' . str_replace(array("\r\n", "\r", "\n"), '</p><p>', $body) . '</p>';
						$body = str_replace ('<p></p>', '', $body);
						$body = preg_replace ("@\<p\>(\<div(.*?)\>(.*?)\</div\>)\</p\>@is", '$1', $body);
						$post->body = $body;
						if (is_callable(self::$_funcFormatter)) {
							$post = call_user_func(self::$_funcFormatter, $post);
						}
					}
					
					$post->day = date("j", $post->date);
					$post->month = date("M", $post->date);
					$post->year = date("Y", $post->date);
					$post->timestamp = $post->date;
					$post->rfcDate = date("Y-m-d\TH:i:s", $post->date);
					$post->date = date("F j, Y", $post->date);
					
					// Write to cache
					Lib\Cache::Set($cacheKey, $post);
					
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

			}
			
			return $post;

		}

		private static function _formatComment ($comment)
		{

			$body = $comment->body;
			$body = htmlentities($body);
			$body = preg_replace('@http://([.\S]+)@is', '<a href="http://$1" target="_blank">http://$1</a>', $body);
			$body = implode ('</p><p>', explode (chr(13), $body));
			$body = '<p>'.str_replace(array(chr(10), chr(13)), '', $body).'</p>';
			$body = str_replace ('<p></p>', '', $body);
			$comment->body = $body;
			$comment->rfcTime = date ("Y-m-d\TH:i:s", (int)$comment->date);
			$comment->date = date("F j, Y", (int)$comment->date);
			return $comment;

		}
		
	}
		
}
	