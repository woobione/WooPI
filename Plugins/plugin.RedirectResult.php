<?php

class RedirectResult extends WPIResult implements IPlugin {

	/**
	 * Redirect types
	 */
	const RedirectType_Permanent = 301;
	const RedirectType_SeeOther = 303;
	const RedirectType_Temporary = 307;

	/**
	 * @var string
	 */
	private $location;

	/**
	 * @var int
	 */
	private $redirectType;

	/**
	 * Takes redirect location on construct
	 * @param string $location Redirect location
	 * @param int $redirectType Type of redirect - RedirectResult::RedirectType_xxx
	 */
	public function __construct($location, $redirectType = self::RedirectType_Permanent) {
		$this->SetLocation($location);
		$this->redirectType = $redirectType;
	}

	/**
	 * Sets location for redirect
	 * @param string $location Redirect location
	 */
	public function SetLocation($location) {
		if (!strlen($location))
			throw new Exception("Invalid location was supplied in RedirectResult");

		$this->location = $location;
	}

	/**
	 * Prevent any output after redirect
	 */
	public function Result() {
		exit();
	}

	/**
	 * Set redirect headers
	 */
	public function SetHeaders() {
		header('Location: ' . $this->location, true, $this->redirectType);
	}

}

/**
 * Redirect location helper
 */
class RedirectLocation {

	/**
	 * Redirect to internal action
	 * @todo Make use of internal WPI routing
	 * @return string
	 */
	public static function Action($controller, $action) {
		return $controller . DIRECTORY_SEPARATOR . $action;
	}

	/**
	 * Redirect to a url
	 * @param string $url Url to redirect to
	 * @return string
	 */
	public static function Url($url) {
		return $url;
	}

}
