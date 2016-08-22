<?php

require_once('app/Autoloader.php');
spl_autoload_register(['lib\app\Autoloader' , 'actionAutoload']);