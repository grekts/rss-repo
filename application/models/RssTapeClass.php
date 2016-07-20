<?php

namespace application\models;

class RssTapeClass
{
  public $rssTapesList;
  public $numberRssTapesInDb;
  public $rssTapesId;
  
  function getRssTapesFromDB($dbConnect)
  {
    try {
      $query = 'SELECT rss_url_list.rss_url, rss_url_list_id
      FROM rss_url_list';
      $prepareQuery = $dbConnect->prepare($query);
      $prepareQuery->execute(array());
      $dataFromDb = $prepareQuery->fetchAll(\PDO::FETCH_NUM);
    } catch(PDOException $e) {
      trigger_error(ERROR_SYSTEM_ERROR.'|!|'.$_SESSION['initializer'], E_USER_ERROR);
    }
    
    $numberRssTapes = count($dataFromDb);
    if($numberRssTapes !== 0) {
      for($i = 0; $i<$numberRssTapes; $i++){
        $this->rssTapesList[] = $dataFromDb[$i][0];
        $this->rssTapesId[] = (int)$dataFromDb[$i][1];
        $this->numberRssTapesInDb = $numberRssTapes;
      }
    } else {
      $this->numberRssTapesInDb = $numberRssTapes;
    }
    
    unset($dbConnect, $query, $prepareQuery, $dataFromDb, $numberRssTapes);
  }
  
  function sendNewsTapeToDB($tapeUrlForSend, $dbConnect)
  {
    $flagTapeExistInDB = 0;

    if($this->numberRssTapesInDb !== 0) {
      //find in tape from DB tape wich need send to DB
      for($i = 0; $i < $this->numberRssTapesInDb; $i++) {
        if($this->rssTapesList[$i] === $tapeUrlForSend) {
          $flagTapeExistInDB = 1;
          break;
        }
      }
    }
    
    if($flagTapeExistInDB === 0) {
      try {
        $query = 'INSERT INTO rss_url_list VALUES (?, ?)';
        $prepareQuery = $dbConnect->prepare($query);
        $prepareQuery->execute(array(NULL, $tapeUrlForSend));
      } catch(PDOException $e) {
        $dbConnect->rollBack();
        trigger_error(ERROR_SYSTEM_ERROR.'|!|'.$_SESSION['initializer'], E_USER_ERROR);
      }
    } else {
      trigger_error(ERROR_RSS_EXIST_IN_DB.'|!|'.$_SESSION['initializer'], E_USER_ERROR);
    }
    
    unset($tapeUrlForSend, $dbConnect, $flagTapeExistInDB, $query, $prepareQuery);
  }
}

