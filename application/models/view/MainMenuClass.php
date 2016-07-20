<?php

namespace application\models\view;

class MainMenuClass
{
  public $maneMenuCode;
  
  //formation main menu
  function formationMainMenuForPage($urlNumber)
  {
    if(($urlNumber == 0) 
      || ($urlNumber == 1) 
      || ($urlNumber == 5)) {
      $this->maneMenuCode = '<nav>
                <ul class="ulMainMenu">           
                    <li class="liMainMenu"><a href="/tape-list" class="aMainMenu">Список лент</a></li>
                </ul>
            </nav>';
    }
    
    unset($urlNumber);
  }
}

