<?php

function renderContent() {
	
	DxDisplay::setTemplate('comic');
	
	$comic = Dx::call('comic', 'getComic', array('perma'=>$_GET['perma']));
	$comic->body->date = date('F j, Y', $comic->body->date);
	DxDisplay::setVariable('title', $comic->body->title);
	$comic = DxDisplay::compile($comic->body, 'comic');
	DxDisplay::setVariable('content', $comic);

}