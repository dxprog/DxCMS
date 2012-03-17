<?php

/**
 * DxApi Comic Module
 * @copyright Copyright (C) Matt Hackmann 2010
 */

namespace Api {
 
	use Lib;
 
	class Comic {

		public $id;
		public $title;
		public $perma;
		public $date;
		public $sj_id;
		public $image;
		public $next = null;
		public $previous = null;

		public function __construct($item) {
			
			if ($item) {
				$this->id = $item->content_id;
				$this->title = $item->content_title;
				$this->perma = $item->content_perma;
				$this->date = intVal($item->content_date);
				$date = date("Ymd", $this->date);
				$this->image = 'cm_' . $date . '_' . substr(md5($date), 0, 1) . '.png';
				$this->sj_id = @unserialize($item->content_meta)->sj_id;
			}
			
		}

		public static function getComic($vars) {
		
			// See if we're getting a particular comic
			$where = '';
			$params = array();
			if ($vars['perma']) {
				$where = 'content_perma = :perma AND ';
				$params[':perma'] = $vars['perma'];
			}
			
			$result = Lib\Db::Query('SELECT * FROM content WHERE content_type="comic" AND ' . $where . '1 ORDER BY content_date DESC LIMIT 1', $params);
			$row = Lib\Db::Fetch($result);
			$retVal = new Comic($row);
			$retVal->next = self::_getComicNeighbor($retVal->date, 'next');
			$retVal->previous = self::_getComicNeighbor($retVal->date, 'prev');
			return $retVal;
		
		}
		
		private static function _getComicNeighbor($date, $dir) {
		
			$query = '';
			switch ($dir) {
				case 'next':
					$query = 'SELECT * FROM content WHERE content_type="comic" AND content_date > ' . $date . ' ORDER BY content_date ASC LIMIT 1';
					break;
				case 'prev':
					$query = 'SELECT * FROM content WHERE content_type="comic" AND content_date < ' . $date . ' ORDER BY content_date DESC LIMIT 1';
					break;
			}
			
			$row = Lib\Db::Fetch(Lib\Db::Query($query));
			return new Comic($row);
		
		}

	}
	
}