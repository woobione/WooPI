WoobiPI (WPI)
==================

Version 1.0
-----------
WoobiPI (WPI) is a super lightweight API controller framework for PHP

About
-----------
WoobiPI is all about simplicity. It allows you to only focus on your controllers while WoobiPI does the rest for you. Not only is it very configurable but also super lightweight.

WoobiPI comes with a default configuration suited for most people. It then allows you to override almost everything at almost any place.

Configuration
-----------
Configuration in WoobiPI is as easy as eating a tasty slice of pie. You could say that the whole point with WoobiPI is configurability. You can override and append to the config from any place in the project; In the index.php file (global configuration), in your controller's $Configuration array (controller specific) or even in the controller's $ActionConfiguration array (for only a specific action). Se example below:

#### Handling exceptions ####
WoobiPI handles exceptions in a unique and nice way. As a matter of fact, the Result is responsible for handling the exceptions. As always with WoobiPI, you can configure the exception handling on many different levels.

- For the whole project
- For a specific controller
- Or for a specific action

To allow the JsonResult to handle all your exceptions, write this inside your index.php file:

```
WoobiPI::Configure(array(
    WoobiPI::Config_ExceptionMode => 'Json'
));
```

The default ExceptionMode is WPI which handles you exceptions like php normally would.

That might be just what you want in, let's say, a delete action. But let's say you decide that your PersonsController::Post should return a json formatted error to let your application know that the person was NOT created. Then just write this in you PersonsController __construct method:

```
$this->ActionConfiguration = array(
    'Post' => array(
        WoobiPI::Config_ExceptionMode => 'Json'
    )
);
```



Notes
-----------
This product is free to use for everyone and comes as is. Please write me if you like it and recommend improvements.

This guide is a very good read for how it is supposed to function: http://www.vinaysahni.com/best-practices-for-a-pragmatic-restful-api
