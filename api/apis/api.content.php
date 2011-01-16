<?php

/**
 * DxApi
 * @author Matt Hackmann <matt@dxprog.com>
 * @package DxApi
 * @license GPLv3
 */

define('HITS_CACHE_THRESHOLD', 50);
define('HITS_CACHE_TIMEOUT', 600);
 
class Content {
 
	public static function getContent($vars) {

		// Get the properties passed
		$retVal = null;
		$select = isset($vars['select']) ? strlen($vars['select']) > 0 ? $vars['select'] : 'c.*' : 'c.*';
		$id = isset($vars['id']) ? $vars['id'] : null;
		$perma = isset($vars['perma']) ? $vars['perma'] : null;
		$parent = isset($vars['parent']) ? $vars['parent'] : null;
		$max = isset($vars['max']) ? is_numeric($vars['max']) ? $vars['max'] : 15 : 15;
		$mindate = isset($vars['mindate']) ? $vars['mindate'] : null;
		$maxdate = isset($vars['maxdate']) ? $vars['maxdate'] : null;
		$type = isset($vars['contentType']) ? $vars['contentType'] : null;
		$offset = isset($vars['offset']) ? is_numeric($vars['offset']) ? $vars['offset'] : 0 : 0;
		$tag = isset($vars['tag']) ? $vars['tag'] : null;
		$order = isset($vars['order']) ? $vars['order'] : null;
		$meta = isset($vars['meta']) ? serialize($vars['meta']) : null;
		$noTags = isset($vars['noTags']) ? $vars['noTags'] === 'true' || $vars['noTags'] === true : false;
		
		// Build the query
		db_Connect();
		$join = '';
		$where = '';
		if (is_numeric($id)) {
			$where .= 'c.content_id=' . $id . ' AND ';
		}
		if (strlen($perma) > 0) {
			$where .= 'c.content_perma="' . db_Escape($perma) . '" AND ';
		}
		if ($select != 'c.*') {
			$items = explode(',', $select);
			// The ID always gets returned
			$select = 'c.content_id,';
			
			// Build the returns based upon the comma delimeted string passed in
			foreach ($items as $item) {
				switch ($item) {
					case 'title':
						$select .= 'c.content_title,';
						break;
					case 'body':
						$select .= 'c.content_body,';
						break;
					case 'date':
						$select .= 'c.content_date,';
						break;
					case 'meta':
						$select .= 'c.content_meta,';
						break;
					case 'perma':
						$select .= 'c.content_perma,';
						break;
				}
			}
			$select = substr($select, 0, strlen($select) - 1);
		}
		if (is_numeric($parent)) {
			$where .= 'c.content_parent=' . $parent . ' AND ';
		}
		if (is_numeric($mindate) || ($mindate = strtotime($mindate)) !== false) {
			$where .= 'c.content_date >= ' . $mindate . ' AND ';
		}
		if (is_numeric($maxdate) || ($maxdate = strtotime($maxdate)) !== false) {
			$maxdate = $maxdate > time() ? time() : $maxdate;
			$where .= 'c.content_date <= ' . $maxdate . ' AND ';
		} else {
			$where .= 'c.content_date <= ' . time() . ' AND ';
		}
		if (null != $type) {
			$types = explode(',', $type);
			$t = '(';
			foreach ($types as $item) {
				$t .= 'c.content_type="' . db_Escape($item) . '" OR ';
			}
			$where .= substr($t, 0, strlen($t) - 4) . ') AND ';
		}
		if (strlen($tag) > 0) {
			$join .= 'INNER JOIN tags t ON t.content_id=c.content_id ';
			$where .= 't.tag_name="' . db_Escape($tag) . '" AND ';
		}
		if (null != $meta) {
			preg_match('/\{(.*?)\}/', $meta, $m);
			$where .= 'c.content_meta LIKE "%' . db_Escape($m[1]) . '%" AND ';
		}
		switch (strtolower($order)) {
			case 'asc':
			case 'ascending':
				$order = 'ASC';
				break;
			case 'desc':
			case 'descending':
			default:
				$order = 'DESC';
				break;
		}
		$noCount = isset($vars['noCount']) && $vars['noCount'] === true;
		$count = $noCount ? '' : ', (SELECT COUNT(*) FROM content WHERE content_parent=c.content_id) AS children_count';
		
		// Get the item count
		if (!$noCount) {
			$retVal->count = db_Fetch(db_Query('SELECT COUNT(*) AS total FROM content c ' . $join . 'WHERE ' . $where . '1'))->total;
		}
		
		// If there isn't anything to get, don't get it
		if ($retVal->count > 0 || $noCount) {
			$query = 'SELECT ' . $select . $count . ' FROM content c ';
			$where .= '1 ORDER BY c.content_date ' . $order . ' ';
			$query .= $join . 'WHERE ' . $where;

			if ($max > 0) {
				 $query .= 'LIMIT ' . $offset . ', ' . $max;
			}

			// Execute the query and lump the results into the outgoing object
			$result = db_Query($query);
			$retVal->content = array();
			while ($row = db_Fetch($result)) {
				$obj = null;
				$obj->id = $row->content_id;
				$obj->title = $row->content_title;
				$obj->title = $row->content_title;
				$obj->perma = $row->content_perma;
				$obj->date = $row->content_date;
				$obj->body = $row->content_body;
				$obj->type = $row->content_type;
				$obj->children = $row->children_count;
				$obj->meta = unserialize($row->content_meta);
				if (!$noTags) {
					$obj->tags = self::getTags(array('id'=>$obj->id, 'noCount'=>true));
				}
				$retVal->content[] = $obj;
			}
		}
		
		return $retVal;

	}

