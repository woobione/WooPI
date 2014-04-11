<?php

return array(
	WoobiPI::Config_Debug								=> false,
	WoobiPI::Config_PluginPath							=> 'Plugins/',
	WoobiPI::Config_RequestHandler						=> 'RequestHandler',
	WoobiPI::Config_ResultHandler						=> 'ResultHandler',
	
	RequestHandler::Config_AllowGet						=> false,
	RequestHandler::Config_ControllerPath				=> 'Controllers/',
	RequestHandler::Config_DefaultController			=> 'Home',
	RequestHandler::Config_DefaultAction				=> 'Index'
);