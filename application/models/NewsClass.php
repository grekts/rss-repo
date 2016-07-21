<?php

namespace application\models;

class NewsClass extends \DOMDocument
{
  private $newsCodeFromRssObject;
  private $newsTitleFromRssArray;
  public $newsTitleFromDbArray;
  public $newsDescriptionArray;
  private $newsLinkArray;
  private $newsPublicationDateArray;
  
  //method get news from rss tape
  function getNewsCodeFromRssTape($urlForGetNews)
  {
    $loadResult = $this->load($urlForGetNews);
    if($loadResult === true) {
      $this->newsCodeFromRssObject = $this->getElementsByTagName('item');
    } else{
      trigger_error(ERROR_RSS_NOT_FINED.'|!|'.$_SESSION['initializer'], E_USER_ERROR);
    }
    
    unset($urlForGetNews, $loadResult);
  }
  
  function getNewsTitleFromDb($dbConnect)
  {
    try {
      $query = 'SELECT news.news_title
      FROM news';
      $prepareQuery = $dbConnect->prepare($query);
      $prepareQuery->execute(array());
      $dataFromDb = $prepareQuery->fetchAll(\PDO::FETCH_NUM);
    } catch(PDOException $e) {
      trigger_error(ERROR_SYSTEM_ERROR.'|!|'.$_SESSION['initializer'], E_USER_ERROR);
    }
    
    $numberTitleInDb = count($dataFromDb);
    if($numberTitleInDb !== 0) {
      $this->newsTitleFromDbArray = $dataFromDb;
    }
    
    unset($dbConnect, $numberTitleInDb, $query, $prepareQuery, $dataFromDb);
  }
  
  function parseReceivedNewsCode()
  {
    $newsExistInDbFlag = 0;
    //parse all "item" elements
    foreach($this->newsCodeFromRssObject as $oneNewsCodeFromRss) {
      $newsTitleObject = $oneNewsCodeFromRss->getElementsByTagName('title');
      $titleText = $newsTitleObject->item(0)->nodeValue;
      //search exist in DB processed news
      foreach ($this->newsTitleFromDbArray as $oneNewsTitleFromDb) {        
        //if titles match
        if(strpos(trim($oneNewsTitleFromDb[0]), trim($titleText)) !== false) {
          $newsExistInDbFlag = 1;
          break;
        } else {
          $newsExistInDbFlag = 0;
        }
      }
      if ($newsExistInDbFlag === 0){
        $this->newsTitleFromRssArray[] = htmlspecialchars($titleText);
        $newsDescriptionObject = $oneNewsCodeFromRss->getElementsByTagName('description');
        $this->newsDescriptionArray[] = htmlspecialchars($newsDescriptionObject->item(0)->nodeValue);
        $newsLinkObject = $oneNewsCodeFromRss->getElementsByTagName('link');
        $this->newsLinkArray[] = htmlspecialchars($newsLinkObject->item(0)->nodeValue);
        $newsDateObject = $oneNewsCodeFromRss->getElementsByTagName('pubDate');
        $publicationDate = htmlspecialchars($newsDateObject->item(0)->nodeValue);
        $explodeDate = explode(' ', $publicationDate);
        $day = (int)$explodeDate[1];
        switch($explodeDate[2]) {
          case 'Jan': $month = 1; break;
          case 'Feb': $month = 2; break;
          case 'Mar': $month = 3; break;
          case 'Apr': $month = 4; break;
          case 'May': $month = 5; break;
          case 'Jun': $month = 6; break;
          case 'Jul': $month = 7; break;
          case 'Aug': $month = 8; break;
          case 'Sep': $month = 9; break;
          case 'Oct': $month = 10; break;
          case 'Nov': $month = 11; break;
          case 'Dec': $month = 12; break;
        }
        $year = $explodeDate[3];
        
        $explodeTime = explode(':', $explodeDate[4]);
        $hours = (int)$explodeTime[0];
        $minuts = (int)$explodeTime[1];
        $seconds = (int)$explodeTime[2];
        $this->newsPublicationDateArray[] = mktime($hours, $minuts, $seconds, $month, $day, $year);
      }
    }
    
    unset($newsExistInDbFlag, $oneNewsCodeFromRss, $newsTitleObject, $titleText, $oneNewsTitleFromDb, $newsDescriptionObject, $newsLinkObject, $this->newsTitleFromDbArray);
  }
  
  function sendNewsToDb($idParsedTape, $dbConnect)
  {
    $numberNewsForSend = count($this->newsTitleFromRssArray);
    if($numberNewsForSend !== 0){
      for ($i = 0; $i<$numberNewsForSend; $i++) {
        try {
          $query = 'INSERT INTO news VALUES (?, ?, ?, ?, ?, ?)';
          $prepareQuery = $dbConnect->prepare($query);
          $prepareQuery->execute(array(NULL, $idParsedTape, $this->newsTitleFromRssArray[$i], htmlspecialchars_decode($this->newsDescriptionArray[$i]), $this->newsLinkArray[$i], $this->newsPublicationDateArray[$i]));
        } catch(PDOException $e) {
          $dbConnect->rollBack();
          trigger_error(ERROR_SYSTEM_ERROR.'|!|'.$_SESSION['initializer'], E_USER_ERROR);
        }
      }
    }
    
    unset($dbConnect, $numberNewsForSend, $query, $prepareQuery, $idParsedTape, $this->newsTitleFromRssArray, $this->newsDescriptionArray);
    unset($this->newsLinkArray, $this->newsPublicationDateArray);
  }
  
  function separatingNewsDescriptionIntoParagraphs()
  {
    $numberDescriptions = count($this->newsDescriptionArray);
    for($i = 0; $i<$numberDescriptions; $i++) {
      $explodeNewsText = explode('<br', $this->newsDescriptionArray[$i]);
      $this->newsDescriptionArray[$i] = '';
      foreach($explodeNewsText as $onePartNewsText) {
        $onePartNewsText = trim($onePartNewsText);
        if($onePartNewsText !== '/>') {
          $this->newsDescriptionArray[$i] = $this->newsDescriptionArray[$i].$onePartNewsText.'|!|';
        }
      }
    }
    unset($numberDescriptions, $explodeNewsText, $onePartNewsText);
  }
  
  function deleteReadNews($newsId, $dbConnect) 
  {
    $newsId = (int)$newsId;
    try {
      //delete tape from DB
      $query = 'DELETE FROM news
      WHERE news.news_id = ?';
      $prepareQuery = $dbConnect->prepare($query);
      $prepareQuery->execute(array($newsId));
    } catch(PDOException $e) {
      $dbConnect->rollBack();
      trigger_error(ERROR_SYSTEM_ERROR.'|!|'.$_SESSION['initializer'], E_USER_ERROR);
    }
    
    unset($newsId, $dbConnect, $query, $prepareQuery);
  }
  
}

