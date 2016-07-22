<?php

namespace application\models;

class RouterClass
{
  function redirect($internalServiceUrlObject)
  {
    switch($internalServiceUrlObject->numberRequestedUrl) {
      case 0: require_once(CONTROLLER_PUTH.'ViewController.php'); break;
      case 1: require_once(CONTROLLER_PUTH.'ViewController.php'); break;
      case 2: require_once(CONTROLLER_PUTH.'PublicationController.php'); break;
      case 3: require_once(CONTROLLER_PUTH.'TapeController.php'); require_once(CONTROLLER_PUTH.'PublicationController.php'); break;
      case 4: require_once(CONTROLLER_PUTH.'PublicationController.php'); break;
      case 5: require_once(CONTROLLER_PUTH.'ViewController.php'); break;
      case 6: require_once(CONTROLLER_PUTH.'TapeController.php'); break;
      case 7: require_once(CONTROLLER_PUTH.'PublicationController.php'); break;
      case 8: require_once(CONTROLLER_PUTH.'ViewController.php'); break;
      case 9: require_once(CONTROLLER_PUTH.'PublicationController.php'); break;
    }

    unset($internalServiceUrlObject);
  }
}

