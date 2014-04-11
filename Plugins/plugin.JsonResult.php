<?php

class JsonResult extends WPIResult {
	
	private $object = null;
	private $jsonString = null;
	
	public function __construct($object = array()) {
		$this->object = $object;
		$this->jsonString = self::Encode($object);
	}
	
	public function Result() {
		echo $this->jsonString;
	}
	
	public function SetHeaders() {
		header('Content-type: application/json');
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
