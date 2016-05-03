<?php

/**
 * Log handler adds logging to WooPI
 * @since 1.3
 */
class LogHandler implements ILogHandler {

	/**
	 * @var LogMessage[]
	 */
	private $loggedMessages = array();

	/**
	 * Log message
	 * @param LogMessage Log message
	 */
	public function Log(LogMessage $message) {
		array_push($this->loggedMessages, $message);
	}

}

/**
 * Log message for LogHandler
 */
class LogMessage implements ILogMessage {

	/**
	 * Log levels
	 */
	const LogLevel_Informational = 10;
	const LogLevel_Notice = 20;
	const LogLevel_Error = 30;
	const LogLevel_Catastrophic = 40;

	/**
	 * @var string
	 */
	private $message;

	/**
	 * @var int
	 */
	private $level;

	/**
	 * Populates log message on construct
	 * @param string $message Log message
	 * @param int $level Log level (LogMessage::LogLevel_xxx)
	 */
	public function __construct($message, $level = self::LogLevel_Notice) {
		$this->message = $message;
		$this->level = $level;
	}

}
