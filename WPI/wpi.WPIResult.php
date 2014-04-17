<?php

abstract class WPIResult {

	/**
	 * Execute result
	 */
	abstract public function Result();
	
	/**
	 * Set results response headers
	 */
	abstract public function SetHeaders();
	
	/**
	 * Results exception handler
	 * @param Exception $e
	 */
	public static function HandleException(Exception $e) {
		echo "Exception: " . $e->getMessage() . ' Trace:' . $e->getTraceAsString();
	}

}
