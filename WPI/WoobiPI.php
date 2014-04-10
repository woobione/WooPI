<?php

/**
 * WoobiPI (WPI)
 * @version 1.0
 * @author Anton Netterwall <anton@woobione.se>
 */
class WoobiPI {
	
	const Config_Debug = 'debug';

	/**
	 * @var WoobiPI 
	 */
	protected static $instance = null;

	/**
	 * @var bool
	 */
	private $frameworkIsLoaded = false;

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
	public static function Instance() {
		if (!isset(static::$instance)) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Private constructor
	 */
	private function __construct() {
		// Foo Bar
	}

	/**
	 * Load WoobiPI framework
	 * @todo Should only be able to run once (in constructor)
	 */
	public static function Load() {
		if (!self::Instance()->FrameworkIsLoaded()) {
			self::Instance()->LoadFramework();
			self::Instance()->ConfigHandler = new ConfigHandler();
			self::Instance()->RequestHandler = new RequestHandler();
			self::Instance()->ResultHandler = new ResultHandler();
		}
	}

	/**
	 * Add configuration
	 * @param array $configuration
	 */
	public static function Configure(Array $configuration) {
		self::Instance()->ConfigHandler->AddToConfig($configuration);
	}

	/**
	 * Get a config value from the config
	 * @param type $key
	 */
	public static function GetConfig($key) {
		return self::Instance()->ConfigHandler->Get($key);
	}

	/**
	 * Handle the user request
	 */
	public static function HandleRequest() {
		self::Instance()->RequestHandler->HandleRequest();
	}

	/**
	 * Handle the result from the request
	 * @param mixed $result
	 */
	public static function HandleResult($result) {
		self::Instance()->ResultHandler->HandleResult($result);
	}

	/**
	 * Load the WPI framework
	 */
	public function LoadFramework() {
		foreach (glob(WPI_PATH . 'wpi.*.php') as $frameworkFile) {
			require_once $frameworkFile;
		}
		$this->frameworkIsLoaded = true;
	}

	/**
	 * Check if the framework is loaded
	 * @return bool
	 */
	public function FrameworkIsLoaded() {
		return $this->frameworkIsLoaded;
	}
	
	/**
	 * Check if WoobiPI is in debug mode
	 * @return bool
	 */
	public static function IsDebug() {
		return self::GetConfig(self::Config_Debug);
	}

}
