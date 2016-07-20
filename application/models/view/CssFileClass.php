<?php

namespace application\models\view;

class CssFileClass
{
  public $nameCssFileTextData;
  
  //Метод определения css файла для страницы
  function formationCssFileName($pageNumber)
  {
    $userAgentData = $_SERVER["HTTP_USER_AGENT"];
    switch ($pageNumber){
      case 0: 
      case 1:
      case 5:
        if(strpos($userAgentData, 'Firefox') !== false){$this->nameCssFileTextData = 'index-mozilla.css';}
        elseif(strpos($userAgentData, 'YaBrowser') !== false){$this->nameCssFileTextData = 'index.css';}
        elseif(strpos($userAgentData, 'Edge') !== false){$this->nameCssFileTextData = 'index-mozilla.css';}
        elseif(strpos($userAgentData, 'Chrome') !== false){$this->nameCssFileTextData = 'index.css';}
        elseif(strpos($userAgentData, 'rv:11.0') !== false){$this->nameCssFileTextData = 'index-mozilla.css';}
        else{$this->nameCssFileTextData = 'index.css';} break;
    }
    
    unset($pageNumber, $userAgentData);
  }
}

