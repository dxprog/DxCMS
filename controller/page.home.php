<?php

// Get the featured stuff
$xml = simplexml_load_file ("$_baseURI/api/?type=xml&method=home.getFeatured");
$features = "";
foreach ($xml->body->features->feature as $feature)
	$features .= "<li><a href=\"$feature->url\" title=\"$feature->summary\"><img src=\"http://images.dxprog.com/featured/$feature->title.jpg\" alt=\"$feature->summary\" /></a></li>";

// Get the five latest blog entries
$xml = simplexml_load_file ("$_baseURI/api/?type=xml&method=blog.getPosts&max=5");
$entries = "";
foreach ($xml->body->entry as $post)
	$entries .= "<li><a href=\"$_baseURI/entry/".$post->title->attributes ()->perma."/\">$post->title</a>$post->time</li>";
	
$GLOBALS["_content"] = "<section id=\"featured\"><ul>$features</ul></section><article><header><h1>$about_me_head</h1></header>$about_me_body</article><aside><header><h1>Latest Blog Entries</h1></header><ul>$entries</ul></aside>";

?>