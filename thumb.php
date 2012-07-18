<?php

$Max = 280;

// ob_start();

$outWidth = isset($_GET['width']) ? $_GET['width'] : false;
$outHeight = isset($_GET['height']) ? $_GET['height'] : false;

$outFile = md5 ($_GET['file'] . '_' . $outWidth . '_' . $outHeight);
$file = $_GET['file'];

// Check to see if there's a cached thumbnail already
if (file_exists ('cache/' . $outFile . '.png') && filemtime('cache/' . $outFile . '.png') > filemtime($file) && filesize('cache/' . $outFile . '.png') > 1024) {
	header ('Location: cache/' . $outFile . '.png');
	exit;
}

// If not http, map the path
$file = $file{0} == '/' ? '.' . $file : $file;
if (strpos($file, 'http') !== 0) {
	$file = realpath($file);
}

// Load the image based on extension
switch (strtolower(end(explode('.', $file)))) {
	case 'jpeg':
	case 'jpg':
		$img = imagecreatefromjpeg ($file);
		break;
	case 'png':
		$img = imagecreatefrompng ($file);
		break;
}

// Get the dimensions of the image and figure out the appropriate, rescaled size
$imgWidth = imagesx ($img);
$imgHeight = imagesy ($img);
$scaleWidth = $outWidth;
$scaleHeight = $outHeight;
$x = $y = 0;

// Scale depending on what dimensions were passed
if (!$outWidth && $outHeight) {
	$outWidth = $scaleWidth = floor($imgWidth / $imgHeight * $outHeight);
} else if (!$outHeight && $outWidth) {
	$outHeight = $scaleHeight = floor($imgHeight / $imgWidth * $outWidth);
} else {
	
	
	
}

// Create the new image and copy the resized one over
$out = imagecreatetruecolor ($outWidth, $outHeight);
imagecopyresampled ($out, $img, $x, $y, 0, 0, $scaleWidth, $scaleHeight, $imgWidth, $imgHeight);

// Save out the file and do a redirect
imagepng ($out, './cache/' . $outFile . '.png');
header ('Expires: ' . date('r', strtotime('+1 year')));
header ('Location: ./cache/' . $outFile . '.png');

?>