<?php

/**
 * File with misc helper functions
 * @since 1.3
 */

/**
 * Check if a class implements and interface
 * @param mixed $classNameOrInstance Name or instance of class
 * @param string $interfaceName Name of interface to check for
 * @return bool Class implements interface
 */
function wpi_class_implements($classNameOrInstance, $interfaceName) {
	return array_key_exists($interfaceName, class_implements($classNameOrInstance));
}