	/**
	 * Returns a list of unique tags to the given content filters
	 */
	public static function getTags($vars)
	{

		// Split out the variables
		$retVal = array();
		$id = isset($vars['id']) ? $vars['id'] : null;
		$perma = isset($vars['perma']) ? $vars['perma'] : null;
		$parent = isset($vars['parent']) ? $vars['parent'] : null;
		$max = isset($vars['max']) ? $vars['max'] : 25;
		$mindate = isset($vars['mindate']) ? $vars['mindate'] : 0;
		$maxdate = isset($vars['maxdate']) ? $vars['maxdate'] : time();
		$type = $vars['type'];
		$noCount = isset($vars['noCount']) && $vars['noCount'] === true ? null : $count = 'count(*) AS tag_count, ';
		
		// Build the query
		db_Connect();
		$query = 'SELECT ' . $count . 't.tag_name FROM content c INNER JOIN tags t ON t.content_id=c.content_id WHERE ';
		if (is_numeric($id)) {
			$query .= 'c.content_id=' . $id . ' AND ';
		}
		if (strlen($perma) > 0) {
			$query .= 'c.content_perma="' . db_Escape($perma) . '" AND ';
		}
		if (is_numeric($parent)) {
			$query .= 'c.content_parent=' . $parent . ' AND ';
		}
		if (is_numeric($mindate) || ($mindate = strtotime($mindate)) !== false) {
			$query .= 'c.content_date >= ' . $mindate . ' AND ';
		}
		if (is_numeric($maxdate) || ($maxdate = strtotime($maxdate)) !== false) {
			$query .= 'c.content_date <= ' . $maxdate . ' AND ';
		} else {
			$query .= 'c.content_date <= ' . time() . ' AND ';
		}
		if (strlen($type) > 0) {
			$query .= 'c.content_type="' . db_Escape($type) . '" AND ';
		}
		$query .= '1 GROUP BY t.tag_name ' . ($noCount ? 'ORDER BY tag_count DESC ' : '');
		if (is_numeric($max)) {
			$query .= 'LIMIT ' . $max;
		} else {
			$query .= 'LIMIT 25';
		}
		
		// Round up all the returned tags into an array for output
		$result = db_Query($query);
		while ($row = db_Fetch($result)) {
			$t = null;
			$t->name = $row->tag_name;
			$t->count = $row->tag_count;
			$retVal[] = $t;
		}

		return $retVal;
		
	}

