<?php

namespace application\models\view;

class NewsClass
{
  public $newsTitleArray;
  public $newsDescriptionArray;
  public $newsLinkArray;
  public $numberNewsForShow;
  public $newsPublicationDateArray;
  public $newsIdArray;
      
  function getListWithNewsForShow($dbConnect)
  {
    try {
      $query = 'SELECT news.news_title, news.news_description, news.news_link, news.publication_date, news.news_id
      FROM news
      WHERE news.read = ?';
      $prepareQuery = $dbConnect->prepare($query);
      $prepareQuery->execute(array(0));
      $dataFromDb = $prepareQuery->fetchAll(\PDO::FETCH_NUM);
    } catch(PDOException $e) {
      trigger_error(ERROR_SYSTEM_ERROR.'|!|'.$_SESSION['initializer'], E_USER_ERROR);
    }
    
    $this->numberNewsForShow = count($dataFromDb);
    if($this->numberNewsForShow !== 0) {
      for($i = 0; $i<$this->numberNewsForShow; $i++) {
        $this->newsTitleArray[] = $dataFromDb[$i][0];
        $this->newsDescriptionArray[] = $dataFromDb[$i][1];
        $this->newsLinkArray[] = $dataFromDb[$i][2];
        $this->newsPublicationDateArray[] = $dataFromDb[$i][3];
        $this->newsIdArray[] = $dataFromDb[$i][4];
      }
    }
    
    unset($dbConnect, $query, $prepareQuery, $dataFromDb);
  }
  
  function getListWithNewsFromArchiveForShow($dbConnect)
  {
    try {
      $query = 'SELECT news_archive.news_title, news_archive.news_description, news_archive.news_link, news_archive.publication_date, news_archive.news_archive_id
      FROM news_archive';
      $prepareQuery = $dbConnect->prepare($query);
      $prepareQuery->execute(array());
      $dataFromDb = $prepareQuery->fetchAll(\PDO::FETCH_NUM);
    } catch(PDOException $e) {
      trigger_error(ERROR_SYSTEM_ERROR.'|!|'.$_SESSION['initializer'], E_USER_ERROR);
    }
    
    $this->numberNewsForShow = count($dataFromDb);
    if($this->numberNewsForShow !== 0) {
      for($i = 0; $i<$this->numberNewsForShow; $i++) {
        $this->newsTitleArray[] = $dataFromDb[$i][0];
        $this->newsDescriptionArray[] = $dataFromDb[$i][1];
        $this->newsLinkArray[] = $dataFromDb[$i][2];
        $this->newsPublicationDateArray[] = $dataFromDb[$i][3];
        $this->newsIdArray[] = $dataFromDb[$i][4];
      }
    }
    
    unset($dbConnect, $query, $prepareQuery, $dataFromDb);
  }
}