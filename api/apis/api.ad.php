<?php

class Ad {

	function Get($vars)
	{
		
		if (!$vars['size'] || !$vars['site']) {
			return;
		}
		
		// Used for weighting
		$rand = rand() % 10;
		db_Connect();

		// Grab an ad
		$row = db_Fetch(db_Query('SELECT * FROM ads WHERE ad_size="' . db_Escape($vars['size']) . '" AND ad_site="' . db_Escape($vars['site']) . '" AND ad_weight >= ' . $rand . ' ORDER BY RAND() LIMIT 1'));
		
		// Increment the impressions on this ad
		db_Query('UPDATE ads SET ad_impressions=ad_impressions+1 WHERE ad_id='.$row->ad_id);
		
		return $row->ad_code;
		
	}

}