	/**
	 * Returns a list of months/years where there are blog posts
	 **/
	public static function getArchives($vars) {
		
		db_Connect();
		$retVal = array();
		
		// Get a date for every month/year there was a post
		$result = db_Query("SELECT MONTH(FROM_UNIXTIME(content_date)) AS month, YEAR(FROM_UNIXTIME(content_date)) AS year FROM content GROUP BY year, month ORDER BY year DESC, month DESC");
		while ($row = db_Fetch($result)) {
			$t = null;
			$t->timestamp = mktime(0, 0, 0, $row->month, 1, $row->year) + 3600;
			$t->text = date("F Y", $t->timestamp);
			$retVal[] = $t;
		}
		
		return $retVal;
		
	}
	
	public static function logContentView($vars) {
		
		// Fancy caching happens here
		global $_apiPath;
		$cacheKey = 'ContentHits';
		$hits = DxCache::Get($cacheKey);
		if ($hits === false) {
			$hits = array();
		}
		
		// Sniff for bots
		$botString = "/(Slurp!|Googlebot|AdsBot|msnbot|bingbot|crawler|Spinn3r|spider|robot|yandex|slurp|dotbot)/i";
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		if (!$userAgent || preg_match($botString, $userAgent) > 0) {
			return false;
		}
		
		// Make sure the values are good and log the view
		if (is_numeric($vars['id'])) {
			
			// Set up an object with all the info and stuff it into the cached array
			$obj = null;
			$obj->ip = $_SERVER['REMOTE_ADDR'];
			$obj->id = intVal($vars['id']);
			$obj->time = time();
			$hits[] = $obj;
			$lastStore = DxCache::Get('ContentHits_Date');

			// If we've hit the threshold (count or timeout), dump all of the hits into the database
			if (count($hits) >= HITS_CACHE_THRESHOLD || $lastStore + HITS_CACHE_TIMEOUT < time()) {
				db_Connect();
				$query = 'INSERT INTO hits VALUES ';
				foreach ($hits as $hit) {
					$query .= '(' . $hit->id . ', "' . $hit->ip . '", ' . $hit->time . '),';
				}
				$query = substr($query, 0, strlen($query) - 1);
				db_Query($query);
				
				// Null out the array so we can start over
				$hits = null;
				DxCache::Set('ContentHits_Date', time());
				
			}
			
			// Update the cache
			DxCache::Set($cacheKey, $hits);
			
		} else {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Gets the most popular posts based on page views
	 * @param string $type Output format. Valid options: rss, json, xml, php. Required
	 * @param int $mindate Unix timestamp of how far back to calculate page views. Default: 1 week prior
	 * @param int $mac Number of results to return
	 */
	public static function getPopular($vars) {

		$retVal = array();
		
		// If no valid timestamp was passed, we'll default to one week
		if (!is_numeric($vars['mindate'])) {
			$vars['mindate'] = time() - 604800;
		}
		
		// If no valid max value was passed, default to 5
		if (!isset($vars['max']) || !is_numeric($vars['max'])) {
			$vars['max'] = 5;
		}
		
		// Get the most viewed posts within the time frame
		db_Connect();
		$result = db_Query('SELECT count(DISTINCT h.hit_ip) AS total, c.content_id, c.content_title, c.content_perma FROM hits h INNER JOIN content c ON c.content_id=h.content_id WHERE h.hit_date >= ' . $vars['mindate'] . ' GROUP BY h.content_id ORDER BY total DESC, c.content_date DESC LIMIT ' . $vars['max']);
		while ($row = db_Fetch($result)) {
			$t = null;
			$t->count = $row->total;
			$t->id = $row->content_id;
			$t->title = $row->content_title;
			$t->perma = $row->content_perma;
			$retVal[] = $t;
		}

		return $retVal;
		
	}

	/**
	 * Returns all of the comments associated with a post
	 * @param string $type Output format. Valid options: rss, json, xml, php. Required
	 * @param int $max Maximum number of posts to return
	 * @param string $perma Perma-link of post to get related items from
	 */
	public static function getRelated($vars) {
		
		$retVal = array();
		db_Connect();
		
		// Get the five pieces of content with the most similar tags. Sort by most relevant and most recent
		if (is_numeric($vars['id'])) {
			
			$result = db_Query('SELECT COUNT(*) AS total, c.content_id, c.content_title, c.content_perma FROM tags t INNER JOIN content c ON c.content_id=t.content_id WHERE t.tag_name IN (SELECT tag_name FROM tags WHERE content_id=' . $vars['id'] . ') AND t.content_id != ' . $vars['id'] . ' GROUP BY content_id ORDER BY total DESC, c.content_date DESC LIMIT 5');
			while ($row = db_Fetch($result)) {
				$obj = null;
				$obj->title = $row->content_title;
				$obj->perma = $row->content_perma;
				$retVal[] = $obj;
			}
			
		}
		
		return $retVal;
		
	}
	
	public static function syncContent($vars, $obj) {
	
		$id = is_numeric($obj->id) ? intVal($obj->id) : null;

		db_Connect();
		
		if ($id !== null && $id > 0) {
			// If there is an ID set, do an UPDATE
			$query = 'UPDATE content SET content_title="' . db_Escape($obj->title) . '", content_body="' . db_Escape($obj->body) . '", content_date=' . intVal($obj->date) . ', content_meta="' . db_Escape(serialize($obj->meta)) . '" WHERE content_id=' . $id;
			db_Query($query);
		} else {
			// Otherwise, do an INSERT
			$query = 'INSERT INTO content (content_title, content_perma, content_body, content_date, content_type, content_parent, content_meta) VALUES ';
			$query .= '("' . db_Escape($obj->title) . '", "' . db_Escape($obj->perma) . '", "' . db_Escape($obj->body) . '", ' . intVal($obj->date) . ', "' . db_Escape($obj->type) . '", ' . intVal($obj->parent) . ', "' . db_Escape(serialize($obj->meta)) . '")';
			$id = $obj->id = db_Query($query);
		}
	
		// Sync the tags
		if ($id !== true) {
			self::_syncTags($obj);
		}
	
		$retVal = null;
		if ($id !== null) {
			$retVal = self::getContent(array('id'=>$id));
		}

		return $retVal;
	
	}
	
	public static function contentSearch($vars) {
		
	}
	
	public static function postComment($vars, $obj) {
		$retVal = null;
		if ($vars['perma']) {
			$id = self::_getIdFromPerma($vars['perma']);
			if ($id) {
				$query = 'INSERT INTO content (content_parent, content_body, content_meta, content_date, content_type) VALUES ';
				$query .= '(' . $id . ', "' . db_Escape($obj->body) . '", "' . db_Escape(serialize($obj->meta)) . '", ' . time() . ', "cmmnt")';
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
		$row = db_Fetch(db_Query('SELECT content_id FROM content WHERE content_perma="' . db_Escape($perma) . '" AND content_type!="cmmnt"'));
		return $row->content_id;
		
	}
	
	private static function _syncTags($obj) {
		
		if (is_numeric($obj->id) && count($obj->tags) > 0) {
			
			db_Connect();
			
			// Delete old tags first
			db_Query('DELETE FROM tags WHERE content_id=' . $obj->id);
			
			// Loop through all the tags and add them
			$query = 'INSERT INTO tags VALUES ';
			foreach	($obj->tags as $tag) {
				if (is_object($tag)) {
					$tag = $tag->name;
				}
				$query .= '(' . $obj->id . ', "' . db_Escape($tag) . '"),';
			}
			$query = substr($query, 0, strlen($query) - 1);
			db_Query($query);
			
		}
		
	}

}