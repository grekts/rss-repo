<?php
require_once('../lib/autoload.php');

require_once('../lib/app/ErrorHandler.php');
(new \lib\app\ErrorHandler) -> registerErrorHandler();

require_once('../lib/app/Maker.php');
(new \lib\app\Maker)->run();
