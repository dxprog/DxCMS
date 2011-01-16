<?php

/**
 * dxprog.com Caching library
 */

if (isset($_GET['flushCache'])) {
	DxCache::Flush();
}

// APC caching
class DxCache {
	
	public static function Set($key, $val, $expiration = 600) {
		$retVal = false;
		if ($key) {
			$retVal = @apc_store(md5($key), $val, $expiration);
		}
		return $retVal;
	}
	
	public static function Get($key) {
		$retVal = false;
		if ($key) {
			$retVal = apc_fetch(md5($key));
		}
		return $retVal;
	}
	
	public static function Flush() {
		apc_clear_cache();
	}

}

?>