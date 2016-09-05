<?php

namespace liw\vendor\app\modules;

/**
 * Класс фильрации данных, пришедших от пользователя.
 * Класс предоставляет функционал удаления из строки тегов, экранирования 
 * спец. символов и удаление пробелов в начале  и конце строк
 * 
 * @author Roman Tsutskov
 */
class Filter
{
	/**
	 * Фильтрует пришедшие от пользователя текстовые данные на наличие спец. символов, html тегов и пробелов в начале и конце пришедшей строки данных
	 * 
	 * @param string $data Пришедшие от пользователя данные
	 * @return string Проверенный текст
	 */
	public function filter($data) {
		$dataType = gettype($data); 
		if(($dataType === 'string') && ($data !== '')) {
			$inputText = strip_tags($data);
			$inputText = htmlspecialchars($inputText);
			$inputText = trim($inputText);

			unset($data, $dataType);

			return $inputText;
		} else {
			if($dataType !== 'string') {
				\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' тип данных входного параметра не соответствует типу string');
			}
			if($data === '') {
				\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' не указаны данные во входном параметре');
			}
		}
	}
}