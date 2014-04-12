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
	// Put your configuration here
	// HINT: You can configure almost everything
));

// Handle the request
WoobiPI::HandleRequest();
