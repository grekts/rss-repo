<?php

namespace application\models;

class DataBaseClass
{
  private $host;
  private $user;
  private $dataBaseName;
  private $password;
  public $pdoObject;
    
  function __construct($host, $user, $password, $dataBaseName) {
    $this->host = $host;
    $this->user = $user;
    $this->password = $password;
    $this->dataBaseName = $dataBaseName;
  }
  
  function connectionWithDb()
  {
    $dataType1 = gettype($this->host);
    $dataType2 = gettype($this->user);
    $dataType3 = gettype($this->password);
    $dataType4 = gettype($this->dataBaseName);
    if(($dataType1 == 'string') && ($dataType2 == 'string') && ($dataType3 == 'string') 
      && ($dataType4 == 'string') && ($this->host != '') && ($this->user != '') && ($this->dataBaseName != '')) {
      try{
        $dsn = "mysql:host=".$this->host.";dbname=".$this->dataBaseName.";charset=utf8mb4";
        $this->pdoObject = new \PDO($dsn, $this->user, $this->password);
        $this->pdoObject->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);
        $this->pdoObject->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
        trigger_error(ERROR_SYSTEM_ERROR.'|!|'.$_SESSION['initializer'], E_USER_ERROR);
      }
    } else {
      trigger_error(ERROR_SYSTEM_ERROR.'|!|'.$_SESSION['initializer'], E_USER_ERROR);
    }
    
    unset($dataType1, $dataType2, $dataType3, $dataType4, $dsn);
  }
}


