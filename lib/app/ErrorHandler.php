<?php

namespace lib\app;

class ErrorHandler
{
	//Метод установки показывать ошибки или нет (зависит от настроек)
	public function determineShowErrors($flagWorkDebug) {
		$dataType = gettype($flagWorkDebug);
		if(($dataType === 'string') && ($flagWorkDebug !== '')) {
			error_reporting(E_ALL | E_STRICT);
			ini_set('display_errors', $flagWorkDebug);
		} else {
			trigger_error('Входные данные пусты или имеют неверный тип||0');
		}
	}

	//Регистрация обработчиков ошибок
	public function registerErrorHandler() {
		error_reporting(E_ALL | E_STRICT);
		ini_set('display_errors', 'Off');
		set_error_handler([$this, 'processingError']);
		register_shutdown_function([$this, 'processingFatalError']);
		set_exception_handler([$this, 'processingExceptionError']);
	}

	//Обработка ьпользовательских ошибок
	public function processingError($errorNumber, $errorString, $errorFile, $errorLine) {
		$this -> showError($errorNumber, $errorString, $errorFile, $errorLine);
		exit();
	}

	//Обраьотка фатальных ошибок
	public function processingFatalError() {
		$errorData = error_get_last();
		if($errorData !== null) {
			ob_get_clean();
			$this -> showError($errorData['type'], $errorData['message'], $errorData['file'], $errorData['line']);
		}
		exit();
	}

	//Обработка исключений
	public static function processingExceptionError(\Exception $e) {
		$this -> showError(get_class($e), $e -> getMessage(), $e -> getFile(), $e -> getLine());
		exit();
	}

	private function showError($errorNumberOrType, $errorString, $errorFile, $errorLine) {
		$errorMessage = 'Номер ошибки: '.$errorNumberOrType.' Сообщение: '.$errorString.' Файл: '.$errorFile.' Номер строки: '.$errorLine.' Дата: '.date('d.m.Y H:i');
		$logFile = fopen(__DIR__."/../../logs/errorLog.txt", "a");
		fwrite($logFile, $errorMessage."\r\n");
		fclose($logFile);

		//Если включен режим дебага
		if(Maker::$app -> configData['debug'] === 'On') {
			echo 'error|<hr>Номер ошибки: '.$errorNumberOrType.'<hr>Сообщение: '.$errorString.'<hr>Файл: '.$errorFile.'<hr>Номер строки: '.$errorLine;
		} else { //Если дебаг выключен
			//Если сообщение не содержит метки, что оно является системным (метка пользовательская или установленная разработчиком приложения)
			if((mb_stripos(Maker::$app -> configData['techMessage'], $errorString) === false) && (mb_stripos('||0', $errorString) === false)) {
				echo 'error|'.$errorString;
			} else {
				echo 'error|Ошибка системы';
			}
		}


	}
}