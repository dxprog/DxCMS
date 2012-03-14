<?php

/**
 * Sets up the Google Analytics code if enabled. Code is auto-running, private scope
 */
{
	$gaEnabled = Lib\Dx::getOption('ga_enabled');
	$gaScript = '';
	if ($gaEnabled) {
		$gaKey = Lib\Dx::getOption('ga_key');
		$gaScript = '<script type="text/javascript">var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));</script>';
		$gaScript .= '<script type="text/javascript">try {var pageTracker = _gat._getTracker("' . $gaKey . '");pageTracker._trackPageview();} catch(err) {}</script>';
	}
	Lib\Display::setVariable('ga', $gaScript);
}