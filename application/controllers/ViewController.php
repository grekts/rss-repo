<?php

// set level error loging
error_reporting(E_ALL | E_STRICT);
// set mode error out
ini_set('display_errors', 'Off');

use application\models\view\TitleClass as titleClassNamespace;
use application\models\view\MetaDescriptionClass as pageMetaDescriptionClassNamespace;
use application\models\view\CssFileClass as cssFileClassNamespace;
use application\models\view\MainMenuClass as mainMenuClassNamespace;
use application\models\view\RobotsAccessClass as robotsAccessClassNamespace;
use application\models\view\NewsClass as newsClassNamespace;
use application\models\SessionClass as sessionClassNamespace;
use application\models\DataBaseClass as dataBaseClassNamespace;

$sessionAnalysis = new sessionClassNamespace();
$sessionAnalysis->startSession();

//set flag with use error handler for determine who start script (cron or user)
$_SESSION['initializer'] = 'user';

$dataBase = new dataBaseClassNamespace(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
//connect to database
$dataBase->connectionWithDb();

if(($internalServiceUrlObject->numberRequestedUrl === 0) || ($internalServiceUrlObject->numberRequestedUrl === 1)) {
  $pageTitle = new titleClassNamespace();
  //determine page title
  $pageTitle->formationTitleTextForPage($internalServiceUrlObject->numberRequestedUrl);
  
  $pageMetaDescription = new pageMetaDescriptionClassNamespace();
  //determine page description
  $pageMetaDescription->formationMetaDescriptionTextForPage($internalServiceUrlObject->numberRequestedUrl);
  
  $cssFile = new cssFileClassNamespace();
  //determine css file
  $cssFile->formationCssFileName($internalServiceUrlObject->numberRequestedUrl);
  
  $mainMenu = new mainMenuClassNamespace();
  //formation main menu
  $mainMenu->formationMainMenuForPage($internalServiceUrlObject->numberRequestedUrl);
  
  $robotsAccess = new robotsAccessClassNamespace();
  //formation code for close pade for index
  $robotsAccess->formingCodeBanAccessRobotsOnPage($internalServiceUrlObject->numberRequestedUrl);
  
  $newsList = new newsClassNamespace();
  //get all news from DB for show
  $newsList->getListWithNewsForShow($dataBase->pdoObject);
  
  require_once(VIEW_PUTH.'HtmlHeadView.php');
  require_once(VIEW_PUTH.'BodyHeadView.php');
  require_once(VIEW_PUTH.'BodyIndexPageView.php');
  require_once(VIEW_PUTH.'FooterView.php');
}  

if($internalServiceUrlObject->numberRequestedUrl === 5) {
  $pageTitle = new titleClassNamespace();
  //determine page title
  $pageTitle->formationTitleTextForPage($internalServiceUrlObject->numberRequestedUrl);
  
  $pageMetaDescription = new pageMetaDescriptionClassNamespace();
  //determine page description
  $pageMetaDescription->formationMetaDescriptionTextForPage($internalServiceUrlObject->numberRequestedUrl);
  
  $cssFile = new cssFileClassNamespace();
  //determine css file
  $cssFile->formationCssFileName($internalServiceUrlObject->numberRequestedUrl);
  
  $mainMenu = new mainMenuClassNamespace();
  //formation main menu
  $mainMenu->formationMainMenuForPage($internalServiceUrlObject->numberRequestedUrl);
  
  $robotsAccess = new robotsAccessClassNamespace();
  //formation code for close pade for index
  $robotsAccess->formingCodeBanAccessRobotsOnPage($internalServiceUrlObject->numberRequestedUrl);
  
  require_once(VIEW_PUTH.'HtmlHeadView.php');
  require_once(VIEW_PUTH.'BodyHeadView.php');
  require_once(VIEW_PUTH.'BodyTapeListPageView.php');
  require_once(VIEW_PUTH.'FooterView.php');
} 


