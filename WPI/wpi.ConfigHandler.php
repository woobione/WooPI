<?php

/**
 * The confighandler handles all WoobiPI configuration
 */
class ConfigHandler {

	private $configuration = array();
	private $defaultConfigFileName = 'config.Default.php';

	/**
	 * ConfigHandler initialization
	 */
	public function __construct() {
		$this->LoadDefaultConfiguration();
	}

	/**
	 * Load the default config file into the configuration
	 */
	public function LoadDefaultConfiguration() {
		$this->AddToConfig(require_once(WPI_PATH . $this->defaultConfigFileName));
	}

	/**
	 * Add an array of values to the configuration
	 * @param array $configuration
	 */
	public function AddToConfig(Array $configuration) {
		$this->configuration = array_replace_recursive($this->configuration, $configuration);
	}

	/**
	 * Get the configuration value for $key
	 * @param string $key
	 * @return mixed
	 */
	public function Get($key) {
		return $this->configuration[$key];
	}

}
