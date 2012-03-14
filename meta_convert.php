<?php

mysql_connect('localhost', 'root', 'FARuIq~M');
mysql_select_db('dxcms');
$result = mysql_query('SELECT content_id, content_meta FROM content WHERE content_meta IS NOT NULL');

while ($row = mysql_fetch_object($result)) {
	
	$obj = unserialize($row->content_meta);
	$obj = json_encode($obj);
	$query = 'UPDATE content SET content_meta = "' . mysql_real_escape_string($obj) . '" WHERE content_id = ' . $row->content_id;
	mysql_query($query);
	
}