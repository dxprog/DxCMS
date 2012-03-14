<?php

namespace Api {

	class Ad {

		function Get($vars)
		{
			
			if (!$vars['size'] || !$vars['site']) {
				return;
			}
			
			// Used for weighting
			$rand = rand() % 10;

			// Grab an ad
			$params = array(':size'=>$vars['size'], ':site'=>$vars['site'], ':weight'=>$rand);
			$row = Db::Fetch(Db::Query('SELECT * FROM ads WHERE ad_size=:size AND ad_site=:site AND ad_weight >= :weight ORDER BY RAND() LIMIT 1', $params));
			
			// Increment the impressions on this ad
			Db::Query('UPDATE ads SET ad_impressions=ad_impressions+1 WHERE ad_id=:id', array(':id'=>$row->ad_id));
			
			return $row->ad_code;
			
		}

	}

}