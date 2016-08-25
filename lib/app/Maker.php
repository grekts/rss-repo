<?php

namespace lib\app;

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'On');

class Maker
{
	public static $app;

	public $configData = '';	

	public function run() {
		//Сохраняем экземпляр объекта приложения
		self::$app = $this;
		//Формируем конфигурациооный файл и сохраняем его в переменной объекта приложения
		self::$app -> configData = $this -> formingConfig();
		//Опредееляем отображать ошибки все ошибки при отладке или только опредеелнные обраьотчиком ошибок
		self::$app -> determineShowErrors(self::$app -> configData['debug']);
		//подключаемся к базе данных
		self::$app -> DbConnect();
		//Перенаправление на нужное дейсвие контроллера
		self::$app -> routingToAction();
	}

	//Метод формирования конфигурациооного файла
	private function formingConfig() {
		//Сканируем дирректирю с пользовательскими файоами конфигурации
		$result = scandir('../config');
		//Если удалось просканировать дирректорию
		if($result !== false) {
			//Считаем сколько пришло ссылок на файлы 
			$filesNumber = count($result);
			//Если 3 ссылки ('.', '..' и файл)
			if($filesNumber === 3) {
				//Формируем адрес до пользовательского файла конфигурации
				$userConfigFilePuth = __DIR__.'/../../config/'.$result[2];
				//Еще раз проверяе есть ли такой файл
				if(file_exists($userConfigFilePuth)) {
					//Получаем данные из конфигурациооного файла пользователя
					$userConfigData = require_once(__DIR__.'/../../config/'.$result[2]);
					//Получаем данные из системного конфигурациооного файла
					$makerConfigData = require_once(__DIR__.'/config/makerConfig.php');
					//Получаем ключи (названия параметров) из пользователького файла конфигурации
					$userConfigKeys = array_keys($userConfigData);
					//Получаем ключи (названия параметров) из системного файла конфигурации
					$makerConfigKeys = array_keys($makerConfigData);
					//пробегаем все системные ключи (названия параметров) из системного файла конфигурации
					foreach ($makerConfigKeys as $makerConfigKey) {
						//Если параметр системного файла указан в пользовательском
						if(in_array($makerConfigKey, $userConfigKeys))
						{	
							//Сохраняем параметр и значения из пользовательского файла
							$config[$makerConfigKey] = $userConfigData[$makerConfigKey];
						} else { //Если параметра в пользовательском файле нет
							//Берем значения из системного конфигурациооного файла
							$config[$makerConfigKey] = $makerConfigData[$makerConfigKey];
						}
					}
					
					return $config;
				} else {
					trigger_error('Не найден конфигурациооный файл||0');
				}
			} else {
				trigger_error('В папке config находится более одного файла||0');
			}
		} else {
			trigger_error('Не найдена системная папка конфигурации||0');
		}
	}

	//Метод создания приложением нового обънкта модуля
	private function newObject($objectName) {
		$modulesDir = scandir(__DIR__);
		$fileName = $objectName.'.php';
		foreach ($modulesDir as $moduleFile) {
			if($fileName === $moduleFile) {
				$url = '\lib\app\\'.$objectName;
				return new $url;
			}
		}

		$modulesDir = scandir(__DIR__.'/modules');
		$fileName = $objectName.'.php';
		foreach ($modulesDir as $moduleFile) {
			if($fileName === $moduleFile) {
				$url = '\lib\app\modules\\'.$objectName;
				return new $url;
			}
		}

		$userControllersDir = scandir(__DIR__.'/../../controllers');
		foreach ($userControllersDir as $userController) {
			if($fileName === $userController) {
				$url = '\contrillers\\'.$objectName;
				return new $url;
			}
		}

		$userModelsDir = scandir(__DIR__.'/../../models');
		foreach ($userModelsDir as $userModel) {
			if($fileName === $userModel) {
				$url = '\models\\'.$objectName;
				return new $url;
			}
		}

		$modulesDir = scandir(__DIR__.'/../idna');
		$fileName = $objectName.'.php';
		//Флаг определяет нашел ли последний блок кода файл класса
		$flagCreateObject = 0;
		foreach ($modulesDir as $moduleFile) {
			if($fileName === $moduleFile) {
				$flagCreateObject = 1;
				$url = '\lib\idna\\'.$objectName;
				return new $url;
			}
		}

		//Если поиск файла класса во всех нужных папках не привело к успеху
		if($flagCreateObject === 0) {
			trigger_error('файл класса не найден');
		}
	}

