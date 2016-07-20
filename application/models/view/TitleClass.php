<?php

namespace application\models\view;

class TitleClass
{
  public $titleTextData;

  //Метод формирования заголовка страницы
  function formationTitleTextForPage($pageNumber)
  {
    if(isset($_SERVER['REQUEST_URI'])) {    
      switch($pageNumber){
        case 0: $this->titleTextData = 'Лента новостей'; break;
        case 1: $this->titleTextData = 'Лента новостей'; break;
        case 5: $this->titleTextData = 'RSS ленты'; break;
      }
    }
    
    unset($pageNumber);
  }
}

