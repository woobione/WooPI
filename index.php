<?php

// Set include path
set_include_path(dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('WPI_PATH', 'WPI' . DIRECTORY_SEPARATOR);

// Include WoobiPI
include WPI_PATH . 'WooPI.php';

// Load WoobiPI
WooPI::Instance()->Load();

// Configure WoobiPI
WooPI::Configure(include('config.php'));

// Handle the request
WooPI::HandleRequest();
