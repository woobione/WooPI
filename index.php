<?php

// Set include path
set_include_path(dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('WPI_PATH', 'WPI' . DIRECTORY_SEPARATOR);

// Include WooPI
include WPI_PATH . 'WooPI.php';

// Load WooPI
WooPI::Instance()->Load();

// Configure WooPI
WooPI::Configure(include('config.php'));

// Handle the request
WooPI::HandleRequest();
