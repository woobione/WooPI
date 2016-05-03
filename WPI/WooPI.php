<?php

define('WOOPI_VERSION', '1.3-beta');

/**
 * WooPI (WPI)
 * @version 1.3-beta
 * @author Anton Netterwall <anton@woobione.se>
 */
class WooPI {

	const Config_Debug = 'debug';
	const Config_PluginPath = 'plugin_path';
	const Config_LogHandler = 'log_handler';
	const Config_RequestHandler = 'request_handler';
	const Config_ResultHandler = 'result_handler';
	const Config_ExceptionMode = 'exception_mode';
	const Config_CurrentApiVersion = 'current_api_version';
	const Config_AvailableApiVersions = 'available_api_versions';

	/**
	 * @var WooPI
	 */
	protected static $instance = null;

	/**
	 * @var bool
	 */
	protected $frameworkIsLoaded = false;

	/**
	 * @var ConfigHandler
	 */
	public $ConfigHandler = null;

	/**
	 * @var RequestHandler
	/**
	 * @var ILogHandler
	 */
	public $LogHandler = null;
	 */
	public $RequestHandler = null;

	/**
	 * @var IResultHandler
	 */
	public $ResultHandler = null;

	/**
	 * Get singleton instance of WooPI
	 * @return WooPI
	 */
	public static final function Instance() {
		if (!static::$instance)
			static::$instance = new static();

		return static::$instance;
	}

	/**
	 * Private stuff
	 */
	private function __construct() {}
	private function __clone() {}

	/**
	 * Load WooPI
	 * @throws WPIException
	 */
	public function Load() {
		if (!$this->frameworkIsLoaded) {
			$this->loadFramework();
			$this->ConfigHandler = new ConfigHandler();
			$this->loadPlugins();
			// Load log handler
			$logHandler = $this->ConfigHandler->Get(self::Config_LogHandler);
			if (array_key_exists('ILogHandler', class_implements($logHandler)))
				$this->LogHandler = new $logHandler();
			else
				throw new WPIException("LogHandler '$logHandler' does not implement required interface ILogHandler");

			// Load request handler
			$requestHandler = $this->ConfigHandler->Get(self::Config_RequestHandler);
			if (array_key_exists('IRequestHandler', class_implements($requestHandler)))
				$this->RequestHandler = new $requestHandler();
			else
				throw new WPIException("RequestHandler '$requestHandler' does not implement required interface IRequestHandler");

			// Load result handler
			$resultHandler = $this->ConfigHandler->Get(self::Config_ResultHandler);
			if (array_key_exists('IResultHandler', class_implements($resultHandler)))
				$this->ResultHandler = new $resultHandler();
			else
				throw new WPIException("ResultHandler '$resultHandler' does not implement required interface IResultHandler");
		}
	}

	/**
	 * Load the WPI framework
	 */
	private function loadFramework() {
		foreach (glob(WPI_PATH . 'wpi.*.php') as $frameworkFile) {
			require_once $frameworkFile;
		}
		$this->frameworkIsLoaded = true;
	}

	/**
	 * Load plugins
	 * @todo Should keep track of lodaded plugins
	 */
	private function loadPlugins() {
		foreach (glob(WooPI::GetConfig(self::Config_PluginPath) . 'plugin.*.php') as $pluginFile) {
			require_once $pluginFile;
		}
	}

	/**
	 * Check if WooPI is in debug mode
	 * @return bool
	 */
	public static function IsDebug() {
		return WooPI::GetConfig(self::Config_Debug);
	}

	/**
	 * Add configuration
	 * @param array $configuration
	 */
	public static function Configure(Array $configuration) {
		WooPI::Instance()->ConfigHandler->AddToConfig($configuration);
	}

	/**
	 * Get a config value from the config
	 * @param string $key
	 */
	public static function GetConfig($key) {
		return WooPI::Instance()->ConfigHandler->Get($key);
	}

	/**
	 * Handle the user request
	 */
	public static function HandleRequest() {
		WooPI::Instance()->RequestHandler->HandleRequest();
	}

	/**
	 * Handle the result from the request
	 * @param WPIResult $result
	 */
	public static function HandleResult(WPIResult $result) {
		WooPI::Instance()->ResultHandler->HandleResult($result);
	}

	/**
	 * Log message
	 * @param ILogMessage $message Log message to log
	 */
	public static function Log(ILogMessage $message) {
		WooPI::Instance()->LogHandler->Log($message);
	}

	/**
	 * Get logged messages
	 * @return ILogMessage[]
	 */
	public static function GetLogs() {
		return WooPI::Instance()->LogHandler->GetLoggedMessages();
	}

}

/**
 * Handle exceptions using results
 */
set_exception_handler(function(Exception $e) {
	$e = !is_a($e, 'WPIException') ? $e : new Exception($e->getMessage());
	call_user_func_array(array(WooPI::GetConfig(WooPI::Config_ExceptionMode) . 'Result', 'HandleException'), array($e));
});

/**
 * WooPI internal exceptions
 */
class WPIException extends Exception {}
