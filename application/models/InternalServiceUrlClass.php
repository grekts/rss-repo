<?php

namespace application\models;

class InternalServiceUrlClass
{
  public $numberRequestedUrl;
  public $requestUrl;
  
  function __construct() {
    $this->requestUrl = $_SERVER['REQUEST_URI'];
  }
  
  function formationNumberRequestedUrl()
  {
    if(isset($this->requestUrl)) {
      
      if(isset($_GET['get-news']) === true){
        $this->requestUrl = 'get-news';
      }
      
      //array with all url wich exist in the service
      $urlVariants = array('/', 
        '', 
        'get-news', 
        'new-tape', 
        'read', 
        'tape-list', 
        'delete-tape', 
        'send-news-to-archive', 
        'archive',
        'delete-news-from-archive');
      
      //count number letter in url
      $coutLetterInUrl = strlen($this->requestUrl);

      if($coutLetterInUrl > 1) {
        //if url have settings
        if(strpos($this->requestUrl, '/') !== false) {
          //delete settings form url
          $urlWithoutSlash = preg_replace('/\/|\?.*/is', '', $this->requestUrl);
        } else {
          $urlWithoutSlash = $this->requestUrl;
        }
      } else {    
        $urlWithoutSlash = '/';
      }

      //count number letter in url
      $lieghtUrl1 = strlen($urlWithoutSlash);

      //count number url in service
      $countUrlVariants = count($urlVariants);
      //determine number lst url in array with urls
      $lastCountUrlVariants = $countUrlVariants - 1;
      for($i = 0; $i < $countUrlVariants; $i++) {
        //count number of letter in url from array
        $lieghtUrl2 = strlen($urlVariants[$i]);
        //check exist in array with internal urls requst url
        $namePosition = strpos($urlVariants[$i], $urlWithoutSlash);
        //if url exist
        //if requst url exist in array with internal urls
        //if length url from array and request url the same
        if(($namePosition !== false)
        && ($namePosition == 0)
        && ($lieghtUrl1 == $lieghtUrl2)) {
          $this->numberRequestedUrl = $i;
          break;
        } else {
          if($i == $lastCountUrlVariants) {
            header("HTTP/1.1 404 Not Found");
            echo "Запрашиваемая страница не существует";
            exit();
          }
        }
      }
    } else {
      $this->numberRequestedUrl = 1;
    }
    
    unset($urlVariants, $coutLetterInUrl, $urlWithoutSlash, $lieghtUrl1, $countUrlVariants, $lastCountUrlVariants, $lieghtUrl2, $namePosition);
  }
}

