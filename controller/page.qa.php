<?php

include('./pages/ext/content.ext.twitter.php');

function renderContent() {
	
	
	$xml = '<root>';
	if (isset($_POST['submit'])) {
		$xml .= postQuestion();
	}
	
	$obj = Dx::Call('content', 'getContent', array('contentType'=>'qa', 'max'=>0));
	$ser = new SerializeXML();
	$xml .= str_replace('<?xml version="1.0" encoding="ISO-8859-1"?>', '', $ser->serialize($obj, 'qa_obj'));
	unset($ser);
	$xml .= '</root>';
	
	// Display stuff
	content_sidebarTwitter();
	$content = Display::Compile($xml, 'qa');
	Display::setTemplate('about');
	Display::setVariable('content', $content);
	
}

function postQuestion() {

	$retVal = '';

	$question = isset($_POST['question']) ? $_POST['question'] : false;
	$checksum = isset($_POST['checksum']) ? $_POST['checksum'] : false;
	
	if ($question && md5($question) == $checksum) {
		$obj = new stdClass();
		$obj->title = $question;
		$obj->date = time();
		$obj->type = 'qa';
		$obj->parent = 1;
		$obj->id = null;
		$obj->body = null;
		$obj->meta = null;
		$obj->tags = null;
		Dx::post('content', 'syncContent', null, $obj);
		$retVal = '<message>Thanks for your question! I\'ll probably have it answered in a day or so, so keep checking back!</message>';		
	} else {
		$retVal = '<message>Oh, snap! Looks like something went wrong. I\'ve sent my minions to check it out.</message>';
	}

	return $retVal;
	
}