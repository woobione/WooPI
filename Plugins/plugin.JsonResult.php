<?php

class JsonResult extends WPIResult {

	/**
	 * @var mixed
	 */
	private $object = null;

	/**
	 * @var string
	 */
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
		echo Request::Get('readable', false) ? self::MakeReadable($this->jsonString) : $this->jsonString;
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
		WooPI::HandleResult(new JsonResult(array('success' => false, 'exception' => $e->getMessage(), 'trace' => $e->getTraceAsString())));
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

	/**
	 * Makes JSON string more readable
	 * @link http://www.daveperrett.com/articles/2008/03/11/format-json-with-php/ Origin
	 * @param string $json
	 * @return string
	 */
	public static function MakeReadable($json) {
		$result = '';
		$pos = 0;
		$strLen = strlen($json);
		$indentStr = '  ';
		$newLine = "\n";
		$prevChar = '';
		$outOfQuotes = true;

		for ($i = 0; $i <= $strLen; $i++) {
			$char = substr($json, $i, 1);

			if ($char == '"' && $prevChar != '\\') {
				$outOfQuotes = !$outOfQuotes;
			} else if (($char == '}' || $char == ']') && $outOfQuotes) {
				$result .= $newLine;
				$pos --;
				for ($j = 0; $j < $pos; $j++) {
					$result .= $indentStr;
				}
			}

			$result .= $char;

			if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
				$result .= $newLine;
				if ($char == '{' || $char == '[') {
					$pos ++;
				}

				for ($j = 0; $j < $pos; $j++) {
					$result .= $indentStr;
				}
			}

			$prevChar = $char;
		}

		return $result;
	}

}
