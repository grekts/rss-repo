<?php

namespace lib\app;

class Autoloader
{
    //Метод загрузки классов
    public static function actionAutoload($className)
    {
        $dataType = gettype($className);
        if(($dataType == 'string') && ($className !== '')) {
            $classNameWithReplaceSlash = str_replace('\\', '/', $className);
            $existFileWithUrl = file_exists('../'.$classNameWithReplaceSlash.'.php');
            if($existFileWithUrl) {
                require_once('../'.$classNameWithReplaceSlash.'.php'); 
            } else {
                trigger_error('Файла класса не существует||0');
            }
            clearstatcache();
        } else {
            trigger_error('Пришедшие данные не соответствуют требуемому типу или пустые||0');
        }
    }
}