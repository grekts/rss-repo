<?php

namespace liw\vendor\app;

/**
 * Класс приложения.
 * Данный класс является основным классом приложения.
 * В нем каходится метод запуска приложеня, а так же методы, 
 * отвечающие за запуск функционала модулей приложения
 * 
 * @author Roman Tsutskov
 */
class Maker
{
	/**
	 * Хранилище экземпляра объекта прилдожения
	 * Применняется при построении и вызове комманд приложения
	 * 
	 * @var object
	*/
	public static $app;

	/**
	 * Массив с конфигурационными данными приложения
	 * Содержи массив с параметрами и свойстами, применяемыми при работе приложения.
	 * Формируется пут сравнения пользовательского конфигурационного файла и конфигурационного файла приложения
	 * 
	 * @var array|string
	*/
	public $configData = '';	

	/**
	 * Метод запуска приложения
	 */
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

	/**
	 * Метод формирования конфигурациооного файла
	 * 
	 * @return array Именованнывй массив с параметрами сформированного конфигурационного файла
	 */
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

	/**
	 * Метод создания объекта элемента
	 * 
	 * @param string $objectName Имя модуля, экземпляр объекта которого необходимо создать
	 * @return object Экземпляр объекта
	 */
	private function newObject($objectName) {
		$modulesDir = scandir(__DIR__);
		$fileName = $objectName.'.php';
		foreach ($modulesDir as $moduleFile) {
			if($fileName === $moduleFile) {
				$url = '\liw\vendor\app\\'.$objectName;
				return new $url();
			}
		}

		$modulesDir = scandir(__DIR__.'/modules');
		$fileName = $objectName.'.php';
		foreach ($modulesDir as $moduleFile) {
			if($fileName === $moduleFile) {
				$url = '\liw\vendor\app\modules\\'.$objectName;
				return new $url();
			}
		}

		$userControllersDir = scandir(__DIR__.'/../../controllers');
		foreach ($userControllersDir as $userController) {
			if($fileName === $userController) {
				$url = 'controllers\\'.$objectName;
				return new $url();
			}
		}

		$userModelsDir = scandir(__DIR__.'/../../models');
		foreach ($userModelsDir as $userModel) {
			if($fileName === $userModel) {
				$url = 'models\\'.$objectName;
				return new $url();
			}
		}

		$modulesDir = scandir(__DIR__.'/../mso/idna-convert/src');
		$fileName = $objectName.'.php';
		//Флаг определяет нашел ли последний блок кода файл класса
		$flagCreateObject = 0;
		foreach ($modulesDir as $moduleFile) {
			if($fileName === $moduleFile) {
				$flagCreateObject = 1;
				$url = 'Mso\IdnaConvert\\'.$objectName;
				return new $url();
			}
		}

		//Если поиск файла класса во всех нужных папках не привело к успеху
		if($flagCreateObject === 0) {
			trigger_error('файл класса не найден');
		}
	}

	/**
	 * Метод создания экземпляра объекта Converter, и вызова метода разделения ссылки на домен и путь
	 * 
	 * @param string $data Часть ссылки, где указан контроллер и имя действия (получается после проверки наличия ссылки-синонима)
	 * @return array Ассоциированный массив, где по ключу 'domain' доступно имя домена из ссылки, 'path' - путь из ссылки
	 */
	public function devideUrl($data = '') {
		$object = $this -> newObject('Converter');
	 	$result = $object -> devideUrl($data);
	 	$object = null;
	 	return $result;
	}

	/**
	 * Метод создания экземпляра объекта Converter, и вызова метода кодирования пути, в котором находятся кирилические символы
	 * 
	 * @param string $data Часть ссылки, где указан контроллер и имя действия (получается после проверки наличия ссылки-синонима)
	 * @return string Кодированная ссылка
	 */
	public function encodingPuth($data = '') {
		$object = $this -> newObject('Converter');
	 	$result = $object ->encodingPuth($data);
	 	$object = null;
	 	return $result;
	}

