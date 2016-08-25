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
            $existFileWithUrl = file_exists(__DIR__.'/../../'.$classNameWithReplaceSlash.'.php');
            if($existFileWithUrl) {
                require_once(__DIR__.'/../../'.$classNameWithReplaceSlash.'.php'); 
            } else {
                Maker::$app -> error('В методе '.__METHOD__.' указан не существующий файл класса');
            }
            clearstatcache();
        } else {
            if($dataType !== 'string') {
                Maker::$app -> error('В методе '.__METHOD__.' тип данных входного параметра не соответствует типу string');
            }
            if($className === '') {
                Maker::$app -> error('В методе '.__METHOD__.' не указаны данные во входном параметре');
            }
        }
    }
}