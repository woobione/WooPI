<?php

/**
 * Handles user requests
 * @todo Support for Default controller
 */
class RequestHandler {

	const Config_ControllerPath = 'controller_path';
	const Config_DefaultController = 'default_controller';
	const Config_DefaultAction = 'default_action';

	private $requestString;
	private $requestParts;
	private $requestPartSeparator = '/';

	/**
	 * @var WPIController 
	 */
	public $Controller = null;

	/**
	 * @var string 
	 */
	public $ControllerName = null;

	/**
	 * @var string 
	 */
	public $ActionName = null;

	/**
	 * @var WPIResult
	 */
	public $ActionResult = null;

	/**
	 * Handle the users request
	 */
	public function HandleRequest() {
		$this->parseRequestString();
		$this->loadController();
		$this->performAction();
	}

	/**
	 * Parse the request string into request parts
	 */
	private function parseRequestString() {
		$this->requestString = trim(filter_input(INPUT_GET, 'request'), '/');
		$this->requestParts = explode($this->requestPartSeparator, $this->requestString);
	}

	/**
	 * Get controller name from request or config
	 * @return string
	 */
	private function getControllerName() {
		if (!empty($this->requestParts[0]))
			return $this->requestParts[0];
		else
			return WoobiPI::GetConfig(self::Config_DefaultController);
	}

	/**
	 * Get filename for current controller
	 * @return string
	 */
	private function getControllerFileName() {
		return WoobiPI::GetConfig(self::Config_ControllerPath) . $this->ControllerName . '.php';
	}

	/**
	 * Get action name from request or config
	 * @return string
	 */
	private function getActionName() {
		if (array_key_exists(1, $this->requestParts))
			return $this->requestParts[1];
		else
			return WoobiPI::GetConfig(self::Config_DefaultAction);
	}

	/**
	 * Initiate the controller and load it into the RequestHandler
	 */
	private function loadController() {
		$this->ControllerName = $this->getControllerName();
		if (file_exists($this->getControllerFileName())) {
			require_once $this->getControllerFileName();

			$this->Controller = new $this->ControllerName();
			WoobiPI::Configure($this->Controller->Configuration);
		}
	}

	/**
	 * Perform the chosen action on the controller and handle the result
	 */
	private function performAction() {
		$this->ActionName = $this->getActionName();
		WoobiPI::HandleResult($this->Controller->{$this->ActionName}());
	}

}