	/**
	 * Метод создания экземпляра объекта Converter, и вызова метода получения ссылки с учетом протокола передачи данных и www. Может использоваться для проверки работоспособности ссылок.
	 * 
	 * @param string $data Часть ссылки, где указан контроллер и имя действия (получается после проверки наличия ссылки-синонима)
	 * @return string Работающая ссылка с протоколом передачи данных и, при необходимости, www
	 */
	public function getFullLink($data = '') {
		$object = $this -> newObject('Converter');
	 	$result = $object ->getFullLink($data);
	 	$object = null;
	 	return $result;
	}

	/**
	 * Метод создания экземпляра объекта Converter, и вызова метода разделения текста по указанному html тегу
	 * 
	 * @param string $data текст, подлежащий обработке
	 * @param string $inputSepatator Html тег, по которому будет делиться текст
	 * @return string Разделенный текст, в местах разделения которого стоят символы-разделители
	 */
	public function devideByTag($data = '', $inputSepatator = '') {
		$outputSepatator = Maker::$app -> configData['separator'];
		$object = $this -> newObject('Converter');
	 	$result = $object -> devideByTag($data, $inputSepatator, $outputSepatator);
	 	$object = null;
	 	return $result;
	}

	/**
	 * Метод создания экземпляра объекта Converter, и вызова метода удаления html тегов из указанной строки
	 * 
	 * @param string $data текст, подлежащий обработке
	 * @param array $tagList Массив тегов, подлежащих удалению.  Если массив не указан, удаляются все теги
	 * @return string Текст с удаленными тегами
	 */
	public function deleteHtmlTags($data = '', $tagList = [])	{
		$object = $this -> newObject('Converter');
	 	$result = $object -> deleteHtmlTags($data, $tagList);
	 	$object = null;
	 	return $result;
	}

	/**
	 * Метод создания экземпляра объекта Converter, и вызова метода добавления ко внешним ссылкам свойств rel=”nofollow” и target=”_blanck” и css класс
	 * 
	 * @param string $data Текст, ссылки в котором будут обрабатываться
	 * @param array $cssClass Имя CSS свойства, которое будет применено к ссылке
	 * @return string Текст с замененными ссылками
	 */
	public function convertExternalUrls($data = '', $cssClass = '')	{
		$object = $this -> newObject('Converter');
	 	$result = $object -> convertExternalUrls($data, $cssClass);
	 	$object = null;
	 	return $result;
	}

	/**
	 * Метод создания экземпляра объекта Converter, и вызова метода определения имеются ли в конфигурационном файле ссылки-синонимы и если есть, возвращает нужную ссылку.
	 * Если нет, возвращает исходную сслку
	 * 
	 * @return string Путь, исходя из которого будут подключаться пользовательские контроллеры и их действия
	 */
	public function synonymyLink() {
		$object = $this -> newObject('Converter');
	 	$result = $object -> synonymyLink();
	 	$object = null;
	 	return $result;
	}

	/**
	 * Метод создания экземпляра объекта Converter, и вызова метода формирования имени контроллера, исходя из ссылки
	 * 
	 * @param string $url Часть ссылки, где указан контроллер и имя действия (получается после проверки наличия ссылки-синонима)
	 * @return string Имя запрашиваемого контроллера
	 */
	public function getControllerName($url = '') {
		$object = $this -> newObject('Converter');
	 	$result = $object -> getControllerName($url);
	 	$object = null;
	 	return $result;
	}

	/**
	 * Метод создания экземпляра объекта Converter, и вызова метода формирования имени действия в контроллере, исходя из ссылки
	 * 
	 * @param string $url Часть ссылки, где указан контроллер и имя действия (получается после проверки наличия ссылки-синонима)
	 * @return string Имя запрашиваемого действия
	 */
	public function getActionName($url = '') {
		$object = $this -> newObject('Converter');
	 	$result = $object -> getActionName($url);
	 	$object = null;
	 	return $result;
	}

