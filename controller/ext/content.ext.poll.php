<?php

namespace Controller {

	use Lib;

	class Poll implements Extension {

		public static function init() {
			Content::registerExtension('Poll', 'formatPostPoll', 'formatted');
		}

		function ext_formatPostPoll($body) {
			
			// We need to check to see if the user has voted on this poll. First check for a cookie
			$cookieName = 'Poll' . $body->id . 'Voted';
			if (isset($_COOKIE[$cookieName])) {
				$body->voted = $_COOKIE[$cookieName];
			} else {
				$body->voted = Dx::call('poll', 'userVoted', array('id'=>$body->id), 0)->body;
				setcookie($cookieName, $body->voted, time() + 31536000);
			}
			return $body;
			
		}

	}
	
}