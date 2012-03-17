<?php

// Database credentials
define ("DB_HOST", "localhost");
define ("DB_USER", "root");
define ("DB_PASS", "PASSWORD");
define ("DB_NAME", "dxcms");

// Location of the local cache folder
$GLOBALS["_cache"] = "./cache";

// Location of the songs
$GLOBALS["_songDir"] = "./songs";
$GLOBALS["_artDir"] = "./album_art";

// User login hash
$GLOBALS["_userHash"] = md5("Hi");

// View directory
define('VIEW_PATH', './view/');

?>