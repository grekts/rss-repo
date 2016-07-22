<?php

namespace application\models\view;

class RobotsAccessClass
{
  public $codeBanAccessRobotsOnPageCode;
  
  //Формирование строки кода для закрытия от индексации страниц в панели, страницы входа в панель и восстановления пароля
  function formingCodeBanAccessRobotsOnPage($pageNumber)
  {  
    if(($pageNumber == 0) 
    || ($pageNumber == 1)
    || ($pageNumber == 5)
    || ($pageNumber == 8)) {
      $this->codeBanAccessRobotsOnPageCode = '<meta name="robots" content="noindex, follow" />';
    } else {
      $this->codeBanAccessRobotsOnPageCode = '';
    }
    
    unset($pageNumber);
  }
}

