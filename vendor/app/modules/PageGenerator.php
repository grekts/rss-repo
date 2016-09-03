<?php

namespace liw\vendor\app\modules;

class PageGenerator
{
	//Метд рендеринга
	public function render($viewName, $vars) {
		$dataType1 = gettype($viewName);
		$dataType2 = gettype($vars);
		if(($dataType1 === 'string') && ($dataType2 === 'array') && ($viewName !== '')) {
			//Формируем блоки кодов зарегистрированных виджетов
			$widgetsCode = $this -> formingWidgets();

			//Формируем переменные, которые будут содержать кода виджетов
			foreach ($widgetsCode as $widgetsName => $widgetCode) {
				$$widgetsName = $widgetCode;
			}

			$widgetsNames = array_keys($widgetsCode);

			//Выводим теги, коотрые указал пользователь
			$tagsList = $this -> formingTags($widgetsNames);
			//Формируем переменные, котоыре будут выводиться в шаблоне
			$htmlTag = $tagsList['htmlTagCode'];
			$headTags = $tagsList['headTagsCode'];
			$scriptTags = $tagsList['scriptTags'];

			//Подключаем вид
			$content = \liw\vendor\app\Maker::$app -> routingToView($viewName, $vars);
			//Формируем переменную с котдом вида, которая будет выводиться в шаблоне
			//Подключаем шаблон
			if(file_exists(__DIR__.'/../../../views/layouts/main.php')) {
				require_once(__DIR__.'/../../../views/layouts/main.php');
			} else {
				\liw\vendor\app\Maker::$app -> error('Не найден файл шаблона main.php');
			}
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
		}
	}

