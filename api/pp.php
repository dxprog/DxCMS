<?php

define('TOP_HIGHSCORES', 20);

// Panic mode game states
define('STATE_IN_PROGRESS', 101);
define('STATE_RECEIVING', 102);
define('STATE_WAITING', 103);
define('PANIC_CACHE', 'PANEL_PANIC_MODE_CACHE');

// Game state durations
define('DURATION_IN_PROGRESS', 120);
define('DURATION_RECEIVING', 15);
define('DURATION_WAITING', 15);
define('DURATION_TOTAL', DURATION_IN_PROGRESS + DURATION_RECEIVING + DURATION_WAITING);

class PP {
	
	/**
	 * Returns the top X highscores in the database
	 */
	public static function getHighscores($vars) {
		
		$retVal = array();
		$type = is_numeric($vars['gamemode']) ? $vars['gamemode'] : -1;
		if ($type > -1) {
			db_Connect();
			$result = db_Query('SELECT score_handle, score_value, score_date FROM scores WHERE score_type = ' . $type . ' ORDER BY score_value DESC LIMIT ' . TOP_HIGHSCORES);
			while ($row = db_Fetch($result)) {
				$row->score_date = date('Y-m-d H:i:s', $row->score_date);
				$retVal[] = $row;
			}
		}
		
		return $retVal;
		
	}
	
	public static function getUserRank($vars) {
	
	}
	
	public static function getUserHandle($vars) {
	
		$retVal = false;
		$phoneId = isset($vars['phoneid']) ? strtolower($vars['phoneid']) : false;
		$valid = strlen($phoneId) == 32 && preg_match('/[a-f0-9]{32}/', $phoneId);
		if ($valid) {
			
			db_Connect();
			$result = db_Query('SELECT score_handle FROM scores WHERE score_id="' . $phoneId . '"');
			$row = db_Fetch($result);
			if (null != $row) {
				$retVal = isset($row->score_handle) ? $row->score_handle : false;
			}
			
		}
	
		return $retVal;
	
	}
	
	public static function getPanicState($vars) {
		
		$time = time();
		$gamesSinceEpoch = floor($time / DURATION_TOTAL);
		$gameStart = $gamesSinceEpoch * DURATION_TOTAL;
		$delta = $time - $gameStart;
		
		$retVal = new stdClass();
		if ($delta <= DURATION_IN_PROGRESS) {
			$retVal->state = STATE_IN_PROGRESS;
			$retVal->remaining = DURATION_IN_PROGRESS - $delta;
		} else if ($delta <= DURATION_IN_PROGRESS + DURATION_RECEIVING) {
			$retVal->state = STATE_RECEIVING;
			$retVal->remaining = DURATION_IN_PROGRESS + DURATION_RECEIVING - $delta;
		} else {
			$retVal->state = STATE_WAITING;
			$retVal->remaining = DURATION_IN_PROGRESS + DURATION_RECEIVING + DURATION_WAITING - $delta;
		}
		
		return $retVal;
		
	}
	
	public static function getPanicScores() {
		$retVal = DxCache::Get(PANIC_CACHE);
		return false === $retVal ? null : $retVal;
	}
	
	public static function setPanicScore($vars) {
		
		$phoneId = isset($vars['phoneid']) ? $vars['phoneid'] : false;
		$handle = isset($vars['handle']) ? $vars['handle'] : false;
		$score = isset($vars['score']) ? $vars['score'] : false;
		$retVal = false;
		$state = self::getPanicState();
		$scores = null;
		
		if ($phoneId && $handle && $score && self::_validatePhoneId($phoneId) && _checkSignature($vars) && $state->state == STATE_RECEIVING) {
			
			$scores = self::getPanicScores();
			$scores = null === $scores ? array() : $scores;
			$obj = new stdClass();
			$obj->score_handle = $handle;
			$obj->score_value = $score;
			$scores[$phoneId] = $obj;
			DxCache::Set(PANIC_CACHE, $scores, 30);
			
			db_Connect();
			$query = 'INSERT INTO scores_raw (score_phone, score_handle, score_date, score_value, score_type) VALUES ("' . $phoneId . '", "' . $handle . '", ' . time() . ', ' . $score . ', 9001)';
			db_Query($query);
			
		}
		
		return $scores;
		
	}
	
	public static function storeHighscore($vars) {
		
		$retVal = false;
		
		// Make sure a phone ID was passed
		$phoneId = isset($vars['phoneid']) ? strtolower($vars['phoneid']) : false;
		if ($phoneId !== false) {
			
			// Make sure the phone ID is valid
			if (self::_validatePhoneId($phoneId) && _checkSignature($vars) && self::_verifyUserHandle($vars['handle'], $phoneId)) {
					
				// We're in. Clean some parameters
				db_Connect();
				$type = is_numeric($vars['gamemode']) ? $vars['gamemode'] : -1;
				$score = is_numeric($vars['score']) ? $vars['score'] : -1;
				if ($score > -1 && $type > -1) {
					$result = db_Query('SELECT COUNT(1) AS total FROM scores WHERE score_id = "' . $phoneId . '" AND score_type = ' . $type);
					$row = db_Fetch($result);
					$handle = db_Escape($vars['handle']);
					db_Query('INSERT INTO scores_raw (score_phone, score_handle, score_date, score_value, score_type) VALUES ("' . $phoneId . '", "' . $handle . '", ' . time() . ', ' . $score . ', ' . $type . ')');
					if ($row->total > 0) {
						$query = 'UPDATE scores SET score_value=' . $vars['score'] . ', score_date=' . time() . ' WHERE score_id="' . $phoneId . '" AND score_type = ' . $type . ' AND score_value < ' . $vars['score'];
					} else {
						$query = 'INSERT INTO scores (score_id, score_handle, score_date, score_value, score_type) VALUES ("' . $phoneId . '", "' . $handle . '", ' . time() . ', ' . $score . ', ' . $type . ')';
					}
					db_Query($query);
					$retVal = true;
				}
			}
		}
		
		return $retVal;
	
	}
	
	/**
	 * Verifies that the user handle belongs to the phone
	 */
	private static function _verifyUserHandle($handle, $phoneId) {
		
		$retVal = true;
		db_Connect();
		$result = db_Query('SELECT score_id FROM scores WHERE score_handle = "' . db_Escape($handle) . '"');
		while ($row = db_Fetch($result)) {
			if ($row->score_id != $phoneId) {
				raiseError(203, 'Username taken');
				break;
			}
		}
		return $retVal;
		
	}
	
	/**
	 * Ensures that an incoming phone Id is valid
	 */
	private function _validatePhoneId($phoneId) {
		return strlen($phoneId) == 32 && preg_match('/[a-f0-9]{32}/', $phoneId);
	}

}