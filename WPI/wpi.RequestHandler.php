<?php

/**
 * Directs the user request
 * @since 1.0
 */
class RequestHandler implements IRequestHandler {

	const Config_ControllerSuffix = 'controller_suffix';
	const Config_ControllerPath = 'controller_path';
	const Config_DefaultController = 'default_controller';
	const Config_RequestPartSeparator = 'request_part_separator';
	const Config_GetAction = 'get_action';
	const Config_PostAction = 'post_action';
	const Config_PutAction = 'put_action';
	const Config_PatchAction = 'patch_action';
	const Config_DeleteAction = 'delete_action';
	const Config_AllowRequestTypes = 'allowed_request_types';
	const Config_AllowHttp = 'allow_http';

	/**
	 * Request Types
	 */
	const RequestType_Get = 'get';
	const RequestType_Post = 'post';
	const RequestType_Put = 'put';
	const RequestType_Patch = 'patch';
	const RequestType_Delete = 'delete';

	private $requestString;
	private $requestParts;
	private $unusedRequestParts;
	private $apiVersionWasDefined = null;
	private $controllerNameWasDefined = null;
	private $actionNameWasDefined = null;
	private $apiVersionFolder = "";

	/**
	 * @var string
	 */
	public $APIVersion = null;

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
		$this->loadApiVersion();
		$this->loadController();
		$this->loadAction();
		$this->handleProtocol();
		$this->handleRequestType();
		$this->performAction();
	}

	/**
	 * Parse the request string into request parts
	 */
	private function parseRequestString() {
		$this->requestString = trim(Request::Get('request'), WooPI::GetConfig(self::Config_RequestPartSeparator));
		$this->requestParts = explode(WooPI::GetConfig(self::Config_RequestPartSeparator), $this->requestString);
		$this->unusedRequestParts = $this->requestParts;
	}

	/**
	 * Returns true if API version was submitted in url
	 * @return bool
	 */
	private function apiVersionWasDefined() {
		if (is_null($this->apiVersionWasDefined))
			$this->apiVersionWasDefined = array_key_exists(0, $this->unusedRequestParts) && !empty($this->unusedRequestParts[0]) && in_array($this->unusedRequestParts[0], WooPI::GetConfig(WooPI::Config_AvailableApiVersions));
		return $this->apiVersionWasDefined;
	}

	/**
	 * Get chosen API version
	 * @return string
	 */
	private function getApiVersion() {
		if (is_null($this->APIVersion))
			$this->APIVersion = $this->apiVersionWasDefined() ? $this->unusedRequestParts[0] : WooPI::GetConfig(WooPI::Config_CurrentApiVersion);
		return $this->APIVersion;
	}

	/**
	 * Returns true if the chosen API version is the current version
	 * @return bool
	 */
	private function apiVersionIsCurrent() {
		return $this->getApiVersion() == WooPI::GetConfig(WooPI::Config_CurrentApiVersion);
	}

	/**
	 * Load the current API version
	 */
	private function loadApiVersion() {
		if ($this->apiVersionWasDefined() && $this->getApiVersion())
			array_shift($this->unusedRequestParts);

		if (is_dir(WooPI::GetConfig(self::Config_ControllerPath) . $this->getApiVersion()))
			$this->apiVersionFolder = $this->getApiVersion() . DIRECTORY_SEPARATOR;
		elseif ($this->apiVersionIsCurrent())
			$this->apiVersionFolder = '';
		else
			throw new WPIException('API version "' . $this->getApiVersion() . '" is not available');
	}

	/**
	 * Allows or disallows protocols
	 */
	private function handleProtocol() {
		if (!WooPI::GetConfig(self::Config_AllowHttp) && !$this->isSecureProtocol())
			throw new WPIException('Using disallowed protocol HTTP');
	}

	/**
	 * Check if the used protocol is secure (HTTPS)
	 * @return bool
	 */
	private function isSecureProtocol() {
		return (array_key_exists('HTTPS', $_SERVER) && $_SERVER['HTTPS'] === 'on') || $_SERVER['SERVER_PORT'] === '443';
	}

	/**
	 * Get request type
	 * @return string
	 */
	private function getRequestType() {
		return strtolower(Request::Post('_method', false)) ?: strtolower($_SERVER['REQUEST_METHOD']);
	}

	/**
	 * Check if the current request type is allowed on the current controller
	 * @return bool
	 */
	private function isAllowedRequestType() {
		return in_array($this->getRequestType(), explode(',', WooPI::GetConfig(self::Config_AllowRequestTypes)));
	}

	/**
	 * Handle the request type
	 * @throws WPIException
	 */
	private function handleRequestType() {
		if (!$this->isAllowedRequestType() && !WooPI::IsDebug())
			throw new WPIException('Request type ' . strtoupper($this->getRequestType()) . ' is not allowed');
	}

	/**
	 * Returns true if a controller name was defined in the url
	 * @return bool
	 */
	private function controllerNameWasDefined() {
		if (is_null($this->controllerNameWasDefined))
			$this->controllerNameWasDefined = array_key_exists(0, $this->unusedRequestParts) && !empty($this->unusedRequestParts[0]);
		return $this->controllerNameWasDefined;
	}

	/**
	 * Get controller name from request or config
	 * @return string
	 */
	private function getControllerName() {
		if (is_null($this->ControllerName))
			$this->ControllerName = ucfirst(($this->controllerNameWasDefined() ? $this->unusedRequestParts[0] : WooPI::GetConfig(self::Config_DefaultController)) . WooPI::GetConfig(self::Config_ControllerSuffix));
		return $this->ControllerName;
	}

	/**
	 * Get filename for current controller
	 * @return string
	 */
	private function getControllerFileName() {
		return WooPI::GetConfig(self::Config_ControllerPath) . $this->apiVersionFolder . $this->getControllerName() . '.php';
	}

	/**
	 * Initiate the controller and load it into the RequestHandler
	 * @throws WPIException
	 */
	private function loadController() {
		$controllerName = $this->getControllerName();
		if (file_exists($this->getControllerFileName())) {
			require_once $this->getControllerFileName();
			array_shift($this->unusedRequestParts);

			$this->Controller = new $controllerName();
			WooPI::Configure($this->Controller->Configuration);
			return;
		}

		throw new WPIException("No controller with given name '$controllerName' exists");
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
	 * Returns true if the action name was defined in the url
	 * @return bool
	 */
	private function actionNameWasDefined() {
		if (is_null($this->actionNameWasDefined))
			$this->actionNameWasDefined = array_key_exists(0, $this->unusedRequestParts) && !empty($this->unusedRequestParts[0]) && $this->isAllowedAction($this->unusedRequestParts[0]);
		return $this->actionNameWasDefined;
	}

	/**
	 * Returns action names based on request type
	 * @return string
	 */
	private function getRequestBasedActionName() {
		$requestBasedActionName = ucfirst($this->getRequestType());
		$customRequestBasedActionName = WooPI::GetConfig(constant('self::Config_' . $requestBasedActionName . 'Action'));
		return $customRequestBasedActionName ? : $requestBasedActionName;
	}

	/**
	 * Get action name from request or config
	 * @return string
	 */
	private function getActionName() {
		if (is_null($this->ActionName))
			$this->ActionName = $this->actionNameWasDefined() ? ucfirst($this->unusedRequestParts[0]) : $this->getRequestBasedActionName();
		return $this->ActionName;
	}

	/**
	 * Loads all data related to the action before actually performing it
	 */
	private function loadAction() {
		if ($this->actionNameWasDefined() && $this->getActionName())
			array_shift($this->unusedRequestParts);

		if (array_key_exists($this->getActionName(), $this->Controller->ActionConfiguration))
			WooPI::Configure($this->Controller->ActionConfiguration[$this->getActionName()]);
	}

	/**
	 * Get parameters for action
	 * @return array
	 */
	private function getActionParameters() {
		return !empty($this->unusedRequestParts) ? $this->unusedRequestParts : array();
	}

	/**
	 * Get parameters for action, casted to best matching objecttype
	 * @return array
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
	 * @throws WPIException
	 */
	private function performAction() {
		if (!method_exists($this->Controller, $this->getActionName()))
			throw new WPIException("Action '{$this->getActionName()}' not implemented in controller: '{$this->getControllerName()}'");

		$actionResult = call_user_func_array(array($this->Controller, $this->getActionName()), $this->getCastedActionParameters());
		if (!($actionResult instanceof WPIResult))
			throw new WPIException("No valid WPIResult returned from action '{$this->getActionName()}' in controller '{$this->getControllerName()}'");

		WooPI::HandleResult($actionResult);
	}

}

/**
 * Request helper
 */
class Request {

	/**
	 * Return GET variable
	 * @param string $name
	 * @param mixed $defaultValue
	 * @return string
	 */
	public static function Get($name, $defaultValue = null) {
		return filter_input(INPUT_GET, $name) ? : $defaultValue;
	}

	/**
	 * Return POST variable
	 * @param string $name
	 * @param mixed $defaultValue
	 * @return string
	 */
	public static function Post($name, $defaultValue = null) {
		return filter_input(INPUT_POST, $name) ? : $defaultValue;
	}

}
