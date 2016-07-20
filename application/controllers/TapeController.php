<?php
// set level error loging
error_reporting(E_ALL | E_STRICT);
// set mode error out
ini_set('display_errors', 'Off');

use application\models\SessionClass as sessionClassNamespace;
use application\models\DataFromClientClass as dataFromClientClassNamespace;
use application\models\DataBaseClass as dataBaseClassNamespace;
use application\models\RssTapeClass as rssTapeClassNamespace;
use application\models\UrlClass as urlClassNamespace;
use application\lib\idna_convert as idnaConvertNamespace;

$sessionAnalysis = new sessionClassNamespace();
$sessionAnalysis->startSession();

$dataBase = new dataBaseClassNamespace(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
//connect to database
$dataBase->connectionWithDb();

//set flag with use error handler for determine who start script (cron or user)
$_SESSION['initializer'] = 'user';

//if come data with new news tape for send to DB
if(isset($_POST['nameNewTape']) === true) {
  $dataFromClient = new dataFromClientClassNamespace();
  //check data which come from user
  $dataFromClient->screeningDataFromClient($_POST['nameNewTape']);
  
  $urlToRss = new urlClassNamespace();
  $urlToRss->checkUrlFormat($dataFromClient->screeningDataFromClient);

  if($urlToRss->urlFormatCheckFlag === 1) {
    //get domain and ather url part
    $urlToRss->devideUrl();

    //encoding url with russian letter
    $urlToRss->encodingUrl();

    $idnaConverter = new idnaConvertNamespace();
    ////encoding domain with russian letter
    $encodedDomain = $idnaConverter->encode($urlToRss->domainName);

    $rssTape = new rssTapeClassNamespace();
    //get all rss tapes from database
    $rssTape->getRssTapesFromDB($dataBase->pdoObject);

    $urlToRss->checkUrlCapacityAndDetermineUrlStructure($encodedDomain.$urlToRss->encodingUrlPartWithoutDomain);

    //send new tape to DB
    $rssTape->sendNewsTapeToDB($urlToRss->rssUrlWithAllElements, $dataBase->pdoObject);
    
    echo 'notError|RSS лента добавлена в базу';
  } else {
    trigger_error(ERROR_SYSTEM_ERROR.'|!|'.$_SESSION['initializer'], E_USER_ERROR);
  }
}

