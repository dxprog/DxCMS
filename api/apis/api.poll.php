<?php

/**
 * DxApi Poll Module
 * @author Matt Hackmann <matt@dxprog.com>
 * @package DXAPI
 * @license GPLv3
 */

// Include the content API lib as this is an extension of it
require_once('api.content.php');
 
/**
 * Error constants
 */
define('PERR_ALREADY_VOTED', 1000);
define('PERR_POLL_CLOSED', 1001);

/**
 * API methods and data class for polls
 */
class Poll extends Content {
	
	/**
	 * Poll items (array of PollItem). Alias of content->meta
	 */
	public $items;
	
	/**
	 * Whether the user has voted on this poll or not
	 */
	public $voted;
	
	/**
	 * Total number of votes in this poll
	 */
	public $totalVotes;

	/**
	 * Constructor for poll data class
	 */
	public function __construct($_title, $_items, $_perma) {
		$this->title = $_title;
		$this->type = 'poll';
		$this->perma = $_perma;
		
		// Calculate values for each item
		$this->calculate($_items);
		
	}
	
	/**
	 * Adds a new poll item to the poll
	 */
	public function addItem($title) {
		
		// Instantiate the items array if needed
		$this->items = is_array($this->items) ? $this->items : array();
		
		// Lump in the new object
		$id = count($this->items) + 1;
		$this->items[] = new PollItem($id, $title, 0, $this->totalVotes);
		$this->meta = $this->items;
		
	}
	
	/**
	 * Syncs a poll to the database
	 */
	public function sync() {
	
		// Recalc and sync the poll
		$this->meta = $this->items;
		$this->calculate($this->items);
		self::syncContent(null, $this);
	
	}
	
	/**
	 * Calculates the percentages for each poll option
	 */
	private function calculate($_items) {
		
		if (is_array($_items)) {
			
			$new = array();
			
			// Calculate total votes
			$this->totalVotes = 0;
			foreach ($_items as $item) {
				$this->totalVotes += $item->votes;
			}

			// Update the values for each item
			foreach ($_items as $item) {
				$new[] = new PollItem($item->id, $item->title, $item->votes, $this->totalVotes);
			}
			
			$this->items = $new;
			$this->meta = $new;
			
		}
		
	}
	
	/**
	 * Gets the information and items for a poll based upon either ID or perma
	 * @param int $id ID of poll to retrieve. If supplied, this takes precedence over perma
	 * @param string $perma Perma ID of poll to retrieve
	 */
	public static function get($vars) {
		
		$retVal = null;
		
		$id = isset($vars['id']) && is_numeric($vars['id']) ? $vars['id'] : null;
		$perma = isset($vars['perma']) ? $vars['perma'] : null;
		if (null != $id || null != $perma) {
			
			// Pull together what criteria we'll search on (ID taking precedence over perma)
			$param = null != $perma ? array('perma'=>$perma) : null;
			$param = null != $id ? array('id'=>$id) : $param;
			$param['noTags'] = true;
			$param['noCount'] = true;
			$obj = self::getContent($param);
			if (is_array($obj->content)) {
				$obj = $obj->content[0];
				$retVal = new Poll($obj->title, $obj->meta, $obj->perma);
				$retVal->id = $obj->id;
				$retVal->type = $obj->type;
				$retVal->date = $obj->date;
				$retVal->voted = Poll::userVoted($obj->id);
			}
			
		}
		
		return $retVal;
		
	}
	
	/**
	 * Gets all polls from the database and returns only basic information
	 */
	public static function getAll($vars) {
	
		// Get all the polls via the content API and return the results
		return self::getContent(array('contentType'=>'poll', 'noCount'=>true, 'noTags'=>true));
	
	}
	
	/**
	 * Checks to see if the current IP has voted in the specified poll
	 * @param int $id ID of poll to check
	 * @param string $ip An IP to check for. If not specified, the IP of the request will be used
	 * @return bool Whether the IP has voted on the poll or not
	 */
	public static function userVoted($vars) {
		
		$retVal = false;
		$id = isset($vars['id']) && is_numeric($vars['id']) ? $vars['id'] : null;
		$ip = isset($vars['ip']) ? $vars['ip'] : $_SERVER['REMOTE_ADDR'];
		
		if (null != $id) {
			
			db_Connect();
			$row = db_Fetch(db_Query('SELECT COUNT(1) AS count FROM hits WHERE content_id=' . $id . ' AND hit_ip="' . db_Escape($ip) . '"'));
			if ($row->count > 0) {
				$retVal = true;
			}
			
		}
		
		return $retVal;
		
	}
	
	/**
	 * Adds a vote to a poll
	 * @param int $id ID of poll to vote on
	 * @param int $item ID of poll item to add vote to
	 */
	public static function vote($vars) {
		
		$retVal = null;
		
		$id = isset($vars['id']) && is_numeric($vars['id']) ? $vars['id'] : null;
		$item = isset($vars['item']) && is_numeric($vars['id']) ? $vars['item'] : null;
		
		if (null != $id && null != $item) {
			
			// Ensure the user hasn't already voted
			if (!self::userVoted(array('id'=>$id))) {
				
				// Get the poll from the database
				$poll = self::get(array('id'=>$id));
				$poll->items[$item - 1]->votes++;
				$poll->sync();
				$retVal = $poll;
				
				// Register this vote
				self::logContentView(array('id'=>$id, 'forceWrite'=>true));
				
			} else {
				raiseError(PERR_ALREADY_VOTED, 'You have already voted on this poll');
			}
			
		}
		
		return $retVal;
		
	}

}

/**
 * Data class for poll items
 */
class PollItem {

	/**
	 * ID of this poll item in respect to its parent
	 */
	public $id;
	
	/**
	 * Option title
	 */
	public $title;
	
	/**
	 * Number of votes option has received
	 */
	public $votes;
	
	/**
	 * Percentage of votes this option has received
	 */
	public $percent;
	
	/**
	 * Constructor
	 * @param int $id ID of this poll item
	 * @param string $_title The title of the option
	 * @param int $_votes Number of votes this option option has received
	 * @param int $_total Total number of votes in the poll. This is used to calculate this option's percentage of votes
	 */
	public function __construct ($_id, $_title, $_votes, $_total) {
		$this->id = $_id;
		$this->title = $_title;
		$this->votes = $_votes;
		$this->percent = $_total > 0 ? round(($_votes / $_total) * 100) : 0;
	}

}