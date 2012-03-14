<?php

function rpc_getFeatured ()
{

	db_Connect ();
	$result = db_Query ("SELECT * FROM featured ORDER BY feature_id DESC LIMIT 4");
	$obj = array (); $xml = "";
	while ($row = db_Fetch ($result)) {
		$t = null;
		$t->title = $row->feature_title;
		$t->url = $row->feature_url;
		$t->summary = $row->feature_summary;
		$t->image = $row->feature_image;
		$obj[] = $t;
	}
	
	return $obj;

}

?>