<?php

namespace application\models\view;

class H1TitleClass
{
  public $h1TitleTextData;

  //Метод формирования заголовка страницы
  function formationH1TitleTextForPage($pageNumber)
  {
    if(isset($_SERVER['REQUEST_URI'])) {    
      switch($pageNumber){
        case 0: $this->h1TitleTextData = 'Лента новостей'; break;
        case 1: $this->h1TitleTextData = 'Лента новостей'; break;
        case 5: $this->h1TitleTextData = 'RSS ленты'; break;
        case 8: $this->h1TitleTextData = 'Архив новостей'; break;
      }
    }
    
    unset($pageNumber);
  }
}