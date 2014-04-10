<?php

class JsonResult extends WPIResult {
	
	private $object = null;
	
	public function __construct($object) {
		$this->object = $object;
	}
	
	public function Result() {
		
	}
	
	public function SetHeaders() {
		
	}

}
