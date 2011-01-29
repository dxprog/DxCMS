<?php

define('UPLOAD_DIRECTORY', '/uploads/');

function renderContent() {

	DxDisplay::setTemplate('admin');

	$user = Dx::call('user', 'getUserFromSession', null, 0)->body;
	if ($user === false) {
		if ($_GET['action'] == 'login') {
			$user = Dx::call('user', 'login', array('user'=>$_POST['username'], 'pass'=>md5($_POST['password'])));
		}
		if ($user === false) {
			DxDisplay::setVariable('content', DxDisplay::compile('<admin />', 'admin_login'));
		} else {
			header('Location: /admin/');
		}
	} else {
		
		switch ($_GET['action']) {
			case 'new':
				newContent($_GET['type']);
				break;
			case 'create':
				syncContent($_GET['type'], 0);
				break;
			case 'edit':
				editContent($_GET['type'], $_GET['id']);
				break;
			case 'update':
				syncContent($_GET['type'], $_GET['id']);
				header('Location: /admin/' . $_GET['type'] . '/overview/');
				break;
			case 'overview':
				showContent($_GET['type']);
				break;
			case 'upload':
				iframeUploadFile();
				break;
			default:
				echo 'Default';
				break;
		}
		
	}
	
}

function newContent($type) {

	$render = '';
	if (function_exists('newcontent' . $type)) {
		$render = call_user_func('newcontent' . $type, $type);
	} else {
		$render = DxDisplay::Compile('<admin />', 'admin_' . $type . '_form');
	}
	DxDisplay::setVariable('content', $render);

}

function syncContent($type, $id) {

	if (function_exists('synccontent' . $type)) {
		$ret = call_user_func('synccontent' . $type, $type);
	} else {
		$obj = null;
		if (is_numeric($id) && $id > 0) {
			$obj->id = $id;
		}
		$obj->title = $_POST['title'];
		$obj->perma = $_POST['perma'];
		$obj->body = $_POST['body'];
		$obj->date = strlen($_POST['date']) > 0 ? strtotime($_POST['date']) : time();
		$obj->tags = explode(',', $_POST['tags']);
		$obj->type = $_POST['contentType'];
		$obj->parent = 0;
		$obj->meta = null;

		switch ($type) {
			case 'gallery':
				$obj->meta->formatting = $_POST['formatting'] == 'on';
				$obj->meta->thumb = $_POST['thumb_file'];
				$obj->meta->file = $_POST['item_file'];
				$obj->meta->width = intVal($_POST['width']);
				$obj->meta->height = intVal($_POST['height']);
				$obj->meta->ratio = round(intVal($_POST['width']) / intVal($_POST['height']), 4);
				break;
			case 'featured':
				
		}
		
		$ret = Dx::post('content', 'syncContent', null, $obj);
		
	}
	
}

function showContent($type) {
	
	if (function_exists('showcontent' . $type)) {
		$render = call_user_func('showcontent' . $type, $type);
	} else {
		$obj = Dx::call('content', 'getContent', array('contentType'=>$type, 'page'=>2), 0)->body;
		for ($i = 0, $count = count($obj->content); $i < $count; $i++) {
			$obj->content[$i]->displayDate = date('m/d/Y H:i:s', $obj->content[$i]->date);
		}
		$render = DxDisplay::compile($obj, 'admin_' . $type . '_overview');
	}
	
	DxDisplay::setVariable('content', $render);
	
}

function showContentAll($type) {
	return Dx::call('content', 'getContent', null);
}

function showContentGallery($type) {

	$obj = Dx::call('content', 'getContent', array('contentType'=>array('art', 'portfolio', 'video'), 'max'=>30), 0)->body;
	for ($i = 0, $count = count($obj->content); $i < $count; $i++) {
		$obj->content[$i]->displayDate = date('m/d/Y H:i:s', $obj->content[$i]->date);
	}
	$render = DxDisplay::compile($obj, 'admin_gallery_overview');
	return $render;

}

function editContent($type, $id) {

	if (is_numeric($id)) {
	
		$obj = Dx::call('content', 'getContent', array('id'=>$id), 0)->body->content[0];
		$obj->displayDate = date('m/d/Y', $obj->date);
		$render = DxDisplay::compile($obj, 'admin_' . $type . '_form');
		DxDisplay::setVariable('content', $render);
	
	}

}

function iframeUploadFile() {

	$uploadId = $_POST['uploadId'];
	$retVal = '{"status":"FAIL", "uploadId":"' . $uploadId . '"}';

	// Make an absolute path to the upload directory
	$uploadPath = str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['SCRIPT_FILENAME']) . UPLOAD_DIRECTORY;
	DxDisplay::setTemplate('upload');
	
	// Upload the file
	$file = $_FILES[$_GET['control']];
	if (is_uploaded_file($file['tmp_name'])) {
		if (move_uploaded_file($file['tmp_name'], $uploadPath . $file['name'])) {
			$retVal = '{"status":"OK","file":"' . UPLOAD_DIRECTORY . $file['name'] . '", "control":"' . $_GET['control'] . '", "uploadId":"' . $uploadId . '"}';
		}
	} else {
		
	}

	DxDisplay::setVariable('upload_info', $retVal);
	
}