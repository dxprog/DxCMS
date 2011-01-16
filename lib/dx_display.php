<?php

class DxDisplay {
	
	private static $templateVars = array();
	private static $theme;
	private static $pageTemplate;
	
	/**
	 * Renders the page
	 **/
	public function render()
	{

		$out = file_get_contents('./themes/'.self::$theme.'/'.self::$pageTemplate.'.tpl');
		foreach (self::$templateVars as $name=>$val) {
			$out = str_replace('{'.$name.'}', $val, $out);
		}
		echo $out;
		
	}
	
	public function setTheme($name) {
		self::$theme = $name;
	}
	
	public function setTemplate($name) {
		self::$pageTemplate = $name;
	}
	
	public function setVariable($name, $val) {
		self::$templateVars[strtoupper($name)] = $val;
	}
	
	public function compile($data, $template, $cacheKey = null) {
		
		global $_baseURI, $_theme;
		$xml = new DOMDocument();
		$xsl = new DOMDocument();
		$t = new XSLTProcessor();
		$parseXml = false;
		$retVal = '';
		
		// Check to see if this transform is cached
		if (null == $cacheKey || ($retVal = DxCache::Get($cacheKey)) === false) {
		
			// If the incoming data is an object, serialize it before continuing
			if (!is_string($data)) {
				$xs = new SerializeXML();
				$parseXml = $xs->serialize($data, $template);
			} else {
				$parseXml = $data;
			}
		
			// Run the transform and return the results
			if (!$xml->loadXML($parseXml)) {
				echo $parseXml;
				echo '<!-- ERROR -->';
				exit();
			}
			$xsl->load('./themes/'.self::$theme.'/'.$template.'.xslt');
			$t->importStyleSheet($xsl);
			$t->registerPHPFunctions();
			$retVal = str_replace('_BASEURI', $_baseURI, $t->transformToXML($xml));
			$retVal = str_replace(' xmlns:php="http://php.net/xsl"', '', $retVal);
			unset($t);
			unset($xsl);
			unset($xml);
			
			// Strip XML headers out
			$retVal = str_replace('<?xml version="1.0"?>', '', $retVal);
			
			if (null != $cacheKey) {
				DxCache::Set($cacheKey, $retVal);
			}
			
		}
		
		return $retVal;
	}

}