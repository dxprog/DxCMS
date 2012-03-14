<?php

function newContentPoll($type) {
	
	return Display::compile(null, 'admin_poll_form');
	
}

function syncContentPoll($type, $id = null) {
	
	$poll = new Poll($_POST['title'], null, null);
	$poll->perma = $_POST['perma'];
	$poll->body = $_POST['body'];
	$items = explode(',', $_POST['items']);
	$obj->date = time();
	$obj->tags = explode(',', $_POST['tags']);
	foreach ($items as $item) {
		$poll->addItem($item);
	}
	$poll->sync();
	
}

function editContentPoll($type, $key) {
	

	
}