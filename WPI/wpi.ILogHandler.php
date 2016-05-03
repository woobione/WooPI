<?php

interface ILogHandler {

	/**
	 * Log message to log
	 * @param ILogMessage $message Message to log
	 */
	public function Log(ILogMessage $message);

	/**
	 * @return ILogMessage[]
	 */
	public function GetLoggedMessages();

}