	/**
	 * Метод создания экземпляра объекта Converter, и вызова метода формирования имени подключаемого виджета
	 * 
	 * @param string $url Часть ссылки, где указан контроллер и имя действия (получается после проверки наличия ссылки-синонима)
	 * @return string Имя запрашиваемого виджета
	 */
	public function getWidgetName($url = '') {
		$object = $this -> newObject('Converter');
	 	$result = $object -> getWidgetName($url);
	 	$object = null;
	 	return $result;
	}

	/**
	 * Метод создания экземпляра объекта Converter, и вызова метода формирования ссылки на файл вида
	 * 
	 * @param string $url Часть ссылки, где указан контроллер и имя действия (получается после проверки наличия ссылки-синонима)
	 * @param string $viewName Имя вида, ссылка на который будет формироваться
	 * @return string ссылка на файл запрашиваемого вида
	 */
	public function getViewPuth($url = '', $viewName = '') {
		$object = $this -> newObject('Converter');
	 	$result = $object -> getViewPuth($url, $viewName);
	 	$object = null;
	 	return $result;
	}

	/**
	 * Метод создания экземпляра объекта DbWorker, и вызова метода подключения к базе данных
	 */
	private function DbConnect() {
		if(\liw\vendor\app\modules\DbWorker::$db === null) {
			\liw\vendor\app\modules\DbWorker::$db = \liw\vendor\app\Maker::$app -> newObject('DbWorker');;
			\liw\vendor\app\modules\DbWorker::$db->connect();
		}
	}

	/**
	 * Метод вызова метода формирования и отправки запроса к БД
	 * 
	 * @param string $query Строка с запросом к базе данных
	 * @param array $vars Массив с параметрами запроса к базе данных
	 * @return array В случае SELECT запроса, именованный масссив с данными, полученными из базы данных
	 */
	public function query($query = '', $vars = []) {
		return \liw\vendor\app\modules\DbWorker::$db -> query($query, $vars);
	}

	/**
	 * Метод создания экземпляра объекта ErrorHandler, и вызова метода установки показывать ошибки или нет (зависит от настроек)
	 * 
	 * @param string $flagShowErrors Флаг из конфигурационного файла, указывающий запущен режим дебага или нет
	 */
	private function determineShowErrors($flagShowErrors = '') {
		$object = $this -> newObject('ErrorHandler');
	 	$object -> determineShowErrors($flagShowErrors);
	 	$object = null;
	}

	/**
	 * Метод создания экземпляра объекта ErrorHandler, и вызова метода формирования сообщения об ошибке
	 * 
	 * @param string $message Сообщение ошибки
	 * @param integer $flagUserMessage Флаг, определяющий нужно ли показывать пользователю данное сообщение об ошибке
	 */
	public function error($message = '', $flagUserMessage = 0) {
		//Получаем данные о том в каком файле и на какой строке произошла ошибка
		$errorPlaceInfo = debug_backtrace();
		$fileName = $errorPlaceInfo[0]['file'];
		$lineNumber = $errorPlaceInfo[0]['line'];

		$object = $this -> newObject('ErrorHandler');
	 	$object ->  error($message, $fileName, $lineNumber, $flagUserMessage);
	 	$object = null;
	}

	/**
	 * Метод создания экземпляра объекта Filter, и вызова метода фильтрования пришедших от пользователя текстовые данные на 
	 * наличие спец. символов, html тегов и пробелов в начале и конце пришедшей строки данных
	 * 
	 * @param string $data Пришедшие от пользователя данные
	 * @return string Проверенный текст
	 */
	public function filter($data = '') {
		$object = $this -> newObject('Filter');
	 	$result = $object -> filter($data);
	 	$object = null;
	 	return $result;
	}

