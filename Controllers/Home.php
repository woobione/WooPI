<?php

class Home extends WPIController {

	public function __construct() {
		$this->Configuration = array(
			RequestHandler::Config_DefaultAction => 'Test'
		);
	}

	public function Test() {
		return new JsonResult();
	}

	public function Index() {
		return new JsonResult();
	}

}
