<?php

function content_sidebarAd ()
{

	$retVal = '';
	$ad = Dx::call('ad', 'get', array('size'=>'300x250', 'site'=>'dxprog'), 0);
	if ($ad != null) {
		$retVal = '<div style="margin:10px 0">'.$ad->body.'</div>';
	}
	DxDisplay::setVariable('ad', $retVal);

}

?>