<?php

class Home extends WPIController {

	public function __construct() {
		$this->Configuration = array(
			RequestHandler::Config_DefaultAction	=> 'Index'
		);
		
		$this->ActionConfiguration = array(
			'Index' => array(
				RequestHandler::Config_AllowGet		=> true
			)
		);
	}

	public function Test() {
		return new JsonResult();
	}

	public function Index() {
		return new JsonResult();
	}

}
