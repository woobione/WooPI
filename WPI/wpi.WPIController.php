<?php

abstract class WPIController {

	/**
	 * Controller specific configuration
	 * @var Array 
	 */
	public $Configuration = array();
	
	/**
	 * Action specific configuration
	 * @var Array 
	 */
	public $ActionConfiguration = array();
	
	/**
	 * Default action on controllers
	 */
	public function Get() {
		return new JsonResult(array('success' => true));
	}

}
