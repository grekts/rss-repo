<?php
// set level error loging
error_reporting(E_ALL | E_STRICT);
// set mode error out
ini_set('display_errors', 'Off');

//include configuratin file
require_once('sys-config.php');
//include class for autoload other classes
require_once(MODEL_PATH.'AutoloderClass.php');
spl_autoload_register(array('application\models\AutoloderClass', 'actionAutoloadClassFile'));

//include file with method for error handing
require_once(MODEL_PATH.'ErrorHandlerClass.php');
set_error_handler(array('application\models\ErrorHandlerClass','processingError'));

use application\models\InternalServiceUrlClass as internalUrlNamespace;
use application\models\RouterClass as routerNamespace;

//instance object internal links
$internalServiceUrl = new internalUrlNamespace;
//method for determine number request url
$internalServiceUrl->formationNumberRequestedUrl();

//instance object redirect to nedd file
$router = new routerNamespace;
//method redirect to need file
$router->redirect($internalServiceUrl);

