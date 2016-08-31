<?php

namespace lib\app\modules;

class Registrator
{

	public static $tagBuffer = [];

	public static $widgetBuffer = [];

	//Метод регистрации html тегов между head, тега html и script
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

	//Метод регистрации виджетов, которые будут использваться на странице
	public function widgetRegistration($widget) {
		$datatype = gettype($widget);
		if(($datatype === 'array') && ($widget !== [])) {
			//Сохраняем ассив с описанием html тегов
			self::$widgetBuffer[] = $widget;
		} else {
			if($dataType !== 'array') {
				Maker::$app -> error('В методе '.__METHOD__.' тип данных входного параметра не соответствует типу array');
			}
			if($widget === []) {
				Maker::$app -> error('В методе '.__METHOD__.' не указаны данные во входном параметре');
			}
		}
	}
}