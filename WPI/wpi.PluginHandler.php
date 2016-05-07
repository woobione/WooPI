<?php

/**
 * Plugin handler improves handling of plugins
 * @since 1.3
 */
class PluginHandler implements IPluginHandler {

	const Config_HardErrors = 'pluginhandler_hard_errors';
	const Config_PluginPath = 'pluginhandler_plugin_path';

	/**
	 * Plugin handler constants
	 */
	const Plugin_FilePrefix = 'plugin.';
	const Plugin_FileExtension = '.php';
	const Plugin_BaseInterface = 'IPlugin';
	const Plugin_ExtendedInterface = 'IExtendedPlugin';
	const Plugin_RegisteredInterface = 'IRegisteredPlugin';

	/**
	 * @var array
	 */
	private $loadedPlugins = array();

	/**
	 * Instances of registered plugins
	 * @var array
	 */
	private $registeredPlugins = array();

	/**
	 * Load plugins
	 * @return bool Plugins were loaded correctly
	 */
	public function LoadPlugins() {
		foreach (glob($this->getPluginPath() . self::Plugin_FilePrefix . '*' . self::Plugin_FileExtension) as $pluginFile) {
			// Load plugin
			require_once $pluginFile;
			$pluginName = str_replace(array(self::Plugin_FilePrefix, self::Plugin_FileExtension), '', basename($pluginFile));

			// Check if is valid plugin
			if ($this->pluginImplementsInterface($pluginName, self::Plugin_BaseInterface)) {
				// Load child files if plugin is extended
				if ($this->pluginImplementsInterface($pluginName, self::Plugin_ExtendedInterface))
					$this->loadPluginChildFiles($pluginName);

				$this->loadedPlugins[$pluginFile] = $pluginName;
			}
		}
		return true;
	}

	/**
	 * Register plugins with framwork
	 */
	public function RegisterPlugins() {
		foreach($this->loadedPlugins as $pluginName) {
			if (wpi_class_implements($pluginName, self::Plugin_RegisteredInterface))
				$this->loadedPlugins[$pluginName] = $pluginName::Register();
		}
	}

	/**
	 * Load plugin child files
	 * @param string $pluginName
	 * @return bool Loaded child files
	 */
	private function loadPluginChildFiles($pluginName) {
		$childFilesPath = $this->getPluginChildPath($pluginName);
		if (!is_dir($childFilesPath)) {
			if (WooPI:GetConfig(self::Config_HardErrors))
				throw new WPIException("Plugin '$pluginName' implements interface {self::Plugin_ExtendedInterface} but has no child folder");

			return false;
		}

		foreach (glob($childFilesPath . strtolower($pluginName) . '.*' . self::Plugin_FileExtension) as $pluginFile) {
			require_once $pluginFile;
		}

		return true;
	}

	/**
	 * Get path to plugin folder
	 * @return string Plugin folder path
	 */
	private function getPluginPath() {
		return WooPI::GetConfig(self::Config_PluginPath);
	}

	/**
	 * Get path to plugin child files folder
	 * @param  string $pluginName Name of plugin
	 * @return string Plugin child files path
	 */
	private function getPluginChildPath($pluginName) {
		return $this->getPluginPath() . $pluginName . DIRECTORY_SEPARATOR;
	}

}

/**
 * Basic plugin interface
 */
interface IPlugin {}

/**
 * Extended plugin (with child files) interface
 */
interface IExtendedPlugin extends IPlugin {}

/**
 * Registered plugin interface - plugin should create an instance
 */
interface IRegisteredPlugin {}
