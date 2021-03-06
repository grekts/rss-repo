<?php

namespace liw\vendor\app;

/**
 * Класс обработки ошибок
 * 
 * Класс предоставляет следующий функционал:
 * - получение информации об ошибке
 * - вывод ошибки и отправка е в лог
 * - определение обработчика ошибок
 * - определение необходимости выводить сообщения об ошибках на страницу
 * 
 * @author Roman Tsutskov
 */
class ErrorHandler
{
	/**
	 * Метод формирования сообщения об ошибке
	 * 
	 * @param string $message Сообщение ошибки
	 * @param string $file Ссылка на файл, в котором произошла ошибка
	 * @param integer $line Номер строки, на которой произошла ошибка
	 * @param integer $flagUserMessage Флаг, определяющий нужно ли показывать пользователю данное сообщение об ошибке
	 */
	public function error($message, $file, $line, $flagUserMessage) {
		$dataType1 = gettype($message);
		$dataType2 = gettype($flagUserMessage);
		$dataType3 = gettype($file);
		$dataType4 = gettype($line);
		if(($dataType1 === 'string') 
			&& ($dataType2 === 'integer') 
			&& ($dataType3 === 'string') 
			&& ($dataType4 === 'integer') 
			&& ($message !== '') 
			&& ($flagUserMessage !== '') 
			&& ($file !== '')
			&& ($line !== '')) {
			//Если сообщение не предназначено для обычного пользователя
			if($flagUserMessage === 0) {
				$this -> showError('-', $message, $file, $line);
			} else { //если сообщение должно выводитьяс обычному пользователю
				$message .= \liw\vendor\app\Maker::$app -> configData['userMessage'];
				$this -> showError('-', $message, $file, $line);
			}
		} else {
			if ($dataType1 !== 'string') {
				trigger_error('В методе '.__METHOD__.' тип данных первого входного параметра, не соответствует типу string');
			}
			if ($dataType2 !== 'integer') {
				trigger_error('В методе '.__METHOD__.' тип данных первого входного параметра, не соответствует типу integer');
			}
			if ($dataType3 !== 'string') {
				trigger_error('В методе '.__METHOD__.' тип данных первого входного параметра, не соответствует типу string');
			}
			if ($dataType4 !== 'integer') {
				trigger_error('В методе '.__METHOD__.' тип данных первого входного параметра, не соответствует типу integer');
			}
			if(($message === '') || ($flagUserMessage === '') || ($file === '') || ($line === '')) {
				trigger_error('В методе '.__METHOD__.' не указаны данные во-входных параметрах');
			}
		}
	}

	/**
	 * Метод установки показывать ошибки или нет (зависит от настроек)
	 * 
	 * @param string $flagWorkDebug Флаг из конфигурационного файла, указывающий запущен режим дебага или нет
	 */
	public function determineShowErrors($flagWorkDebug) {
		$dataType = gettype($flagWorkDebug);
		if(($dataType === 'string') && ($flagWorkDebug !== '')) {
			error_reporting(E_ALL | E_STRICT);
			ini_set('display_errors', $flagWorkDebug);
		} else {
			if($dataType !== 'string') {
				\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' тип данных входного параметра, не соответствует типу string');
			}
			if($flagWorkDebug === '') {
				\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' не указаны данные во входном параметре');
			}
		}
	}

	/**
	 * Метод регистрации обработчиков ошибок
	 */
	public function registerErrorHandler() {
		error_reporting(E_ALL | E_STRICT);
		ini_set('display_errors', 'Off');
		set_error_handler([$this, 'processingError']);
		register_shutdown_function([$this, 'processingFatalError']);
		set_exception_handler([$this, 'processingExceptionError']);
	}

	/**
	 * Обработка ьпользовательских ошибок
	 * 
	 * @param integer $errorNumber Номер ошибки
	 * @param string $errorString Сообщение ошибки
	 * @param string $errorFile Ссылка на файл, в котором произошла ошибка
	 * @param integer $errorLine Номер строки, на которой произошла ошибка
	 */
	public function processingError($errorNumber, $errorString, $errorFile, $errorLine) {
		$this -> showError($errorNumber, $errorString, $errorFile, $errorLine);
		exit();
	}

	/**
	 * Обраьотка фатальных ошибок
	 */
	public function processingFatalError() {
		$errorData = error_get_last();
		if($errorData !== null) {
			ob_get_clean();
			$this -> showError($errorData['type'], $errorData['message'], $errorData['file'], $errorData['line']);
		}
		exit();
	}

	/**
	 * Обработка исключений
	 * 
	 * @param object $e Экземпляр объекта исключения
	 */
	public function processingExceptionError(\Exception $e) {
		$this -> showError(get_class($e), $e -> getMessage(), $e -> getFile(), $e -> getLine());
		exit();
	}

	/**
	 * Метод вывода ошибки на экран и записи в лог
	 * 
	 * @param integer $errorNumberOrType Номер ошибки или ее тип
	 * @param string $errorString Сообщение ошибки
	 * @param string $errorFile Ссылка на файл, в котором произошла ошибка
	 * @param integer $errorLine Номер строки, на которой произошла ошибка
	 */
	private function showError($errorNumberOrType, $errorString, $errorFile, $errorLine) {
		$errorMessage = 'Номер ошибки: '.$errorNumberOrType.' Сообщение: '.$errorString.' Файл: '.$errorFile.' Номер строки: '.$errorLine.' Дата: '.date('d.m.Y H:i');
		$logFile = fopen(__DIR__."/../../logs/errorLog.txt", "a");
		fwrite($logFile, $errorMessage."\r\n");
		fclose($logFile);

		//Если включен режим дебага
		$debagValueLower = strtolower(\liw\vendor\app\Maker::$app -> configData['debug']);
		if($debagValueLower === 'on') {
			echo 'error|<hr>Номер ошибки: '.$errorNumberOrType.'<hr>Сообщение: '.$errorString.'<hr>Файл: '.$errorFile.'<hr>Номер строки: '.$errorLine;
		} else { //Если дебаг выключен
			//Если сообщение не содержит метки, что оно является системным (метка пользовательская или установленная разработчиком приложения)
			if((mb_stripos(\liw\vendor\app\Maker::$app -> configData['userMessage'], $errorString) !== false) || (mb_stripos('||0', $errorString) !== false)) {
				echo 'error|'.$errorString;
			} else {
				echo 'error|Ошибка системы';
			}
		}

		exit();
	}
}