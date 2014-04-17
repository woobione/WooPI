<?php

/**
 * Handles controller results
 */
class ResultHandler implements IResultHandler {
	
	const Config_DefaultHeaders = 'default_headers';

	/**
	 * Handle the result
	 * @param WPIResult $result
	 */
	public function HandleResult(WPIResult $result) {
		$this->setDefaultHeaders();
		$result->SetHeaders();
		$result->Result();
	}
	
	/**
	 * Set default headers for every request
	 */
	private function setDefaultHeaders() {
		$defaultHeadersConfigValue = WoobiPI::GetConfig(self::Config_DefaultHeaders);
		$defaultHeaders = is_array($defaultHeadersConfigValue) ? $defaultHeadersConfigValue : array($defaultHeadersConfigValue);

		foreach($defaultHeaders as $defaultHeader) {
			header($defaultHeader);
		}
	}

}
