<?php

/**
 * Responsible for WooPI configuration
 * @since 1.0
 */
class ConfigHandler {

	/**
	 * @var array
	 */
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
	 * Returns false if key doesn't exist
	 * @param string $key
	 * @return mixed
	 */
	public function Get($key) {
		return array_key_exists($key, $this->configuration) ? $this->configuration[$key] : false;
	}

}