	public function devideUrl($data = '') {
		$object = $this -> newObject('Converter');
	 	$result = $object -> devideUrl($data);
	 	$object = null;
	 	return $result;
	}

	public function encodingPuth($data = '') {
		$object = $this -> newObject('Converter');
	 	$result = $object ->encodingPuth($data);
	 	$object = null;
	 	return $result;
	}

	public function getFullLink($data = '') {
		$object = $this -> newObject('Converter');
	 	$result = $object ->getFullLink($data);
	 	$object = null;
	 	return $result;
	}

	public function devideByTag($data = '', $inputSepatator = '') {
		$outputSepatator = Maker::$app -> configData['separator'];
		$object = $this -> newObject('Converter');
	 	$result = $object -> devideByTag($data, $inputSepatator, $outputSepatator);
	 	$object = null;
	 	return $result;
	}

	public function deleteHtmlTags($data = '', $tagList = [])	{
		$object = $this -> newObject('Converter');
	 	$result = $object -> deleteHtmlTags($data, $tagList);
	 	$object = null;
	 	return $result;
	}

	public function convertExternalUrls($data = '', $cssClass = '')	{
		$object = $this -> newObject('Converter');
	 	$result = $object -> convertExternalUrls($data, $cssClass);
	 	$object = null;
	 	return $result;
	}

	public function synonymyLink() {
		$object = $this -> newObject('Converter');
	 	$result = $object -> synonymyLink();
	 	$object = null;
	 	return $result;
	}

	public function getControllerName($url = '') {
		$object = $this -> newObject('Converter');
	 	$result = $object -> getControllerName($url);
	 	$object = null;
	 	return $result;
	}

	public function  getActionName($url = '') {
		$object = $this -> newObject('Converter');
	 	$result = $object -> getActionName($url);
	 	$object = null;
	 	return $result;
	}

	public function  getViewPuth($url = '', $viewName = '') {
		$object = $this -> newObject('Converter');
	 	$result = $object -> getViewPuth($url, $viewName);
	 	$object = null;
	 	return $result;
	}

	private function DbConnect() {
		if(\lib\app\modules\DbWorker::$db === null) {
			\lib\app\modules\DbWorker::$db = Maker::$app -> newObject('DbWorker');;
			\lib\app\modules\DbWorker::$db->connect();
		}
	}

	public function query($query = '', $vars = []) {
		return \lib\app\modules\DbWorker::$db -> query($query, $vars);
	}

	private function determineShowErrors($flagShowErrors = '') {
		$object = $this -> newObject('ErrorHandler');
	 	$object -> determineShowErrors($flagShowErrors);
	 	$object = null;
	}

	public function error($message = '', $flagUserMessage = 0) {
		//Получаем данные о том в каком файле и на какой строке произошла ошибка
		$errorPlaceInfo = debug_backtrace();
		$fileName = $errorPlaceInfo[0]['file'];
		$lineNumber = $errorPlaceInfo[0]['line'];

		$object = $this -> newObject('ErrorHandler');
	 	$object ->  error($message, $fileName, $lineNumber, $flagUserMessage);
	 	$object = null;
	}

	public function filter($data = '') {
		$object = $this -> newObject('Filter');
	 	$result = $object -> filter($data);
	 	$object = null;
	 	return $result;
	}

	public function encodingDomain($data = '') {
		$object = $this -> newObject('idna_convert');
	 	$result = $object ->encode($data);
	 	$object = null;
	 	return $result;
	}

	public function render($viewName = '', $vars = [])	{
		$object = $this -> newObject('PageGenerator');
	 	$result = $object -> render($viewName, $vars);
	 	$object = null;
	 	return $result;
	}

	public function tagRegistration($tags = [])	{
		$object = $this -> newObject('PageGenerator');
	 	$object -> tagRegistration($tags);
	 	$object = null;
	}

	public function parseRss($data = '') {
		$object = $this -> newObject('Parser');
	 	$result = $object -> parseRss($data);
	 	$object = null;
	 	return $result;
	}

	public function routingToView($viewName = '', $vars = []) {
		$object = $this -> newObject('Router');
		$object -> routingToView($viewName, $vars);
		$object = null;
	}

	private function routingToAction() {
		$object = $this -> newObject('Router');
		$object -> routingToAction();
		$object = null;
	}

	public function checkUrlFormat($data = '') {
		$object = $this -> newObject('Validator');
		$result = $object -> checkUrlFormat($data);
		$object = null;
		return $result;
	}

	public function checkControllerActionName($data = '') {
		$object = $this -> newObject('Validator');
		$result = $object -> checkControllerActionName($data);
		$object = null;
		return $result;
	}

}