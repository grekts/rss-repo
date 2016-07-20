<?php

namespace application\models;

class DataFromClientClass
{
  public $screeningDataFromClient;
  
  //Check data which come from client
  function screeningDataFromClient($dataFromClient)
  {
    $typeUrlFromClient = gettype($dataFromClient); 
    if(($typeUrlFromClient == 'string') && ($dataFromClient != '')) {
      $trimDataFromClient = trim($dataFromClient);
      $this->screeningDataFromClient = htmlspecialchars($trimDataFromClient, ENT_QUOTES);
    } else {
      trigger_error(ERROR_DOMAIN_NOT_INPUT.'|!|'.$_SESSION['initializer'], E_USER_ERROR);
    }
    
    unset($dataFromClient, $typeUrlFromClient, $trimDataFromClient);
  }
}

