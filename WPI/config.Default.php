<?php

return array(
	WoobiPI::Config_Debug								=> false,
	WoobiPI::Config_PluginPath							=> 'Plugins/',
	WoobiPI::Config_RequestHandler						=> 'RequestHandler',
	WoobiPI::Config_ResultHandler						=> 'ResultHandler',
	
	RequestHandler::Config_RequestPartSeparator			=> '/',
	RequestHandler::Config_DefaultController			=> 'Home',
	RequestHandler::Config_ControllerSuffix				=> 'Controller',
	RequestHandler::Config_ControllerPath				=> 'Controllers/',
	RequestHandler::Config_AllowRequestTypes			=> 'get,post,put,patch,delete'
);