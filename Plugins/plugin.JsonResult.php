<?php

class JsonResult extends WPIResult {
	
	private $object = null;
	private $jsonString = null;
	
	/**
	 * Takes object on construct
	 * @param mixed $object
	 */
	public function __construct($object = array()) {
		$this->object = $object;
		$this->jsonString = self::Encode($object);
	}
	
	/**
	 * Print result
	 */
	public function Result() {
		echo $this->jsonString;
	}
	
	/**
	 * Set json headers
	 */
	public function SetHeaders() {
		header('Content-type: application/json');
	}
	
	/**
	 * Handles exceptions as json
	 * @param Exception $e
	 */
	public static function HandleException(Exception $e) {
		WoobiPI::HandleResult(new JsonResult(array('success' => false, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString())));
	}
	
	/**
	 * Encode array or object to json string
	 * @param mixed $object
	 * @return string
	 */
	public static function Encode($object) {
		return json_encode($object);
	}
	
	/**
	 * Decode json string into array
	 * @param string $string
	 * @return array
	 */
	public static function Decode($string) {
		return json_decode($string);
	}

}
