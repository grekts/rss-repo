<?php

namespace application\models;

class ErrorHandlerClass
{

  public static function processingError($errorNumber, $errorData, $errorFile, $errorLine)
  {
    $dataFromVars = '';
    
    switch($errorNumber) {
      case E_USER_ERROR:
      case E_USER_WARNING:
      case E_USER_NOTICE:
      $nowTime = date('d-m-Y H:i:s');
      
      //determine message type (message which created admin or cron)
      $positionByMessageSeparator = strpos($errorData, '|!|');
      //if string "|!|" not exist so it mean what message created cron
      if($positionByMessageSeparator === false) {
        $errorMesage = $errorData;
      } else { //if string "|!|" exist so it mean what message created programmer
        $explodeErrorData = explode('|!|', $errorData);
        //get error message
        $errorMesage = $explodeErrorData[0];
        //get data who started code with created error (user or cron)
        $dataWhoInitializedProcess = $explodeErrorData[1];
      }
      
      $infoAboutError = '['.$nowTime.'] '.$errorMesage.' in '.$errorFile.': '.$errorLine.' '.$errorNumber."\r\n";
      $openFile = fopen("application/data/logs/errorLog.txt", 'a+');
      fwrite($openFile, $infoAboutError);
      fclose($openFile);

      //if not set who start code which created error
      if(isset($dataWhoInitializedProcess) === false) {
        session_destroy();
        exit();
      } else {
        //if user start code which created error
        if($dataWhoInitializedProcess == 'user') {
          //show error message
           echo $errorMesage;
           exit();
        } else { //if cron start code wich created error
          session_destroy();
          exit();
        } 
      }
    }
  }
}