	//Метод формирование тегов в head
	public function formingTags($widgetsNames = '') {
		ob_start();
		$headTagsCode = '';
		$htmlTag = '';
		$scriptTags = '';
		//Если пользователь установил HTML теги для вывода
		if(\liw\vendor\app\modules\Registrator::$tagBuffer !== []) {
			$tags = \liw\vendor\app\modules\Registrator::$tagBuffer[0];
			\liw\vendor\app\modules\Registrator::$tagBuffer = null;
			//Получаем именя тегов
			$tagsNames = array_keys($tags);
			//Если в списке нет обязательного тега title
			if (array_search('html', $tagsNames) === false) {
				$htmlTag .= '<html lang="ru">';
				$htmlTag .= "\r\n";
			} 
			if(array_search('title', $tagsNames) === false) {
				$headTagsCode .= '<title>Документ</title>';
				$headTagsCode .= "\r\n";
			} 
			if (array_search('meta', $tagsNames) === false) {
				$headTagsCode .= '<meta charset="UTF-8">';
				$headTagsCode .= "\r\n";
				$headTagsCode .= '<meta name="description" content="Описание страницы">';
				$headTagsCode .= "\r\n";
			}
			if (array_search('link', $tagsNames) === false) {
				$headTagsCode .= '<link rel="stylesheet" src="" />';
				$headTagsCode .= "\r\n";
			} 
			//Побегаем каждое имя тега (каждый тег)
			foreach ($tags as $tagName => $properties) {
				switch (strtolower($tagName)) {
					case 'title':
						$headTagsCode .= '	<title>'.$properties.'</title>';
						$headTagsCode .= "\r\n";
						break;
					case 'link':
						//Пробегаем кажыдый массив экземпляра определенного тега
						foreach ($properties as $onePropertyList) {
							$headTagsCode .= '	<link ';
							//Пробегаем параметры экземпляра определенного тега
							foreach ($onePropertyList as $propertyName => $propertyValue) {
								$headTagsCode .= strtolower($propertyName).'="'.$propertyValue.'" ';
							}
							$headTagsCode .= '>';
							$headTagsCode .= "\r\n";
						}
						break;
					case 'meta':
						//Пробегаем кажыдый массив экземпляра определенного тега
						foreach ($properties as $onePropertyList) {
							$headTagsCode .= '	<meta ';
							//Пробегаем параметры экземпляра определенного тега
							foreach ($onePropertyList as $propertyName => $propertyValue) {
								$headTagsCode .= strtolower($propertyName).'="'.$propertyValue.'" ';
							}
							$headTagsCode .= '>';
							$headTagsCode .= "\r\n";
						}
						break;
					case 'html':
						//Получаем параметры тега link
						$htmlTag .= '<html ';
						//Пробегаем кажыдй параметр
						foreach ($properties as $propertyName => $propertyValue) {
							$htmlTag .= strtolower($propertyName).'="'.$propertyValue.'" ';
						}
						$htmlTag .= '/>';
						$htmlTag .= "\r\n";
						break;
					case 'script':
						//Пробегаем кажыдый массив экземпляра определенного тега
						foreach ($properties as $onePropertyList) {
							$scriptTags .= '	<script ';
							//Пробегаем параметры экземпляра определенного тега
							foreach ($onePropertyList as $propertyName => $propertyValue) {
								$scriptTags .= strtolower($propertyName).'="'.$propertyValue.'" ';
							}
							$scriptTags .= '></script>';
							$scriptTags .= "\r\n";
						}
						break;
				}
			}

			//Если пришли имена подключенных виджетов, т.е. какие-то виджеты были подключены к странице
			if($widgetsNames !== '') {
				foreach ($widgetsNames as $widgetName) {
					if(file_exists('../vendor/app/widgets/'.$widgetName.'/css/'.$widgetName.'.css') === true) {
						$headTagsCode .= '	<link rel="stylesheet" href="vendor/app/widgets/'.$widgetName.'/css/'.$widgetName.'.css">'."\r\n";
					}

					if(file_exists('../vendor/app/widgets/'.$widgetName.'/scripts/'.$widgetName.'.js') === true) {
						$scriptTags .= '	<script type="text/javascript" src="vendor/app/widgets/'.$widgetName.'/scripts/'.$widgetName.'.js"></script>'."\r\n";
					}
				}
			}

			unset($tags, $tagsNames);

			return ['htmlTagCode' => $htmlTag, 'headTagsCode' => $headTagsCode, 'scriptTags' => $scriptTags];
		} else {

			return ['htmlTagCode' => '<html lang="ru">', 'headTagsCode' => '<meta charset="UTF-8">
	<title>Document</title>
	<meta name="description" content="Описание страницы">
	<link rel="stylesheet" href="">'];
		}
	}

	//Метод формирование тегов в head
	public function formingWidgets() {
		$widgetsCode = [];
		//Если были зарегистрированы виджеты
		if(\liw\vendor\app\modules\Registrator::$widgetBuffer !== []) {
			//Сохраняем данные зарегистрированных виджетов
			$widgetsData = \liw\vendor\app\modules\Registrator::$widgetBuffer[0];
			//Обнуляем буфер с данными виджетов
			\liw\vendor\app\modules\Registrator::$widgetBuffer = null;
			//Получаем имена зарегистрированных виджетов
			$widgetsNames = array_keys($widgetsData);
			//Пробегаем все зарегистрированные виджеты
			foreach ($widgetsNames as $widgetName) {
				//Если существует дирректория с зарегистрированным виджетом
				if(is_dir(__DIR__.'/../widgets/'.$widgetName) === true) {
					//Если у зарегистрированнного виджета есть папака с видом
					if(is_dir(__DIR__.'/../widgets/'.$widgetName.'/views') === true) {
						//Если у виджета есть файл с его конфигурацией
						if(file_exists(__DIR__.'/../widgets/'.$widgetName.'/config/config.php') === true) {
							//Получаем конфигурационный файл виджета
							$widgetConfigParams = require_once(__DIR__.'/../widgets/'.$widgetName.'/config/config.php');
							//Получаем имена параметров, которые обязательны должны быть описаны для виджета
							$paramsNames = array_keys($widgetConfigParams);

							//Получаем имена свойств, описанных у виджета
							$widgetWroteParams = array_keys($widgetsData[$widgetName]);
							//Пробегаем все обязательыне параметры виджета из конфига
							foreach ($paramsNames as $paramName) {
								//Если в массиве с параметрами, которыми описали зарегистрированный виджет, нет текущего проверяемого параметра
								if(in_array($paramName, $widgetWroteParams) === false) {
									\liw\vendor\app\Maker::$app -> error('При регистрации виджета не описан обязательный параметр '.$paramName);
								} else { //Если параметр есть
									//Получаем тип данных, который должен быть у текущего проверяемого обязательного параметра
									$typeValue = $widgetConfigParams[$paramName]['type'];
									//Если в описании виджета проверяемый параметр имеет нужный тип данных
									if(gettype($widgetsData[$widgetName][$paramName]) === $typeValue) {
										//Получаем из конфига информацию о том, может ли быть параметр виджета пустым
										$emptyValue = $widgetConfigParams[$paramName]['empty'];
										//Если не может
										if($emptyValue === 'no') {
											//Исходя из типа данных параметра проверяем его на пустоту
											switch ($typeValue) {
												case 'array':
													if($widgetsData[$widgetName][$paramName] === []) {
														\liw\vendor\app\Maker::$app -> error('При регистрации виджета не указаны значения обязательного параметра '.$paramName);
													}
													break;
												
												case 'string':
													if($widgetsData[$widgetName][$paramName] === '') {
														\liw\vendor\app\Maker::$app -> error('При регистрации виджета не указаны значения обязательного параметра '.$paramName);
													}
													break;
											}
										}
									} else {
										\liw\vendor\app\Maker::$app -> error('При регистрации виджета был неверно описанан обязательный параметр '.$paramName);
									}
								}
							}
						} else {
							\liw\vendor\app\Maker::$app -> error('У виджета '.$widgetName.' отсутсвует конфигурационный файл');
						}

						//Переделываем свойства описанных параметров виджета в переменные и их знаечния
						foreach ($widgetsData[$widgetName] as $widgetParameter => $parameterValue) {
							if(isset($$widgetParameter) === false) {
								$$widgetParameter = $parameterValue;
							} else {
								\liw\vendor\app\Maker::$app -> error('Параметр виджета, имеюющий имя '.$widgetParameter.' уже существует');
							}
						}
						ob_start();
						//Подключаемвид виджета, вставляя туда значения параметров
						require_once(__DIR__.'/../widgets/'.$widgetName.'/views/'.$widgetName.'.php');
						$widgetCode = ob_get_clean();

						$widgetsCode[$widgetName] = $widgetCode;
					} else {
						\liw\vendor\app\Maker::$app -> error('У виджета '.$widgetName.' отсутствуют виды');
					}
				} else {
					\liw\vendor\app\Maker::$app -> error('Не найден виджет '.$widgetName);
				}
			}
			//Возвращаем массив с кодами виджетов
			return $widgetsCode;
		} else {
			\liw\vendor\app\Maker::$app -> error('Не определены виджеты и их параметры');
		}
	}

}