<?php

class HomeController extends WPIController {

	public function __construct() {
		$this->Configuration = array(
			
		);
		
		$this->ActionConfiguration = array(
			'Foo'
		);
	}

	/**
	 * Action for get requests
	 * @return JsonResult
	 */
	public function Get($id = 0) {
		return new JsonResult(array('id' => $id));
	}
	
	/**
	 * Custom action defined in ActionConfiguration
	 * @return JsonResult
	 */
	public function Foo() {
		return new JsonResult(array('foo' => 'bar'));
	}

}
