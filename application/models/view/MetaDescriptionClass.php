<?php

namespace application\models\view;

class MetaDescriptionClass
{
  public $metaDescriptionTextData;
  
  //Метод определения текста в meta description
  function formationMetaDescriptionTextForPage($pageNumber)
  {
    if(isset($_SERVER['REQUEST_URI'])) {
      switch($pageNumber){
        case 0: $this->metaDescriptionTextData = 'Лента новых новостей'; break;
        case 1: $this->metaDescriptionTextData = 'Лента новых новостей'; break;
        case 5: $this->metaDescriptionTextData = 'Список RSS лент'; break;
      }
    }
    
    unset($pageNumber);
  }
}