	/**
	 * Метод создания экземпляра объекта IdnaConvert, и вызова метода конвертирования кирилического домена
	 * 
	 * @param string $data Домен
	 * @return string Кодированный домен
	 */
	public function encodingDomain($data = '') {
		$object = $this -> newObject('IdnaConvert');
	 	$result = $object ->encode($data);
	 	$object = null;
	 	return $result;
	}

	/**
	 * Метод создания экземпляра объекта PageGenerator, и вызова метода рендера страницы сайта
	 * 
	 * @param string $viewName Имя запрашиваемого файла вида
	 * @param array $vars Данные, полученные из базы данных для вывода на странице
	 */
	public function render($viewName = '', $vars = [])	{
		$object = $this -> newObject('PageGenerator');
	 	$result = $object -> render($viewName, $vars);
	 	$object = null;
	 	return $result;
	}

	/**
	 * Метод создания экземпляра объекта Registrator, и вызова метода регистрации html тегов между head, тега html и script
	 * 
	 * @param array $tags Список тегов, кода которых нужно сформировать
	 */
	public function tagRegistration($tags = [])	{
		$object = $this -> newObject('Registrator');
	 	$object -> tagRegistration($tags);
	 	$object = null;
	}

	/**
	 * Метод создания экземпляра объекта Registrator, и вызова метода регистрации виджетов, которые будут использваться на странице
	 * 
	 * @param array $tags Список виджетов с их параметрами, кода которых нужно сформировать
	 */
	public function widgetRegistration($tags = [])	{
		$object = $this -> newObject('Registrator');
	 	$object -> widgetRegistration($tags);
	 	$object = null;
	}

	/**
	 * Метод создания экземпляра объекта Parser, и вызова метода парсинга RSS фида
	 * 
	 * @param string $data Ссылка на фид, подлежащий парсингу
	 * @return array Именованный массив с частями кода rss фида
	 */
	public function parseRss($data = '') {
		$object = $this -> newObject('Parser');
	 	$result = $object -> parseRss($data);
	 	$object = null;
	 	return $result;
	}

	/**
	 * Метод создания экземпляра объекта Router, и вызова метода перенаправления на запрошенный файл вида страницы
	 * 
	 * @param string $viewName Имя запрашиваемого файла вида
	 * @param array $vars Массив с данными для формирования переменных, содержащих контент(кода виджетов, вывода текстовых данных), подлежащий выводу на странице
	 * @return string HTML код вида
	 */
	public function routingToView($viewName = '', $vars = []) {
		$object = $this -> newObject('Router');
		$result = $object -> routingToView($viewName, $vars);
		$object = null;
		return $result;
	}

	/**
	 * Метод создания экземпляра объекта Router, и вызова метода перенаправления поступившего запроса на запрошенное действие запрошенного контроллера
	 */
	private function routingToAction() {
		$object = $this -> newObject('Router');
		$object -> routingToAction();
		$object = null;
	}

	/**
	 * Метод создания экземпляра объекта Validator, и вызова метода проверки входных данных на то, что они являются ссылкой на сраницу
	 * 
	 * @param string $data Ссылка, подлежащая проверке
	 * @return integer 1 - в случае, если проверяемая строка является ссылкой, 0 - если проверяемая строка не является ссылкой
	 */
	public function checkUrlFormat($data = '') {
		$object = $this -> newObject('Validator');
		$result = $object -> checkUrlFormat($data);
		$object = null;
		return $result;
	}

	/**
	 * Метод создания экземпляра объекта Validator, и вызова метода проверки соответствия имени класса и метода установленным парвилам именования
	 * 
	 * @param string $data Имя класа или метода
	 * @return integer 1 - в случае, если проверяемое имя класса или метода соответствуют требованиям
	 */
	public function checkControllerActionName($data = '') {
		$object = $this -> newObject('Validator');
		$result = $object -> checkControllerActionName($data);
		$object = null;
		return $result;
	}

}