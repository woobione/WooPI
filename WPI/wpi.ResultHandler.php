<?php

/**
 * Handles result from the request
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
		$defaultHeaders = WoobiPI::GetConfig(self::Config_DefaultHeaders);
		
		if (is_array($defaultHeaders)) {
			foreach($defaultHeaders as $defaultHeader) {
				header($defaultHeader);
			}
		} else {
			header($defaultHeaders);
		}
	}

}
