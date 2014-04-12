<?php

/**
 * Handles user requests
 */
class RequestHandler {

	const Config_ControllerSuffix = 'controller_suffix';
	const Config_ControllerPath = 'controller_path';
	const Config_DefaultController = 'default_controller';
	const Config_RequestPartSeparator = 'request_part_separator';
	const Config_GetAction = 'get_action';
	const Config_PostAction = 'post_action';
	const Config_PutAction = 'put_action';
	const Config_DeleteAction = 'delete_action';
	const Config_PatchAction = 'patch_action';
	const Config_AllowRequestTypes = 'allowed_request_types';
	const Config_AllowHttp = 'allow_http';
	
	/**
	 * Request Types
	 */
	const RequestType_Get = 'get';
	const RequestType_Post = 'post';
	const RequestType_Put = 'put';
	const RequestType_Delete = 'delete';
	const RequestType_Patch = 'patch';

	private $requestString;
	private $requestParts;
	private $unusedRequestParts;

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
		$this->requestString = trim(filter_input(INPUT_GET, 'request'), WoobiPI::GetConfig(self::Config_RequestPartSeparator));
		$this->requestParts = explode(WoobiPI::GetConfig(self::Config_RequestPartSeparator), $this->requestString);
		$this->unusedRequestParts = $this->requestParts;
	}

	/**
	 * Get request type
	 * @return string
	 */
	private function getRequestType() {
		return strtolower($_SERVER['REQUEST_METHOD']);
	}

	/**
	 * Check if the current request type is allowed on the current controller
	 * @return bool
	 */
	private function isAllowedRequestType() {
		return in_array($this->getRequestType(), explode(',', WoobiPI::GetConfig(self::Config_AllowRequestTypes)));
	}

	/**
	 * Handle the request type
	 */
	private function handleRequestType() {
		if (!$this->isAllowedRequestType() && !WoobiPI::IsDebug()) {
			exit('Request type ' . strtoupper($this->getRequestType()) . ' is not allowed');
		}
	}

	/**
	 * Get controller name from request or config
	 * @return string
	 */
	private function getControllerName() {
		return ucfirst((!empty($this->requestParts[0]) ? $this->requestParts[0] : WoobiPI::GetConfig(self::Config_DefaultController)) . WoobiPI::GetConfig(self::Config_ControllerSuffix));
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
		array_shift($this->unusedRequestParts);
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
	 * Check if controller has allowed the action to be accessed
	 * @param string $actionName
	 * @return bool
	 */
	private function isAllowedAction($actionName) {
		$actionName = ucfirst($actionName);
		return in_array($actionName, $this->Controller->ActionConfiguration) || array_key_exists($actionName, $this->Controller->ActionConfiguration);
	}

	/**
	 * Returns action names based on request type
	 * @return string
	 */
	private function getRequestBasedActionName() {
		return ucfirst($this->getRequestType());
	}

	/**
	 * Get action name from request or config
	 * @return string
	 */
	private function getActionName() {
		if (array_key_exists(1, $this->requestParts) && $this->isAllowedAction($this->requestParts[1])) {
			array_shift($this->unusedRequestParts);
			return ucfirst($this->requestParts[1]);
		} else {
			return $this->getRequestBasedActionName();
		}
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
	 * Get parameters for action
	 * @return Array
	 */
	private function getActionParameters() {
		return !empty($this->unusedRequestParts) ? $this->unusedRequestParts : array();
	}

	/**
	 * Get parameters for action, casted to best matching objecttype
	 * @return Array
	 */
	private function getCastedActionParameters() {
		$actionParameters = array();
		foreach ($this->getActionParameters() as $actionParameter) {
			// Parse numeric value
			if (is_numeric($actionParameter)) {
				if (intval($actionParameter) == $actionParameter)
					$actionParameter = intval($actionParameter);
				else
					$actionParameter = floatval($actionParameter);
				
			// Parse boolean value
			} elseif (in_array($actionParameter, array('true', 'false'))) {
				$actionParameter = ($actionParameter == 'true');
			}
			
			array_push($actionParameters, $actionParameter);
		}
		return $actionParameters;
	}

	/**
	 * Perform the chosen action on the controller and handle the result
	 */
	private function performAction() {
		WoobiPI::HandleResult(call_user_func_array(array($this->Controller, $this->ActionName), $this->getCastedActionParameters()));
	}

}
