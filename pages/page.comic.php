<?php

function renderContent() {
	
	DxDisplay::setTemplate('comic');
	
	$perma = isset($_GET['perma']) ? $_GET['perma'] : '';
	$comic = Dx::call('comic', 'getComic', array('perma'=>$perma, 'contentType'=>'comic'));
	if (null != $comic && $comic->status->ret_code == 0 && null != $comic->body) {
		$comic->body->date = date('F j, Y', $comic->body->date);
		DxDisplay::setVariable('title', $comic->body->title);
		$comic = DxDisplay::compile($comic->body, 'comic');
		DxDisplay::setVariable('content', $comic);
	} else {
		DxDisplay::showError($comic->status->ret_code, 'Something went boom!');
	}

}