<?php
// set level error loging
error_reporting(E_ALL | E_STRICT);
// set mode error out
ini_set('display_errors', 'Off');

use application\models\SessionClass as sessionClassNamespace;
use application\models\DataBaseClass as dataBaseClassNamespace;
use application\models\RssTapeClass as rssTapeClassNamespace;
use application\models\NewsClass as newsClassNamespace;
use application\models\HtmlTags as htmlTagsNamespace;
use application\models\DataFromClientClass as dataFromClientClassNamespace;

$sessionAnalysis = new sessionClassNamespace();
$sessionAnalysis->startSession();

$dataBase = new dataBaseClassNamespace(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
//connect to database
$dataBase->connectionWithDb();

//if come commant not for change flag reeded news
if(isset($_POST['idReadNews']) === false) {
  //set flag with use error handler for determine who start script (cron or user)
  $_SESSION['initializer'] = 'cron';
  
  $rssTape = new rssTapeClassNamespace();
  //get all rss tapes from database
  $rssTape->getRssTapesFromDB($dataBase->pdoObject);

  //news object
  $news = new newsClassNamespace();

  $htmlTags = new htmlTagsNamespace();

  //processing each rss tape
  for($i = 0; $i<$rssTape->numberRssTapesInDb; $i++) {
    $news->getNewsCodeFromRssTape($rssTape->rssTapesList[$i]);
    $news->getNewsTitleFromDb($dataBase->pdoObject);
    $news->parseReceivedNewsCode();
    $news->separatingNewsDescriptionIntoParagraphs();
    $htmlTags->deleteSeveralHtmlTagsFromNewsCode($news->newsDescriptionArray);
    $htmlTags->rewriteUrlTagsInCode();
    $news->newsDescriptionArray = '';
    $news->newsDescriptionArray = $htmlTags->stringWithDeleteSeveralHtmlTags;
    $news->sendNewsToDb($rssTape->rssTapesId[$i], $dataBase->pdoObject);
  }
} else {
  //set flag with use error handler for determine who start script (cron or user)
  $_SESSION['initializer'] = 'user';
  
  $dataFromClient = new dataFromClientClassNamespace();
  //check data which come from user
  $dataFromClient->screeningDataFromClient($_POST['idReadNews']);
  
  $news = new newsClassNamespace();
  $news->deleteReadNews($dataFromClient->screeningDataFromClient, $dataBase->pdoObject);
}



