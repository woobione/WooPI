<?php

interface IPluginHandler {

	/**
	 * Load plugins
	 * @return bool Plugins were loaded correctly
	 */
	public function LoadPlugins();

	/**
	 * Register plugins with framework
	 * @return bool Plugins were registered correctly
	 */
	public function RegisterPlugins();

}
