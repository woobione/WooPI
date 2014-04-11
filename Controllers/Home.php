<?php

class Home extends WPIController {

	public function __construct() {
		$this->Configuration = array(
			RequestHandler::Config_DefaultAction	=> 'Foo'
		);
		
		$this->ActionConfiguration = array(
			'Foo' => array(
				RequestHandler::Config_AllowGet		=> true
			)
		);
	}

	/**
	 * This is normally the default action
	 * In this controller we override it to be 'Foo' instead
	 * @return JsonResult
	 */
	public function Index() {
		return new JsonResult();
	}
	
	/**
	 * This is our default action
	 * Here we allow get for the fun of foo bar
	 * @return JsonResult
	 */
	public function Foo() {
		return new JsonResult(array('foo' => 'bar'));
	}

}
