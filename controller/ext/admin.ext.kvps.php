<?php

function showContentKvps($type) {

	$obj = Dx::call('kvp', 'getall', null, 0);
	return Display::compile($obj->body, 'admin_kvps_overview');

}

function newContentKvps($type) {
	
	return Display::compile(null, 'admin_kvps_create');
	
}

function syncContentKvps($type, $id = null) {

	$key = $_POST['key'];
	$value = $_POST['value'];
	if (Dx::post('kvp', 'set', array('key'=>$key), $value) === true) {
		header('Location', '/admin/kvps/overview');
	};

}

function editContentKvps($type, $key) {
	
	$result = Dx::call('kvp', 'get', array('key'=>$key), 0);
	$obj = null;
	$obj->value = $result->body;
	$obj->key = $key;
	return Display::compile($obj, 'admin_kvps_create');
	
}