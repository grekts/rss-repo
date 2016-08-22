<?php

namespace lib\app\modules;

class Validator
{
	//Метод проверки входных данных на то, что они являются ссылкой на сраницу
	public function checkUrlFormat($url) {
		$type = gettype($url);
    	if(($type === 'string') && ($url != '')) {
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
            			trigger_error('Ссылка указывает на файл а не на страницу');
          			} elseif ($pregMatchResult !== 0) { //Если нашли расширение файла
          				unset($url, $type, $pregMatchResult);

			        	return '0';
			        }
        		}
    		} else { //Если про поиске ссылки произошла ошибка
    			//Если произошла ошибка
		        if($pregMatchResult === false) {
		        	trigger_error('Указанные данные не являютя ссылкой');
		        } elseif ($pregMatchResult === 0) { //Если просто не нашли ссылку
		        	unset($url, $type, $pregMatchResult);
		        	
		        	return '0';
		        }
    		}
	    } else {
	    	trigger_error('Неверно указаны входные данные||0');
	    }
	}
}