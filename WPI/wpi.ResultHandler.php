<?php

/**
 * Handles result from the request
 */
class ResultHandler {
	
	const Config_PluginPath = 'plugin_path';
	
	/**
	 * Load plugins on construct
	 */
	public function __construct() {
		$this->loadPlugins();
	}

	/**
	 * Handle the result
	 * @param WPIResult $result
	 */
	public function HandleResult(WPIResult $result) {
		$result->Result();
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

}
