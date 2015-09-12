WooPI (WPI)
==================

Version 1.2
-----------
WooPI (WPI) is a super lightweight API controller framework for PHP

About
-----------
WooPI is all about simplicity. It allows you to only focus on your controllers while WooPI does the rest for you. Not only is it very configurable but also super lightweight.

WooPI comes with a default configuration suited for most people. It then allows you to override almost everything at almost any place.

Configuration
-----------
Configuration in WooPI is as easy as eating a tasty slice of pie. You could say that the whole point with WooPI is configurability. You can override and append to the config from any place in the project; In the config.php file (global configuration), in your controller's $Configuration array (controller specific) or even in the controller's $ActionConfiguration array (for only a specific action). Se example below:

#### Global configuration ####
Inside your config.php

```php
return array(
	ConfigurableObject::Config_OptionName => mixed {, .. more}
);
```

#### Controller configuration ####
Inside your controller's __construct method

```php
$this->Configuration = array(
	ConfigurableObject::Config_OptionName => mixed {, .. more}
);
```

#### Action configuration ####
Inside your controller's __construct method

```php
$this->ActionConfiguration = array(
	'ActionName' => array(
		ConfigurableObject::Config_OptionName => mixed {, .. more}
	) {, .. more}
);
```

#### Available configuration options ####
Configuration is done with the following syntax

```php
ConfigurableObject::Config_OptionName => valueType|defaultValue|{can be overriden in global|controller|action} // Comment
```

##### WooPI #####
```php
WooPI::Config_Debug => bool|false|{global} // Should WooPI be in debug mode
WooPI::Config_PluginPath => string|'Plugins/'|{global} // Path to plugins
WooPI::Config_RequestHandler => string|'RequestHandler'|{global} // Name of request handler class (allows you to override the requesthandler if you want more functionality)
WooPI::Config_ResultHandler => string|'ResultHandler'|{global} // Name of the result handle class (allows you to override the resulthandler if you want more functionality)
WooPI::Config_ExceptionMode => string|'WPI'{global|controller|action} // How exceptions are handled (name of result type) i.e. 'Json' would handle exceptions using Json
WooPI::Config_CurrentApiVersion => string|'1.0'|{global} // Your current API version (if no version is specified in the url - this will be used)
WooPI::Config_AvailableApiVersions => array|['1.0']|{global} // Available versions of your API (put named folders in the Controllers folder with names excactly like in this array)
```

##### Request Handler #####
```php
RequestHandler::Config_RequestPartSeparator => string|'/'|{global} // How to split the request string (default is /(Version)/(Controller)/(Action)/(.. more parameters separated by value specified here))
RequestHandler::Config_DefaultController => string|'Home'|{global} // Default controller if none is specified
RequestHandler::Config_ControllerSuffix => string|'Controller'|{global} // Controller class name suffix
RequestHandler::Config_ControllerPath => string|'Controllers/'|{global} // Path to controllers
RequestHandler::Config_AllowHttp => bool|false|{global|controller|action} // Allow connections to the API without https
RequestHandler::Config_AllowRequestTypes => string|'get,post,put,patch,delete'|{global|controller|action} // Comma separated request types
```

##### Result Handler #####
```php
ResultHandler::Config_DefaultHeaders => array|['User-agent: WooPI API ' . WOOBIPI_VERSION]|{global|controller|action} // Default headers to set when responding
```

Notes
-----------
This product is free to use for everyone and comes as is. Please write me if you like it and recommend improvements.

This guide is a very good read for how it is supposed to function: http://www.vinaysahni.com/best-practices-for-a-pragmatic-restful-api
