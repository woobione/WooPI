<?php

abstract class WPIResult {

	abstract public function Result();
	
	/**
	 * Set results response headers
	 */
	public function SetHeaders() {
		// Void
	}
	
	/**
	 * Default exception handler
	 * @param Exception $e
	 */
	public static function HandleException(Exception $e) {
		echo "Exception: " . $e->getMessage() . ' Trace:' . $e->getTraceAsString();
	}

}
