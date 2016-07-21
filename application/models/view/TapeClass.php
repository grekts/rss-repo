<?php

namespace application\models\view;

class TapeClass
{
  public $tapeListArray;
  public $numberTape;
  
  function getTapeList($dbConnect)
  {
    try {
      $query = 'SELECT rss_url_list.rss_url, rss_url_list.rss_url_list_id
      FROM rss_url_list';
      $prepareQuery = $dbConnect->prepare($query);
      $prepareQuery->execute(array());
      $dataFromDb = $prepareQuery->fetchAll(\PDO::FETCH_NUM);
    } catch(PDOException $e) {
      trigger_error(ERROR_SYSTEM_ERROR.'|!|'.$_SESSION['initializer'], E_USER_ERROR);
    }
    
    $numberTapeInDb = count($dataFromDb);
    if($numberTapeInDb !== 0) {
      $this->tapeListArray = $dataFromDb;
      $this->numberTape = $numberTapeInDb;
    } else {
      $this->tapeListArray = '';
      $this->numberTape = 0;
    }
  }
}

