<?php
// require_once(__DIR__.'/../lib/autoload.php');

require_once(__DIR__.'/../vendor/autoload.php');

require_once(__DIR__.'/../vendor/app/ErrorHandler.php');
(new liw\vendor\app\ErrorHandler) -> registerErrorHandler();

require_once(__DIR__.'/../vendor/app/Maker.php');
(new liw\vendor\app\Maker)->run();
