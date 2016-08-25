<?php

namespace lib\app\modules;

class Filter
{
	//Метод фильтрования данных
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
				Maker::$app -> error('В методе '.__METHOD__.' тип данных входного параметра не соответствует типу string');
			}
			if($data === '') {
				Maker::$app -> error('В методе '.__METHOD__.' не указаны данные во входном параметре');
			}
		}
	}
}