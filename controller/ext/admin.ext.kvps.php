<?php

function showContentKvps($type) {

	$obj = Api\KVP::GetAll();
	return Lib\Display::compile($obj->body, 'admin_kvps_overview');

}

function newContentKvps($type) {
	
	return Lib\Display::compile(null, 'admin_kvps_create');
	
}

function syncContentKvps($type, $id = null) {

	$key = $_POST['key'];
	$value = $_POST['value'];
	if (Lib\Dx::post('kvp', 'set', array('key'=>$key), $value) === true) {
		header('Location', '/admin/kvps/overview');
	};

}

function editContentKvps($type, $key) {
	
	$result = Api\KVP::Get(array( 'key'=>$key ));
	echo $key;
	$obj = new stdClass;
	$obj->value = $result->body;
	$obj->key = $key;
	return Lib\Display::compile($obj, 'admin_kvps_create');
	
}