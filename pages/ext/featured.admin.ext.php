<?php

function showContentFeatured($type) {

	$obj = null;
	$obj->featured = 'true';
	$obj = Dx::call('content', 'getContent', array('meta'=>$obj), 0);
	for ($i = 0, $count = count($obj->content); $i < $count; $i++) {
		$obj->content[$i]->displayDate = date('m/d/Y H:i:s', $obj->content[$i]->date);
	}
	$render = DxDisplay::compile($obj, 'admin_gallery_overview');
	return $render;
	
}

function newContentFeatured($type) {

	$obj = Dx::call('content', 'getContent', array('contentType'=>'blog,art,video,portfolio,comic', 'noCount'=>true, 'noTags'=>true, 'select'=>'title'), 0);
	$retVal = DxDisplay::compile($obj, 'admin_featured_form');
	return $retVal;
	
}

function syncContentFeatured($type) {

	$obj = Dx::call('content', 'getContent', array('id'=>$_POST['contentId']), 0)->body->content[0];
	$obj->meta->featured = true;
	$obj->meta->featured_teaser = $_POST['teaser'];
	$obj->meta->featured_image = $_POST['image_file'];
	return Dx::post('content', 'syncContent', array('noTags'=>true), $obj);

}