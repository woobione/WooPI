<?php

abstract class WPIResult {

	abstract public function Result();
	
	/**
	 * Set results response headers
	 */
	public function SetHeaders() {
		// Void
	}

}
