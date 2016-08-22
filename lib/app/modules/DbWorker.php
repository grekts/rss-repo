<?php

namespace lib\app\modules;

use \lib\app\Maker;

class DbWorker
{
	private $dbName;
	private $dbPass;
	private $dbHost;
	private $dbUserName;
	private $connectId;
	public static $db = null;

	function __construct() {
		//Получаем настройки БД из сформированных ранее данных конфигурации
		$dbSettings = Maker::$app -> configData['db'];

		$this->dbName = $dbSettings['dbName'];
		$this->dbUserName = $dbSettings['dbUserName'];
		$this->dbPass = $dbSettings['dbPass'];
		$this->dbHost = $dbSettings['dbHost'];

	}

	//Метод подключения к БД
	public function connect() {
		$dataType1 = gettype($this->dbHost);
	    $dataType2 = gettype($this->dbUserName);
	    $dataType3 = gettype($this->dbPass);
	    $dataType4 = gettype($this->dbName);
	    if(($dataType1 == 'string') && ($dataType2 == 'string') && ($dataType3 == 'string') 
	      && ($dataType4 == 'string') && ($this->dbHost != '') && ($this->dbUserName != '') && ($this->dbName != '')) {
	      	try{
	        	$dsn = "mysql:host=".$this->dbHost.";dbname=".$this->dbName.";charset=utf8mb4";
	        	$this->connectId = new \PDO($dsn, $this->dbUserName, $this->dbPass);
	        	$this->connectId->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);
	        	$this->connectId->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

	        	unset($dataType1, $dataType2, $dataType3, $dataType4, $dsn);
	      	} catch (PDOException $e) {
	        	trigger_error('Произошла ошибка пр подключению к БД||0');
	      	}
	    } else {
	      trigger_error('Указаны не все данные для подключения к БД или они указаны неверно||0');
	    }
	}

	//Метод формирования и отправки запроса к БД
	public function query($query, $vars) {
		$dataType1 = gettype($query);
		$dataType2 = gettype($vars);
		if(($dataType1 === 'string') && ($dataType2 === 'array') && ($query !== '')) {
			//Если пришла команда на создание таблицы
			if(strpos($query, 'CREATE TABLE') !== false) {
				//Делаем запрос
				$execResult = $this->connectId->exec($query);
				if($execResult === false) {
					trigger_error('Ошибка запроса к базе данных||0');
				}
			} else { //Если запрос не на создание страницы
				//Подготавливаем запрос
				$prepareQuery = $this->connectId->prepare($query);
				if($prepareQuery === false) {
					trigger_error('Ошибка подготовки запроса к базе данных||0');
				}
				//Считаем переменные, коотрые нужно вставить в запрос
				$numberVars = count($vars);
				if($numberVars !== 0) {
					//Пробигаем все переменные
					for($i = 0; $i < $numberVars; $i++) {
						//Получаем тип перменной
						$varType = gettype($vars[$i]);
						//Сопоставляем тип переменной с параметром pdo, который обозначает данный тип
						switch ($varType) {
							case 'boolean': $pdoDataType = \PDO::PARAM_BOOL; break;
							case 'integer': $pdoDataType = \PDO::PARAM_INT; break;
							case 'string': $pdoDataType = \PDO::PARAM_STR; break;
							case 'NULL': $pdoDataType = \PDO::PARAM_NULL; break;
						}
						//Аставляем в запрос параметры
						$prepareQuery->bindValue($i + 1, $vars[$i], $pdoDataType);
					}
				}
				//Выполняем запрос
				$executeResult = $prepareQuery->execute();
				if($executeResult === false) {
					trigger_error('Ошибка запуска подготовленного запроса к базе данных||0');
				}
				//Если запрос Select
				if(strpos($query, 'SELECT') !== false) {
					//Получаем данные
					$dataFromDb = $prepareQuery->fetchAll(\PDO::FETCH_ASSOC);
					if($dataFromDb === false) {
						trigger_error('ошибка запроса к базе данных||0');
					}

					unset($query, $vars, $prepareQuery, $numberVars, $i, $varType, $pdoDataType, $executeResult, $execResult);

					return $dataFromDb;
				}

				unset($query, $vars, $prepareQuery, $numberVars, $i, $varType, $pdoDataType, $executeResult, $execResult);
			}
		} else {
			trigger_error('Неверно указаны входные данные||0');
		}
	}
}