<?php

define('WOOBIPI_VERSION', '1.1');

/**
 * WoobiPI (WPI)
 * @version 1.1
 * @author Anton Netterwall <anton@woobione.se>
 */
class WoobiPI {
	
	const Config_Debug = 'debug';
	const Config_PluginPath = 'plugin_path';
	const Config_RequestHandler = 'request_handler';
	const Config_ResultHandler = 'result_handler';
	const Config_ExceptionMode = 'exception_mode';
	const Config_CurrentApiVersion = 'current_api_version';
	const Config_AvailableApiVersions = 'available_api_versions';

	/**
	 * @var WoobiPI 
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
	 */
	public $RequestHandler = null;

	/**
	 * @var ResultHandler 
	 */
	public $ResultHandler = null;

	/**
	 * Get singleton instance of WoobiPI
	 * @return WoobiPI
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
	 * Load WoobiPI
	 */
	public function Load() {
		if (!$this->frameworkIsLoaded) {
			$this->loadFramework();
			$this->ConfigHandler = new ConfigHandler();
			$this->loadPlugins();

			// Load request handler
			$requestHandler = $this->ConfigHandler->Get(self::Config_RequestHandler);
			$this->RequestHandler = new $requestHandler();

			// Load result handler
			$resultHandler = $this->ConfigHandler->Get(self::Config_ResultHandler);
			$this->ResultHandler = new $resultHandler();
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
		foreach (glob(WoobiPI::GetConfig(self::Config_PluginPath) . 'plugin.*.php') as $pluginFile) {
			require_once $pluginFile;
		}
	}
	
	/**
	 * Check if WoobiPI is in debug mode
	 * @return bool
	 */
	public static function IsDebug() {
		return WoobiPI::GetConfig(self::Config_Debug);
	}

	/**
	 * Add configuration
	 * @param array $configuration
	 */
	public static function Configure(Array $configuration) {
		WoobiPI::Instance()->ConfigHandler->AddToConfig($configuration);
	}

	/**
	 * Get a config value from the config
	 * @param type $key
	 */
	public static function GetConfig($key) {
		return WoobiPI::Instance()->ConfigHandler->Get($key);
	}

	/**
	 * Handle the user request
	 */
	public static function HandleRequest() {
		WoobiPI::Instance()->RequestHandler->HandleRequest();
	}

	/**
	 * Handle the result from the request
	 * @param mixed $result
	 */
	public static function HandleResult($result) {
		WoobiPI::Instance()->ResultHandler->HandleResult($result);
	}

}

/**
 * Handles exceptions
 */
set_exception_handler(function(Exception $e) {
	$exceptionMode = WoobiPI::GetConfig(WoobiPI::Config_ExceptionMode);
	call_user_func_array(array($exceptionMode . 'Result', 'HandleException'), array($e));
});