<?php

return array(
	WooPI::Config_Debug								=> false,
	WooPI::Config_PluginPath						=> 'Plugins/',
	WooPI::Config_RequestHandler					=> 'RequestHandler',
	WooPI::Config_ResultHandler						=> 'ResultHandler',
	WooPI::Config_ExceptionMode						=> 'WPI',
	WooPI::Config_CurrentApiVersion					=> '1.0',
	WooPI::Config_AvailableApiVersions				=> array(
		'1.0'
	),
	
	RequestHandler::Config_RequestPartSeparator			=> '/',
	RequestHandler::Config_DefaultController			=> 'Home',
	RequestHandler::Config_ControllerSuffix				=> 'Controller',
	RequestHandler::Config_ControllerPath				=> 'Controllers/',
	RequestHandler::Config_AllowHttp					=> false,
	RequestHandler::Config_AllowRequestTypes			=> 'get,post,put,delete',
	
	ResultHandler::Config_DefaultHeaders				=> array(
		'User-agent: WooPI API ' . WOOPI_VERSION
	)
);