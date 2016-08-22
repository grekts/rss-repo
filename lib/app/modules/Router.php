<?php

namespace lib\app\modules;

use lib\app\Maker;

class Router {

	//Метод перенаправления на действие в контроллере
	public function routingToAction() {
		//Если был ввод ссылки
		if(($_SERVER['REQUEST_URI']) && ($_SERVER['REQUEST_URI'] !== '/favicon.ico')) {
			//Проверяем наличие синонима у ссылки и если есть, то конвертируем запоршенную ссылку в синоним
			$url = Maker::$app -> synonymyLink();
			//Получаем  из ссылки имя контроллера и вызываемого действия
			$controllerName = Maker::$app -> getControllerName($url);
			$actionName = Maker::$app -> getActionName($url);
			if(file_exists('../controllers/'.$controllerName.'.php')) {
				$contorllerPuth = '\controllers\\'.$controllerName;
				$contorllerPuth::$actionName();
			} else {
				trigger_error('Запрашиваемый файл контроллера не найден||0'.$url.'|'.$controllerName);
			}
		}
	}

	//Метод подключения вида
	public function routingToView($viewName, $vars) {
		$dataType1 = gettype($viewName);
		$dataType2 = gettype($vars);
		if(($dataType1 === 'string') && ($dataType2 === 'array') && ($viewName !== '') && ($vars !== [])) {
			//Формируем переменные для вывода данных на странице
			$varsNames = array_keys($vars);
			foreach ($varsNames as $varName) {
				$$varName = $vars[$varName];
			}

			//Проверяем наличие синонима у ссылки и если есть, то конвертируем запоршенную ссылку в синоним
			$url = Maker::$app -> synonymyLink();

			//Формируем ссылку на запрашиваемый файл вида
			$linkToView = Maker::$app -> getViewPuth($url, $viewName);
			return require_once($linkToView);
		} else {
			trigger_error('Имя вида и параметры не указаны или имеют неверный тип||0');
		}
	}

}