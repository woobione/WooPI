<?php

class JsonParser {

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
