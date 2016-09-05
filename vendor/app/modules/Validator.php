<?php

namespace liw\vendor\app\modules;

/**
 * Класс валидации данных
 * Класс предоставляет следующий функционал:
 * - валидацию ссылок
 * - валидацию имен классов и методов
 * 
 * @author Roman Tsutskov
 */
class Validator
{
	/**
	 * Метод проверки входных данных на то, что они являются ссылкой на сраницу
	 * 
	 * @param string $url Ссылка, подлежащая проверке
	 * @return integer 1 - в случае, если проверяемая строка является ссылкой, 0 - если проверяемая строка не является ссылкой
	 */
	public function checkUrlFormat($url) {
		$type = gettype($url);
    	if(($type === 'string') && ($url !== '')) {
    		$pregMatchResult = preg_match('/[\/]*[http\:\/\/]*[https\:\/\/]*[www\.]*[a-zа-я0-9\-]+\.[a-zа-я0-9\-\?\#\=\.\/]+\.*[a-zа-я0-9\-\?\#\=\.\/]*\.*[a-zа-я0-9\-\?\#\=\.\/]*\.*[a-zа-я0-9\-\?\#\=\.\/]*\.*[a-zа-я0-9\-\?\#\=\.\/]*|\//isU' , $url);
    		//Если пришедшая строка является ссылкой
			if(($pregMatchResult !== 0) && ($pregMatchResult !== false)) {
				//Ищем в строке расшиерния файлов, не относящихся к странице
				$pregMatchResult = preg_match('/\.jpg|.\.jpeg|\.png|\.gif|\.xls|\.xlsx|\.doc|\.docx|\.pdf/isU' , $url); 
				//Если расширений не нашли
        		if(($pregMatchResult === 0) && ($pregMatchResult !== false)) {
        			unset($url, $type, $pregMatchResult);
        			//Значит пришедшая строка является ссылкой на страницу
			        return '1';
        		} else { //Если произошла ошибка или нашли расширени яфайлов
        			//Если была ошибка
          			if($pregMatchResult === false) {
          				\liw\vendor\app\Maker::$app -> error('Ссылка указывает на файл, а не на страницу', 1);
          			} elseif ($pregMatchResult !== 0) { //Если нашли расширение файла
          				unset($url, $type, $pregMatchResult);

			        	return '0';
			        }
        		}
    		} else { //Если про поиске ссылки произошла ошибка
    			//Если произошла ошибка
		        if($pregMatchResult === false) {
		        	\liw\vendor\app\Maker::$app -> error('произошда ошибка при проверке ссылки', 1);
		        } elseif ($pregMatchResult === 0) { //Если просто не нашли ссылку
		        	unset($url, $type, $pregMatchResult);
		        	
		        	return '0';
		        }
    		}
	    } else {
	    	if($type !== 'string') {
	    		\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' тип данных входного параметра не соответствует типу string');
			}
			if($url === '') {
				\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' не указаны данные во входном параметре');
			}
	    }
	}

	/**
	 * Метод проверки соответствия имени класса и метода установленным парвилам именования
	 * 
	 * @param string $name Имя класа или метода
	 * @return integer 1 - в случае, если проверяемое имя класса или метода соответствуют требованиям
	 */
	public function checkControllerActionName($name) {
		$type = gettype($name);
    	if(($type === 'string') && ($name !== '')) {
    		$pregMatchName = preg_match('/[a-z\-]*/isU' , $name);
    		if(($pregMatchName !== 0) && ($pregMatchName !== false)) {
		    	return '1';
		    } else {
		    	if($pregMatchControllerName === 0) {
		    		\liw\vendor\app\Maker::$app -> error('Имя контроллера не соответствует требованиям фреймворка');
		    	}
		    	if($pregMatchControllerName === false) {
		    		\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' произошла техническая ошибка');
		    	}
		    }
    	} else {
    		if($dataType !== 'string') {
    			\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' тип данных входного параметра не соответствует типу string');
			}
			if($name === '') {
				\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' не указаны данные во входном параметре');
			}
    	}
	}
}