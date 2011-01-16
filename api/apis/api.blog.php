<?php

/**
 * DXAPI Blog Module
 * @author Matt Hackmann <matt@dxprog.com>
 * @package DXAPI
 * @license GPLv3
 */

class Blog {

	public static function postComment($vars, $obj) {
		$retVal = null;
		if ($vars['perma']) {
			$id = self::_getIdFromPerma($vars['perma']);
			if ($id) {
				$query = 'INSERT INTO content (content_perma, content_parent, content_body, content_meta, content_date, content_type) VALUES ';
				$query .= '("' . db_Escape($vars['perma']) . '", ' . $id . ', "' . db_Escape($obj->body) . '", "' . db_Escape(serialize($obj->meta)) . '", ' . time() . ', "cmmnt")';
				$commentId = db_Query($query);
				$retVal = $commentId;
			} else {
				$retVal = false;
			}
		}
		return $retVal;
	}
	
	private static function _getIdFromPerma($perma) {
		
		db_Connect();
		$row = db_Fetch(db_Query('SELECT content_id FROM content WHERE content_perma="' . db_Escape($perma) . '" AND content_type="blog"'));
		return $row->content_id;
		
	}

}

?>