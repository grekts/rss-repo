<?php

namespace application\models;

class SessionClass
{
  function startSession()
  {
    if(isset($_SESSION['initializer']) === false) {
      session_start(); 
    }
  }
  
  function closeSession()
  {
    if(isset($_SESSION['initializer']) === true) {
      session_destroy(); 
    }
  }
}

