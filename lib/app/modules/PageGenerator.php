<?php

namespace lib\app\modules;

use \lib\app\Maker;

class PageGenerator
{
	public static $tagBuffer = [];

	//Метд рендеринга
	public function render($viewName, $vars) {
		$dataType1 = gettype($viewName);
		$dataType2 = gettype($vars);
		if(($dataType1 === 'string') && ($dataType2 === 'array') && ($viewName !== '')) {
			//Выводим теги, коотрые указал пользователь
			$tagsList = $this -> outTags();
			//Формируем переменные, котоыре будут выводиться в шаблоне
			$htmlTag = $tagsList['htmlTagCode'];
			$headTags = $tagsList['headTagsCode'];
			$scriptTags = $tagsList['scriptTags'];

			ob_start();
			//Подключаем вид
			Maker::$app -> routingToView($viewName, $vars);
			//Формируем переменную с котдом вида, которая будет выводиться в шаблоне
			$content = ob_get_clean();
			//Подключаем шаблон
			if(file_exists(__DIR__.'/../../../views/layouts/main.php')) {
				require_once(__DIR__.'/../../../views/layouts/main.php');
			} else {
				Maker::$app -> error('Не найден файл шаблона main.php');
			}
		} else {
			if($dataType1 !== 'string') {
				Maker::$app -> error('В методе '.__METHOD__.' тип данных первого входного параметра не соответствует типу string');
			}
			if($dataType2 !== 'array') {
				Maker::$app -> error('В методе '.__METHOD__.' тип данных второго входного параметра не соответствует типу array');
			}
			if($viewName === '') {
				Maker::$app -> error('В методе '.__METHOD__.' не указаны данные в первом входном параметре');
			}
		}
	}

	public function tagRegistration($tags) {
		$datatype = gettype($tags);
		if(($datatype === 'array') && ($tags !== [])) {
			//Сохраняем ассив с описанием html тегов
			self::$tagBuffer[] = $tags;
		} else {
			if($dataType !== 'array') {
				Maker::$app -> error('В методе '.__METHOD__.' тип данных входного параметра не соответствует типу array');
			}
			if($tags === []) {
				Maker::$app -> error('В методе '.__METHOD__.' не указаны данные во входном параметре');
			}
		}
	}

	//Метод формирование тегов в head
	public function outTags() {
		$headTagsCode = '';
		$htmlTag = '';
		$scriptTags = '';
		//Если пользователь установил HTML теги для вывода
		if(self::$tagBuffer !== []) {
			$tags = self::$tagBuffer[0];
			self::$tagBuffer = null;
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
			unset($tags, $tagsNames);

			return ['htmlTagCode' => $htmlTag, 'headTagsCode' => $headTagsCode, 'scriptTags' => $scriptTags];
		} else {

			return ['htmlTagCode' => '<html lang="ru">', 'headTagsCode' => '<meta charset="UTF-8">
	<title>Document</title>
	<meta name="description" content="Описание страницы">
	<link rel="stylesheet" href="">'];
		}
		
	}

}