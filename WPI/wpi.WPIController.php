<?php

abstract class WPIController {

	/**
	 * @var Array 
	 */
	public $Configuration = array();
	
	/**
	 * Default action on controllers
	 */
	public function Index() {
		return new JsonResult("foo bar");
	}

}
