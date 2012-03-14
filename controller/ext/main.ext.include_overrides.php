<?php

/**
 * Gets the cache override values for JS/CSS files
 */
{
	$cssDate = Lib\Dx::getOption('css_date');
	$jsDate = Lib\Dx::getOption('js_date');
	Lib\Display::setVariable('css_date', $cssDate);
	Lib\Display::setVariable('js_date', $jsDate);
}