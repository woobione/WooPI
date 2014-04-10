<?php

return array(
	WoobiPI::Config_Debug								=> false,
	
	RequestHandler::Config_AllowGet						=> false,
	RequestHandler::Config_ControllerPath				=> 'Controllers/',
	RequestHandler::Config_DefaultController			=> 'Home',
	RequestHandler::Config_DefaultAction				=> 'Index',
	
	ResultHandler::Config_PluginPath					=> 'Plugins/'
);