<?php

$Max = 125;

// Check to see if there's a cached thumbnail already
if (file_exists ("./cache/".md5 ($_GET["file"]).".png")) {
	header ("Location: ./cache/".md5 ($_GET["file"]).".png");
	exit ();
}

// Load the image based on extension
switch (strtolower (substr ($_GET["file"], strlen ($_GET["file"]) - 3, 3))) {
case "jpg":
	$Image = imagecreatefromjpeg ($_GET["file"]);
	break;
case "png":
	$Image = imagecreatefrompng ($_GET["file"]);
	break;
}

// Get the dimensions of the image and figure out the appropriate, rescaled size
$Width = imagesx ($Image);
$Height = imagesy ($Image);
$NWidth = $Width < $Max ? $Width : $Max;
$NHeight = $Height < $Max ? $Height : $Max;

if ($Width > $Max && $Width > $Height) {
	$NWidth = $Max;
	$NHeight = $Max * ($Height / $Width);
}

if ($Height > $Max && $Height > $Width) {
	$NHeight = $Max;
	$NWidth = $Max * ($Width / $Height);
}

$x = ($Max - $NWidth) / 2;
$y = ($Max - $NHeight) / 2;

// Create the new image and copy the resized one over
$Out = imagecreatetruecolor ($Max, $Max);
imagecopyresampled ($Out, $Image, $x, $y, 0, 0, $NWidth, $NHeight, $Width, $Height);

// Save out the file and do a redirect
imagepng ($Out, "./cache/".md5 ($_GET["file"]).".png");
header ("Location: ./cache/".md5 ($_GET["file"]).".png");

?>