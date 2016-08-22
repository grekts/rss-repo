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
			trigger_error('Не указаны входные данные или их тип неверен||0');
		}
	}
}