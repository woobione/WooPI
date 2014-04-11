<?php

// Set include path
set_include_path(dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('WPI_PATH', 'WPI' . DIRECTORY_SEPARATOR);

// Include WoobiPI
include WPI_PATH . 'WoobiPI.php';

// Load WoobiPI
WoobiPI::Instance()->Load();

// Configure WoobiPI
WoobiPI::Configure(array(
	WoobiPI::Config_Debug => false
));

// Handle the request
WoobiPI::HandleRequest();
