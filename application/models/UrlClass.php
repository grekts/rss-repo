<?php

namespace application\models;

class UrlClass
{
  public $urlWithDomainAndCheckedFormat;
  public $urlFormatCheckFlag = 0;
  public $domainName;
  public $urlWithoutDomain;
  public $encodingUrlPartWithoutDomain;
  public $rssUrlWithAllElements;
  
  function checkUrlFormat($urlForCheckFormat)
  {
    $dataType = gettype($urlForCheckFormat);
    if(($dataType === 'string') && ($urlForCheckFormat != '')) {
      $pregMatchResult = preg_match('/[\/]*[http\:\/\/]*[https\:\/\/]*[www\.]*[a-zа-я0-9\-]+\.[a-zа-я0-9\-\?\#\=\.\/]+\.*[a-zа-я0-9\-\?\#\=\.\/]*\.*[a-zа-я0-9\-\?\#\=\.\/]*\.*[a-zа-я0-9\-\?\#\=\.\/]*\.*[a-zа-я0-9\-\?\#\=\.\/]*|\//isU' , $urlForCheckFormat);
      //if in string exist domain anf analyse haven't errors
      if(($pregMatchResult !== 0) && ($pregMatchResult !== false)) {
        $pregMatchResult = preg_match('/\.jpg|.\.jpeg|\.png|\.gif|\.xls|\.xlsx|\.doc|\.docx|\.pdf/isU' , $urlForCheckFormat); 
        //if url not leads to several files
        if(($pregMatchResult === 0) && ($pregMatchResult !== false)) {
         //set flag value - check done
         $this->urlFormatCheckFlag = 1;
         //save checked url
         $this->urlWithDomainAndCheckedFormat = $urlForCheckFormat;
        } else { //if url specify to banned file on was error
          //if was error
          if($pregMatchResult === false) {
            trigger_error(ERROR_SYSTEM_ERROR.'|!|'.$_SESSION['initializer'], E_USER_ERROR);
          }
          //if url specify to banned file
          if($pregMatchResult !== 0) {
            trigger_error(ERROR_URL_NOT_TO_RSS.'|!|'.$_SESSION['initializer'], E_USER_ERROR);
          }
        }
      } else {
        if($pregMatchResult === false) {
          trigger_error(ERROR_SYSTEM_ERROR.'|!|'.$_SESSION['initializer'], E_USER_ERROR);
        }
        //if string not url
        if($pregMatchResult === 0) {
          trigger_error(ERROR_NOT_URL.'|!|'.$_SESSION['initializer'], E_USER_ERROR);
        }
      }
    } else {
      trigger_error(ERROR_URL_NOT_INPUT.'|!|'.$_SESSION['initializer'], E_USER_ERROR);
    }
    
    unset($urlForCheckFormat, $dataType, $pregMatchResult);
  }
  
  function devideUrl()
  {
    $dataType = gettype($this->urlWithDomainAndCheckedFormat);
    if(($dataType == 'string') && ($this->urlWithDomainAndCheckedFormat != '')) {
      //Если пользователь вставил в поле не полную ссылку
      if(strpos($this->urlWithDomainAndCheckedFormat, 'http') === false) {
        //Если ссылка не состоит из нескольких частей
        if(strpos($this->urlWithDomainAndCheckedFormat, '/') === false) {
          $this->domainName = $this->urlWithDomainAndCheckedFormat;
        } else {
          $explodeUrl = explode('/', $this->urlWithDomainAndCheckedFormat, 2);
          $this->domainName = $explodeUrl[0];
          $this->urlWithoutDomain = '/'.$explodeUrl[1];
        }
      } else {
        $parseUrl = parse_url($this->urlWithDomainAndCheckedFormat);
        $this->domainName = $parseUrl['host'];
        $this->urlWithoutDomain = $parseUrl['path'];
      }
    } else {
      trigger_error(ERROR_SYSTEM_ERROR.'|!|'.$_SESSION['initializer'], E_USER_ERROR);
    }
    
    unset($dataType, $explodeUrl, $parseUrl);
  }
  
