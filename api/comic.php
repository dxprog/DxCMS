<?php

/**
 * DxApi Comic Module
 * @copyright Copyright (C) Matt Hackmann 2010
 */

namespace Api {
 
	use Lib;
 
	class Comic extends Content {

		public $sj_id;
		public $image;
		public $next = null;
		public $previous = null;

		public function __construct($obj, $getNeighbors = false) {
			
			if (is_object($obj)) {
				
				if ($obj instanceof Comic) {
				
				} else {
					$this->_createObjectFromRow($obj, false);
					$date = date('Ymd', $this->date);
					$this->image = 'cm_' . $date . '_' . substr(md5($date), 0, 1) . '.png';
					$this->sj_id = isset($this->meta->sj_id) ? $this->meta->sj_id : 0;
					if ($getNeighbors) {
						$this->next = self::_getComicNeighbor($this->date, 'next');
						$this->previous = self::_getComicNeighbor($this->date, 'prev');
					}
				}
			}
			
		}

		public static function getComic($vars) {
			
			$retVal = false;
			
			// See if we're getting a particular comic
			$where = '';
			$params = array();
			if ($vars['perma']) {
				$where = 'content_perma = :perma AND ';
				$params[':perma'] = $vars['perma'];
			}
			
			$result = Lib\Db::Query('SELECT * FROM content WHERE content_type="comic" AND ' . $where . '1 ORDER BY content_date DESC LIMIT 1', $params);
			$row = Lib\Db::Fetch($result);
			if ($row) {
				$retVal = new Comic($row, true);
			}
			
			return $retVal;
		
		}
		
		private static function _getComicNeighbor($date, $dir) {
		
			$retVal = null;
			$query = '';
			$params = array( ':date'=>$date );
			$sort = $dir == 'next' ? 'ASC' : 'DESC';
			$opr = $dir == 'next' ? '>' : '<';
			$query = 'SELECT * FROM content WHERE content_type="comic" AND content_date ' . $opr . ' :date ORDER BY content_date ' . $sort . ' LIMIT 1';
			
			$result = Lib\Db::Query($query, $params);
			if ($result && $result->count > 0) {
				$row = Lib\Db::Fetch($result);
				$retVal = new Comic($row);
			}
			
			return $retVal;
		
		}

	}
	
}