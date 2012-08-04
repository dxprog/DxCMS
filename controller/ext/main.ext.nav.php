<?php

{
	
	// Pluck off the first part of the URL
	$urlChunks = explode('/', $_SERVER['REQUEST_URI']);
	$page = next($urlChunks) ?: '/';
	
	// Special cases
	if ($page == 'blog' || $page == 'entry' || $page == 'archives' || $page == 'tag') {
		$page = '/';
	}
	
	$nav = new stdClass;
	$nav->page = $page != '/' ? '/' . $page . '/' : $page;
	$nav->items = array(
		'Home' => '/',
		'Portfolio' => '/portfolio/',
		'Art' => '/art/',
		'Code' => '/code/',
		'Comics' => '/comic/',
		'About' => '/about/'
	);
	
	$nav = Lib\Display::compile($nav, 'nav');
	Lib\Display::setVariable('navigation', $nav);
	
}