  //encoding url part
  function encodingUrl() 
  {
    $dataType = gettype($this->urlWithoutDomain);
    if(($dataType == 'string') && ($this->urlWithoutDomain != '')){
      $encodingLetters = array(array('А', '%d0%90'), array('Б', '%d0%91'), array('В', '%d0%92'), array('Г', '%d0%93'),
      array('Д', '%d0%94'), array('Е', '%d0%95'), array('Ё', '%d0%81'), array('Ж', '%d0%96'), array('З', '%d0%97'),
      array('И', '%d0%98'), array('Й', '%d0%99'), array('К', '%d0%9a'), array('Л', '%d0%9b'), array('М', '%d0%9c'),
      array('Н', '%d0%9d'), array('О', '%d0%9e'), array('П', '%d0%9f'), array('Р', '%d0%a0'), array('С', '%d0%a1'),
      array('Т', '%d0%a2'), array('У', '%d0%a3'), array('Ф', '%d0%a4'), array('Х', '%d0%a5'), array('Ц', '%d0%a6'),
      array('Ч', '%d0%a7'), array('Ш', '%d0%a8'), array('Щ', '%d0%a9'), array('Ъ', '%d0%aa'), array('Ы', '%d0%ab'),
      array('Ь', '%d0%ac'), array('Э', '%d0%ad'), array('Ю', '%d0%ae'), array('Я', '%d0%af'), array('а', '%d0%b0'),
      array('б', '%d0%b1'), array('в', '%d0%b2'), array('г', '%d0%b3'), array('д', '%d0%b4'), array('е', '%d0%b5'),
      array('ё', '%d1%91'), array('ж', '%d0%b6'), array('з', '%d0%b7'), array('и', '%d0%b8'), array('й', '%d0%b9'),
      array('к', '%d0%ba'), array('л', '%d0%bb'), array('м', '%d0%bc'), array('н', '%d0%bd'), array('о', '%d0%be'),
      array('п', '%d0%bf'), array('р', '%d1%80'), array('с', '%d1%81'), array('т', '%d1%82'), array('у', '%d1%83'),
      array('ф', '%d1%84'), array('х', '%d1%85'), array('ц', '%d1%86'), array('ч', '%d1%87'), array('ш', '%d1%88'),
      array('щ', '%d1%89'), array('ъ', '%d1%8a'), array('ы', '%d1%8b'), array('ь', '%d1%8c'), array('э', '%d1%8d'),
      array('ю', '%d1%8e'), array('я', '%d1%8f'));
      
      $this->encodingUrlPartWithoutDomain = $this->urlWithoutDomain;
      foreach($encodingLetters as $oneLineEncodingLetters) {
        $this->encodingUrlPartWithoutDomain = preg_replace("/$oneLineEncodingLetters[0]/", "$oneLineEncodingLetters[1]", $this->encodingUrlPartWithoutDomain);
      }
    } else {
      trigger_error(ERROR_SYSTEM_ERROR.'|!|'.$_SESSION['initializer'], E_USER_ERROR);
    }
    
    unset($dataType, $encodingLetters);
  }
  
  function checkUrlCapacityAndDetermineUrlStructure($processedUrl)
  {
    $dataType1 = gettype($processedUrl);
    if(($dataType1 == 'string') 
      && ($processedUrl != '')) {
      //url varians
      $urlVariants = array('http://'.$processedUrl, 'http://'.$processedUrl.'/', 'https://'.$processedUrl, 'https://'.$processedUrl.'/', 'http://www.'.$processedUrl, 'http://www.'.$processedUrl.'/', 'https://www.'.$processedUrl, 'https://www.'.$processedUrl.'/', $processedUrl);
      //check url variants
      for ($i = 0; $i < 9; $i++) {
        $curlQuery = curl_init();
        curl_setopt($curlQuery, CURLOPT_HEADER, 1);
        curl_setopt($curlQuery, CURLOPT_NOBODY, 1);
        curl_setopt($curlQuery, CURLOPT_TIMEOUT, 5);
        curl_setopt($curlQuery, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlQuery, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curlQuery, CURLOPT_URL, $urlVariants[$i]);
        $headerData = curl_exec($curlQuery); //Получаем header
        curl_close($curlQuery);

        //if server send empty answer
        if($headerData === false) {
          //if checked not last url variant
          if($i != 8) {
            unset($curlQuery, $headerData);
            //go check next url variant
            continue;
          } else {
            trigger_error(DOMAIN_NOT_EXIST.'|!|'.$_SESSION['initializer'], E_USER_ERROR);
          }
        }
 
        //explode server answer
        $explodeHeaderData = explode(' ', $headerData);
        //if server send 200 OK
        if($explodeHeaderData[1] == '200') {
          $this->rssUrlWithAllElements = $urlVariants[$i];
          break;
        } else {
          //if check all url variants
          if($i == 8) {
            trigger_error(DOMAIN_NOT_EXIST.'|!|'.$_SESSION['initializer'], E_USER_ERROR);
          } else {
            unset($curlQuery, $headerData, $explodeHeaderData);
          }
        }
      }
    } else {
      trigger_error(ERROR_SYSTEM_ERROR.'|!|'.$_SESSION['initializer'], E_USER_ERROR);
    }
  }
}
