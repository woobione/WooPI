<?php

/**
 * Handles user requests
 * @todo Support for Default controller
 */
class RequestHandler {

	const Config_ControllerPath = 'controller_path';
	const Config_DefaultController = 'default_controller';
	const Config_DefaultAction = 'default_action';
	const Config_AllowGet = 'allow_get';
	
	const RequestType_Get = 'get';
	const RequestType_Post = 'post';

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
		$this->loadAction();
		$this->handleRequestType();
		$this->performAction();
	}

	/**
	 * Parse the request string into request parts
	 * @todo Read separator from config
	 */
	private function parseRequestString() {
		$this->requestString = trim(filter_input(INPUT_GET, 'request'), $this->requestPartSeparator);
		$this->requestParts = explode($this->requestPartSeparator, $this->requestString);
	}

	/**
	 * Get request type
	 * @return string
	 */
	private function getRequestType() {
		return strtolower($_SERVER['REQUEST_METHOD']);
	}

	/**
	 * Handle the request type
	 */
	private function handleRequestType() {
		if (!WoobiPI::GetConfig(self::Config_AllowGet) && $this->getRequestType() == self::RequestType_Get && !WoobiPI::IsDebug()) {
			exit('GET is not allowed');
		}
	}

	/**
	 * Get controller name from request or config
	 * @return string
	 */
	private function getControllerName() {
		return !empty($this->requestParts[0]) ? $this->requestParts[0] : WoobiPI::GetConfig(self::Config_DefaultController);
	}

	/**
	 * Get filename for current controller
	 * @return string
	 */
	private function getControllerFileName() {
		return WoobiPI::GetConfig(self::Config_ControllerPath) . $this->ControllerName . '.php';
	}
	
	/**
	 * Loads the controller name
	 */
	private function loadControllerName() {
		$this->ControllerName = $this->getControllerName();
	}

	/**
	 * Initiate the controller and load it into the RequestHandler
	 */
	private function loadController() {
		$this->loadControllerName();
		if (file_exists($this->getControllerFileName())) {
			require_once $this->getControllerFileName();

			$this->Controller = new $this->ControllerName();
			WoobiPI::Configure($this->Controller->Configuration);
		}
	}

	/**
	 * Get action name from request or config
	 * @return string
	 */
	private function getActionName() {
		return array_key_exists(1, $this->requestParts) ? $this->requestParts[1] : WoobiPI::GetConfig(self::Config_DefaultAction);
	}
	
	/**
	 * Locates the action part of the request
	 */
	private function loadActionName() {
		$this->ActionName = $this->getActionName();
	}
	
	/**
	 * Loads all data related to the action before actually performing it
	 */
	private function loadAction() {
		$this->loadActionName();
		if (array_key_exists($this->ActionName, $this->Controller->ActionConfiguration)) {
			WoobiPI::Configure($this->Controller->ActionConfiguration[$this->ActionName]);
		}
	}

	/**
	 * Perform the chosen action on the controller and handle the result
	 */
	private function performAction() {
		WoobiPI::HandleResult($this->Controller->{$this->ActionName}());
	}

}
