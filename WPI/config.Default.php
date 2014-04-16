<?php

return array(
	WoobiPI::Config_Debug								=> false,
	WoobiPI::Config_PluginPath							=> 'Plugins/',
	WoobiPI::Config_RequestHandler						=> 'RequestHandler',
	WoobiPI::Config_ResultHandler						=> 'ResultHandler',
	WoobiPI::Config_ExceptionMode						=> 'WPI',
	WoobiPI::Config_CurrentApiVersion					=> '1.0',
	WoobiPI::Config_AvailableApiVersions				=> array(
		'1.0'
	),
	
	RequestHandler::Config_RequestPartSeparator			=> '/',
	RequestHandler::Config_DefaultController			=> 'Home',
	RequestHandler::Config_ControllerSuffix				=> 'Controller',
	RequestHandler::Config_ControllerPath				=> 'Controllers/',
	RequestHandler::Config_AllowHttp					=> false,
	RequestHandler::Config_AllowRequestTypes			=> 'get,post,put,patch,delete',
	
	ResultHandler::Config_DefaultHeaders				=> array(
		'User-agent: WoobiPI API ' . WOOBIPI_VERSION
	)
);