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
		throw new Exception();
		return new JsonResult(array('id' => $id));
	}
	
	/**
	 * This is our default action
	 * Here we allow get for the fun of foo bar
	 * @return JsonResult
	 */
	public function Foo() {
		throw new Exception();
		return new JsonResult(array('foo' => 'bar'));
	}

}
