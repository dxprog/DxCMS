<?php

define('POST_CACHE', 600); // Length of time to cache posts
define('ENTRIES_PER_PAGE', 5);
define('SEARCH_RESULTS_PER_PAGE', 15);

function renderContent() {

	global $_extFormatPost, $_methods;

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
	
	// If no action, default to main page
	if (!isset($_GET['action']) || !$_GET['action']) {
		$_GET['action'] = 'posts';
	}
	
	// Generate the main content
	$func = "content_".strtolower ($_SERVER["REQUEST_METHOD"]).$_GET["action"];
	if (!isset ($_methods[$func])) {
		$GLOBALS["_content"] = "Page doesn't exist";
	} else {
		$GLOBALS["_content"] = call_user_func ($func);
	}
		
	// Generate the sidebars as specified in the config file
	foreach ($_methods as $func=>$val) {
		if (strpos($func, 'content_sidebar') !== false) {
			call_user_func ($func);
		}
	}

}

function content_getEntry ($error = "", $body = "")
{

	global $_methods, $_baseURI, $_title;

	$retVal = new stdClass();
	$templateData = new stdClass();
	
	// Switch to the entry template
	DxDisplay::setTemplate('content_entry');
	
	// Get the post and log a page view
	$entry = Dx::call('content', 'getContent', array('perma'=>$_GET['perma']));
	if (null != $entry->body && isset($entry->body->content) && $entry->status->ret_code == 0) {
		$post = $entry->body->content[0];
		Dx::call('content', 'logContentView', array('id'=>$post->id), 0);
		$templateData->post = _formatPost($post);

		// Set the page title
		DxDisplay::setVariable('title', $templateData->post->title.' - '.$_title);
		
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
			$comments = Dx::call('content', 'getContent', array('parent'=>$post->id, 'max'=>0, 'order'=>'asc', 'noCount'=>true, 'noTags'=>true), 0);
			for ($i = 0, $count = sizeof($comments->body->content); $i < $count; $i++) {
				$comments->body->content[$i] = _formatComment($comments->body->content[$i]);
			}
			$templateData->comments = $comments->body->content;
		}
		
		// Get user info should there be some
		$templateData->user = content_getUser();

		// Run the post and comments through the template
		$retVal = DxDisplay::compile($templateData, "content_article");
		DxDisplay::setVariable('content', $retVal);
		content_getRelated($post->id);
	} else {
		DxDisplay::showError($entry->status->ret_code, 'Something broke along the way!');
	}

}

function content_getRss() {
	
	DxDisplay::setTemplate('rss');
	$obj = Dx::call('content', 'getContent', array('max'=>15, 'parent'=>0, 'noCount'=>true))->body->content;
	foreach ($obj as &$item) {
		$item = _formatPost($item, true);
		$item->body = $item->body;
		$item->rfcDate = date('r', $item->timestamp);
	}
	header('Content-type: text/xml');
	$render = DxDisplay::compile($obj, 'content_rss', 'RssFeed');
	DxDisplay::setVariable('rss', $render);
	
}

function content_getUser()
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

function content_postComment ()
{
	
	// Get the user's status
	$user = content_getUser();
	
	// Store all the values we'll need coming in from the form
	$sync = new stdClass();
	$sync->meta = new stdClass();
	$sync->body = $_POST['comment'];
	$sync->perma = $_GET['perma'];
	$sync->meta->user_ip = $_SERVER['REMOTE_ADDR'];
	
	// Check to see if the user is anonymous or authenticated and set values accordingly
	if ($user->showSignIn == 'true') {
		$sync->meta->user_email = $_POST['email'];
		$sync->meta->user_name = $_POST['name'];
		$sync->meta->user_avatar = $user->avatar;
		if ($_POST['botProof'] != md5($sync->meta->user_email)) {
			echo 'We don\'t serve your kind';
			exit();
		}
	} else {
		$sync->meta->user_name = $user->user_name;
		$sync->meta->user_avatar = $user->avatar;
		$sync->meta->user_auth = $user->auth_type;
	}
	
	// If all is good
	if ($sync->perma && $sync->body) {
		$ret = Dx::post('content', 'postComment', array('perma'=>$sync->perma), $sync);
		if (!$ret->status->ret_code) {
			// Clear the cache for this post
			Dx::call('content', 'getContent', array('perma'=>$_GET['perma']), 0);
			header('Location: /entry/' . $sync->perma);
		}
	}
	
}

/**
 * Retrieves blog posts based on criteria from the query string
 */
