<?php
require_once(__DIR__.'/../lib/autoload.php');

require_once(__DIR__.'/../lib/app/ErrorHandler.php');
(new \lib\app\ErrorHandler) -> registerErrorHandler();

require_once(__DIR__.'/../lib/app/Maker.php');
(new \lib\app\Maker)->run();
