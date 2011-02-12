<?php

// Include the content formatting functions
include('./pages/ext/content.ext.formatting.php');

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

/**
 * Content rendering logic
 */
function renderContent() {

	$action = isset($_GET['action']) ? $_GET['action'] : null;

	switch ($action) {
		case 'item':
			showGalleryItem($_GET['perma']);
			break;
		default:
			showAllGalleryItems();
			break;
	}

}

/**
 * Renders gallery content selecting out a particular content type
 */
function showAllGalleryItems($type = null) {
	
	global $_title;
	
	// Change the template and set the title variables
	DxDisplay::setTemplate('gallery');
	DxDisplay::setVariable('title', ucfirst($_GET['type']) . ' - ' . $_title);
	DxDisplay::setVariable('section', ucfirst($_GET['type']));
	
	// Get the items, display them
	$items = Dx::call('content', 'getContent', array('contentType'=>$_GET['type'], 'max'=>0, 'noCount'=>true, 'noTags'=>true));
	$render = DxDisplay::compile($items, 'gallery');
	DxDisplay::setVariable('content', $render);
	
}

/**
 * Gets a gallery item and displays it in a "chromeless" template
 */
function showGalleryItem($perma) {
	
	global $_title;
	
	// Get the item from the database and log a view on it
	$item = Dx::call('content', 'getContent', array('perma'=>$perma));
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
		
		$render = DxDisplay::compile($obj, 'content_article');
		DxDisplay::setTemplate('gallery_item');
		DxDisplay::setVariable('title', $obj->post->title . ' - ' . $_title);
		DxDisplay::setVariable('content', $render);
	} else {
		DxDisplay::showError(404, 'We weren\'t able to find what you were looking for.');
	}
	
}