function content_getPosts ()
{
	
	global $_methods, $_baseURI, $_title, $_api;
	
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
		DxDisplay::setTemplate('content_plain');
		$homePage = false;
	}
	
	$type = '';
	if (isset($_GET['type'])) {
		$type = $_GET['type'];
	}
	
	// Grab the fifteen latest posts
	$obj = Dx::call('content', 'getContent', array('offset'=>($page - 1) * ENTRIES_PER_PAGE, 'tag'=>$tag, 'mindate'=>$minDate, 'maxdate'=>$maxDate, 'max'=>ENTRIES_PER_PAGE, 'parent'=>0, 'contentType'=>$type));
	if (null != $obj && $obj->status->ret_code == 0 && isset($obj->body->content)) {
		
		// Format each entry
		$arr = array();
		
		foreach ($obj->body->content as $post) {
			$arr[] = _formatPost ($post, true);
		}
	
		// Figure up the paging buttons
		$numPages = $obj->body->count / ENTRIES_PER_PAGE;
		$localDir = str_replace ('index.php', '', $_SERVER['SCRIPT_NAME']);
		$rawPage = preg_replace ('@/page/(\d+)/@', '/', str_replace ($localDir, '/', $_SERVER['REQUEST_URI']));

		$t->contentType = $type ? '/' . $type . '/' : '/';
		if ($numPages > $page) {
			$t->prev = $_baseURI.$rawPage.'page/'.($page + 1).'/';
		}
		if ($page > 1) {
			$t->next = $_baseURI.$rawPage.'page/'.($page - 1).'/';
		}
		
		// Run the articles through the template
		$cacheKey = null;
		if (!$homePage) {
			$cacheKey = 'BlogHome_' . $page . '_' . $minDate . '_' . $maxDate . '_' . $tag . '_' . $type;
		}
		$t->articles = $arr;
		$retVal = DxDisplay::compile($t, 'content_articles', $cacheKey);
	
	} else {
		DxDisplay::showError($obj->status->ret_code, 'There was an error siplaying that page!');
	}

	DxDisplay::setVariable('title', $_title);
	DxDisplay::setVariable('content', $retVal);
	
}

function content_getSearch() {

	global $_title;

	if ($_GET['q']) {
		
		// Switch to a basic template
		DxDisplay::setTemplate('content_plain');
		DxDisplay::setVariable('title', 'Search results - ' . $_title);
		
		// Do the search and start populating our outgoing object with related data
		$obj = new stdClass();
		$page = intVal($_GET['p']);
		$obj->query = $_GET['q'];
		$obj->results = Dx::call('content', 'search', array('q'=>$_GET['q'], 'noTags'=>true, 'page'=>$page, 'max'=>SEARCH_RESULTS_PER_PAGE));
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
			$item = _formatPost($item, true);
			$item->date = date('F j, Y', $item->timestamp);
			$item->body = preg_replace('/<[^>]*>/', '', $item->body);
			$item->body = _truncateText($item->body, 140);
		}
		
		// Display
		$content = html_entity_decode(DxDisplay::compile($obj, 'content_search', 0, 0));
		DxDisplay::setVariable('content', $content);
	}

}

function content_getRelated($id) {
	
	global $_baseURI;
	$cacheKey = 'RelatedPosts_' . $id;
	$obj = DxCache::Get($cacheKey);
	if ($obj === false) {
		$obj = Dx::call('content', 'getRelated', array('id'=>$id));
	}
	$retVal = DxDisplay::compile($obj->body, 'content_related');
	DxDisplay::setVariable('related', $retVal);
	
}

function content_sidebarFeatured () {
	
	$retVal = '';
	$t = new stdClass();
	$t->featured = true;
	$obj = Dx::call('content', 'getContent', array('meta'=>$t, 'select'=>'title,perma,meta', 'max'=>4));
	$retVal = DxDisplay::compile($obj->body, 'content_featured');
	DxDisplay::setVariable('featured', $retVal);
	
}

function content_sidebarArchives ()
{

	global $_baseURI;

	$obj = new stdClass();
	
	// First, check to see if an archive cache file exists and it's not too old
	$cacheKey = "sidebar_archives";
	$obj = DxCache::Get("sidebar_archives");
	if ($obj === false) {
	
		$t = Dx::call('content', 'getArchives');
		
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
		DxCache::Set($cacheKey, $obj);
		
	}
	
	return DxDisplay::compile((string)$obj->xml, "content_archives");
	
}

function content_sidebarPopular ()
{

	$cacheKey = 'sidebar_mostpopular';
	$obj = DxCache::Get($cacheKey);
	if ($obj === false) {
		$obj = Dx::Call('content', 'getPopular', array('max'=>5));
		DxCache::Set($cacheKey, $obj->body);
		$obj = $obj->body;
	}

	$retVal = DxDisplay::compile($obj, 'content_mostpopular');
	DxDisplay::setVariable('popular', $retVal);
	
}

function content_sidebarTagCloud ()
{
	
	global $_baseURI;
	
	$obj = new stdClass();
	$retVal = '';
	
	// Check for a cached version before continuing
	$cacheKey = 'sidebar_tagcloud';
	$retVal = DxCache::Get($cacheKey);
	if ($retVal === false) {
		
		// Retrieve the top 25 tags
		$obj = Dx::call('content', 'getTags', array('max'=>25, 'type'=>'blog'));
		$obj = $obj->body;
		
	}
	
	$retVal = DxDisplay::compile($obj, 'content_tagcloud', $cacheKey);
	DxDisplay::setVariable('tagcloud', $retVal);
	
}

function revertXMLSafe ($string)
{
	$replace = array ("\"", "'", ">", "<", "&");
	$find = array ("&quot;", "&apos;", "&gt;", "&lt;", "&amp;");
	return str_replace ($find, $replace, $string);
}
 
function _truncateText ($text, $length)
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
 
?>