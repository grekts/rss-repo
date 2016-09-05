<?php

namespace liw\vendor\app\modules;

/**
 * Класс перенаправления запроса, указанного в ссылке
 * Класс предоставляет следующий функционал:
 * - перенаправление запроса на требуемое действие требуемого контроллера с сего последующим вызовом
 * - перенаправление запроса для подключение требуемого вида и получение его кода
 * 
 * @author Roman Tsutskov
 */
class Router {

	/**
	 * Перенаправляет поступивший запрос на запрошенное действие запрошенного контроллера
	 */
	public function routingToAction() {
		//Если был ввод ссылки
		if(($_SERVER['REQUEST_URI']) && ($_SERVER['REQUEST_URI'] !== '/favicon.ico')) {
			//Проверяем наличие синонима у ссылки и если есть, то конвертируем запоршенную ссылку в синоним
			$url = \liw\vendor\app\Maker::$app -> synonymyLink();
			//Получаем  из ссылки имя контроллера и вызываемого действия
			$controllerName = \liw\vendor\app\Maker::$app -> getControllerName($url);
			$actionName = \liw\vendor\app\Maker::$app -> getActionName($url);
			//Проверяем наличие пользовательского контроллера с запрошенным именем
			if(file_exists(__DIR__.'/../../../controllers/'.$controllerName.'.php') === true) {
				$contorllerPuth = '\liw\controllers\\'.$controllerName;
				$contorllerPuth::$actionName();
			} else { //Если пользовательского контроллера с запрошенным именем нет
				//Получаем имя предполгаемого виджета
				$widgetName = \liw\vendor\app\Maker::$app -> getWidgetName($url);
				if(file_exists(__DIR__.'/../widgets/'.$widgetName.'/controllers/'.$controllerName.'.php') === true) {
					$contorllerPuth = '\liw\vendor\app\widgets\\'.$widgetName.'\controllers\\'.$controllerName;
					$contorllerPuth::$actionName();
				} else {
					\liw\vendor\app\Maker::$app -> error('Файл контроллера '.$url.'|'.$controllerName.' не найден');
				}
			}
		}
	}

	/**
	 * Перенаправляет на запрошенный файл вида страницы
	 * 
	 * @param string $viewName Имя запрашиваемого файла вида
	 * @param array $vars Массив с данными для формирования переменных, содержащих контент(кода виджетов, вывода текстовых данных), подлежащий выводу на странице
	 * @return string HTML код вида
	 */
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
			$url = \liw\vendor\app\Maker::$app -> synonymyLink();

			//Формируем ссылку на запрашиваемый файл вида
			$linkToView = \liw\vendor\app\Maker::$app -> getViewPuth($url, $viewName);
			ob_start();
			require_once($linkToView);
			$viewCode = ob_get_clean();
			return $viewCode;
		} else {
			if($dataType1 !== 'string') {
				\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' тип данных первого входного параметра не соответствует типу string');
			}
			if($dataType2 !== 'array') {
				\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' тип данных второго входного параметра не соответствует типу array');
			}
			if($viewName === '') {
				\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' не указаны данные в первом входном параметре');
			}
			if($vars === []) {
				\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' не указаны данные во втором входном параметре');
			}
		}
	}

}