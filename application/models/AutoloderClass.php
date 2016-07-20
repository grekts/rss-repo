<?php

namespace application\models;

class AutoloderClass
{
  //method conect files with class
  public static function actionAutoloadClassFile($className)
  {
    $classNameWithReplaceSlash = str_replace('\\', '/', $className);
    $existFileWithUrl = file_exists($classNameWithReplaceSlash.'.php');
    if($existFileWithUrl === true){
      require_once($classNameWithReplaceSlash.'.php'); 
    } else {
      echo 'error';
    }
    
    unset($className, $classNameWithReplaceSlash, $existFileWithUrl);
  }
}

