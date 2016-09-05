<?php

namespace liw\vendor\app\modules;

/**
 * Класс работы с базой данных.
 * Класс предоставляет следующий функционал:
 * - подключение к БД
 * - отправка запросов к БД и получение данных
 * 
 * @author Roman Tsutskov
 */
class DbWorker
{
	/**
	 * Имя базы данных.
	 * Имя базы данных, к которой будет происходить подключение.
	 * Указывается в пользовательском конфигурационном файле.
	 * 
	 * @var string
	*/
	private $dbName;

	/**
	 * Пароль базы данных.
	 * Пароль для подключения к базе данных
	 * Указывается в пользовательском конфигурационном файле.
	 * 
	 * @var string
	*/
	private $dbPass;

	/**
	 * Host базы данных
	 * Определяет host, где находится запрашиваемая база данных
	 * Указывается в пользовательском конфигурационном файле.
	 * 
	 * @var string
	*/
	private $dbHost;

	/**
	 * Имя пользователя базы данных
	 * Определяетимя пользователя, коотрому разрешено подключаться к требуемой базе данных
	 * Указывается в пользовательском конфигурационном файле.
	 * 
	 * @var string
	*/
	private $dbUserName;

	/**
	 * Экземпляр объекта, определяющего подключеную базу данных
	 * Хранит объект, идентифицирующий базу данных, к которой было произведено подключение
	 * 
	 * @var object
	*/
	private $connectId;

	/**
	 * Хранит экземпляр объекта, отвечающего за работу с базой данны (DbWorker)
	 * Используется приложением для подключения к БД и отправки запроса к БД
	 * 
	 * @var null|object
	*/
	public static $db = null;

	/**
	 * Конструктор класса работы с базой данных
	 * Сохраняет данные базы данных в свойствах класса
	 */
	function __construct() {
		//Получаем настройки БД из сформированных ранее данных конфигурации
		$dbSettings = \liw\vendor\app\Maker::$app -> configData['db'];

		$this->dbName = $dbSettings['dbName'];
		$this->dbUserName = $dbSettings['dbUserName'];
		$this->dbPass = $dbSettings['dbPass'];
		$this->dbHost = $dbSettings['dbHost'];

	}

	/**
	 * Метод подключения к базе данных
	 */
	public function connect() {
		$dataType1 = gettype($this->dbHost);
	    $dataType2 = gettype($this->dbUserName);
	    $dataType3 = gettype($this->dbPass);
	    $dataType4 = gettype($this->dbName);
	    if(($dataType1 === 'string') && ($dataType2 === 'string') && ($dataType3 === 'string') 
	      && ($dataType4 === 'string') && ($this->dbHost !== '') && ($this->dbUserName !== '') && ($this->dbName !== '')) {
	      	try{
	        	$dsn = "mysql:host=".$this->dbHost.";dbname=".$this->dbName.";charset=utf8mb4";
	        	$this->connectId = new \PDO($dsn, $this->dbUserName, $this->dbPass);
	        	$this->connectId->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);
	        	$this->connectId->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

	        	unset($dataType1, $dataType2, $dataType3, $dataType4, $dsn);
	      	} catch (PDOException $e) {
	        	\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' произошла ошибка при подключении к БД');
	      	}
	    } else {
	      	if($dataType1 !== 'string') {
	      		\liw\vendor\app\Maker::$app -> error('Тип свойства dbHost параметра db, указанного в конфигурационном файле, не соответсвует типу string');
	      	}
	      	if($dataType2 !== 'string') {
	      		\liw\vendor\app\Maker::$app -> error('Тип свойства dbUserName параметра db, указанного в конфигурационном файле, не соответсвует типу string');
	      	}
	      	if($dataType3 !== 'string') {
	      		\liw\vendor\app\Maker::$app -> error('Тип свойства dbPass параметра db, указанного в конфигурационном файле, не соответсвует типу string');
	      	}
	      	if($dataType4 !== 'string') {
	      		\liw\vendor\app\Maker::$app -> error('Тип свойства dbName параметра db, указанного в конфигурационном файле, не соответсвует типу string');
	      	}
	      	if($this->dbHost === '') {
	      		\liw\vendor\app\Maker::$app -> error('В конфигурационном файле не указано свойство dbHost параметра db');
	      	}
	      	if($this->dbUserName === '') {
	      		\liw\vendor\app\Maker::$app -> error('В конфигурационном файле не указано свойство dbUserName параметра db');
	      	}
	      	if($this->dbName === '') {
	      		\liw\vendor\app\Maker::$app -> error('В конфигурационном файле не указано свойство dbName параметра db');
	      	}
	    }
	}

	/**
	 * Метод формирования и отправки запроса к БД
	 * 
	 * @param string $query Строка с запросом к базе данных
	 * @param array $vars Массив с параметрами запроса к базе данных
	 * @return array В случае SELECT запроса, именованный масссив с данными, полученными из базы данных
	 */
	public function query($query, $vars) {
		$dataType1 = gettype($query);
		$dataType2 = gettype($vars);
		if(($dataType1 === 'string') && ($dataType2 === 'array') && ($query !== '')) {
			//Если пришла команда на создание таблицы
			if(strpos($query, 'CREATE TABLE') !== false) {
				//Делаем запрос
				$execResult = $this->connectId->exec($query);
				if($execResult === false) {
					\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' ошибка запроса к базе данных');
				}
			} else { //Если запрос не на создание страницы
				//Подготавливаем запрос
				$prepareQuery = $this->connectId->prepare($query);
				if($prepareQuery === false) {
					\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' ошибка подготовки запроса к базе данных');
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
					\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' ошибка запуска подготовленного запроса к базе данных');
				}
				//Если запрос Select
				if(strpos($query, 'SELECT') !== false) {
					//Получаем данные
					$dataFromDb = $prepareQuery->fetchAll(\PDO::FETCH_ASSOC);
					if($dataFromDb === false) {
						\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' ошибка запроса к базе данных');
					}

					unset($query, $vars, $prepareQuery, $numberVars, $i, $varType, $pdoDataType, $executeResult, $execResult);

					return $dataFromDb;
				}

				unset($query, $vars, $prepareQuery, $numberVars, $i, $varType, $pdoDataType, $executeResult, $execResult);
			}
		} else {
			if($dataType1 !== 'string') {
				\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' тип данных первого входного параметра не соответствует типу string');
			}
			if($dataType2 !== 'array') {
				\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' тип данных второго входного параметра не соответствует типу array');
			}
			if($query === '') {
				\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' не указаны данные в первом входном параметре');
			}
		}
	}
}